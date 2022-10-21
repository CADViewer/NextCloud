<?php


//  Http Host
//  URL to the location of home directory the converter infrastructure
//  Windows   Linux
	$httpHost = "http://localhost/cadviewer_4_1_0";


//  Home directory, the local path corresponding to the http host
//  Linux
//	$home_dir = "/var/www/htdocs/cadviewer";
//  Windows
	$home_dir = "C:/xampp/htdocs/cadviewer_4_1_0";


//  ALL PATHS ARE SET UP BASED ON HttpHost and home_dir    (Users can change this setting if an implementation needs to split up locations)



//  URL to the location of controlling php files
//  Windows  Linux
	$httpPhpUrl = $httpHost . "/php/";



//  location of created files and temporary file folder
//  Linux Windows
	$fileLocation = $home_dir . "/converters/files/";


//  location of created files and temporary file folder, http
//  Linux Windows
	$fileLocationUrl = $httpHost . "/converters/files/";



//  Path to the location of the AutoXchange AX2020 converter infrastructure
//  Linux
//	$converterLocation = $home_dir . "/converters/ax2020/linux/";
//  Windows
	$converterLocation = $home_dir . "/converters/ax2020/windows/";



//  Conversion engines executables - names stays stable with each upgrade of conversion engines:
// 	Linux
//	$ax2017_executable = "ax2017_L64_01";
// 	Windows
//	$ax2017_executable = "ax2017.exe";


//  Conversion engines executables - names stays stable with each upgrade of conversion engines:
// 	Linux
//	$ax2019_executable = "ax2019_L64_19_04_06";
// 	Windows
//	$ax2019_executable = "ax2019.exe";
	


//  Conversion engines executables - names stays stable with each upgrade of conversion engines:
// 	Linux
//	$ax2020_executable = "ax2019_L64_19_04_06";
// 	Windows
	$ax2020_executable = "ax2020.exe";


	

//  Path to the location of the license key axlic.key file, typically this is the same location as AX2017
// 	Linux Windows
//	$licenseLocation = $home_dir . "/converters/ax2020/linux/";
//  Windows
	$licenseLocation = $home_dir . "/converters/ax2020/windows/";



//  Path to the XRef locations for external referenced drawings
//  Linux Windows
	$xpathLocation = $home_dir . "/converters/files/";

	

//  Name of PHP document that controls call-back file-transfer to CADViewerJS
	$callbackMethod = "getFile_09.php";



//  Debug parameter to check installation - false for normal operation, if true, the document will echo debug information, - no drawings will be displayed -
	$debug = TRUE;


// Java install folder
//  Linux
//	$javaFolder = "/home/user/jdk1.8.0_121";
//  Windows
	$javaFolder = "C:\\jdk1.8.0_121";


// Pdf converter folder
//  Linux
//	$pdfConverterFolder = $home_dir. "/converters/pdf_converter";

//  Windows
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
	
	
//  Path to the location of the DWGMerge 2019 converter infrastructure
//  Linux
//	$dwgmergeLocation = $home_dir . "/converters/dwgmerge2019/linux/";
//  Windows
	$dwgmergeLocation = $home_dir . "/converters/dwgmerge2020/windows/";



//  DwgMerge engines executables - names stays stable with each upgrade of conversion engines:
// 	Linux
//	$dwgmerge2019_executable = "DwgMerge_W32_19_01_02";
// 	Windows
	$dwgmerge2020_executable = "DwgMerge2020.exe";
	
	
		
	
	
	
	
?>