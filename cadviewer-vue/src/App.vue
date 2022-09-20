<template>
  <div id="app">
    <!-- <img alt="Vue logo" src="./assets/logo.png" width="40px"> -->
    <!-- <app-cadviewerhelpermethods ref="cadviewerhelpermethods"></app-cadviewerhelpermethods> -->
    <!--<app-cadviewerspaceobjects ref="cadviewerspaceobjects"></app-cadviewerspaceobjects> -->
    <app-cadviewercanvas 
      ref="cadviewercanvas" 
      :ServerBackEndUrl="ServerBackEndUrl" 
      :ServerLocation="ServerLocation"
      :ServerUrl="ServerUrl"
      :FileName="FileName"
    ></app-cadviewercanvas>

    
  </div>
</template>


<script>

// NOTE:  IF YOU IN INDEX.HTML ADD IN CADVIEWER CLASS DECLARATIONS FROM /PUBLIC/CADVIEWER_TOPLEVEL_VUE/APP/.. then the components _no_npm can be used.

// Standard use
import CADViewerCanvas from './components/CADViewerCanvas.vue'    // STANDARD NPM INSTALL
// import CADViewerHelperMethods from './components/CADViewerHelperMethods.vue' // STANDARD NPM INSTALL
//import CADViewerSpaceObjects from './components/CADViewerSpaceObjects.vue' // STANDARD NPM INSTALL


// Altenative use, see index.html for import of CADViewer and jQuery
/*
import CADViewerCanvas from './components/CADViewerCanvas_no_npm.vue'   // NO NPM INSTAL - GLOBAL use cadviewer_toplevel_vue - not recommended
import CADViewerHelperMethods from './components/CADViewerHelperMethods_no_npm.vue' // NO NPM INSTAL - GLOBAL use cadviewer_toplevel_vue - not recommended
import CADViewerSpaceObjects from './components/CADViewerSpaceObjects_no_npm.vue' // NO NPM INSTAL - GLOBAL use cadviewer_toplevel_vue - not recommended
*/

export default {
  name: 'App',
  data() {
    return {
      ServerBackEndUrl: Object.fromEntries(new URLSearchParams(window.location.search).entries()).ServerBackEndUrl,
      ServerLocation: Object.fromEntries(new URLSearchParams(window.location.search).entries()).ServerLocation,
      ServerUrl: Object.fromEntries(new URLSearchParams(window.location.search).entries()).ServerUrl,
      FileName: Object.fromEntries(new URLSearchParams(window.location.search).entries()).FileName
    }
  },
  components: {
    // 'app-cadviewerhelpermethods' : CADViewerHelperMethods,
/*    'app-cadviewerspaceobjects' : CADViewerSpaceObjects, */
    'app-cadviewercanvas': CADViewerCanvas,
  }
}
</script>


<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 10px;
}
</style>
