<?php


// NOTE: USE THIS FOR LOCALHOST RUNNING HTTP!!!!!!!
// $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

//  New: Use this code to find $httpHost and $home_dir based on current location, if under /cadviewer/ and in a docker containter
$actual_link = "https" . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";



	$pos1 = stripos($actual_link, "/cadviewer/");
	$httpHost = substr($actual_link, 0, $pos1+ 11);

	$currentpath = __FILE__;
	$pos1 = stripos($currentpath, "cadviewer");
	$home_dir = substr($currentpath, 0, $pos1+ 10);

//  Http Host   - note use direct setting if path different from /cadviewer/. 
//  URL to the location of home directory the converter infrastructure
//	$httpHost = "http://localhost/cadviewer/";

//  Home directory, the local path corresponding to the http host - note use direct setting if path different from cadviewer. 
//  Windows
//	$home_dir = "/xampp/htdocs/cadviewer";
//  Linux
	$home_dir = "/var/www/html/cadviewer";


//MOST PATHS ARE SET UP BASED ON HttpHost and home_dir    (Users can change this setting if an implementation needs to split up locations)
// NEW  we make an $home_dir_app  to give the user the ability to freely move $fileLocation + $converterLocation away from web-structure
	$home_dir_app = $home_dir;


// set the platform for /converter subfolder
//	$platform = "windows";
	$platform = "linux";
	
//  Conversion engines executables - names stays stable with each upgrade of conversion engines:
// 	Linux
	$ax2023_executable = "ax2023_L64_23_08_92";
// 	Windows
//	$ax2023_executable = "ax2023_L64_23_08_92.exe";

//  USE svgz compression
	$svgz_compress = false;   // default is false


//  DwgMerge engines executables - names stays stable with each upgrade of conversion engines:
// 	Linux
	$dwgmerge2020_executable = "DwgMerge_W32_19_01_02";
// 	Windows
//	$dwgmerge2020_executable = "DwgMerge_W32_20_02_00b.exe";

//  DwgMerge engines executables - names stays stable with each upgrade of conversion engines:
// 	Linux
	$linklist2023_executable = "LinkList_2023_W64_23_05_25";
// 	Windows
//	$linklist2023_executable = "LinkList_2023_W64_23_05_25.exe";



	// if checkorigin is false, all domains allowed * , if true, then checking from $allowed_domains
	$checkorigin=false;
	// allowed domains!
	$allowed_domains = array(
		'http://localhost:8080',
		'http://localhost',
		'*'
	  );

	// set to false for CADViewer 7.1.8 onwards,   true for previous versions of CADViewer
	$jsonp_flag = false;



//  URL to the location of controlling php files
//  Windows  Linux
	$httpPhpUrl = $httpHost . "/php/";

//  location of created files and temporary file folder
//  Linux Windows
	$fileLocation = $home_dir . "/converters/files/";


//  location of created files and temporary file folder, http
//  Linux Windows
	$fileLocationUrl = $httpHost . "/converters/files/";


//  Path to the location of the AutoXchange AX2023 converter infrastructure
	$converterLocation = $home_dir . "/converters/ax2023/".$platform."/";


//  Path to the location of the DWGMerge 2019 converter infrastructure
	$dwgmergeLocation = $home_dir . "/converters/dwgmerge2023/".$platform."/";

//  Path to the location of the Linklist converter infrastructure
	$linklistLocation = $home_dir . "/converters/linklist2023/".$platform."/";


//  Conversion engines executables - Community Version
	$community_executable = "dwg2SVG.exe";

//  Path to the location of the license key axlic.key file, typically this is the same location as AX2020
	$licenseLocation = $home_dir . "/converters/ax2023/".$platform."/";


//  Path to the XRef locations for external referenced drawings
//  Linux Windows
	$xpathLocation = $home_dir . "/converters/files/";

	
//  Name of PHP document that controls call-back file-transfer to CADViewerJS
	$callbackMethod = "getFile_09.php";

//  Debug parameter to check installation - false for normal operation, if true, the document will echo debug information, - no drawings will be displayed -
	$debug = TRUE;

//  We want bat processing on Windows, to set CODEPAGE for Asian and Chinese UNICODE
	$windowsbatprocessing = FALSE;

// Java install folder
//  Linux
//	$javaFolder = "/home/user/jdk1.8.0_121";
//  Windows
	$javaFolder = "C:\\jdk1.8.0_121";


// Pdf converter folder
	$pdfConverterFolder = $home_dir. "/converters/pdf_converter";


// Pdf converter batch executable
//  Linux
//	$pdfBatchExecutable = "run_pdftosvgmainclass.bash";
//  Windows
	$pdfBatchExecutable = "run_pdftosvgmainclass";

	
// Pdf converter, get pages batch executable
//  Linux
//	$pdfGetPagesExecutable = "run_pdftosvg_pages.bash";
//  Windows
	$pdfGetPagesExecutable = "run_pdftosvg_pages";
	

// Batik install folder
//  Linux
//	$batikFolder = $home_dir . "/converters/pdf_converter/batik-1.9";
//  Windows
	$batikFolder = $home_dir . "\\converters\\pdf_converter\\batik-1.9";


// Batik version
//  Linux Windows
	$batikVersion = "1.9";


// Pdfbox install folder
//  Linux
//	$pdfboxFolder = $home_dir . "/converters/pdf_converter/pdfbox";
//  Windows
	$pdfboxFolder = $home_dir . "\\converters\\pdf_converter\\pdfbox";


// Pdfbox version
//  Linux  Windows
	$pdfboxVersion = "1.8.13";

	
	
// SVG to PDF converter batch executable
//  Linux
//	$pdfBatchExecutable = "run_svg2pdf.bash";
//  Windows
	$svg2pdfExecutable = "run_svg2pdf";



//  Force a higher Maximum Java Heap for PDF conversions	
	$svg2pdfJavaHeap =  "-Xmx1024m";
	

// PDF split batch executable
//  Linux
//	$pdfSplitExecutable = "run_splitpdf.bash";
//  Windows
	$pdfSplitExecutable = "run_splitpdf";


// PDF merge batch executable
//  Linux
//	$pdfsMergeExecutable = "run_mergepdfs.bash";
//  Windows
	$pdfsMergeExecutable = "run_mergepdfs";

	
// Pdfbox version
//  Linux  Windows
	$pdfboxVersionSplitMerge = "2.0.9";
	
		
		
?>
