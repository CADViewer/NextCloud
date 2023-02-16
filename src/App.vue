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
  created: function () {
    setTimeout(() => {
      const myDiv = document.createElement("div");
      myDiv.id = "cadviewer_app_canvas";
      document.body.appendChild(myDiv);
      const appCanvas = new Vue({
        el: "#cadviewer_app_canvas",
        render: (h) =>
          h(CADViewerCanvasVue, {
            props: {},
          }),
      });
    }, 200);
  },
  components: {
    "app-cadviewercanvas": CADViewerCanvasVue,
  },
  props: {
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
