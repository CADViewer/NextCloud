# cadviewer-testapp-vue-01

The repository contains a CADViewer sample implementation on VueJS.


## This package contains

1: CADViewer script library  - installed as npm in node_module structure.

2: Sample implementation .vue files of for running CADViewer canvas found in /src/components/. Base file is CADViewerCanvas.vue. There are two additional helper components to illustrate API driven interaction with the CADViewer canvas and to illustrate insertion of image objects to the canvas.

and

3: CADViewer script library - standard class library installed under /public/cadviewer_toplevel_vue/ .

4: Sample implementation .vue files for running standard CADViewer class library under Vue, these files are market ( _ no _ npm.vue ) in /src/components/ folder. Base file is CADViewerCanvas_no_npm.vue. There are two additional helper components to illustrate API driven interaction with the CADViewer canvas and to illustrate insertion of image objects to the canvas.



## This package does not contain

5: The back-end CAD Converter structure must be installed separately, and linked in by setting the back-end server variable  ***var ServerBackEndUrl = "myUrl";*** and ***ServerLocation=myLocation*** to the url of the back-end. The variables are found in /src/components/CADViewerCanvas.vue or /src/components/CADViewerCanvas_no_npm.vue .

The sample **cadviewer-testapp-vue-01** is tested using the CADViewer NodeJS CAD Server, that can be downloaded from: https://github.com/CADViewer/cadviewer-conversion-server



## How to Use

Once installed, you must also install a suitable back-end Conversion server such as CADViewer NodeJS CAD Server, download from: https://github.com/CADViewer/cadviewer-conversion-server.

An alternative CAD Conversion server can be the PHP based Linux Conversion Server, download from: https://github.com/CADViewer/cadviewer-script-library-php-linux , this VueJS sample will use the scrips, converters and content parts of the installation.  


A: If running CADViewer as top level script library, the script declarations in /public/index.html must be uncommented, if running as npm install keep as is.

B: In the files /src/components/CADViewerCanvas.vue and /src/components/CADViewerCanvas_no_npm.vue depending on set-up, locate the variables: 

	var ServerBackEndUrl = "http://localhost:3000/";
	var ServerLocation = "";
	var ServerUrl = "http://localhost:8080/";

The ***ServerUrl*** is the URL of the front-end, using the sample as is, it will spin up under localhost:8080.  

The ServerLocation and ServerBackEndUrl are the Url and location of the back-end server. The ServerLocation can be masked at a later time. The **ServerUrl** will remain unchanged if running this sample, the **ServerBackEndUrl** and **ServerLocation** will change depending on installation location of the back-end CAD Conversion server. 

Also note that the Front-End/Back-End combination must be set through a CADViewer API call cvjs_setHandlers_FrontEnd() also found in /src/components/CADViewerCanvas.vue and /src/components/CADViewerCanvas_no_npm.vue depending on set-up.

For the NodeJS CAD Conversion Server with npm library, sample settings are: 

	var ServerBackEndUrl = "http://127.0.0.1:3000/";
	var ServerLocation = "";
	var ServerUrl = "http://localhost:8080/";

	cadviewer.cvjs_setHandlers_FrontEnd('NodeJS', 'VueJS','floorPlan');

For the PHP CAD Conversion Server under Apache with npm library, sample settings are: 

	var ServerBackEndUrl = "http://localhost/cadviewer/";
	var ServerLocation = "";
	var ServerUrl = "http://localhost:8080/";

	cadviewer.cvjs_setHandlers_FrontEnd('PHP', 'VueJS','floorPlan');




C: Run the sample from within /cadviewer-testapp-vue-01, with the command

	npm run serve

It will then open on the Url listed:

	App running at:
	  - Local:   http://localhost:8080/
	  - Network: http://172.26.12.117:8080/

	  Note that the development build is not optimized.
	  To create a production build, run npm run build.
  
  
D: Open the web-browser developer console. It will provide a debug trace for CADViewer and it's server communication.  Depending on conversion server installation, there are server-side debug traces available also. 


## General Documentation 

-   [CADViewer Techdocs and Installation Guide](https://cadviewer.com/cadviewertechdocs/download)


* Try out the sample and build your own application!
 



# General install descriptions for Installing CADViewer on Frameworks

This is the general documentation for installing CADViewer within the frameworks. This documentation below can be used when integrating directly. The documentation below can also be useful when porting the ***cadviewer-testapp-vue-01*** sample into an application. 






## CADViewer for ReactJS, Angular and VueJS

Install CADViewer via *npm i cadviewer* on all platforms, see specifics below for each platform on how to add auxillary files and connect with back-end conversion server.


## Install Instructions for ReactJS and VueJS

1: Install CADViewer from: *npm i cadviewer* 


To see how a CAD Canvas is set up with callback methods and initialization of CADViewer use the following samples as a template:

2A: ReactJS - download the CADViewer [React](https://github.com/CADViewer/cadviewer-testapp-react-01) implementation sample from the [Github](https://github.com/CADViewer/cadviewer-testapp-react-01) repository [cadviewer-testapp-react-01](https://github.com/CADViewer/cadviewer-testapp-react-01).

2B: VueJS - download the CADViewer [VueJS](https://github.com/CADViewer/cadviewer-testapp-vue-01) implementation sample from [Github](https://github.com/CADViewer/cadviewer-testapp-vue-v01) repository [cadviewer-testapp-vue-01](https://github.com/CADViewer/cadviewer-testapp-vue-01).

These samples illustrates initialization and loading of CADViewer as well as illustrates the functional interface for highlight and adding interactive image content to the CAD canvas. 


3: Install a back-end CAD Conversion server to process CAD files and communicate with CADViewer.

Download the Node JS CAD Conversion server (or alternatively the PHP, .NET or Servlet Server implementations):  Go to:  https://cadviewer.com/download/, register and receive email and then download from **CADViewer Handler/Connector Scripts**.

The [CADViewer](https://github.com/CADViewer/cadviewer-conversion-server) NodeJS CAD Conversion Server can be downloaded from [Github](https://github.com/CADViewer/cadviewer-conversion-server) from the repository [cadviewer-conversion-server](https://github.com/CADViewer/cadviewer-conversion-server).

You can always update the CAD Converter AutoXchange 2022 in the server structure:  Go to: https://cadviewer.com/download/, register and receive email and then download from **AutoXchange 2022 Downloads**.


Note that the path book-keeping is important for proper initialization, where the ServerBackEndUrl and ServerLocation is the location and Url of the CAD Server and ServerUrl is the Url of the React/VueJS application encapulating CADViewer. 

		var ServerBackEndUrl = "http://localhost:3000/";
		var ServerUrl = "http://localhost:8000/";
		var ServerLocation = "";  // leave blank, for devopment purposes can be set: c:/nodejs/cadviewer-conversion-server/

The CADViewer React JS general install instructions are at: https://cadviewer.com/cadviewertechdocs/handlers/reactjs/

**LICENSE: TMS 1.0:** Use freely on localhost. Commercial use requires licensing, both using entirely or in parts. Forbidden to remove license key check.  Contact Tailor Made Software, https://cadviewer.com/contact, for more information. 

Use the [CADViewer API](https://cadviewer.com/cadviewerproapi/global.html) to open and manipulate drawings in your application. 

Read the Guide on how to **[create hotspots](https://cadviewer.com/highlight/main/)** (Space Objects), it outlines how spaces can be processed on a drawing to create interactive objects. 

Read the Guide on how to **[modify hotspots](https://cadviewer.com/highlight2/main/)**  (Space Objects), this will help you work with the code in this sample. 

Read the general documentation on **CADViewer** is found at: https://cadviewer.com/cadviewertechdocs/.

The general documentation on **AutoXchange 2022** is found at: https://tailormade.com/ax2020techdocs/.

The CADViewer API is found at: https://cadviewer.com/cadviewerproapi/global.html.



## Install Instruction for Angular

1A: Install CADViewer: *npm i cadviewer* 

1B: There are some general image, style and XML configuration files that CADViewer needs during execution, please download [angular_src_asset_folder_cadviewer_6_7.zip](https://cadviewer.com/downloads/handlers/angular/angular_src_asset_folder_cadviewer_6_7.zip) and place in your Angular /src/assets/ project folder.   

1C: In *angular.json* , reference the cadviewer related stylesheets from /src/assets/:

            "styles": [
              "src/styles.css",
              "src/assets/cadviewer/app/css/bootstrap.min.css",              
              "src/assets/cadviewer/app/css/jquery.qtip.min.css",
              "src/assets/cadviewer/app/css/jquery-ui-1.11.4.min.css",
              "src/assets/cadviewer/app/css/bootstrap-multiselect.css",
              "src/assets/cadviewer/app/css/cvjs_6.5.css"
            ],

As an alternative:

2: Download a CADViewer [Angular](https://github.com/CADViewer/cadviewer-testapp-angular-v01) implementation sample from [Github](https://github.com/CADViewer/cadviewer-testapp-angular-v01) repository [cadviewer-testapp-angular-v01](https://github.com/CADViewer/cadviewer-testapp-angular-v01).


For both methods 1: and 2: , then do the following:


Download the Node JS CAD Conversion server (or alternatively the PHP, .NET or Servlet Server implementations):  Go to:  https://cadviewer.com/download/, register and receive email and then download from **CADViewer Handler/Connector Scripts**.

The [CADViewer](https://github.com/CADViewer/cadviewer-conversion-server) NodeJS CAD Conversion Server can be downloaded from [Github](https://github.com/CADViewer/cadviewer-conversion-server) from the repository [cadviewer-conversion-server](https://github.com/CADViewer/cadviewer-conversion-server).

Download the CAD Converter AutoXchange 2022:  Go to: https://cadviewer.com/download/, register and receive email and then download from **AutoXchange 2022 Downloads**.

Use the Github [cadviewer-testapp-angular-v01](https://github.com/CADViewer/cadviewer-testapp-angular-v01) as reference sample. This sample illustrates initialization and loading of CADViewer as well as illustrates the functional interface for highlight and adding interactive image content to the CAD canvas. 

Note that the path book-keeping is important for proper initialization, where the ServerBackEndUrl and ServerLocation is the location and Url of the CAD Server and ServerUrl is the Url of the Angular application encapulating CADViewer. 


		var ServerBackEndUrl = "http://localhost:3000/";
		var ServerUrl = "http://localhost:4200/";
		var ServerLocation = "c:/nodejs/cadviewer-conversion-server/";

The CADViewer Angular JS general install instructions are at: https://cadviewer.com/cadviewertechdocs/handlers/angular/

**LICENSE: TMS 1.0:** Use freely on localhost. Commercial use requires licensing, both using entirely or in parts. Forbidden to remove license key check.  Contact Tailor Made Software, https://cadviewer.com/contact, for more information. 

Use the [CADViewer API](https://cadviewer.com/cadviewerproapi/global.html) to open and manipulate drawings in your application. 

Read the Guide on how to **[create hotspots](https://cadviewer.com/highlight/main/)** (Space Objects), it outlines how spaces can be processed on a drawing to create interactive objects.  

Read the Guide on how to **[modify hotspots](https://cadviewer.com/highlight2/main/)**  (Space Objects), this will help you work with the code in this sample. 

Read the general documentation on **CADViewer** is found at: https://cadviewer.com/cadviewertechdocs/.

The general documentation on **AutoXchange 2023** is found at: https://tailormade.com/ax2020techdocs/.

The CADViewer API is found at: https://cadviewer.com/cadviewerproapi/global.html.

