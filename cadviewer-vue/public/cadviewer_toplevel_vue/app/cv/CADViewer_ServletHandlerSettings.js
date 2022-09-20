// CADVIEWER INSTALLATION FOLDERS

 // ON Under Linux replace c:\xampp\tomcat\webapps with /var/lib/tomcat{X}
 
// Location of installation Path
	var ServerLocation = "C:\\xampp\\tomcat\\webapps\\cadviewer\\"; 	            
// Location of installation Url
	var ServerUrl = "http://localhost:8080/cadviewer/";        					

	var ServerBackEndUrl = ServerUrl;

	cvjs_setRestApiControllerLocation(ServerUrl+"/servlets/servlet/");
	cvjs_setRestApiController("callApiConversionServlet");  	 // AX2020  - controller document for AX2020 server side conversion
	cvjs_setServerAccessToServlet(true);  // We are telling to use Servlets POST instead of php json connection
	cvjs_restApiConverter("AutoXchange AX2020");
	cvjs_restApiConverter("V1.00");

// NOTE: Make Sure Location of installation folders ServerLocation add ServerUrl are defined 
// NOTE: Currently in: /cadviewer/html/CV-JS_ServerSettings.js	

		// NOTE BELOW: THESE SETTINGS ARE FOR SERVER CONTROLS FOR HANDLING OF PRINT OBJECTS and FILE OBJECTS
		// The default settings is using PHP in /php/ folder with controlling documents in /php/ folder and print objects in /php/temp_print

		// SAMPLE SETTINGS FOR SERVLETS - ALTERNATIVE TO DEFAULT PHP SETTING
		cvjs_setServerHandlersPath(ServerUrl+"/servlets/servlet");          // location of print handlers, in the standard case this in the /php/ folder with redline and file controllers
				
		cvjs_setPrintObjectPathAbsolute(ServerUrl+"/temp_print/", ServerLocation+"/temp_print/");      // absolute location of Print object, url and server
		cvjs_setServerSaveFileHandlerPrint("SaveServlet");	// name of server side save-file controller document
		cvjs_setServerAppendFileHandlerPrint("AppendServlet"); // name of server side append-file controller document
		cvjs_setServerDeleteFileHandlerPrint("DeleteServlet"); // name of server side delete-file controller docoment
		cvjs_setServerListDirectoryHandler("ListDirectoryContent");
		cvjs_setServerLoadHandler("LoadServlet");

		// Controls for merge redlines into DWG or PDF for mail attachments
		cvjs_setCustomMergedEmailHandler("MergeEmailServlet");
		cvjs_setServerCopyFileHandler("CopyServlet");
		cvjs_setServerMergeDWGHandler("MergeDwgServlet");
		cvjs_setServerScreenToPDFHandler("MakeSinglepagePDFServlet");
		
		
		// Servlet settings for multipage SVG and multipage PDF conversion
		cvjs_setServerListDirectoryHandler("ListDirectoryContent");
		cvjs_setReturnPDFparamsController("ReturnPDFParamsServlet");
		cvjs_setGetFileController("getFileServlet");


		// Custom control of PrintToPDF
		//cvjs_setCustomPDFprintControllerFlag(true);


		// NOTE ABOVE: THESE SETTINGS ARE FOR REPLACING PHP SERVER CONTROLS WITH SERVLETS (OR CUSTOM HANDLERS)


		// NOTE BELOW: THESE SETTINGS ARE FOR SERVER CONTROLS FOR UPLOAD OF REDLINES
		// I am setting the location of the php scripts controlling redlines and file manager relative to (this calling), the default is "../php/"
		// as defined in cvjs_setServerHandlersPath("../php/")


		// REDLINE UPLOAD/SAVE - ALTERNATIVE TO PHP SETTINGS
		cvjs_setServerSaveHandlerRedlines("SaveRedlinesServlet");	// name of server side save-file controller document
		cvjs_setServerLoadHandlerRedlines("LoadRedlinesServlet"); // name of server side append-file controller document
		cvjs_setServerRedlinesListDirectoryHandler("ListDirectoryContent");


