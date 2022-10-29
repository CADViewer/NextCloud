<template></template>
<script>
import CADViewerCanvasVue from "./components/CADViewerCanvas.vue";
import Vue from 'vue'

export default {
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
              console.log(element);
              if (element.path) {
                const content_dir = element.path;
                this.ServerBackEndUrl = `${window.location.origin}/apps/cadviewer/converter/`
                this.ServerLocation = `/var/www/html/apps/cadviewer/converter`
                this.ServerUrl = `${window.location.origin}/apps/cadviewer/`
                this.FileName = `${content_dir}/${filename}`
                this.ModalTitle = filename
                // this.modal = true;

                const myDiv = document.createElement("div");
                myDiv.id = 'cadviewer_app_canvas';

                document.body.appendChild(myDiv);

                setTimeout(() => {
                  context.fileList.showFileBusyState(tr, false);
                    const appCanvas = new Vue({
                        el: '#cadviewer_app_canvas',
                        render: h => h(CADViewerCanvasVue, {props:{ 
                          ServerBackEndUrl: this.ServerBackEndUrl,
                          ServerLocation: this.ServerLocation,
                          ServerUrl: this.ServerUrl,
                          FileName: this.FileName,
                          ModalTitle: this.ModalTitle,
                          closeModal: () => {
                            appCanvas.$destroy();
                            // appCanvas.$el.parentNode.removeChild(this.$el);
                            document.querySelector(".modal-mask").remove();
                            document.querySelector("#cadviewer_app_canvas").remove();
                          }
                        }})
                    });
                }, 200);
              } else {
                context.fileList.showFileBusyState(tr, false);
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
      this.ModalTitle = ''
      this.ServerBackEndUrl = ''
      this.ServerLocation = ''
      this.ServerUrl = ''
      this.FileName = ''
		},

  },

  created () {
    this.init();
    OCA.Files.fileActions.setDefault("application/octet-stream", "view_dwg");
  },
  components: {
    'app-cadviewercanvas': CADViewerCanvasVue,
  },
  props: {
    foo: String,
  }
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