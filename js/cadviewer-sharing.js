"use strict";
(self["webpackChunkcadviewer"] = self["webpackChunkcadviewer"] || []).push([["sharing"],{

/***/ "./node_modules/babel-loader/lib/index.js!./node_modules/vue-loader/lib/index.js??vue-loader-options!./src/App.vue?vue&type=script&lang=js&":
/*!**************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js!./node_modules/vue-loader/lib/index.js??vue-loader-options!./src/App.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _components_CADViewerCanvas_vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./components/CADViewerCanvas.vue */ "./src/components/CADViewerCanvas.vue");
 // STANDARD NPM INSTALL

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  name: 'App',
  data: function data() {
    return {
      ServerBackEndUrl: Object.fromEntries(new URLSearchParams(window.location.search).entries()).ServerBackEndUrl,
      ServerLocation: Object.fromEntries(new URLSearchParams(window.location.search).entries()).ServerLocation,
      ServerUrl: Object.fromEntries(new URLSearchParams(window.location.search).entries()).ServerUrl,
      FileName: Object.fromEntries(new URLSearchParams(window.location.search).entries()).FileName
    };
  },
  components: {
    'app-cadviewercanvas': _components_CADViewerCanvas_vue__WEBPACK_IMPORTED_MODULE_0__["default"]
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js!./node_modules/vue-loader/lib/index.js??vue-loader-options!./src/components/CADViewerCanvas.vue?vue&type=script&lang=js&":
/*!*************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js!./node_modules/vue-loader/lib/index.js??vue-loader-options!./src/components/CADViewerCanvas.vue?vue&type=script&lang=js& ***!
  \*************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var cadviewer__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! cadviewer */ "./node_modules/cadviewer/dist/cadviewer_7.2.7e.min.js");
/* harmony import */ var cadviewer__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(cadviewer__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _main_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../main.js */ "./src/main.js");
/* provided dependency */ var console = __webpack_require__(/*! ./node_modules/console-browserify/index.js */ "./node_modules/console-browserify/index.js");



var textLayer1;
var selected_handles = [];
var handle_selector = false;
var current_selected_handle = "";

// We should to define all the CADViewer methods in which we are getting information return from CADViewer
// THEY CAN BE PLACEHOLDERS ONLY 

//export function cvjs_OnLoadEnd(){

function cvjs_OnLoadEnd() {
  // generic callback method, called when the drawing is loaded
  // here you fill in your stuff, call DB, set up arrays, etc..
  // this method MUST be retained as a dummy method! - if not implemeted -

  cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_resetZoomPan("floorPlan");
  var user_name = "Bob Smith";
  var user_id = "user_1";

  // set a value for redlines
  cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_setCurrentStickyNoteValues_NameUserId(user_name, user_id);
  cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_setCurrentRedlineValues_NameUserid(user_name, user_id);
  // cadviewer.cvjs_dragBackgroundToFront_SVG("floorPlan");					
  //cvjs_initZeroWidthHandling("floorPlan", 1.0);			

  textLayer1 = cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_clearLayer(textLayer1);
  cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_LayerOff("EC1 Space Names");
  cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_LayerOff("EC1 Space Status Descs");
  cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_LayerOff("EC1 Space Project");
  cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_LayerOff("EC1 Space Function Descs");
  cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_LayerOff("EC1 Space Type Descs");
  cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_LayerOff("EC1 Tenant Names");
  cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_LayerOff("EC1 UDA Design Capacity");
  cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_LayerOff("EC1 UDA Is Secured");
}
function cvjs_OnLoadEndRedlines() {
  // generic callback method, called when the redline is loaded
  // here you fill in your stuff, hide specific users and lock specific users
  // this method MUST be retained as a dummy method! - if not implemeted -

  // I am hiding users added to the hide user list
  cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_hideAllRedlines_HiddenUsersList();

  // I am freezing users added to the lock user list
  cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_lockAllRedlines_LockedUsersList();
}

// Callback Method on Creation and Delete 
//export function cvjs_graphicalObjectOnChange(type, graphicalObject, spaceID){
function cvjs_graphicalObjectOnChange(type, graphicalObject, spaceID) {
  try {
    var myobject;
    // do something with the graphics object created! 
    //	window.alert("CALLBACK: cvjs_graphicalObjectOnChange: "+type+" "+graphicalObject+" "+spaceID+" indexSpace: "+graphicalObject.toLowerCase().indexOf("space"));
    console.log("CALLBACK: cvjs_graphicalObjectOnChange: " + type + " " + graphicalObject + " " + spaceID + " indexSpace: " + graphicalObject.toLowerCase().indexOf("space"));
    if (type == 'Create' && graphicalObject.toLowerCase().indexOf("space") > -1 && graphicalObject.toLowerCase().indexOf("circle") == -1) {
      /**
       * Return a JSON structure of all content of a given ID: <br>
      * 	var jsonStructure =  	{	"path":   path, <br>
      *								"tags": tags, <br>
      *								"node": node, <br>
      *								"outerhtml": outerHTML, <br>
      *								"occupancy": occupancy, <br>
      *								"name": name, <br>
      *								"type": type, <br>
      *								"id": id, <br>
      *								"defaultcolor": defaultcolor, <br>
      *								"layer": layer, <br>
      *								"group": group, <br>
      *								"linked": linked, <br>
      *								"attributes": attributes, <br>
      *								"attributeStatus": attributeStatus, <br>
      *								"displaySpaceObjects": displaySpaceObjects, <br>
      *								"translate_x": translate_x, <br>
      *								"translate_y": translate_y, <br>
      *								"scale_x": scale_x ,<br>
      *								"scale_y": scale_y ,<br>
      *								"rotate": rotate, <br>
      *								"transform": transform} <br>
      * @param {string} spaceID - Id of the Space Object to return
      * @return {Object} jsonSpaceObject - Object with the entire space objects content
      */

      myobject = cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_returnSpaceObjectID(spaceID);
      // I can save this object into my database, and then use command 
      // cvjs_setSpaceObjectDirect(jsonSpaceObject) 
      // when I am recreating the content of the drawing at load
      // for the fun of it, display the SVG geometry of the space:			
      console.log("This is the SVG: " + myobject.outerhtml);
    }
    if (type == 'Delete' && graphicalObject.toLowerCase().indexOf("space") > -1) {
      // remove this entry from my DB

      window.alert("We have deleted: " + spaceID);
    }
    if (type == 'Move' && graphicalObject.toLowerCase().indexOf("space") > -1) {
      // remove this entry from my DB

      console.log("This object has been moved: " + spaceID);
      myobject = cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_returnSpaceObjectID(spaceID);
    }
  } catch (err) {
    console.log("cvjs_graphicalObjectOnChange: " + err);
  }
}
function cvjs_saveStickyNotesRedlinesUser() {
  // there are two modes, user handling of redlines
  // alternatively use the build in redline file manager

  cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_openRedlineSaveModal("floorPlan");

  // custom method startMethodRed to set the name and location of redline to save
  // see implementation below
  //startMethodRed();
  // API call to save stickynotes and redlines
  //cvjs_saveStickyNotesRedlines("floorPlan");
}

// This method is linked to the load redline icon in the imagemap
function cvjs_loadStickyNotesRedlinesUser() {
  cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_openRedlineLoadModal("floorPlan");

  // first the drawing needs to be cleared of stickynotes and redlines
  //cvjs_deleteAllStickyNotes();
  //cvjs_deleteAllRedlines();

  // custom method startMethodRed to set the name and location of redline to load
  // see implementation below
  // startMethodRed();

  // API call to load stickynotes and redlines
  //cvjs_loadStickyNotesRedlines("floorPlan");
}

// Here we are writing a basic function that will be used in the PopUpMenu
// this is template on all the good stuff users can add
function my_own_clickmenu1() {
  var id = cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_idObjectClicked();
  //		var node = cvjs_NodeObjectClicked();
  window.alert("Custom menu item 1: Here developers can implement their own methods, the look and feel of the menu is controlled in the settings.  Clicked object ID is: " + id);
}

// Here we are writing a basic function that will be used in the PopUpMenu
// this is template on all the good stuff users can add
function my_own_clickmenu2() {
  var id = cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_idObjectClicked();
  //var node = cvjs_NodeObjectClicked();

  window.alert("Custom menu item 2: Here developers can implement their own methods, the look and feel of the menu is controlled in the settings. Clicked object ID is: " + id);
  //window.alert("Custom menu item 2: Clicked object Node is: "+node);
}

function cvjs_popupTitleClick(roomid) {
  window.alert("we have clicked " + roomid);
}

// HANDLING OF MOUSE OPERATION

// ENABLE ALL API EVENT HANDLES FOR AUTOCAD Handles
function cvjs_mousedown(id, handle, entity) {}
function cvjs_click(id, handle, entity) {
  try {
    console.log("click " + id + "  " + handle);
    // if we click on an object, then we add to the handle list
    if (id != "dragcanvas") if (handle_selector) {
      selected_handles.push({
        id: id,
        handle: handle
      });
      current_selected_handle = handle;
    }

    // tell to update the Scroll bar 
    //vqUpdateScrollbar(id, handle);
    // window.alert("We have clicked an entity: "+entity.substring(4)+"\r\nThe AutoCAD Handle id: "+handle+"\r\nThe svg id is: "+id+"\r\nHighlight SQL pane entry");
  } catch (err) {
    console.log("click: " + err);
  }
}
function cvjs_dblclick(id, handle, entity) {
  console.log("mysql dblclick " + id + "  " + handle);
  window.alert("We have double clicked entity with AutoCAD Handle: " + handle + "\r\nThe svg id is: " + id);
}
function cvjs_mouseout(id, handle, entity) {
  console.log("mysql mouseout " + id + "  " + handle);
  if (current_selected_handle == handle) {
    // do nothing
  } else {
    cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_mouseout_handleObjectStyles(id, handle);
  }
}
function cvjs_mouseover(id, handle, entity) {
  console.log("mysql mouseover " + id + "  " + handle); // +"  "+jQuery("#"+id).css("color"))
  //cvjs_mouseover_handleObjectPopUp(id, handle);	
}

function cvjs_mouseleave(id, handle, entity) {
  console.log("mysql mouseleave " + id + "  " + handle); // +"  "+jQuery("#"+id).css("color"));
}

function cvjs_mouseenter(id, handle, entity) {
  //	cvjs_mouseenter_handleObjectStyles("#a0a000", 4.0, 1.0, id, handle);
  //	cvjs_mouseenter_handleObjectStyles("#ffcccb", 5.0, 0.7, true, id, handle);

  cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_mouseenter_handleObjectStyles("#F00", 2.0, 1.0, true, id, handle);
}

// END OF MOUSE OPERATION

function cvjs_graphicalObjectCreated(graphicalObject) {
  // do something with the graphics object created!
  //		window.alert(graphicalObject);
}
function cvjs_ObjectSelected(rmid) {
  // placeholder for method in tms_cadviewerjs_modal_1_0_14.js   - must be removed when in creation mode and using creation modal
}
function cvjs_measurementCallback() {}
function cvjs_CalibrateMeasurementCallback() {}
function cvjs_Url_callback() {}
function cvjs_loadSpaceImage_UserConfiguration() {}
function cvjs_NoObjectSelected() {}
function cvjs_SVGfileObjectClicked() {}
function cvjs_SVGfileObjectMouseEnter() {}
function cvjs_SVGfileObjectMouseLeave() {}
function cvjs_SVGfileObjectMouseMove() {}
;
function cvjs_ParseDisplayDataMaps() {}
;
function cvjs_QuickCountCallback() {}
;
function cvjs_OnHyperlinkClick() {}
;
function cvjs_setUpStickyNotesRedlines() {}
;
function custom_host_parser_PopUpMenu() {}
;
function cvjs_customHostParser() {}
function drawPathsGeneric() {}
;
function cvjs_callbackForModalDisplay() {}
;
function cvjs_populateMyCustomPopUpBody() {}
;
function cvjs_customModalPopUpBody() {}
;
function cvjs_NoObjectSelectedStickyNotes() {}
;
function cvjs_NoObjectSelectedHyperlinks() {}
;
function cvjs_ObjectSelectedHyperlink() {}
;
function cvjs_ObjectSelectedStickyNotes() {}
;
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  created: function created() {
    var _this = this;
    _main_js__WEBPACK_IMPORTED_MODULE_2__.eventBus.$on('clearTextLayer', function () {
      _this.clearTextLayer();
    });
    _main_js__WEBPACK_IMPORTED_MODULE_2__.eventBus.$on('AddTextOnSpaceObject_wrapper', function (Id, leftScale, textStringArr, textStyleArr, scaleTextArr, hexColorTextArr, clipping, centering) {
      //window.alert(this.textLayer1+" "+textLayer1)
      // we add text on space object based on the textLayer1 layer defined in this component 
      cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_AddTextOnSpaceObject(textLayer1, Id, leftScale, textStringArr, textStyleArr, scaleTextArr, hexColorTextArr, clipping, centering);
    });
    console.log('created CADViewerCanvas');
  },
  props: {
    msg: String,
    ServerBackEndUrl: String,
    ServerLocation: String,
    ServerUrl: String,
    FileName: String
  },
  mounted: function mounted() {
    // Register an event listener when the Vue component is ready
    window.addEventListener('resize', this.onResize);
    console.log('mounted');
    var ServerBackEndUrl = this.ServerBackEndUrl;
    var ServerLocation = this.ServerLocation;
    var ServerUrl = this.ServerUrl;
    var FileName = this.FileName;

    // Set all paths, and handlers, changes these depending on back-end server
    cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_debugMode(true);
    cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_setIconImageSize("floorPlan", 34, 44);

    // Set all paths, and handlers, changes these depending on back-end server
    cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_setAllServerPaths_and_Handlers(ServerBackEndUrl, ServerUrl, ServerLocation, "PHP", "ReactJS", "floorPlan");

    //      Setting all callback methods  - they have to be injected into the CADViewer class componnet
    cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_setCallbackMethod("cvjs_OnLoadEnd", cvjs_OnLoadEnd);
    cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_setCallbackMethod("cvjs_graphicalObjectOnChange", cvjs_graphicalObjectOnChange);
    cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_setCallbackMethod("cvjs_OnLoadEndRedlines", cvjs_OnLoadEndRedlines);
    cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_setCallbackMethod("cvjs_ObjectSelected", cvjs_ObjectSelected);
    cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_setCallbackMethod("cvjs_measurementCallback", cvjs_measurementCallback);
    cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_setCallbackMethod("cvjs_CalibrateMeasurementCallback", cvjs_CalibrateMeasurementCallback);
    cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_setCallbackMethod("cvjs_Url_callback", cvjs_Url_callback);
    cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_setCallbackMethod("cvjs_loadSpaceImage_UserConfiguration", cvjs_loadSpaceImage_UserConfiguration);
    cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_setCallbackMethod("cvjs_NoObjectSelected", cvjs_NoObjectSelected);
    cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_setCallbackMethod("cvjs_SVGfileObjectClicked", cvjs_SVGfileObjectClicked);
    cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_setCallbackMethod("cvjs_SVGfileObjectMouseEnter", cvjs_SVGfileObjectMouseEnter);
    cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_setCallbackMethod("cvjs_SVGfileObjectMouseLeave", cvjs_SVGfileObjectMouseLeave);
    cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_setCallbackMethod("cvjs_SVGfileObjectMouseMove", cvjs_SVGfileObjectMouseMove);
    cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_setCallbackMethod("cvjs_ParseDisplayDataMaps", cvjs_ParseDisplayDataMaps);
    cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_setCallbackMethod("cvjs_QuickCountCallback", cvjs_QuickCountCallback);
    cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_setCallbackMethod("cvjs_OnHyperlinkClick", cvjs_OnHyperlinkClick);
    cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_setCallbackMethod("cvjs_setUpStickyNotesRedlines", cvjs_setUpStickyNotesRedlines);
    cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_setCallbackMethod("custom_host_parser_PopUpMenu", custom_host_parser_PopUpMenu);
    cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_setCallbackMethod("cvjs_customHostParser", cvjs_customHostParser);
    cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_setCallbackMethod("drawPathsGeneric", drawPathsGeneric);
    cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_setCallbackMethod("cvjs_callbackForModalDisplay", cvjs_callbackForModalDisplay);
    cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_setCallbackMethod("cvjs_populateMyCustomPopUpBody", cvjs_populateMyCustomPopUpBody);
    cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_setCallbackMethod("cvjs_customModalPopUpBody", cvjs_customModalPopUpBody);
    cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_setCallbackMethod("cvjs_NoObjectSelectedStickyNotes", cvjs_NoObjectSelectedStickyNotes);
    cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_setCallbackMethod("cvjs_NoObjectSelectedHyperlinks", cvjs_NoObjectSelectedHyperlinks);
    cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_setCallbackMethod("cvjs_ObjectSelectedHyperlink", cvjs_ObjectSelectedHyperlink);
    cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_setCallbackMethod("cvjs_ObjectSelectedStickyNotes", cvjs_ObjectSelectedStickyNotes);
    cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_setCallbackMethod("cvjs_saveStickyNotesRedlinesUser", cvjs_saveStickyNotesRedlinesUser);
    cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_setCallbackMethod("cvjs_loadStickyNotesRedlinesUser", cvjs_loadStickyNotesRedlinesUser);
    cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_setCallbackMethod("my_own_clickmenu1", my_own_clickmenu1);
    cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_setCallbackMethod("my_own_clickmenu2", my_own_clickmenu2);
    cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_setCallbackMethod("cvjs_popupTitleClick", cvjs_popupTitleClick);
    cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_setCallbackMethod("cvjs_mousedown", cvjs_mousedown);
    cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_setCallbackMethod("cvjs_click", cvjs_click);
    cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_setCallbackMethod("cvjs_dblclick", cvjs_dblclick);
    cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_setCallbackMethod("cvjs_mouseout", cvjs_mouseout);
    cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_setCallbackMethod("cvjs_mouseover", cvjs_mouseover);
    cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_setCallbackMethod("cvjs_mouseleave", cvjs_mouseleave);
    cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_setCallbackMethod("cvjs_mouseenter", cvjs_mouseenter);
    cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_setCallbackMethod("cvjs_graphicalObjectCreated", cvjs_graphicalObjectCreated);

    // END set all callback methods

    // Location of installation folders
    // NOTE: THE LOCATION OF THE ServerLocation/ServerUrl VARIABLES ARE DEFINED IN /cadviewer/app/cv/XXXHandlerSettings.js	
    //	var ServerLocation = 
    //	var ServerUrl =    
    cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_CADViewerPro(true);

    // Pass over the location of the installation, will update the internal paths
    cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_PrintToPDFWindowRelativeSize(0.8);
    cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_setFileModalEditMode(false);

    // For "Merge DWG" / "Merge PDF" commands, set up the email server to send merged DWG files or merged PDF files with redlines/interactive highlight.
    // See php / xampp documentation on how to prepare your server
    cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_emailSettings_PDF_publish("From CAD Server", "my_from_address@mydomain.com", "my_cc_address@mydomain.com", "my_reply_to@mydomain.com");

    // CHANGE LANGUAGE - DEFAULT IS ENGLISH	
    cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_loadCADViewerLanguage("French", ""); //English
    // Available languages:  "English" ; "French, "Korean", "Spanish", "Portuguese", "Chinese-Simplified", "Chinese-Traditional"
    //cadviewer.cvjs_loadCADViewerLanguage("English", "/cadviewer/app/cv/cv-pro/custom_language_table/custom_cadviewerProLanguage.xml");

    cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_DisplayCoordinatesMenu("floorPlan", true);

    // 6.9.18
    // set SpaceObjectsCustomMenu location and json config file,  flag true to display SpaceObject Menu, false to hide
    cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_setSpaceObjectsCustomMenu("/content/customInsertSpaceObjectMenu/", "cadviewercustomspacecommands.json", true);

    // Set Icon Menu Interface controls. Users can: 
    // 1: Disable all icon interfaces
    //  cvjs_displayAllInterfaceControls(false, "floorPlan");  // disable all icons for user control of interface

    // 2: Disable either top menu icon menus or navigation menu, or both

    //cvjs_displayTopMenuIconBar(false, "floorPlan");  // disable top menu icon bar
    //cvjs_displayTopNavigationBar(false, "floorPlan");  // disable top navigation bar

    // 3: Users can change the number of top menu icon pages and the content of pages, based on a configuration file in folder /cadviewer/app/js/menu_config/    		
    cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_setTopMenuXML("floorPlan", "cadviewer_full_commands_01.xml", "");
    //cadviewer.cvjs_setTopMenuXML("floorPlan", "cadviewer_full_commands_01.xml", "/cadviewer/app/cv/cv-pro/menu_config/");
    //cadviewer.cvjs_setTopMenuXML("floorPlan", "cadviewer_menu_all_items_custom_commands.xml", "cadviewer/app/cv/cv-pro/menu_config/");

    // Initialize CADViewer  - needs the div name on the svg element on page that contains CADViewerJS and the location of the
    // main application "app" folder. It can be either absolute or relative

    // SETTINGS OF THE COLORS OF SPACES
    var cvjsRoomPolygonBaseAttributes = {
      fill: '#d8e1e3',
      //'#d8e1e3', // '#ffd7f4', //'#D3D3D3',   // #FFF   #ffd7f4
      "fill-opacity": 0.04,
      //"0.05",   // 0.1
      stroke: '#CCC',
      'stroke-width': 0.25,
      'stroke-linejoin': 'round'
    };
    var cvjsRoomPolygonHighlightAttributes = {
      fill: '#a4d7f4',
      "fill-opacity": "0.5",
      stroke: '#a4d7f4',
      'stroke-width': 0.75
    };
    var cvjsRoomPolygonSelectAttributes = {
      fill: '#5BBEF6',
      "fill-opacity": "0.5",
      stroke: '#5BBEF6',
      'stroke-width': 0.75
    };

    /** FIXED POP-UP MODAL  **/

    // THIS IS THE DESIGN OF THE pop-up MODAL WHEN CLICKING ON SPACES
    // KEEP METHODS NAME AS IS FOR NOW...............

    var my_cvjsPopUpBody = "<div class=\'cvjs_modal_1\' id=\'my_own_clickmenu1()\'>Custom<br>Menu 1<br><i class=\'fa fa-undo\'></i></div>";
    my_cvjsPopUpBody += "<div class=\'cvjs_modal_1\' id=\'my_own_clickmenu2()\'>Custom<br>Menu 2<br><i class=\'fa fa-info-circle\'></i></div>";
    my_cvjsPopUpBody += "<div class=\'cvjs_modal_1\' id=\'cvjs_zoomHere()\'>Zoom<br>Here<br><i class=\'fa fa-search-plus\'></i></div>";

    // Initialize CADViewer - needs the div name on the svg element on page that contains CADViewerJS and the location of the
    // And we intialize with the Space Object Custom values
    //  cvjs_InitCADViewer_highLight_popUp_app("floorPlan", ServerUrl+"app/", cvjsRoomPolygonBaseAttributes, cvjsRoomPolygonHighlightAttributes, cvjsRoomPolygonSelectAttributes, my_cvjsPopUpBody);

    //      cvjs_InitCADViewer_highLight_popUp_app("floorPlan", ServerUrl+ "/cadviewer/app/", cvjsRoomPolygonBaseAttributes, cvjsRoomPolygonHighlightAttributes, cvjsRoomPolygonSelectAttributes, my_cvjsPopUpBody );
    cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_InitCADViewer_highLight_popUp_app("floorPlan", "/cadviewer/app/", cvjsRoomPolygonBaseAttributes, cvjsRoomPolygonHighlightAttributes, cvjsRoomPolygonSelectAttributes, my_cvjsPopUpBody);

    // set the location to license key, typically the js folder in main app application folder ../app/cv/
    //cadviewer.cvjs_setLicenseKeyPath("/cadviewer/app/cv/");
    // alternatively, set the key directly, by pasting in the cvKey portion of the cvlicense.js file, note the JSON \" around all entities 	 
    cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_setLicenseKeyDirect('{ \"cvKey\": \"00110010 00110010 00110000 00110001 00110010 00110000 00110100 00110001 00110100 00111000 00110001 00110100 00110101 00110001 00110101 00110111 00110001 00110101 00111001 00110001 00110100 00111000 00110001 00110101 00110010 00110001 00110100 00110101 00110001 00110100 00110001 00110001 00110100 00110000 00110001 00111001 00110111 00110010 00110000 00110111 00110010 00110000 00110110 00110010 00110000 00110001 00110010 00110001 00110000 00110010 00110000 00111000 00110010 00110001 00110000 00110010 00110000 00111000 00110010 00110001 00110000 00110010 00110000 00110111 00110001 00111001 00111000 00110010 00110000 00110110 00110010 00110000 00111000 00110010 00110000 00110110 00110010 00110000 00110101 00110010 00110001 00110001 00110010 00110000 00111000 00110010 00110000 00110111 00110010 00110001 00110001 00110010 00110000 00110101 00110010 00110000 00110111 00110001 00111001 00111000 00110001 00110100 00110001 00110001 00110100 00110100 00110001 00110101 00111001 00110001 00110101 00110111 00110001 00110101 00110101 \" }');

    // Sets the icon interface for viewing, layerhanding, measurement, etc. only
    //cvjs_setIconInterfaceControls_ViewingOnly();
    // disable canvas interface.  For developers building their own interface
    // cvjs_setIconInterfaceControls_DisableIcons(true);

    cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_allowFileLoadToServer(true);

    //		cvjs_setUrl_singleDoubleClick(1);
    //		cvjs_encapsulateUrl_callback(true);

    // NOTE BELOW: THESE SETTINGS ARE FOR SERVER CONTROLS FOR UPLOAD OF REDLINES

    // NOTE BELOW: THESE SETTINGS ARE FOR SERVER CONTROLS FOR UPLOAD OF REDLINES, FILES, SPACE OBJECTS
    cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_setServerFileLocation_AbsolutePaths(ServerLocation + '/content/drawings/dwg/', ServerBackEndUrl + 'content/drawings/dwg/', "", "");
    cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_setRedlinesAbsolutePath(ServerBackEndUrl + '/content/redlines/fileloader_610/', ServerLocation + '/content/redlines/fileloader_610/');
    cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_setSpaceObjectsAbsolutePath(ServerBackEndUrl + '/content/spaceObjects/', ServerLocation + '/content/spaceObjects/');
    cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_setInsertImageObjectsAbsolutePath(ServerBackEndUrl + '/content/inserted_image_objects/', ServerLocation + '/content/inserted_image_objects/');
    cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_conversion_clearAXconversionParameters();

    // process layers for spaces  RL/TL
    cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_conversion_addAXconversionParameter("RL", "RM_");
    cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_conversion_addAXconversionParameter("TL", "RM_TXT");
    // calculate areas of spaces
    cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_conversion_addAXconversionParameter("LA", "");
    cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_conversion_addAXconversionParameter("last", "");
    // NOTE ABOVE: THESE SETTINGS ARE FOR SERVER CONTROLS FOR CONVERTING DWG, DXF, DWF files

    // FOR MEASUREMENT ENABLE HANDLE PROCESSING
    cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_conversion_addAXconversionParameter("hlall", "");

    // Load file - needs the svg div name and name and path of file to load
    cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_LoadDrawing("floorPlan", FileName);

    // set maximum CADViewer canvas side
    cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_resizeWindow_position("floorPlan");

    // alternatively set a fixed CADViewer canvas size
    //	cvjs_resizeWindow_fixedSize(600, 400, "floorPlan");			   
  },

  name: 'CADViewer01',
  methods: {
    onResize: function onResize(e) {
      console.log("RESIZE");
      //  cadviewer resize event 
      cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_resizeWindow_position("floorPlan");
    },
    clearTextLayer: function clearTextLayer() {
      textLayer1 = cadviewer__WEBPACK_IMPORTED_MODULE_1___default().cvjs_clearLayer(textLayer1);
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./src/App.vue?vue&type=template&id=7ba5bd90&":
/*!*************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./src/App.vue?vue&type=template&id=7ba5bd90& ***!
  \*************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render),
/* harmony export */   "staticRenderFns": () => (/* binding */ staticRenderFns)
/* harmony export */ });
var render = function render() {
  var _vm = this,
    _c = _vm._self._c;
  return _c("div", {
    attrs: {
      id: "app"
    }
  }, [_c("app-cadviewercanvas", {
    ref: "cadviewercanvas",
    attrs: {
      ServerBackEndUrl: _vm.ServerBackEndUrl,
      ServerLocation: _vm.ServerLocation,
      ServerUrl: _vm.ServerUrl,
      FileName: _vm.FileName
    }
  })], 1);
};
var staticRenderFns = [];
render._withStripped = true;


/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./src/components/CADViewerCanvas.vue?vue&type=template&id=cfb03220&scoped=true&":
/*!************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./src/components/CADViewerCanvas.vue?vue&type=template&id=cfb03220&scoped=true& ***!
  \************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render),
/* harmony export */   "staticRenderFns": () => (/* binding */ staticRenderFns)
/* harmony export */ });
var render = function render() {
  var _vm = this,
    _c = _vm._self._c;
  return _vm._m(0);
};
var staticRenderFns = [function () {
  var _vm = this,
    _c = _vm._self._c;
  return _c("div", {
    staticClass: "cadviewerCanvasTest01"
  }, [_c("div", {
    attrs: {
      id: "floorPlan"
    }
  })]);
}];
render._withStripped = true;


/***/ }),

/***/ "./node_modules/css-loader/dist/cjs.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/vue-loader/lib/index.js??vue-loader-options!./src/components/CADViewerCanvas.vue?vue&type=style&index=0&id=cfb03220&scoped=true&lang=css&":
/*!***************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/vue-loader/lib/index.js??vue-loader-options!./src/components/CADViewerCanvas.vue?vue&type=style&index=0&id=cfb03220&scoped=true&lang=css& ***!
  \***************************************************************************************************************************************************************************************************************************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_css_loader_dist_runtime_noSourceMaps_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../node_modules/css-loader/dist/runtime/noSourceMaps.js */ "./node_modules/css-loader/dist/runtime/noSourceMaps.js");
/* harmony import */ var _node_modules_css_loader_dist_runtime_noSourceMaps_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_css_loader_dist_runtime_noSourceMaps_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../node_modules/css-loader/dist/runtime/api.js */ "./node_modules/css-loader/dist/runtime/api.js");
/* harmony import */ var _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_1__);
// Imports


var ___CSS_LOADER_EXPORT___ = _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_1___default()((_node_modules_css_loader_dist_runtime_noSourceMaps_js__WEBPACK_IMPORTED_MODULE_0___default()));
// Module
___CSS_LOADER_EXPORT___.push([module.id, "\n#floorPlan[data-v-cfb03220] {\n\ttext-align: left;\n\tmargin-top: 30px;\n  \tmargin-left: 2px;   /* margin-left: 50px;   */\n} \n \n\n", ""]);
// Exports
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (___CSS_LOADER_EXPORT___);


/***/ }),

/***/ "./node_modules/style-loader/dist/cjs.js!./node_modules/css-loader/dist/cjs.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/vue-loader/lib/index.js??vue-loader-options!./src/components/CADViewerCanvas.vue?vue&type=style&index=0&id=cfb03220&scoped=true&lang=css&":
/*!*******************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader/dist/cjs.js!./node_modules/css-loader/dist/cjs.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/vue-loader/lib/index.js??vue-loader-options!./src/components/CADViewerCanvas.vue?vue&type=style&index=0&id=cfb03220&scoped=true&lang=css& ***!
  \*******************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! !../../node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js */ "./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js");
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _node_modules_style_loader_dist_runtime_styleDomAPI_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! !../../node_modules/style-loader/dist/runtime/styleDomAPI.js */ "./node_modules/style-loader/dist/runtime/styleDomAPI.js");
/* harmony import */ var _node_modules_style_loader_dist_runtime_styleDomAPI_js__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_dist_runtime_styleDomAPI_js__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _node_modules_style_loader_dist_runtime_insertBySelector_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../node_modules/style-loader/dist/runtime/insertBySelector.js */ "./node_modules/style-loader/dist/runtime/insertBySelector.js");
/* harmony import */ var _node_modules_style_loader_dist_runtime_insertBySelector_js__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_dist_runtime_insertBySelector_js__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _node_modules_style_loader_dist_runtime_setAttributesWithoutAttributes_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! !../../node_modules/style-loader/dist/runtime/setAttributesWithoutAttributes.js */ "./node_modules/style-loader/dist/runtime/setAttributesWithoutAttributes.js");
/* harmony import */ var _node_modules_style_loader_dist_runtime_setAttributesWithoutAttributes_js__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_dist_runtime_setAttributesWithoutAttributes_js__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _node_modules_style_loader_dist_runtime_insertStyleElement_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! !../../node_modules/style-loader/dist/runtime/insertStyleElement.js */ "./node_modules/style-loader/dist/runtime/insertStyleElement.js");
/* harmony import */ var _node_modules_style_loader_dist_runtime_insertStyleElement_js__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_dist_runtime_insertStyleElement_js__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _node_modules_style_loader_dist_runtime_styleTagTransform_js__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! !../../node_modules/style-loader/dist/runtime/styleTagTransform.js */ "./node_modules/style-loader/dist/runtime/styleTagTransform.js");
/* harmony import */ var _node_modules_style_loader_dist_runtime_styleTagTransform_js__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_dist_runtime_styleTagTransform_js__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var _node_modules_css_loader_dist_cjs_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_vue_loader_lib_index_js_vue_loader_options_CADViewerCanvas_vue_vue_type_style_index_0_id_cfb03220_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! !!../../node_modules/css-loader/dist/cjs.js!../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../node_modules/vue-loader/lib/index.js??vue-loader-options!./CADViewerCanvas.vue?vue&type=style&index=0&id=cfb03220&scoped=true&lang=css& */ "./node_modules/css-loader/dist/cjs.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/vue-loader/lib/index.js??vue-loader-options!./src/components/CADViewerCanvas.vue?vue&type=style&index=0&id=cfb03220&scoped=true&lang=css&");

      
      
      
      
      
      
      
      
      

var options = {};

options.styleTagTransform = (_node_modules_style_loader_dist_runtime_styleTagTransform_js__WEBPACK_IMPORTED_MODULE_5___default());
options.setAttributes = (_node_modules_style_loader_dist_runtime_setAttributesWithoutAttributes_js__WEBPACK_IMPORTED_MODULE_3___default());

      options.insert = _node_modules_style_loader_dist_runtime_insertBySelector_js__WEBPACK_IMPORTED_MODULE_2___default().bind(null, "head");
    
options.domAPI = (_node_modules_style_loader_dist_runtime_styleDomAPI_js__WEBPACK_IMPORTED_MODULE_1___default());
options.insertStyleElement = (_node_modules_style_loader_dist_runtime_insertStyleElement_js__WEBPACK_IMPORTED_MODULE_4___default());

var update = _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default()(_node_modules_css_loader_dist_cjs_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_vue_loader_lib_index_js_vue_loader_options_CADViewerCanvas_vue_vue_type_style_index_0_id_cfb03220_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_6__["default"], options);




       /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_css_loader_dist_cjs_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_vue_loader_lib_index_js_vue_loader_options_CADViewerCanvas_vue_vue_type_style_index_0_id_cfb03220_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_6__["default"] && _node_modules_css_loader_dist_cjs_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_vue_loader_lib_index_js_vue_loader_options_CADViewerCanvas_vue_vue_type_style_index_0_id_cfb03220_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_6__["default"].locals ? _node_modules_css_loader_dist_cjs_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_vue_loader_lib_index_js_vue_loader_options_CADViewerCanvas_vue_vue_type_style_index_0_id_cfb03220_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_6__["default"].locals : undefined);


/***/ }),

/***/ "./src/App.vue":
/*!*********************!*\
  !*** ./src/App.vue ***!
  \*********************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _App_vue_vue_type_template_id_7ba5bd90___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./App.vue?vue&type=template&id=7ba5bd90& */ "./src/App.vue?vue&type=template&id=7ba5bd90&");
/* harmony import */ var _App_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./App.vue?vue&type=script&lang=js& */ "./src/App.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _App_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _App_vue_vue_type_template_id_7ba5bd90___WEBPACK_IMPORTED_MODULE_0__.render,
  _App_vue_vue_type_template_id_7ba5bd90___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "src/App.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./src/components/CADViewerCanvas.vue":
/*!********************************************!*\
  !*** ./src/components/CADViewerCanvas.vue ***!
  \********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _CADViewerCanvas_vue_vue_type_template_id_cfb03220_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./CADViewerCanvas.vue?vue&type=template&id=cfb03220&scoped=true& */ "./src/components/CADViewerCanvas.vue?vue&type=template&id=cfb03220&scoped=true&");
/* harmony import */ var _CADViewerCanvas_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./CADViewerCanvas.vue?vue&type=script&lang=js& */ "./src/components/CADViewerCanvas.vue?vue&type=script&lang=js&");
/* harmony import */ var _CADViewerCanvas_vue_vue_type_style_index_0_id_cfb03220_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./CADViewerCanvas.vue?vue&type=style&index=0&id=cfb03220&scoped=true&lang=css& */ "./src/components/CADViewerCanvas.vue?vue&type=style&index=0&id=cfb03220&scoped=true&lang=css&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! !../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");



;


/* normalize component */

var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _CADViewerCanvas_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _CADViewerCanvas_vue_vue_type_template_id_cfb03220_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render,
  _CADViewerCanvas_vue_vue_type_template_id_cfb03220_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  "cfb03220",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "src/components/CADViewerCanvas.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./src/App.vue?vue&type=script&lang=js&":
/*!**********************************************!*\
  !*** ./src/App.vue?vue&type=script&lang=js& ***!
  \**********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_node_modules_vue_loader_lib_index_js_vue_loader_options_App_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../node_modules/babel-loader/lib/index.js!../node_modules/vue-loader/lib/index.js??vue-loader-options!./App.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js!./node_modules/vue-loader/lib/index.js??vue-loader-options!./src/App.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_node_modules_vue_loader_lib_index_js_vue_loader_options_App_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./src/components/CADViewerCanvas.vue?vue&type=script&lang=js&":
/*!*********************************************************************!*\
  !*** ./src/components/CADViewerCanvas.vue?vue&type=script&lang=js& ***!
  \*********************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_node_modules_vue_loader_lib_index_js_vue_loader_options_CADViewerCanvas_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../node_modules/babel-loader/lib/index.js!../../node_modules/vue-loader/lib/index.js??vue-loader-options!./CADViewerCanvas.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js!./node_modules/vue-loader/lib/index.js??vue-loader-options!./src/components/CADViewerCanvas.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_node_modules_vue_loader_lib_index_js_vue_loader_options_CADViewerCanvas_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./src/App.vue?vue&type=template&id=7ba5bd90&":
/*!****************************************************!*\
  !*** ./src/App.vue?vue&type=template&id=7ba5bd90& ***!
  \****************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_App_vue_vue_type_template_id_7ba5bd90___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_App_vue_vue_type_template_id_7ba5bd90___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_App_vue_vue_type_template_id_7ba5bd90___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../node_modules/babel-loader/lib/index.js!../node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!../node_modules/vue-loader/lib/index.js??vue-loader-options!./App.vue?vue&type=template&id=7ba5bd90& */ "./node_modules/babel-loader/lib/index.js!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./src/App.vue?vue&type=template&id=7ba5bd90&");


/***/ }),

/***/ "./src/components/CADViewerCanvas.vue?vue&type=template&id=cfb03220&scoped=true&":
/*!***************************************************************************************!*\
  !*** ./src/components/CADViewerCanvas.vue?vue&type=template&id=cfb03220&scoped=true& ***!
  \***************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_CADViewerCanvas_vue_vue_type_template_id_cfb03220_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_CADViewerCanvas_vue_vue_type_template_id_cfb03220_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_CADViewerCanvas_vue_vue_type_template_id_cfb03220_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../node_modules/babel-loader/lib/index.js!../../node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!../../node_modules/vue-loader/lib/index.js??vue-loader-options!./CADViewerCanvas.vue?vue&type=template&id=cfb03220&scoped=true& */ "./node_modules/babel-loader/lib/index.js!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./src/components/CADViewerCanvas.vue?vue&type=template&id=cfb03220&scoped=true&");


/***/ }),

/***/ "./src/components/CADViewerCanvas.vue?vue&type=style&index=0&id=cfb03220&scoped=true&lang=css&":
/*!*****************************************************************************************************!*\
  !*** ./src/components/CADViewerCanvas.vue?vue&type=style&index=0&id=cfb03220&scoped=true&lang=css& ***!
  \*****************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_dist_cjs_js_node_modules_css_loader_dist_cjs_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_vue_loader_lib_index_js_vue_loader_options_CADViewerCanvas_vue_vue_type_style_index_0_id_cfb03220_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../node_modules/style-loader/dist/cjs.js!../../node_modules/css-loader/dist/cjs.js!../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../node_modules/vue-loader/lib/index.js??vue-loader-options!./CADViewerCanvas.vue?vue&type=style&index=0&id=cfb03220&scoped=true&lang=css& */ "./node_modules/style-loader/dist/cjs.js!./node_modules/css-loader/dist/cjs.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/vue-loader/lib/index.js??vue-loader-options!./src/components/CADViewerCanvas.vue?vue&type=style&index=0&id=cfb03220&scoped=true&lang=css&");


/***/ })

}]);
//# sourceMappingURL=cadviewer-sharing.js.map?v=45929adc4b119865afa9