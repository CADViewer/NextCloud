<template>
    <div>
        <div @click="compareVersion" :class="['version',  isCurrent  ?  'disabled' :  '' ]">
            <div>
                <div v-if="!(loadPreview || previewLoaded)" class="version__image"></div>
				<img v-else-if="(isCurrent || version.hasPreview) && !previewErrored"
					:src="version.previewUrl"
					alt=""
					decoding="async"
					fetchpriority="low"
					loading="lazy"
					class="version__image"
					@load="previewLoaded = true"
					@error="previewErrored = true">
				<div v-else
					class="version__image">
					<ImageOffOutline :size="20" />
				</div>
            </div>
            <div class="version__content">
                <div class="version__title">
                    {{ versionLabel }}
                </div>
                <div class="version__info">
                    <span :title="formattedDate">{{ version.mtime | humanDateFromNow }}</span>
                    <!-- Separate dot to improve alignement -->
                    <span class="version__info__size">•</span>
                    <span class="version__info__size">{{ version.size | humanReadableSize }}</span>
                </div>
            </div>
        </div>
	</div>
</template>

<script>
import ImageOffOutline from 'vue-material-design-icons/ImageOffOutline.vue'
import moment from '@nextcloud/moment'
import { translate as t } from '@nextcloud/l10n'
import { loadState } from '@nextcloud/initial-state'

export default {
	name: 'Version',
	components: {
		ImageOffOutline,
	},
	filters: {
		humanReadableSize(bytes) {
			return OC.Util.humanFileSize(bytes)
		},
		humanDateFromNow(timestamp) {
			return moment(timestamp).fromNow()
		},
	},
	props: {
		version: {
			type: Object,
			required: true,
		},
		fileInfo: {
			type: Object,
			required: true,
		},
		isFirstVersion: {
			type: Boolean,
			default: false,
		},
		loadPreview: {
			type: Boolean,
			default: false,
		}
	},
	data() {
        return {
			previewLoaded: false,
			previewErrored: false,
			formVersionLabelValue: this.version.label,
			capabilities: loadState('core', 'capabilities', { files: { version_labeling: false, version_deletion: false } }),
		}
	},
    mounted() {
        console.log({version: this.version, fileInfo: this.fileInfo, isCurrent: this.isCurrent, isFirstVersion: this.isFirstVersion, versionLabel: this.versionLabel})
    },
	computed: {
        isCurrent() {
            return this.version.mtime == this.fileInfo.mtime
        },
		versionLabel() {
			const label = this.version.label ?? ''

			if (this.isCurrent) {
				if (label === '') {
					return 'Current version'
				} else {
					return `${label} (Current version)`
				}
			}

			if (this.isFirstVersion && label === '') {
				return 'Initial version'
			}

			return label
		},

		formattedDate() {
			return moment(this.version.mtime).format('LLL')
		},

	},
	methods: {
		compareVersion() {
            if (this.isCurrent) {
                return
            }
			this.$emit('compare', { version: this.version })
		},
		t,
	},
}
</script>

<style scoped lang="scss">
.version {
	display: flex;
	flex-direction: row;
    cursor: pointer;
    padding: 8px;
    border-radius: 8px;
    margin: 4px 8px;

    &.disabled {
        cursor: not-allowed;   
        *  {
            cursor: not-allowed  !important; 
        }
    }
    &.disabled:hover {
        background-color: transparent !important;
    }

    &:hover {
        background-color: var(--color-background-hover);
    }

    &__content {
        flex: 1 1 auto;
        width: 0;
        margin: auto 0;
        padding-left: 8px;
        cursor: pointer;
    }
    &__title {
        font-weight: bold;
        text-overflow: ellipsis;
        color: var(--color-main-text);
        cursor: pointer;
    }

	&__info {
		display: flex;
		flex-direction: row;
		align-items: center;
		gap: 0.5rem;
        text-overflow: ellipsis;
        color: var(--color-text-maxcontrast);

        cursor: pointer;
		&__size {
			color: var(--color-text-lighter);
            cursor: pointer;
		}
	}

	&__image {
		width: 3rem;
		height: 3rem;
		border: 1px solid var(--color-border);
		border-radius: var(--border-radius-large);

		// Useful to display no preview icon.
		display: flex;
		justify-content: center;
		color: var(--color-text-light); 
        cursor: pointer;
	}
}
</style>