// CADVIEWER INSTALLATION FOLDERS

// Location of installation Path
	var ServerLocation = "C:\\VisualStudio\\cadviewer\\"; 	            // .NET  Windows
// Location of installation Url
	var ServerUrl = "http://localhost:53737/";        					// .NET Windows

	var ServerBackEndUrl = ServerUrl;

	cvjs_setRestApiControllerLocation(ServerUrl);
	cvjs_setRestApiController("callApiConversionHandler.ashx");  	 // AX2020  - controller document for AX2020 server side conversion
	cvjs_setServerAccessToServlet(true);  // We are telling to use Servlets POST instead of php json connection
	cvjs_restApiConverter("AutoXchange AX2020");
	cvjs_restApiConverter("V1.00");


// NOTE: Make Sure Location of installation folders ServerLocation add ServerUrl are defined 
// NOTE: Currently in: /cadviewer/html/CV-JS_ServerSettings.js	


		// SAMPLE SETTINGS FOR ASPX/ ASHX   - ALTERNATIVE TO DEFAULT PHP SETTING
		cvjs_setServerHandlersPath(ServerUrl);    // location of print handlers, in the standard case this in the /php/ folder with redline and file controllers

		cvjs_setPrintObjectPathAbsolute(ServerUrl+"/temp_print/", ServerLocation+"/temp_print/");      // absolute location of Print object, url and server
		cvjs_setServerSaveFileHandlerPrint("SaveFileHandler.ashx");	// name of server side save-file controller document
		cvjs_setServerAppendFileHandlerPrint("AppendFileHandler.ashx"); // name of server side append-file controller document
		cvjs_setServerDeleteFileHandlerPrint("DeleteFileHandler.ashx"); // name of server side delete-file controller docoment
				
		cvjs_setServerListDirectoryHandler("ListDirectoryContent.ashx");
		cvjs_setServerLoadHandler("LoadHandler.ashx");

		// Controls for merge redlines into DWG or PDF for mail attachments
		cvjs_setCustomMergedEmailHandler("MergeEmailServlet");
		cvjs_setServerCopyFileHandler("CopyServlet");
		cvjs_setServerMergeDWGHandler("MergeDwgServlet");
		cvjs_setServerScreenToPDFHandler("MakeSinglepagePDF.ashx");
		
		
		// Servlet settings for multipage SVG and multipage PDF conversion
		cvjs_setServerListDirectoryHandler("ListDirectoryContent.ashx");
		cvjs_setReturnPDFparamsController("ReturnPDFParamsHandler.ashx");
		cvjs_setServerPDFConverterController("ConvertPDFServlet");
		cvjs_setGetFileController("getFileHandler.ashx");

		// Custom control of PrintToPDF
		//cvjs_setCustomPDFprintControllerFlag(true);
		//cvjs_setCustomPDFprintController("ConvertPDFServlet");


		// NOTE ABOVE: THESE SETTINGS ARE FOR REPLACING PHP SERVER CONTROLS WITH SERVLETS (OR CUSTOM HANDLERS)


		// NOTE BELOW: THESE SETTINGS ARE FOR SERVER CONTROLS FOR UPLOAD OF REDLINES
		// I am setting the location of the php scripts controlling redlines and file manager relative to (this calling), the default is "../php/"
		// as defined in cvjs_setServerHandlersPath("../php/")


		// REDLINE UPLOAD/SAVE - ALTERNATIVE TO PHP SETTINGS
		cvjs_setServerSaveHandlerRedlines("SaveRedlinesHandler.ashx");	// name of server side save-file controller document
		cvjs_setServerLoadHandlerRedlines("LoadRedlinesHandler.ashx"); // name of server side append-file controller document
		cvjs_setServerRedlinesListDirectoryHandler("ListDirectoryContentRedlines.ashx");


		
		// SETTING OF CONTROLLER FOR SAVE OF SCREEN BITMAP AND THUMBNAILS
		cvjs_setServerCreateThumb_StickyNote_Controller("MakeThumbnailsHandler.ashx");
		
		
		
		// Upload Files
		cvjs_setUploadControllerPath(ServerBackEndUrl);
		cvjs_setUploadController('UploadfileHandler.ashx');
		
		
