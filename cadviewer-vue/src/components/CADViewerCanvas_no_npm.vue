<template>
  <div class="cadviewerCanvasTest01">
<!--    <h1>{{ msg }}</h1>  -->
    <div id="floorPlan"></div>
    
  </div>
</template>


<script>

//import jQuery from 'jquery';
//import cadviewer from 'cadviewer';


import {eventBus} from "../main.js";

var textLayer1; 

var  selected_handles = [];
var  handle_selector = false;
var  current_selected_handle = "";


// We should to define all the CADViewer methods in which we are getting information return from CADViewer
// THEY CAN BE PLACEHOLDERS ONLY 


//export function cvjs_OnLoadEnd(){

function cvjs_OnLoadEnd(){
	// generic callback method, called when the drawing is loaded
	// here you fill in your stuff, call DB, set up arrays, etc..
	// this method MUST be retained as a dummy method! - if not implemeted -

	cvjs_resetZoomPan("floorPlan");

	var user_name = "Bob Smith";
	var user_id = "user_1";

	// set a value for redlines
	cvjs_setCurrentStickyNoteValues_NameUserId(user_name, user_id );
	cvjs_setCurrentRedlineValues_NameUserid(user_name, user_id);
	// cvjs_dragBackgroundToFront_SVG("floorPlan");					
	//cvjs_initZeroWidthHandling("floorPlan", 1.0);			

	textLayer1 = cvjs_clearLayer(textLayer1);
	

	cvjs_LayerOff("EC1 Space Names");
	cvjs_LayerOff("EC1 Space Status Descs");
	cvjs_LayerOff("EC1 Space Project");
	cvjs_LayerOff("EC1 Space Function Descs");
	cvjs_LayerOff("EC1 Space Type Descs");
	cvjs_LayerOff("EC1 Tenant Names");
	cvjs_LayerOff("EC1 UDA Design Capacity");
	cvjs_LayerOff("EC1 UDA Is Secured");

}

function cvjs_OnLoadEndRedlines(){
	// generic callback method, called when the redline is loaded
	// here you fill in your stuff, hide specific users and lock specific users
	// this method MUST be retained as a dummy method! - if not implemeted -

	// I am hiding users added to the hide user list
	cvjs_hideAllRedlines_HiddenUsersList();

	// I am freezing users added to the lock user list
	cvjs_lockAllRedlines_LockedUsersList();
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

            myobject = cvjs_returnSpaceObjectID(spaceID);
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
            myobject = cvjs_returnSpaceObjectID(spaceID);

        }
    }
    catch(err){
        console.log("cvjs_graphicalObjectOnChange: "+err)
    }


}


function cvjs_saveStickyNotesRedlinesUser(){

// there are two modes, user handling of redlines
// alternatively use the build in redline file manager

cvjs_openRedlineSaveModal("floorPlan");

// custom method startMethodRed to set the name and location of redline to save
// see implementation below
//startMethodRed();
// API call to save stickynotes and redlines
//cvjs_saveStickyNotesRedlines("floorPlan");
}


// This method is linked to the load redline icon in the imagemap
function cvjs_loadStickyNotesRedlinesUser(){


cvjs_openRedlineLoadModal("floorPlan");

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
function my_own_clickmenu1(){
var id = cvjs_idObjectClicked();
//		var node = cvjs_NodeObjectClicked();
window.alert("Custom menu item 1: Here developers can implement their own methods, the look and feel of the menu is controlled in the settings.  Clicked object ID is: "+id);
}

// Here we are writing a basic function that will be used in the PopUpMenu
// this is template on all the good stuff users can add
function my_own_clickmenu2(){
var id = cvjs_idObjectClicked();
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


  console.log("mysql click "+id+"  "+handle);
  // if we click on an object, then we add to the handle list
  if (handle_selector){
      selected_handles.push({id,handle});
      current_selected_handle = handle;
  }

// tell to update the Scroll bar 
//vqUpdateScrollbar(id, handle);
// window.alert("We have clicked an entity: "+entity.substring(4)+"\r\nThe AutoCAD Handle id: "+handle+"\r\nThe svg id is: "+id+"\r\nHighlight SQL pane entry");
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
      cvjs_mouseout_handleObjectStyles(id, handle);
  }
}

function cvjs_mouseover(id, handle, entity){

console.log("mysql mouseover "+id+"  "+handle+"  "+jQuery("#"+id).css("color"))
//cvjs_mouseover_handleObjectPopUp(id, handle);	
}

function cvjs_mouseleave(id, handle, entity){

console.log("mysql mouseleave "+id+"  "+handle+"  "+jQuery("#"+id).css("color"));
}


function cvjs_mouseenter(id, handle, entity){
//	cvjs_mouseenter_handleObjectStyles("#a0a000", 4.0, 1.0, id, handle);
//	cvjs_mouseenter_handleObjectStyles("#ffcccb", 5.0, 0.7, true, id, handle);


    cvjs_mouseenter_handleObjectStyles("#F00", 2.0, 1.0, true, id, handle);

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
			  cvjs_AddTextOnSpaceObject(textLayer1, Id, leftScale, textStringArr, textStyleArr, scaleTextArr, hexColorTextArr, clipping, centering)
	 })

   	  console.log('created CADViewerCanvas');

  },

  mounted: function (){
    // Register an event listener when the Vue component is ready
    window.addEventListener('resize', this.onResize)

    console.log('mounted');

		// Standard NodeJS Server
		var ServerBackEndUrl = "http://127.0.0.1:3000/";
		var ServerLocation = "c:/nodejs/cadviewer-conversion-server/";

		// PHP Apache under Linux
//		var ServerBackEndUrl = "http://localhost/cadviewer/";
//		var ServerLocation = "/var/www/html/cadviewer/";



		var ServerUrl = "http://localhost:8080/";

		
		
		
		
		
		
		//var FileName = ServerBackEndUrl+ "/content/drawings/dwg/LUXR-42-01-PID-005_0-Model.pdf";
		//var FileName = ServerBackEndUrl + "/content/drawings/dwg/BRA_Alta Vila_02_CkIn_06082020.dwg";	
		//var FileName = ServerBackEndUrl+ "/content/drawings/dwg/LUXR-42-01-PID-005_0-Model.pdf";
		//var FileName = ServerBackEndUrl+ "/content/drawings/dwg/hq17_2spaces.dwg";
		var FileName = ServerBackEndUrl+ "/content/drawings/dwg/hq17_.dwg";

		cvjs_debugMode(true);
		cvjs_setServerLocationURL(ServerLocation, ServerUrl);
		cvjs_setServerBackEndUrl(ServerBackEndUrl);

//		cvjs_setHandlerSettings('ReactJS', 'floorPlan'); // standard angular + nodejs


      // USER Controlled - 
      //cvjs_setHandlers_FrontEnd('NodeJS', 'JavaScript','floorPlan');   // user controlled back-end
      //cvjs_setHandlers_FrontEnd('PHP', 'ReactJS','floorPlan');
      //cvjs_setHandlers_FrontEnd('dotNET', 'ReactJS','floorPlan');
      //cvjs_setHandlers_FrontEnd('Servlets', 'ReactJS','floorPlan');


		cvjs_setHandlers_FrontEnd('NodeJS', 'VueJS','floorPlan');   // user controlled back-end   NodeJS
//		cvjs_setHandlers_FrontEnd('PHP', 'VueJS','floorPlan');   // user controlled back-end  PHP Apache



		// PATH and FILE to be loaded, can be in formats DWG, DXF, DWF, SVG , JS, DGN, PCF, JPG, GIF, PNG
		  //var FileName = ServerBackEndUrl+ "/content/drawings/dwg/hq17_.dwg";		
		   
        //      Setting all callback methods  - they have to be injected into the CADViewer class componnet
        cvjs_setCallbackMethod("cvjs_OnLoadEnd", cvjs_OnLoadEnd);
        cvjs_setCallbackMethod("cvjs_graphicalObjectOnChange", cvjs_graphicalObjectOnChange);
        cvjs_setCallbackMethod("cvjs_OnLoadEndRedlines", cvjs_OnLoadEndRedlines);
        cvjs_setCallbackMethod("cvjs_ObjectSelected", cvjs_ObjectSelected);
        cvjs_setCallbackMethod("cvjs_measurementCallback", cvjs_measurementCallback);
        cvjs_setCallbackMethod("cvjs_CalibrateMeasurementCallback", cvjs_CalibrateMeasurementCallback);
        cvjs_setCallbackMethod("cvjs_Url_callback", cvjs_Url_callback);
        cvjs_setCallbackMethod("cvjs_loadSpaceImage_UserConfiguration", cvjs_loadSpaceImage_UserConfiguration);
        cvjs_setCallbackMethod("cvjs_NoObjectSelected", cvjs_NoObjectSelected);
        cvjs_setCallbackMethod("cvjs_SVGfileObjectClicked", cvjs_SVGfileObjectClicked);
        cvjs_setCallbackMethod("cvjs_SVGfileObjectMouseEnter", cvjs_SVGfileObjectMouseEnter);
        cvjs_setCallbackMethod("cvjs_SVGfileObjectMouseLeave", cvjs_SVGfileObjectMouseLeave);
        cvjs_setCallbackMethod("cvjs_SVGfileObjectMouseMove", cvjs_SVGfileObjectMouseMove);
		cvjs_setCallbackMethod("cvjs_ParseDisplayDataMaps", cvjs_ParseDisplayDataMaps);
        cvjs_setCallbackMethod("cvjs_QuickCountCallback", cvjs_QuickCountCallback);
        cvjs_setCallbackMethod("cvjs_OnHyperlinkClick", cvjs_OnHyperlinkClick);
        cvjs_setCallbackMethod("cvjs_setUpStickyNotesRedlines", cvjs_setUpStickyNotesRedlines);
        cvjs_setCallbackMethod("custom_host_parser_PopUpMenu", custom_host_parser_PopUpMenu);
        cvjs_setCallbackMethod("cvjs_customHostParser", cvjs_customHostParser);
        cvjs_setCallbackMethod("drawPathsGeneric", drawPathsGeneric );
        cvjs_setCallbackMethod("cvjs_callbackForModalDisplay", cvjs_callbackForModalDisplay);
        cvjs_setCallbackMethod("cvjs_populateMyCustomPopUpBody", cvjs_populateMyCustomPopUpBody);
        cvjs_setCallbackMethod("cvjs_customModalPopUpBody", cvjs_customModalPopUpBody);
        cvjs_setCallbackMethod("cvjs_NoObjectSelectedStickyNotes", cvjs_NoObjectSelectedStickyNotes);
        cvjs_setCallbackMethod("cvjs_NoObjectSelectedHyperlinks", cvjs_NoObjectSelectedHyperlinks);
        cvjs_setCallbackMethod("cvjs_ObjectSelectedHyperlink", cvjs_ObjectSelectedHyperlink);
        cvjs_setCallbackMethod("cvjs_ObjectSelectedStickyNotes", cvjs_ObjectSelectedStickyNotes);
		cvjs_setCallbackMethod("cvjs_saveStickyNotesRedlinesUser", cvjs_saveStickyNotesRedlinesUser);
        cvjs_setCallbackMethod("cvjs_loadStickyNotesRedlinesUser", cvjs_loadStickyNotesRedlinesUser);
        cvjs_setCallbackMethod("my_own_clickmenu1", my_own_clickmenu1);
        cvjs_setCallbackMethod("my_own_clickmenu2", my_own_clickmenu2);
        cvjs_setCallbackMethod("cvjs_popupTitleClick", cvjs_popupTitleClick);
        cvjs_setCallbackMethod("cvjs_mousedown", cvjs_mousedown);
        cvjs_setCallbackMethod("cvjs_click", cvjs_click);
        cvjs_setCallbackMethod("cvjs_dblclick", cvjs_dblclick);
        cvjs_setCallbackMethod("cvjs_mouseout", cvjs_mouseout);
        cvjs_setCallbackMethod("cvjs_mouseover", cvjs_mouseover);
        cvjs_setCallbackMethod("cvjs_mouseleave", cvjs_mouseleave);
        cvjs_setCallbackMethod("cvjs_mouseenter", cvjs_mouseenter);
        cvjs_setCallbackMethod("cvjs_graphicalObjectCreated", cvjs_graphicalObjectCreated);

		// END set all callback methods

		  // Location of installation folders
		  // NOTE: THE LOCATION OF THE ServerLocation/ServerUrl VARIABLES ARE DEFINED IN /cadviewer/app/cv/XXXHandlerSettings.js	
		  //	var ServerLocation = 
		  //	var ServerUrl =    
		 cvjs_CADViewerPro(true);
		 
		 // Pass over the location of the installation, will update the internal paths
		 cvjs_PrintToPDFWindowRelativeSize(0.8);
		 cvjs_setFileModalEditMode(false);
	   		   
		// For "Merge DWG" / "Merge PDF" commands, set up the email server to send merged DWG files or merged PDF files with redlines/interactive highlight.
		// See php / xampp documentation on how to prepare your server
		cvjs_emailSettings_PDF_publish("From CAD Server", "my_from_address@mydomain.com", "my_cc_address@mydomain.com", "my_reply_to@mydomain.com");
		   	 
		// CHANGE LANGUAGE - DEFAULT IS ENGLISH	
		cvjs_loadCADViewerLanguage("English"); //cvjs_loadCADViewerLanguage("English", "/app/cv/cv-pro/language_table/cadviewerProLanguage.xml");

		// Available languages:  "English" ; "French, "Korean", "Spanish", "Portuguese", "Chinese-Simplified", "Chinese-Traditional"

		// Set Icon Menu Interface controls. Users can: 
		// 1: Disable all icon interfaces
		//  cvjs_displayAllInterfaceControls(false, "floorPlan");  // disable all icons for user control of interface

		// 2: Disable either top menu icon menus or navigation menu, or both

		//cvjs_displayTopMenuIconBar(false, "floorPlan");  // disable top menu icon bar
		//cvjs_displayTopNavigationBar(false, "floorPlan");  // disable top navigation bar

		// 3: Users can change the number of top menu icon pages and the content of pages, based on a configuration file in folder /cadviewer/app/js/menu_config/    		
		cvjs_setTopMenuXML("floorPlan", "cadviewer_full_commands_01.xml");  //cvjs_setTopMenuXML("floorPlan", "cadviewer_full_commands_01.xml", "/cadviewer/app/cv/cv-pro/menu_config/");

		
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
		//  cvjs_InitCADViewer_highLight_popUp_app("floorPlan", ServerUrl+"app/", cvjsRoomPolygonBaseAttributes, cvjsRoomPolygonHighlightAttributes, cvjsRoomPolygonSelectAttributes, my_cvjsPopUpBody);

		//      cvjs_InitCADViewer_highLight_popUp_app("floorPlan", ServerUrl+ "/cadviewer/app/", cvjsRoomPolygonBaseAttributes, cvjsRoomPolygonHighlightAttributes, cvjsRoomPolygonSelectAttributes, my_cvjsPopUpBody );
		cvjs_InitCADViewer_highLight_popUp_app("floorPlan", "/cadviewer/app/", cvjsRoomPolygonBaseAttributes, cvjsRoomPolygonHighlightAttributes, cvjsRoomPolygonSelectAttributes, my_cvjsPopUpBody );
				
		// set the location to license key, typically the js folder in main app application folder ../app/cv/
		//cvjs_setLicenseKeyPath("/cadviewer/app/cv/");
		// alternatively, set the key directly, by pasting in the cvKey portion of the cvlicense.js file, note the JSON \" around all entities 	 
		cvjs_setLicenseKeyDirect('{ \"cvKey\": \"00110010 00110010 00110000 00110010 00110010 00110000 00110010 00110001 00110100 00111000 00110001 00110100 00110101 00110001 00110101 00110111 00110001 00110101 00111001 00110001 00110100 00111000 00110001 00110101 00110010 00110001 00110100 00110101 00110001 00110100 00110001 00110001 00110100 00110000 00110001 00111001 00111000 00110010 00110000 00110110 00110010 00110000 00111000 00110010 00110000 00110110 00110010 00110000 00110111 00110010 00110001 00110001 00110010 00110000 00111000 00110010 00110000 00110011 00110010 00110001 00110001 00110010 00110000 00110101 00110010 00110000 00111000 \" }');		 
			
		// Sets the icon interface for viewing, layerhanding, measurement, etc. only
		//cvjs_setIconInterfaceControls_ViewingOnly();

		// disable canvas interface.  For developers building their own interface
		// cvjs_setIconInterfaceControls_DisableIcons(true);


		cvjs_allowFileLoadToServer(true);
		
		//		cvjs_setUrl_singleDoubleClick(1);
		//		cvjs_encapsulateUrl_callback(true);
		
		// NOTE BELOW: THESE SETTINGS ARE FOR SERVER CONTROLS FOR UPLOAD OF REDLINES

		// NOTE BELOW: THESE SETTINGS ARE FOR SERVER CONTROLS FOR UPLOAD OF REDLINES, FILES, SPACE OBJECTS
		cvjs_setServerFileLocation_AbsolutePaths(ServerLocation+'/content/drawings/dwg/', ServerUrl+'content/drawings/dwg/',"","");
		cvjs_setRedlinesAbsolutePath(ServerBackEndUrl+'/content/redlines/fileloader_610/', ServerLocation+'/content/redlines/fileloader_610/');
		cvjs_setSpaceObjectsAbsolutePath(ServerBackEndUrl+'/content/spaceObjects/', ServerLocation+'/content/spaceObjects/');
		cvjs_setInsertImageObjectsAbsolutePath(ServerBackEndUrl+'/content/inserted_image_objects/', ServerLocation+'/content/inserted_image_objects/')

			
		cvjs_conversion_clearAXconversionParameters();
	//			 cvjs_conversion_addAXconversionParameter("lw", "0.3");		 
	//			 cvjs_conversion_addAXconversionParameter("lwmin", "0.3");		 

	// process layers for spaces  RL/TL
		cvjs_conversion_addAXconversionParameter("RL", "RM_");		 
		cvjs_conversion_addAXconversionParameter("TL", "RM_TXT");		 
		// calculate areas of spaces
		cvjs_conversion_addAXconversionParameter("LA", "");		 

		//      cvjs_conversion_addAXconversionParameter("RL", "EC1 Space Polygons");		 
	//      cvjs_conversion_addAXconversionParameter("TL", "EC1 Space Numbers");		 

		cvjs_conversion_addAXconversionParameter("last", "");		 
		cvjs_conversion_addAXconversionParameter("fpath", ServerLocation + "/converters/ax2020/windows/fonts/");		 
							
		// NOTE ABOVE: THESE SETTINGS ARE FOR SERVER CONTROLS FOR CONVERTING DWG, DXF, DWF files

		// Load file - needs the svg div name and name and path of file to load
		cvjs_LoadDrawing("floorPlan", FileName );

		// set maximum CADViewer canvas side
		cvjs_resizeWindow_position("floorPlan" );

		// alternatively set a fixed CADViewer canvas size
		//	cvjs_resizeWindow_fixedSize(600, 400, "floorPlan");			   
  },

  name: 'CADViewer01',
  props: {
    msg: String
  }
  ,
  methods: {
    onResize(e) {
        console.log("RESIZE");
        //  cadviewer resize event 
        cvjs_resizeWindow_position("floorPlan" );
    },

	clearTextLayer(){
		textLayer1 = cvjs_clearLayer(textLayer1);
	}

  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

#floorPlan {
	text-align: left;
	margin-top: 10px;
  	margin-left: 50px;
} 
 

</style>
