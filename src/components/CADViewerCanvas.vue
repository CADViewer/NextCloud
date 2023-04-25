<template>
	<div id="cadviewer_app_canvas" class="modal__content">
		<app-nc-modal @close="closeModal" v-if="modal" :title="title" size="full" :canClose="canClose">
			<div class="cadviewerCanvasTest01 cadviewer-bootstrap cadviewer-core-styles">
				<div id="floorPlan"></div>
			</div>
			<div class="loading-bloc" v-if="showLoading">
				<app-nc-loading-icon :size="40"/>
			</div>
			<div
				class="header-close close_button"
				@click="closeModal">
					<app-close :size="iconSize" />
			</div>
			<div id="cvjs_controls_min_right" class="cvjs_controls_min" style="height: 60px; width: 29px; position: absolute; right: 10px; top: 100px; left: auto; z-index: 100000;">
				<li style="list-style-type: none; width: 27px; height: 30px; display: flex; cursor: pointer;" id="zoom-extents_floorPlan_svg" @click="shareThisFile">
					<i class="fa fa-share" style="height: 20px; width: 27px; font-size: 14px;"></i>
				</li>
				<li style="list-style-type: none; width: 27px; height: 30px; display: flex; cursor: pointer;" @click="commentThisFile">
					<i class="fa fa-comment" style="height: 20px; width: 27px; font-size: 14px;"></i>
				</li>
			</div>
<!-- 
			<div
				class="header-close compare_button"
				@click="shareThisFile">
				<span class="icon-share"></span>
				Share
			</div>
			<div
				class="header-close load_button"
				@click="commentThisFile">
				<span class="icon-comment"></span>
				Comment
			</div> -->
		</app-nc-modal>
	</div>
</template>


<script>
import cadviewer from 'cadviewer';
import { getLanguage } from '@nextcloud/l10n'
import NcModalVue from "@nextcloud/vue/dist/Components/NcModal.js";
import NcLoadingIcon from "@nextcloud/vue/dist/Components/NcLoadingIcon.js"
import Close from 'vue-material-design-icons/Close.vue'

import {eventBus} from "../main.js";

var textLayer1; 

var  selected_handles = [];
var  handle_selector = false;
var  current_selected_handle = "";


// We should to define all the CADViewer methods in which we are getting information return from CADViewer 
// THEY CAN BE PLACEHOLDERS ONLY     
// // //  // // // //


//export function cvjs_OnLoadEnd(){

function cvjs_OnLoadEnd(UserName, UserId){
	// generic callback method, called when the drawing is loaded
	// here you fill in your stuff, call DB, set up arrays, etc..
	// this method MUST be retained as a dummy method! - if not implemeted -

	cadviewer.cvjs_resetZoomPan("floorPlan");

	// set a value for redlines
	cadviewer.cvjs_setCurrentStickyNoteValues_NameUserId(UserName, UserId );
	cadviewer.cvjs_setCurrentRedlineValues_NameUserid(UserName, UserId);
	// cadviewer.cvjs_dragBackgroundToFront_SVG("floorPlan");					
	//cvjs_initZeroWidthHandling("floorPlan", 1.0);			

	textLayer1 = cadviewer.cvjs_clearLayer(textLayer1);
	
	// resize the floorplan drawing at load
	cadviewer.cvjs_resizeWindow_position("floorPlan" );


	// load in redlines

	startMethodRed();
	// API call to load stickynotes and redlines
	cadviewer.cvjs_loadStickyNotesRedlines("floorPlan");


}

function cvjs_OnLoadEndRedlines(){
	// generic callback method, called when the redline is loaded
	// here you fill in your stuff, hide specific users and lock specific users
	// this method MUST be retained as a dummy method! - if not implemeted -

	// I am hiding users added to the hide user list
	cadviewer.cvjs_hideAllRedlines_HiddenUsersList();

	// I am freezing users added to the lock user list
	cadviewer.cvjs_lockAllRedlines_LockedUsersList();
}

// Callback Method on Creation and Delete 
//export function cvjs_graphicalObjectOnChange(type, graphicalObject, spaceID){
function cvjs_graphicalObjectOnChange(type, graphicalObject, spaceID){

    try{
        var myobject;
        // do something with the graphics object created! 
    //	window.alert("CALLBACK: cvjs_graphicalObjectOnChange: "+type+" "+graphicalObject+" "+spaceID+" indexSpace: "+graphicalObject.toLowerCase().indexOf("space"));
        console.log("CALLBACK: cvjs_graphicalObjectOnChange: "+type+" "+graphicalObject+" "+spaceID+" indexSpace: "+graphicalObject.toLowerCase().indexOf("space"));

        if (type == 'Create' && graphicalObject.toLowerCase().indexOf("space")>-1 && graphicalObject.toLowerCase().indexOf("circle")==-1){
                
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

            myobject = cadviewer.cvjs_returnSpaceObjectID(spaceID);
            // I can save this object into my database, and then use command 
            // cvjs_setSpaceObjectDirect(jsonSpaceObject) 
            // when I am recreating the content of the drawing at load
            // for the fun of it, display the SVG geometry of the space:			
            console.log("This is the SVG: "+myobject.outerhtml)
        }


        if (type == 'Delete' && graphicalObject.toLowerCase().indexOf("space")>-1 ){
            // remove this entry from my DB

            window.alert("We have deleted: "+spaceID)
        }


        if (type == 'Move' && graphicalObject.toLowerCase().indexOf("space")>-1 ){
            // remove this entry from my DB

            console.log("This object has been moved: "+spaceID)		
            myobject = cadviewer.cvjs_returnSpaceObjectID(spaceID);

        }
    }
    catch(err){
        console.log("cvjs_graphicalObjectOnChange: "+err)
    }


}

function startMethodRed(){

	// we keep all redlines in one single file  NOTE! issues with delete?  
	var v1 = "/content/redlines/v7/"+cadviewer.cvjs_computeDynamicRedlineNamePath()+"all-users.json";
	var v2 = "/content/redlines/v7/"+cadviewer.cvjs_computeDynamicRedlineNamePath()+"all-users.json";

	cadviewer.cvjs_setStickyNoteRedlineUrl(v1);
	cadviewer.cvjs_setStickyNoteSaveRedlineUrl(v2);
}



function cvjs_saveStickyNotesRedlinesUser(){

// there are two modes, user handling of redlines
// alternatively use the build in redline file manager

//cadviewer.cvjs_openRedlineSaveModal("floorPlan");

// custom method startMethodRed to set the name and location of redline to save
// see implementation below
	startMethodRed();
	// API call to save stickynotes and redlines
	cadviewer.cvjs_saveStickyNotesRedlines("floorPlan");
}


// This method is linked to the load redline icon in the imagemap
function cvjs_loadStickyNotesRedlinesUser(){

//cadviewer.cvjs_openRedlineLoadModal("floorPlan");

// first the drawing needs to be cleared of stickynotes and redlines
//cvjs_deleteAllStickyNotes();
//cvjs_deleteAllRedlines();

// custom method startMethodRed to set the name and location of redline to load
// see implementation below

	startMethodRed();
	// API call to load stickynotes and redlines
	cadviewer.cvjs_loadStickyNotesRedlines("floorPlan");
}


// Here we are writing a basic function that will be used in the PopUpMenu
// this is template on all the good stuff users can add
function my_own_clickmenu1(){
var id = cadviewer.cvjs_idObjectClicked();
//		var node = cvjs_NodeObjectClicked();
window.alert("Custom menu item 1: Here developers can implement their own methods, the look and feel of the menu is controlled in the settings.  Clicked object ID is: "+id);
}

// Here we are writing a basic function that will be used in the PopUpMenu
// this is template on all the good stuff users can add
function my_own_clickmenu2(){
var id = cadviewer.cvjs_idObjectClicked();
//var node = cvjs_NodeObjectClicked();

window.alert("Custom menu item 2: Here developers can implement their own methods, the look and feel of the menu is controlled in the settings. Clicked object ID is: "+id);
//window.alert("Custom menu item 2: Clicked object Node is: "+node);
}

function cvjs_popupTitleClick(roomid){
	window.alert("we have clicked "+roomid);	
}
   

// HANDLING OF MOUSE OPERATION


// ENABLE ALL API EVENT HANDLES FOR AUTOCAD Handles
function cvjs_mousedown(id, handle, entity){

}

function cvjs_click(id, handle, entity){


	try{
		console.log("click "+id+"  "+handle);
		// if we click on an object, then we add to the handle list
		if (id!="dragcanvas")
			if (handle_selector){
				selected_handles.push({id,handle});
				current_selected_handle = handle;
			}

		// tell to update the Scroll bar 
		//vqUpdateScrollbar(id, handle);
		// window.alert("We have clicked an entity: "+entity.substring(4)+"\r\nThe AutoCAD Handle id: "+handle+"\r\nThe svg id is: "+id+"\r\nHighlight SQL pane entry");
	}
	catch(err){
		console.log("click: "+err);
	}

}

function cvjs_dblclick(id, handle, entity){

console.log("mysql dblclick "+id+"  "+handle);
window.alert("We have double clicked entity with AutoCAD Handle: "+handle+"\r\nThe svg id is: "+id);
}

function cvjs_mouseout(id, handle, entity){

  console.log("mysql mouseout "+id+"  "+handle);
  
  if (current_selected_handle == handle){
      // do nothing
  }
  else{
      cadviewer.cvjs_mouseout_handleObjectStyles(id, handle);
  }
}

function cvjs_mouseover(id, handle, entity){

console.log("mysql mouseover "+id+"  "+handle); // +"  "+jQuery("#"+id).css("color"))
//cvjs_mouseover_handleObjectPopUp(id, handle);	
}

function cvjs_mouseleave(id, handle, entity){

console.log("mysql mouseleave "+id+"  "+handle); // +"  "+jQuery("#"+id).css("color"));
}


function cvjs_mouseenter(id, handle, entity){
//	cvjs_mouseenter_handleObjectStyles("#a0a000", 4.0, 1.0, id, handle);
//	cvjs_mouseenter_handleObjectStyles("#ffcccb", 5.0, 0.7, true, id, handle);


    cadviewer.cvjs_mouseenter_handleObjectStyles("#F00", 2.0, 1.0, true, id, handle);

}

// END OF MOUSE OPERATION

function cvjs_graphicalObjectCreated(graphicalObject){
// do something with the graphics object created!
//		window.alert(graphicalObject);

}

function cvjs_ObjectSelected(rmid){
	// placeholder for method in tms_cadviewerjs_modal_1_0_14.js   - must be removed when in creation mode and using creation modal
}

function cvjs_measurementCallback(){
}
function cvjs_CalibrateMeasurementCallback(){
}
function cvjs_Url_callback(){
}
function cvjs_loadSpaceImage_UserConfiguration(){
}
function cvjs_NoObjectSelected(){
}
function cvjs_SVGfileObjectClicked(){
}
function cvjs_SVGfileObjectMouseEnter(){
}
function cvjs_SVGfileObjectMouseLeave(){
}
function cvjs_SVGfileObjectMouseMove(){
};
function cvjs_ParseDisplayDataMaps(){
};
function cvjs_QuickCountCallback(){
};
function cvjs_OnHyperlinkClick(){
};
function cvjs_setUpStickyNotesRedlines(){
};
function custom_host_parser_PopUpMenu(){
};
function cvjs_customHostParser(){
}
function drawPathsGeneric(){
};
function cvjs_callbackForModalDisplay(){
};
function cvjs_populateMyCustomPopUpBody(){
};
function cvjs_customModalPopUpBody(){
};
function cvjs_NoObjectSelectedStickyNotes(){
};
function cvjs_NoObjectSelectedHyperlinks(){
};
function cvjs_ObjectSelectedHyperlink(){
};
function cvjs_ObjectSelectedStickyNotes(){
};


export default {

  created: function (){
    
    eventBus.$on('clearTextLayer', () => {
              
              this.clearTextLayer();
	});
    

    eventBus.$on('AddTextOnSpaceObject_wrapper', (Id, leftScale, textStringArr, textStyleArr, scaleTextArr, hexColorTextArr, clipping, centering) => {
			  //window.alert(this.textLayer1+" "+textLayer1)
              // we add text on space object based on the textLayer1 layer defined in this component 
			  cadviewer.cvjs_AddTextOnSpaceObject(textLayer1, Id, leftScale, textStringArr, textStyleArr, scaleTextArr, hexColorTextArr, clipping, centering)
	});

	// Add context menu and default file open handler for autocad file
    // this.initViewCadFile("application/octet-stream", true);
    this.initViewCadFile("application/acad", true);
    this.initViewCadFile("application/dxf", true);
    this.initViewCadFile("application/x-dwf", true);
    this.initViewCadFile("application/dgn", true);
    // Add context menu for others documents
    this.initViewCadFile("application/pdf", false);
    this.initViewCadFile("image/tiff", false);
    this.initViewCadFile("image/tif", false);
    // Add context menu for images
    this.initViewCadFile("image/png", false);
    this.initViewCadFile("image/jpeg", false);
    this.initViewCadFile("image/gif", false);
	this.initViewCadFile("image/svg+xml", false);
	this.initViewCadFile("application/octet-stream", false);

  },
  data() {
	return {
		showLoading: true,
		title: "",
		parentDir: "",
		canClose: false,
		modal: false,
		iconSize: 24,
		ModalTitle: "",
		ServerBackEndUrl: "",
		ServerLocation: "",
		ServerUrl: "",
		FileName: "",
		licenceKey: "",
		skin: "deepblue",
		nextcloudColor: "",
		ISOtimeStamp: "",
		ContentDir: "",
		UserName: "",
		UserId: "",
	}
  },
  computed: {
	closeButtonAriaLabel() {
			return t('Close modal')
	},
  },
  props: {
  },

  mounted: () => {

  },
  methods: {
	initCadviewer(axparameters){
		// this.movePdf("f1613016134.svg","/Photos");
		// Register an event listener when the Vue component is ready
		window.addEventListener('resize', this.onResize)
		var self = this;
		
		setTimeout(function() {
			self.onResize()
		}, 1000)

		console.log('mounted');

			var ServerBackEndUrl = this.ServerBackEndUrl;

			var LicenceKey = this.LicenceKey;
			var ServerLocation = this.ServerLocation;
			var ServerUrl = this.ServerUrl;
			var ISOtimeStamp = this.ISOtimeStamp;

			if (ServerLocation.slice(-1) == "/"){

			}
			else{
				ServerLocation += "/";
			}

			if (ServerUrl.indexOf("/converter") == -1){
				ServerUrl += "converter/";
			}

			var FileName = this.FileName;
			var UserName = this.UserName;
			var UserId = this.UserId;

			// Set all paths, and handlers, changes these depending on back-end server
			cadviewer.cvjs_debugMode(true);

			console.log("ServerBackEndUrl, ServerLocation, ServerUrl, FileName declared");
			//console.log("ServerBackEndUrl="+ServerBackEndUrl+"XX ServerLocation="+ServerLocation+"XX FileName="+FileName+"XX ServerUrl="+ServerUrl+"XX");
			
			cadviewer.cvjs_setIconImageSize("floorPlan",34, 44);
			// 
			// Set all paths, and handlers, changes these depending on back-end server
			cadviewer.cvjs_setAllServerPaths_and_Handlers(ServerBackEndUrl, ServerUrl, ServerLocation, "PHP", "VueJS", "floorPlan");
			
			//      Setting all callback methods  - they have to be injected into the CADViewer class componnet
			/*
			cadviewer.cvjs_setCallbackMethod("cvjs_OnLoadEnd", () => {
				cvjs_OnLoadEnd();
				self.onResize();
			});
			*/
			// 


			/*
			// we need to handle SVG separately from bitmaps and drawings
			if (FileName.indexOf(".svg")>-1 || FileName.indexOf("SVG")>-1) {
				//console.log("FileName is SVG");

				if (FileName.indexOf("/var/www/html/")>-1){

					//console.log("Filename is /var/www/html/");

					FileName =  ServerBackEndUrl.substring(0, ServerBackEndUrl.indexOf("/apps")+1) + FileName.substring(FileName.indexOf("/data/"));
				}
			}

			//console.log("FileName="+FileName);
			*/

			// NextCloud setting of save screeen method for save to CADViewer-Markup
			cadviewer.cvjs_saveScreenAsPDF_serverSettings(true, this.movePdf, "", "NextCloud", true, false, true);

			// 8.26.1  8.26.6  
			cadviewer.cvjs_compareDrawings_externalModal(true, this.chooseFileToCompareWith, "NextCloud");
			cadviewer.cvjs_fileLoadModal_externalModal(true, this.chooseFileToLoad, "NextCloud");


			cadviewer.cvjs_setCallbackMethod("cvjs_OnLoadEnd", () => cvjs_OnLoadEnd(UserName, UserId));
			cadviewer.cvjs_setCallbackMethod("cvjs_graphicalObjectOnChange", cvjs_graphicalObjectOnChange);
			cadviewer.cvjs_setCallbackMethod("cvjs_OnLoadEndRedlines", cvjs_OnLoadEndRedlines);
			cadviewer.cvjs_setCallbackMethod("cvjs_ObjectSelected", cvjs_ObjectSelected);
			cadviewer.cvjs_setCallbackMethod("cvjs_measurementCallback", cvjs_measurementCallback);
			cadviewer.cvjs_setCallbackMethod("cvjs_CalibrateMeasurementCallback", cvjs_CalibrateMeasurementCallback);
			cadviewer.cvjs_setCallbackMethod("cvjs_Url_callback", cvjs_Url_callback);
			cadviewer.cvjs_setCallbackMethod("cvjs_loadSpaceImage_UserConfiguration", cvjs_loadSpaceImage_UserConfiguration);
			cadviewer.cvjs_setCallbackMethod("cvjs_NoObjectSelected", cvjs_NoObjectSelected);
			cadviewer.cvjs_setCallbackMethod("cvjs_SVGfileObjectClicked", cvjs_SVGfileObjectClicked);
			cadviewer.cvjs_setCallbackMethod("cvjs_SVGfileObjectMouseEnter", cvjs_SVGfileObjectMouseEnter);
			cadviewer.cvjs_setCallbackMethod("cvjs_SVGfileObjectMouseLeave", cvjs_SVGfileObjectMouseLeave);
			cadviewer.cvjs_setCallbackMethod("cvjs_SVGfileObjectMouseMove", cvjs_SVGfileObjectMouseMove);
			cadviewer.cvjs_setCallbackMethod("cvjs_ParseDisplayDataMaps", cvjs_ParseDisplayDataMaps);
			cadviewer.cvjs_setCallbackMethod("cvjs_QuickCountCallback", cvjs_QuickCountCallback);
			cadviewer.cvjs_setCallbackMethod("cvjs_OnHyperlinkClick", cvjs_OnHyperlinkClick);
			cadviewer.cvjs_setCallbackMethod("cvjs_setUpStickyNotesRedlines", cvjs_setUpStickyNotesRedlines);
			cadviewer.cvjs_setCallbackMethod("custom_host_parser_PopUpMenu", custom_host_parser_PopUpMenu);
			cadviewer.cvjs_setCallbackMethod("cvjs_customHostParser", cvjs_customHostParser);
			cadviewer.cvjs_setCallbackMethod("drawPathsGeneric", drawPathsGeneric );
			cadviewer.cvjs_setCallbackMethod("cvjs_callbackForModalDisplay", cvjs_callbackForModalDisplay);
			cadviewer.cvjs_setCallbackMethod("cvjs_populateMyCustomPopUpBody", cvjs_populateMyCustomPopUpBody);
			cadviewer.cvjs_setCallbackMethod("cvjs_customModalPopUpBody", cvjs_customModalPopUpBody);
			cadviewer.cvjs_setCallbackMethod("cvjs_NoObjectSelectedStickyNotes", cvjs_NoObjectSelectedStickyNotes);
			cadviewer.cvjs_setCallbackMethod("cvjs_NoObjectSelectedHyperlinks", cvjs_NoObjectSelectedHyperlinks);
			cadviewer.cvjs_setCallbackMethod("cvjs_ObjectSelectedHyperlink", cvjs_ObjectSelectedHyperlink);
			cadviewer.cvjs_setCallbackMethod("cvjs_ObjectSelectedStickyNotes", cvjs_ObjectSelectedStickyNotes);
			cadviewer.cvjs_setCallbackMethod("cvjs_saveStickyNotesRedlinesUser", cvjs_saveStickyNotesRedlinesUser);
			cadviewer.cvjs_setCallbackMethod("cvjs_loadStickyNotesRedlinesUser", cvjs_loadStickyNotesRedlinesUser);
			cadviewer.cvjs_setCallbackMethod("my_own_clickmenu1", my_own_clickmenu1);
			cadviewer.cvjs_setCallbackMethod("my_own_clickmenu2", my_own_clickmenu2);
			cadviewer.cvjs_setCallbackMethod("cvjs_popupTitleClick", cvjs_popupTitleClick);
			cadviewer.cvjs_setCallbackMethod("cvjs_mousedown", cvjs_mousedown);
			cadviewer.cvjs_setCallbackMethod("cvjs_click", cvjs_click);
			cadviewer.cvjs_setCallbackMethod("cvjs_dblclick", cvjs_dblclick);
			cadviewer.cvjs_setCallbackMethod("cvjs_mouseout", cvjs_mouseout);
			cadviewer.cvjs_setCallbackMethod("cvjs_mouseover", cvjs_mouseover);
			cadviewer.cvjs_setCallbackMethod("cvjs_mouseleave", cvjs_mouseleave);
			cadviewer.cvjs_setCallbackMethod("cvjs_mouseenter", cvjs_mouseenter);
			cadviewer.cvjs_setCallbackMethod("cvjs_graphicalObjectCreated", cvjs_graphicalObjectCreated);

			// END set all callback methods

			// Location of installation folders
			// NOTE: THE LOCATION OF THE ServerLocation/ServerUrl VARIABLES ARE DEFINED IN /cadviewer/app/cv/XXXHandlerSettings.js	
			//	var ServerLocation = 
			//	var ServerUrl =    
			cadviewer.cvjs_CADViewerPro(true);
			cadviewer.cvjs_setCADViewerInterfaceVersion(8);
			cadviewer.cvjs_setCADViewerSkin(this.skin);  // method can be omitted, alternative is "deepblue" , "nextcloud"




			// 8.8.1
			//cadviewer.cvjs_setRelativeConversionFilesFolder("/converters/files/","/converter/converters/files/");   // 7.4.45
			cadviewer.cvjs_setRelativeConversionFilesFolder("/converters/files/","/converters/files/");   // 7.4.45


			// Pass over the location of the installation, will update the internal paths
			cadviewer.cvjs_PrintToPDFWindowRelativeSize(0.8);
			cadviewer.cvjs_setFileModalEditMode(false);
				
			// For "Merge DWG" / "Merge PDF" commands, set up the email server to send merged DWG files or merged PDF files with redlines/interactive highlight.
			// See php / xampp documentation on how to prepare your server
			cadviewer.cvjs_emailSettings_PDF_publish("From CAD Server", "my_from_address@mydomain.com", "my_cc_address@mydomain.com", "my_reply_to@mydomain.com");
				
			// CHANGE LANGUAGE - DEFAULT IS ENGLISH	
			const languages = {
				"fr": "French",
				"en": "English"
			}

			cadviewer.cvjs_loadCADViewerLanguage(languages[getLanguage()] ? languages[getLanguage()] : "English", ""); //English
			// Available languages:  "English" ; "French, "Korean", "Spanish", "Portuguese", "Chinese-Simplified", "Chinese-Traditional"
			//cadviewer.cvjs_loadCADViewerLanguage("English", "/assets/cadviewer/app/cv/cv-pro/custom_language_table/custom_cadviewerProLanguage.xml");





			cadviewer.cvjs_DisplayCoordinatesMenu("floorPlan",true);

			// 6.9.18
			// set SpaceObjectsCustomMenu location and json config file,  flag true to display SpaceObject Menu, false to hide
			//cadviewer.cvjs_setSpaceObjectsCustomMenu( "/content/customInsertSpaceObjectMenu/", "cadviewercustomspacecommands.json", true);



			// Set Icon Menu Interface controls. Users can: 
			// 1: Disable all icon interfaces
			//  cvjs_displayAllInterfaceControls(false, "floorPlan");  // disable all icons for user control of interface

			// 2: Disable either top menu icon menus or navigation menu, or both

			//cvjs_displayTopMenuIconBar(false, "floorPlan");  // disable top menu icon bar
			//cvjs_displayTopNavigationBar(false, "floorPlan");  // disable top navigation bar

			// 3: Users can change the number of top menu icon pages and the content of pages, based on a configuration file in folder /cadviewer/app/js/menu_config/    		
			// cadviewer.cvjs_setTopMenuXML("floorPlan", "cadviewer_full_commands_01.xml", "");  
			//cadviewer.cvjs_setTopMenuXML("floorPlan", "cadviewer_full_commands_01.xml", "/assets/cadviewer/app/cv/cv-pro/menu_config/");
			


		
			// New NextCloud top bar
			// 8.26.6
			cadviewer.cvjs_setTopMenuXML("floorPlan", "cadviewer_redlines_fileload_nextcloud_04.xml", "/app/cv/cv-pro/menu_config/");
			


			// Initialize CADViewer  - needs the div name on the svg element on page that contains CADViewerJS and the location of the
			// main application "app" folder. It can be either absolute or relative
					
			// SETTINGS OF THE COLORS OF SPACES
			var cvjsRoomPolygonBaseAttributes = {
					fill: '#d8e1e3', //'#d8e1e3', // '#ffd7f4', //'#D3D3D3',   // #FFF   #ffd7f4
					"fill-opacity": 0.04,    //"0.05",   // 0.1
					stroke: '#CCC',  
					'stroke-width': 0.25,
					'stroke-linejoin': 'round',
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

			var  cadviewertoplevel = "";

			if (ServerUrl.indexOf("converter")>-1)
				cadviewertoplevel = ServerUrl.substring(0, ServerUrl.indexOf("converter"));		
			else
				cadviewertoplevel = ServerUrl;


			cadviewer.cvjs_InitCADViewer_highLight_popUp_app("floorPlan", cadviewertoplevel + "/assets/app/", cvjsRoomPolygonBaseAttributes, cvjsRoomPolygonHighlightAttributes, cvjsRoomPolygonSelectAttributes, my_cvjsPopUpBody );

			// set the location to license key, typically the js folder in main app application folder ../app/cv/
			//cadviewer.cvjs_setLicenseKeyPath("/assets/cadviewer/app/cv/");
			// alternatively, set the key directly, by pasting in the cvKey portion of the cvlicense.js file, note the JSON \" around all entities 	 
			cadviewer.cvjs_setLicenseKeyDirect(`{"cvKey": "${LicenceKey}"}`);		 
				
			// Sets the icon interface for viewing, layerhanding, measurement, etc. only
			//cvjs_setIconInterfaceControls_ViewingOnly();
			// disable canvas interface.  For developers building their own interface
			// cvjs_setIconInterfaceControls_DisableIcons(true);

			cadviewer.cvjs_allowFileLoadToServer(true);
			
			//		cvjs_setUrl_singleDoubleClick(1);
			//		cvjs_encapsulateUrl_callback(true);
			
			// NOTE BELOW: THESE SETTINGS ARE FOR SERVER CONTROLS FOR UPLOAD OF REDLINES

			// NOTE BELOW: THESE SETTINGS ARE FOR SERVER CONTROLS FOR UPLOAD OF REDLINES, FILES, SPACE OBJECTS
			cadviewer.cvjs_setServerFileLocation_AbsolutePaths(ServerLocation+'/content/drawings/dwg/', ServerBackEndUrl+'content/drawings/dwg/',"","");
			cadviewer.cvjs_setRedlinesAbsolutePath(ServerBackEndUrl+'/content/redlines/v7/', ServerLocation+'/content/redlines/v7/', true);
			cadviewer.cvjs_setSpaceObjectsAbsolutePath(ServerBackEndUrl+'/content/spaceObjects/', ServerLocation+'/content/spaceObjects/');
			cadviewer.cvjs_setInsertImageObjectsAbsolutePath(ServerBackEndUrl+'/content/inserted_image_objects/', ServerLocation+'/content/inserted_image_objects/')

				
			cadviewer.cvjs_conversion_clearAXconversionParameters();
		
			cadviewer.cvjs_conversion_addAXconversionParameters(axparameters);


			// process layers for spaces  RL/TL
			// cadviewer.cvjs_conversion_addAXconversionParameter("RL", "RM_");		 
			// cadviewer.cvjs_conversion_addAXconversionParameter("TL", "RM_TXT");		 


			// calculate areas of spaces

			// we add -strokea for processing
			//cadviewer.cvjs_conversion_addAXconversionParameter("strokea", "");		 
			// from 109c not needed - cadviewer.cvjs_conversion_addAXconversionParameter("lwtext","");
			//cadviewer.cvjs_conversion_addAXconversionParameter("last", "");		 							
			//cadviewer.cvjs_conversion_addAXconversionParameter("extents","");
			// NOTE ABOVE: THESE SETTINGS ARE FOR SERVER CONTROLS FOR CONVERTING DWG, DXF, DWF files


//			// FOR MEASUREMENT ENABLE HANDLE PROCESSING
//			cadviewer.cvjs_conversion_addAXconversionParameter("hlall", "");		 							
// we do not enable now!!! 2023-02-.22


			// Load file - needs the svg div name and name and path of file to load
			cadviewer.cvjs_setISOtimeStamp(FileName, this.ISOtimeStamp);
			console.log("ISOtimeStamp="+ this.ISOtimeStamp);
			cadviewer.cvjs_LoadDrawing("floorPlan", FileName );

			// set maximum CADViewer canvas side
			cadviewer.cvjs_resizeWindow_position("floorPlan" );

			// alternatively set a fixed CADViewer canvas size
			//	cvjs_resizeWindow_fixedSize(600, 400, "floorPlan");			   
	},
    onResize(e) {
        console.log("RESIZE - cvjs_resizeWindow_position");
        //  cadviewer resize event 
        cadviewer.cvjs_resizeWindow_position("floorPlan" );
    },
	chooseFileToCompareWith() {
		OC.dialogs.filepicker(
			t("cadviewer", "Choose file to compare with"),
			(path) => {
				if(path){
					let nameOfFile = path.split("/").reverse()[0]
					let directory = path.replace(nameOfFile, "")
					var data = { nameOfFile, directory };
					$.ajax({
						type: "POST",
						async: "false",
						url: OC.generateUrl("apps/" + OCA.Cadviewer.AppName + "/ajax/cadviewer.php"),
						data: data,
						success: async (response) => {
							console.log(response);
							if (response.path) {
								const content_dir = response.path;
								const ISOtimeStamp = `${response.ISOtimeStamp}`;
								const FileName = `${content_dir}/${nameOfFile}`;
								cadviewer.cvjs_setCompareDrawings_LoadSecondDrawingDirect("floorPlan", FileName);
								cadviewer.cvjs_LoadDrawing("floorPlan", FileName );   // 8.26.6
							} else {
								OC.dialogs.alert(
									t("cadviewer", response.desc ? response.desc : "Error when trying to connect"),
									t("cadviewer", "Unable to view this file for the moment"),
								);
							}
						},
						error: (response) => {
							OC.dialogs.alert(
								t("cadviewer", response.desc ? response.desc : "Error when trying to connect"),
								t("cadviewer", "Unable to view this file for the moment"),
							);
						},
					});
					
				}
			},
			false,
			[
				"application/acad",
				"application/dxf",
				"application/x-dwf",
				"application/dgn",
				// Add context menu for others documents
				"application/pdf",
				"image/tiff",
				"image/tif",
				// Add context menu for images
				"image/png",
				"image/jpeg",
				"image/gif",
			],
			true,
			OC.dialogs.FILEPICKER_TYPE_CHOOSE,
			this.parentDir
		);
	},
	commentThisFile() {
		if (!$("#app-sidebar-vue").is(":visible")) {
			// add class cadviewer-open to body
			$("body").addClass("cadviewer-open");
			OCA.Files.Sidebar.open(this.parentDir + "/" + this.title);
			OCA.Files.Sidebar.setActiveTab("comments");
		} else {
			this.closeCommentScreen();
		}
	},
	closeCommentScreen() {
		// remove class cadviewer-open to body
		$("body").removeClass("cadviewer-open");
		OCA.Files.Sidebar.close();
	},
	shareThisFile() {
		if (!$("#app-sidebar-vue").is(":visible")) {
			// add class cadviewer-open to body
			$("body").addClass("cadviewer-open");
			OCA.Files.Sidebar.open(this.parentDir + "/" + this.title);
			OCA.Files.Sidebar.setActiveTab("sharing");
		} else {
			this.closeShareScreen();
		}
	},
	closeShareScreen() {
		// remove class cadviewer-open to body
		$("body").removeClass("cadviewer-open");
		OCA.Files.Sidebar.close();
	},
	chooseFileToLoad() {
		console.log(this.parentDir)
		OC.dialogs.filepicker(
			t("cadviewer", "Choose file to load"),
			(path) => {
				if(path){
					let nameOfFile = path.split("/").reverse()[0]
					let directory = path.replace(nameOfFile, "")
					var data = { nameOfFile, directory };
					this.title = nameOfFile;
					$.ajax({
						type: "POST",
						async: "false",
						url: OC.generateUrl("apps/" + OCA.Cadviewer.AppName + "/ajax/cadviewer.php"),
						data: data,
						success: async (response) => {
							console.log(response);
							if (response.path) {
								const content_dir = response.path;
								const ISOtimeStamp = `${response.ISOtimeStamp}`;
								const FileName = `${content_dir}/${nameOfFile}`;
								this.parentDir = directory;
								cadviewer.cvjs_setISOtimeStamp(FileName, ISOtimeStamp);
								cadviewer.cvjs_LoadDrawing("floorPlan", FileName );
							} else {
								OC.dialogs.alert(
									t("cadviewer", response.desc ? response.desc : "Error when trying to connect"),
									t("cadviewer", "Unable to view this file for the moment"),
								);
							}
						},
						error: (response) => {
							OC.dialogs.alert(
								t("cadviewer", response.desc ? response.desc : "Error when trying to connect"),
								t("cadviewer", "Unable to view this file for the moment"),
							);
						},
					});
					
				}
			},
			false,
			[
				"application/acad",
				"application/dxf",
				"application/x-dwf",
				"application/dgn",
				// Add context menu for others documents
				"application/pdf",
				"image/tiff",
				"image/tif",
				// Add context menu for images
				"image/png",
				"image/jpeg",
				"image/gif",
			],
			true,
			OC.dialogs.FILEPICKER_TYPE_CHOOSE,
			this.parentDir
		);
	},
	movePdf(pdfFileName, pdfFolderName="markup") {

		// Make api call for move pdf file into markup folder
		$.ajax({
			method: "POST",
			url: OC.generateUrl("apps/" + OCA.Cadviewer.AppName + "/ajax/cadviewer/move-pdf"),
			data: {
				pdfFileName: pdfFileName,
				pdfFolderName: pdfFolderName
			},
			success: function onSuccess(response) {
				// Todo did we need to notify user when the're is a success or failure message
			}
		});

	},
	clearTextLayer(){
		textLayer1 = cadviewer.cvjs_clearLayer(textLayer1);
	},
	viewCadFileActionHandler(filename, context) {
	  this.showLoading = true;
      var tr = context.fileList.findFileEl(filename);
	  console.log({context, tr, filename})
      context.fileList.showFileBusyState(tr, true);
      var data = {
        nameOfFile: filename,
        directory: context.dir,
      };
	  this.modal = true;
	  this.title = filename;
      $.ajax({
        type: "POST",
        async: "false",
        url: OC.generateUrl("apps/" + OCA.Cadviewer.AppName + "/ajax/cadviewer.php"),
        data: data,
        success: async (response) => {
	  	  this.showLoading = false;
          context.fileList.showFileBusyState(tr, false);
          if (response.path) {
            const content_dir = response.path;
            console.log({ content_dir });
            this.ServerBackEndUrl = `${window.location.href.split("/apps/")[0].replace("/index.php", "")}/apps/cadviewer/converter/`;
            this.ServerLocation = `${response.serverLocation}`;
            this.ISOtimeStamp = `${response.ISOtimeStamp}`;
            this.parentDir = context.dir;
            this.ServerUrl = `${window.location.href.split("/apps/")[0].replace("/index.php", "")}/apps/cadviewer/`;
            this.FileName = `${content_dir}/${filename}`;
            this.ModalTitle = filename;
            this.LicenceKey = response.licenceKey;
			this.skin = response.skin;
			this.nextcloudColor = getComputedStyle(document.documentElement).getPropertyValue('--color-primary');
			this.UserName  = OC.getCurrentUser().displayName;
			this.UserId  = OC.getCurrentUser().uid;
			let axparameters = {};
		    let parameters = []
			axparameters.parameters = parameters;
			if (response.parameters) {
				let keys = ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9"];
				keys.forEach((key) => {
					if (response.parameters[`parameter_${key}`].trim() &&  response.parameters[`parameter_${key}`].trim().length > 0) {
						axparameters.parameters.push({
							"paramName": response.parameters[`parameter_${key}`].trim(), 
							"paramValue": response.parameters[`value_${key}`].trim()
						});
					}
				});
			}

			this.initCadviewer(axparameters);
          } else {
	  		this.modal = false;
			OC.dialogs.alert(
				t("cadviewer", response.desc ? response.desc : "Error when trying to connect"),
				t("cadviewer", "Unable to view this file for the moment"),
			);
          }
        },
        error: (response) => {
          	context.fileList.showFileBusyState(tr, false);
			OC.dialogs.alert(
				t("cadviewer", response.desc ? response.desc : "Error when trying to connect"),
				t("cadviewer", "Unable to view this file for the moment"),
			);
        },
      });
    },
    initViewCadFile(mine_type, is_default) {
      OCA.Files.fileActions.registerAction({
        name: "open_cadviewer_modal",
        displayName: t("cadviewer","Open with CADViewer"),
        mime: mine_type,
        permissions: OC.PERMISSION_NONE,
        type: OCA.Files.FileActions.TYPE_DROPDOWN,
        icon: `${window.location.href.split("/apps/")[0].replace("/index.php", "")}/apps/cadviewer/img/cvlogo.png?v=kevmax`,
        iconClass: "icon-visibility-button",
        order: 1001,
        actionHandler: this.viewCadFileActionHandler,
      });
      if(is_default)
        OCA.Files.fileActions.setDefault(mine_type, "open_cadviewer_modal");
    },
    closeModal() {
	  this.closeShareScreen();
      this.modal = false;
      this.ModalTitle = "";
      this.ServerBackEndUrl = "";
      this.ServerLocation = "";
      this.ServerUrl = "";
      this.FileName = "";
      this.licenceKey = "";
      this.ISOtimeStamp = ""
      this.ContentDir = ""
	  this.UserName = ""
	  this.UserId = ""
    },
  },
  components: {
    'app-nc-modal': NcModalVue,
	'app-close': Close,
	'app-nc-loading-icon': NcLoadingIcon
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

	#floorPlan {
		text-align: left;
		margin-top: 0px;
		margin-left: 0px;   /* margin-left: 50px;   */
	} 

	.load_button {
		position: absolute !important;
		top: 100px;
		right: 4px;
		z-index: 10000;
		background-color: #0082c9 !important;
		color: white !important;
		cursor: pointer;
		border-radius: 10px;
		height: 44px;
		border: 2px solid #CCC;
		display: flex;
		justify-content: center;
		align-items: center;
		padding: 0px 10px;
	} 
	.compare_button {
		position: absolute !important;
		top: 52px;
		right: 4px;
		z-index: 10000;
		background-color: #0082c9 !important;
		color: white !important;
		cursor: pointer;
		border-radius: 10px;
		height: 44px;
		border: 2px solid #CCC;
		display: flex;
		justify-content: center;
		align-items: center;
		padding: 0px 10px;
	}
	
	.close_button {
		position: absolute !important;
		top: 4px;
		right: 10px;
		z-index: 9999;
		background-color: var(--color-primary) !important;
		color: white !important;
		cursor: pointer;
		border-radius: 100%;
		width: 44px;
		height: 44px;
		border: 2px solid #CCC;
		display: flex;
		justify-content: center;
		align-items: center;
	}

	.close_button span {
		cursor: pointer !important;
	}

	.close_button:hover {
		color:  var(--color-primary) !important;
		background-color: white !important;
	}

	.loading-bloc {
		display: flex;
		justify-content: center;
		align-items: center;
		position: absolute;
		top: 0;
		left: 0;
		bottom: 0;
		right: 0;
	}
</style>
