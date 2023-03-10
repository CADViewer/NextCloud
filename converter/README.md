# cadviewer7-script-library-php-linux

The repository contains a full setup of CADViewer with CAD Converters and script controllers for Apache Linux running PHP.

***NOTE:*** Install the content of this repository on Apache Linux with PHP under /var/www/html/cadviewer/



## This package contains

1: CADViewer script library  - in its preferred folder structure

2: AutoXchange AX2024 Converter , LinkList 2024, and DWGMerge 2024 Converter - in their preferred folder structure

3: All structures for file-conversion, sample drawings, redlines, etc. 

4: A number of HTML files with CADViewer samples.

5: The folder structure for script handlers for communication between CADViewer and the back-end AutoXchange 2024.


## This package does not contain

6: The converter folder structure contains a larger set of fonts, installed in /cadviewer/converters/ax2024/linux/fonts/, but a fuller set of fonts can be installed. 

Read the sections on installing and handling [Fonts](https://tailormade.com/ax2020techdocs/installation/fonts/) in [AutoXchange 2023 TechDocs](https://tailormade.com/ax2020techdocs/) and [TroubleShooting](https://tailormade.com/ax2020techdocs/troubleshooting/).



## How to Use

A: Once installed, the HTML samples under /cadviewer/html/ can be run from a web-browser. Use http://localhost/cadviewer/html/CADViewer_fileloader_70.html as a starting point (assuming that your have installed under http://localhost).

B: If changing the location of the installation, please update ***/cadviewer/php/CADViewer_config.php***. The top part of this config file contains automated settings of ***$home_dir*** (cadviewer install folder) and ***$httpHost*** (cadviewer install folder url), but they can be manually overwritten. It also contains settings for the install platform (windows or linux). 

C: If updating any of executables in the install structure, then update their variable names in  ***/cadviewer/php/CADViewer_config.php**. These varaiables can be found with ***"_executable"*** in the variable name, for example ***"ax2020_executable"***. 

D: Please note the variable controls in the HTML documents as described below in ***Troubleshooting***.



## Documentation 

-   [CADViewer Techdocs and Installation Guide](https://cadviewer.com/cadviewertechdocs/download)




## Updating CAD Converters

This repository should contain the latest converters, but in case you need to update any of the back-end converters please follow: 

* [Download **AutoXchange**](/download/) (and other converters), install (unzip) ax2023 in **cadviewer/converters/ax2023/linux** or in the designated folder structure.

* Read the sections on installing and handling [Fonts](https://tailormade.com/ax2020techdocs/installation/fonts/) in [AutoXchange 2023 TechDocs](https://tailormade.com/ax2020techdocs/) and [TroubleShooting](https://tailormade.com/ax2020techdocs/troubleshooting/).

* Try out the samples and build your own application!
 
 

 
 ## Troubleshooting

1: One issue that often appears in installations is that interface icons do not display properly:

![Icons](https://cadviewer.com/cadviewertechdocs/images/missing_icons.png "Icons missing")

Typically the variables *ServerUrl*, *ServerLocation* or *ServerBackEndUrl* in the controlling **HTML**  document in ***/cadviewer/html/*** are not set to reflect the front-end server url or port.

<pre style="line-height: 110%">


    var ServerBackEndUrl = "";  // or what is appropriate for my server
    var ServerUrl = "http://localhost/cadviewer/";   // or what is appropriate for my server
    var ServerLocation = ""; // or what is appropriate for my server
</pre>
<br>


2: Another issue can be that the path settings in ***/cadviewer/php/CADViewer_config.php*** are not done correctly. This typically manifests in that the drawing will not load/display correctly in CADViewer.

Open the debug file: ***/cadviewer/php/call-Api_Conversion_log.txt***

A. check that all paths and executable names correspond to server settings, if not modify ***/cadviewer/php/CADViewer_config.php***.

B: identify the command line string for the conversion, it will be directly after the string **"before call to exec:"** (window string below, but similar for Linux)


<pre style="line-height: 110%">

"/var/www/html/cadviewer/converters/ax2023/linux/ax2023_L64_23_05_88" "-i=/var/www/html/cadviewer/content/drawings/dwg/hq17_.dwg" "-o=/var/www/html/cadviewer/converters/files/f2134145163.svg"  "-f=svg" -last "-rl=RM_" "-tl=RM_TXT" "-lpath=/var/www/html/cadviewer/converters/ax2023/linux/"

</pre>

Run this from a command line prompt. Checks if there are any permission or path issues. Change in ***/cadviewer/php/CADViewer_config.php*** or file-name in the calling HTML document.



 
**Have Fun!**  - and get in [touch](mailto:developer@tailormade.com)  with us!
