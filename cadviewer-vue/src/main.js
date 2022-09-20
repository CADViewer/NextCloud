import Vue from 'vue'
import App from './App.vue'

/*
import CADViewerCanvas from './CADViewerCanvasPlaceholder.vue'
import CADViewerHelperMethods from './CADViewerHelperMethodsPlaceholder.vue'
import CADViewerSpaceObjects from './CADViewerSpaceObjectsPlaceholder.vue'
*/
/*
// main import of CADViewer
import cadviewer from 'cadviewer';
Object.defineProperty(Vue.prototype, '$cadviewer', { value: cadviewer });

import jquery from 'jquery';
Object.defineProperty(Vue.prototype, '$jquery', { value: jquery });

*/

// import resize of components
//import resize from "vue-element-resize-detector";
//Vue.use(resize)

Vue.config.productionTip = false

export const eventBus = new Vue(); // added to trigger inter components call of methods


var app = new Vue({
  render: h => h(App),
}).$mount('#app')




/*

window.App = app



var helpermethods = new Vue({
  render: h => h(CADViewerHelperMethods),
}).$mount('#cadviewerhelpermethods')

window.HelperMethods = helpermethods;

var spaceobjects = new Vue({
  render: h => h(CADViewerSpaceObjects),
}).$mount('#cadviewerspaceobjects')

window.SpaceObjects = spaceobjects

var cadviewercanvas = new Vue({
  render: h => h(CADViewerCanvas),
}).$mount('#cadviewercanvas')

window.CADViewer = cadviewercanvas
*/

