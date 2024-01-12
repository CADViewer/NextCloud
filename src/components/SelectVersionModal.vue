<template>
	<NcModal
        size="small"
		:show="opened"
		@close="onCancel">
        <div>
            <div class="modal__header">
                <div class="modal__title">
                    Select version to compare with
                </div>
            </div>
            <ul data-files-versions-versions-list>
                <FileVersion v-for="version in orderedVersions"
                    :key="version.mtime"
                    :load-preview="isActive"
                    :version="version"
                    :file-info="file"
                    :is-first-version="version.mtime == initialVersionMtime"
                    @compare="compareVersion" />
            </ul>
        </div>
	</NcModal>
</template>

<script lang="ts">
import Vue from 'vue'
import NcModal from '@nextcloud/vue/dist/Components/NcModal.js'
import { getRootUrl } from '@nextcloud/router'
import FileVersion from './FileVersion.vue'
import { fetchVersions } from "../utils/fetchFileVersions.js";

export default Vue.extend({
	name: 'SelectVersionModal',

	components: {
		NcModal,
        FileVersion
	},
	props: {
		file: {
			required: true,
		},
	},
    data() {
		return {
			isActive: false,
			versions: [],
			opened: true,
			loading: false,
		}
	},
	computed: {
         orderedVersions() {
			return [...this.versions].sort((a, b) => {
				if (a.mtime === this.file.mtime.getTime()) {
					return -1
				} else if (b.mtime === this.file.mtime.getTime()) {
					return 1
				} else {
					return b.mtime - a.mtime
				}
			})
		},

		initialVersionMtime() {
			return this.versions
				.map(version => version.mtime)
				.reduce((a, b) => Math.min(a, b))
		},
	},

	async beforeMount() {
		this.versions = await fetchVersions(this.file)
	},

	methods: {
		onCancel() {
			this.opened = false
			this.$emit('cancel')
		},

		versionLabel(version) {
			const label = version.label ?? ''

			if (version.mtime == this.file.mtime.getTime()) {
				if (label === '') {
					return 'Current version'
				} else {
					return `${label} (Current version)`
				}
			}

			if (version.mtime == this.initialVersionMtime && label === '') {
				return 'Initial version'
			}

			return label
		},

        compareVersion({ version }) {
			console.log({ version, mtime: this.file.mtime.getTime(), versions: this.versions })
			const lastVersion = this.versions.findLast((v) => v.mtime == this.file.mtime.getTime())
			this.$emit('select', { version, firstLabel: this.versionLabel(version), currentVersion: this.versionLabel(lastVersion) })
        },

		async fetchVersions() {
			try {
				this.loading = true
				this.versions = await fetchVersions(this.file)
			} finally {
				this.loading = false
			}
		},
	},
})
</script>

<style lang="scss" scoped>
    .modal__title {
        text-overflow: ellipsis;
        color: var(--color-main-text);
        font-size: 20px;
        font-weight: bold;
        margin: 8px 20px;
    }
</style>
