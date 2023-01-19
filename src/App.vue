<template></template>
<script>
import CADViewerCanvasVue from "./components/CADViewerCanvas.vue";
import Vue from "vue";

OCA.Cadviewer = _.extend({}, OCA.Cadviewer);

if (!OCA.Cadviewer.AppName) {
    OCA.Cadviewer = {
        AppName: "cadviewer"
    };
}

export default {
  data() {
    return {
      modal: false,
      ModalTitle: "",
      ServerBackEndUrl: "",
      ServerLocation: "",
      ServerUrl: "",
      FileName: "",
      licenceKey: "",
    };
  },

  methods: {
    viewCadFileActionHandler(filename, context) {
      var tr = context.fileList.findFileEl(filename);
      context.fileList.showFileBusyState(tr, true);
      var data = {
        nameOfFile: filename,
        directory: context.dir,
      };
      $.ajax({
        type: "POST",
        async: "false",
        url: OC.generateUrl("apps/" + OCA.Cadviewer.AppName + "/ajax/cadviewer.php"),
        data: data,
        success: async (response) => {
          console.log(response);
          if (response.path) {
            const content_dir = response.path;
            console.log({ content_dir });
            this.ServerBackEndUrl = `${window.location.href.split("/apps/")[0].replace("/index.php", "")}/apps/cadviewer/converter/`;
            this.ServerLocation = `${response.serverLocation}`;
            this.ServerUrl = `${window.location.href.split("/apps/")[0].replace("/index.php", "")}/apps/cadviewer/`;
            this.FileName = `${content_dir}/${filename}`;
            this.ModalTitle = filename;
            this.licenceKey = response.licenceKey;
            // this.modal = true;

            const myDiv = document.createElement("div");
            myDiv.id = "cadviewer_app_canvas";

            document.body.appendChild(myDiv);

            setTimeout(() => {
              context.fileList.showFileBusyState(tr, false);
              const appCanvas = new Vue({
                el: "#cadviewer_app_canvas",
                render: (h) =>
                  h(CADViewerCanvasVue, {
                    props: {
                      ServerBackEndUrl: this.ServerBackEndUrl,
                      ServerLocation: this.ServerLocation,
                      ServerUrl: this.ServerUrl,
                      FileName: this.FileName,
                      ModalTitle: this.ModalTitle,
                      LicenceKey: this.licenceKey,
                      UserName: OC.getCurrentUser().displayName,
                      UserId: OC.getCurrentUser().uid,
                      closeModal: () => {
                        appCanvas.$destroy();
                        // appCanvas.$el.parentNode.removeChild(this.$el);
                        document.querySelector(".modal-mask").remove();
                        document
                          .querySelector("#cadviewer_app_canvas")
                          .remove();
                      },
                    },
                  }),
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
    initViewCadFile(mine_type, is_default) {
      OCA.Files.fileActions.registerAction({
        name: "open_cadviewer_modal",
        displayName: "Open with CADViewer",
        mime: mine_type,
        permissions: OC.PERMISSION_NONE,
        type: OCA.Files.FileActions.TYPE_DROPDOWN,
        iconClass: "icon-visibility-button",
        order: 1001,
        actionHandler: this.viewCadFileActionHandler,
      });
      if(is_default)
        OCA.Files.fileActions.setDefault(mine_type, "open_cadviewer_modal");
    },
    closeModal() {
      this.modal = false;
      this.ModalTitle = "";
      this.ServerBackEndUrl = "";
      this.ServerLocation = "";
      this.ServerUrl = "";
      this.FileName = "";
      this.licenceKey = "";
    },
  },

  created() {
    // Add context menu and default file open handler for autocad file
    this.initViewCadFile("application/octet-stream", true);
    this.initViewCadFile("aapplication/acad", true);
    this.initViewCadFile("application/dxf", true);
    this.initViewCadFile("application/x-dwf", true);
    this.initViewCadFile("application/dgn", true);
    // Add context menu for others documents
    this.initViewCadFile("application/pdf", false);
    this.initViewCadFile("image/tiff", false);
    this.initViewCadFile("image/tif", false);
  },
  components: {
    "app-cadviewercanvas": CADViewerCanvasVue,
  },
  props: {
    foo: String,
  },
};
</script>
<style>
.modal-container,
.cadviewerCanvasTest01 {
  height: calc(100vh - var(--header-height)) !important;
  width: 100vw !important;
  overflow: hidden !important;
}
.modal-wrapper {
  width: 100vw !important;
  height: 100vh !important;
}
</style>
