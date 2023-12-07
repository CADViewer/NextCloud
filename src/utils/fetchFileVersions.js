import path from 'path'
import { createClient } from 'webdav'

import { getCurrentUser, getRequestToken } from '@nextcloud/auth'
import { generateRemoteUrl, generateUrl } from '@nextcloud/router'
import moment from '@nextcloud/moment'
import { joinPaths } from '@nextcloud/paths'
import { getLoggerBuilder } from '@nextcloud/logger'

const logger = getLoggerBuilder()
	.setApp('demo')
	.detectUser()
	.build()

// init webdav client on default dav endpoint
const client = createClient(
	generateRemoteUrl('dav'),
	{
		headers: {
			// Add this so the server knows it is an request from the browser
			'X-Requested-With': 'XMLHttpRequest',
			// Inject user auth
			requesttoken: getRequestToken() ?? '',
		},
	},
)

function encodeFilePath(path) {
	const pathSections = (path.startsWith('/') ? path : `/${path}`).split('/')
	let relativePath = ''
	pathSections.forEach((section) => {
		if (section !== '') {
			relativePath += '/' + encodeURIComponent(section)
		}
	})
	return relativePath
}

function formatVersion(version, fileInfo) {
	const mtime = moment(version.lastmod).unix() * 1000
	let previewUrl = ''
	let filename = ''

	if (mtime === fileInfo.mtime) { // Version is the current one
		filename = path.join('files', getCurrentUser()?.uid ?? '', fileInfo.path)
		previewUrl = generateUrl('/core/preview?fileId={fileId}&c={fileEtag}&x=250&y=250&forceIcon=0&a=0', {
			fileId: fileInfo.fileid,
			fileEtag: fileInfo._attributes.etag,
		})
	} else {
		filename = version.filename
		previewUrl = generateUrl('/apps/files_versions/preview?file={file}&version={fileVersion}', {
			file: fileInfo.path,
			fileVersion: version.basename,
		})
	}

	return {
		fileId: fileInfo.id,
		label: version.props['version-label'],
		filename,
		basename: moment(mtime).format('LLL'),
		mime: version.mime,
		etag: `${version.props.getetag}`,
		size: version.size,
		type: version.type,
		mtime,
		permissions: 'R',
		hasPreview: version.props['has-preview'] === 1,
		previewUrl,
		url: joinPaths('/remote.php/dav', filename),
		source: generateRemoteUrl('dav') + encodeFilePath(filename),
		fileVersion: version.basename,
	}
}

export async function fetchVersions(fileInfo) {
	const path = `/versions/${getCurrentUser()?.uid}/versions/${fileInfo.id}`

	try {
		/** @type {import('webdav').ResponseDataDetailed<import('webdav').FileStat[]>} */
		const response = await client.getDirectoryContents(path, {
			data: `<?xml version="1.0"?>
                <d:propfind xmlns:d="DAV:"
                    xmlns:oc="http://owncloud.org/ns"
                    xmlns:nc="http://nextcloud.org/ns"
                    xmlns:ocs="http://open-collaboration-services.org/ns">
                    <d:prop>
                        <d:getcontentlength />
                        <d:getcontenttype />
                        <d:getlastmodified />
                        <d:getetag />
                        <nc:version-label />
                        <nc:has-preview />
                    </d:prop>
                </d:propfind>`,
			details: true,
		})
		return response.data
			// Filter out root
			.filter(({ mime }) => mime !== '')
			.map(version => formatVersion(version, fileInfo))
	} catch (exception) {
		logger.error('Could not fetch version', { exception })
		throw exception
	}
}
