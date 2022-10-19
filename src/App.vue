<template>
  <div id="iframe_container" class="modal__content">
    <NcModal v-if="modal" @close="closeModal" title="Cadviewer" size="full">
      <CADViewerCanvas
        ref="cadviewercanvas"
        :ServerBackEndUrl="ServerBackEndUrl"
        :ServerLocation="ServerLocation"
        :ServerUrl="ServerUrl"
        :FileName="FileName"
      ></CADViewerCanvas>
    </NcModal>
  </div>
</template>

<script>
import CADViewerCanvas from "./components/CADViewerCanvas.vue";
import NcModal from "@nextcloud/vue/dist/Components/NcModal.js";

export default {
  name: "App",
  data() {
    return {
      modal: false,
      ModalTitle: '',
      ServerBackEndUrl: '',
      ServerLocation: '',
      ServerUrl: '',
      FileName: '',
    };
  },

  methods: {
    init() {
      OCA.Files.fileActions.registerAction({
        name: "view_dwg",
        displayName: "Visualiser",
        mime: "application/octet-stream",
        permissions: OC.PERMISSION_NONE,
        type: OCA.Files.FileActions.TYPE_DROPDOWN,
        iconClass: "icon-visibility-button",
        order: 1000,
        actionHandler: (filename, context) => {
          var tr = context.fileList.findFileEl(filename);
          context.fileList.showFileBusyState(tr, true);
          var data = {
            nameOfFile: filename,
            directory: context.dir,
          };
          $.ajax({
            type: "POST",
            async: "false",
            url: OC.filePath("cadviewer", "ajax", "cadviewer.php"),
            data: data,
            success: async (element) => {
              context.fileList.showFileBusyState(tr, false);
              console.log(element);
              if (element.path) {
                const content_dir = element.path;
                this.ServerBackEndUrl = `${window.location.origin}/apps/cadviewer/converter/`
                this.ServerLocation = `/var/www/html/apps/cadviewer/converter`
                this.ServerUrl = `${window.location.origin}/apps/cadviewer/`
                this.FileName = `${content_dir}/${filename}`
                this.ModalTitle = filename
                this.modal = true;
              } else {
                OC.dialogs.alert(
                  t(
                    "cadviewer",
                    "Impossible de visualiser ce fichier Autocad pour le moment"
                  ) + filename,
                  t("cadviewer", "Une erreur inconnue c'est produite")
                );
              }
            },
            error: (resultat) => {
              context.fileList.showFileBusyState(tr, false);
              OC.dialogs.alert(
                t(
                  "cadviewer",
                  "Impossible de visualiser ce fichier Autocad pour le moment"
                ) + filename,
                t("cadviewer", "Une erreur inconnue c'est produite")
              );
            },
          });
        },
      });
    },
    closeModal() {
			this.modal = false
		},

  },

  created () {
    this.init();
    OCA.Files.fileActions.setDefault("application/octet-stream", "view_dwg");
  },
  components: {
    CADViewerCanvas,
    NcModal,
  },
};
</script>
<style>
  .modal-container, .cadviewerCanvasTest01 {
    height: calc(100vh - var(--header-height)) !important;
    width: 100vw !important;
    overflow: hidden !important;
  }
  .modal-wrapper {
    width: 100vw !important;
    height: 100vh !important;
    
  }
</style>