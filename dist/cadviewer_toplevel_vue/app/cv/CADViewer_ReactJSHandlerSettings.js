// CADVIEWER INSTALLATION FOLDERS


// Location of installation Front-End ServerUrl
	var ServerUrl = "http://localhost:8000/";        	    			// NodeJS, Windows


//	var ServerLocation = "/usr/bin/nodejs/cadviewer/";   				// NodeJS, Linux
var ServerLocation = "C:/nodejs/cadviewer/"; 	    				// NodeJS, Windows


// Location of installation BackEnd ServerUrl
	var ServerBackEndUrl = "http://localhost:3000/";        	    	// NodeJS, Windows


// LOCATION OF MAIN CONTROLLER FOR FILE CONVERTER 

//	NODEJS  - Angular
	cvjs_setRestApiControllerLocation(ServerBackEndUrl);
	cvjs_setRestApiController("callapiconversion");  	 // AX2020  - controller document for AX2020 server side conversion
	cvjs_setServerAccessToServlet(true);  // We are telling to use Servlets POST instead of php json connection
	cvjs_setConverter("AutoXchange AX2020", "V1.00");



// SETTINGS OF PLATFORM SPECIFIC CONFIGURATION OF CONNECTORS FOR FILE READ/WRITE, REDLINE READ/WRITE, PRINT, PDF GENERATION, ETC

		cvjs_setNodeJSserver(true);

		// SAMPLE SETTINGS FOR NODE JS  -   ALTERNATIVE TO DEFAULT PHP SETTING
		cvjs_setServerHandlersPath(ServerBackEndUrl);    // location of print handlers, in the standard case this in the /php/ folder with redline and file controllers

//		cvjs_setPrintObjectPathAbsolute("assets/temp_print/", ServerLocation+"/assets/temp_print/");      // absolute location of Print object, url and server
		cvjs_setPrintObjectPathAbsolute(ServerUrl + "temp_print", ServerLocation +"temp_print/");      // absolute location of Print object, url and server, in nodejs, temp_print is a handler

		cvjs_setServerSaveFileHandlerPrint("savefile");	// name of server side save-file controller document
		cvjs_setServerAppendFileHandlerPrint("appendfile"); // name of server side append-file controller document
		cvjs_setServerDeleteFileHandlerPrint("deletefile"); // name of server side delete-file controller docoment
				
		cvjs_setServerListDirectoryHandler("listdirectory");
		cvjs_setServerLoadHandler("loadfile");

		// Controls for merge redlines into DWG or PDF for mail attachments
		cvjs_setCustomMergedEmailHandler("mergeemail");
		cvjs_setServerCopyFileHandler("copyfile");
		cvjs_setServerMergeDWGHandler("mergedwg");
		cvjs_setServerScreenToPDFHandler("makesinglepagepdf");
		cvjs_setServerCreateThumb_StickyNote_Controller("makethumbnails")
		
		
		// Servlet settings for multipage SVG and multipage PDF conversion
		cvjs_setServerListDirectoryHandler("listdirectory");
		cvjs_setReturnPDFparamsController("returnpdfparams");
		cvjs_setServerPDFConverterController("convertpdf");
		cvjs_setGetFileController("getfile");


		// Custom control of PrintToPDF
		//cvjs_setCustomPDFprintControllerFlag(true);
		//cvjs_setCustomPDFprintController("ConvertPDFServlet");


		// NOTE ABOVE: THESE SETTINGS ARE FOR REPLACING PHP SERVER CONTROLS WITH SERVLETS (OR CUSTOM HANDLERS)


		// NOTE BELOW: THESE SETTINGS ARE FOR SERVER CONTROLS FOR UPLOAD OF REDLINES
		// I am setting the location of the php scripts controlling redlines and file manager relative to (this calling), the default is "../php/"
		// as defined in cvjs_setServerHandlersPath("../php/")


		// REDLINE UPLOAD/SAVE - ALTERNATIVE TO PHP SETTINGS
		cvjs_setServerSaveHandlerRedlines("saveredline");	// name of server side save-file controller document
		cvjs_setServerLoadHandlerRedlines("loadredline"); // name of server side append-file controller document
		cvjs_setServerRedlinesListDirectoryHandler("listdirectoryredlines");


		
		// SETTING OF CONTROLLER FOR SAVE OF SCREEN BITMAP AND THUMBNAILS
		cvjs_setServerCreateThumb_StickyNote_Controller("makethumbnails");
		
		
		// Upload Files
		cvjs_setUploadControllerPath(ServerBackEndUrl);
		cvjs_setUploadController('uploadfile');
		
		
		
