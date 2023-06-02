# CADViewer Integration for NextCloud

To enable viewing of DWG, DXF, DWF and DGN CAD files using ***[CADViewer](https://www.cadviewer.com)***, please proceed as follows:

### 1. Copy CADViewer to /apps/cadviewer

Copy the content of this cadviewer install folder and put it in the /apps/ folder of your nextcloud installation under /apps/cadviewer/.

### 2. Permissions

In a typical NextCloud installation, the installation is done with owner www-data:www-data and permissions over the files and folders in the app as chmod 755. 
In this case you will not need to to do anything change permission of files, the CADViewer back-end will function normally under these settings.

If this is not the case of your installation, please do one of the following 2A.) or 2B.) below.  Also refer to 4. troubleshooting, section 5.) below.  


#### 2A. Excecute permission script

Navigate to the ***/apps/cadviewer/scripts/*** */ folder and execute the ***permission.sh*** script. 

This script will do the recommended permission settings (chmod 755) for the relevant CADViewer folders and files. 

If preferred, the user can as an alternative set these permissions manually using the instructions below:

#### 2B. Manually set permissions  - alternative to permission script 2A)

In the NextCloud /apps/ folder-structure, set the recommended permissions (chmod 755) for the following folders and files:

**Execution:**
```
/apps/cadviewer/converter/converters/ax2024/linux/ax2023_L64_xx_yy_zz
```
**Conversion folders:**
```
/apps/cadviewer/converter/converters/ax2024/linux/
/apps/cadviewer/converter/converters/files/
/apps/cadviewer/converter/converters/files/merged/
/apps/cadviewer/converter/converters/files/print/
/apps/cadviewer/converter/converters/files/pdf/
```
**Redlines folders:**
```
/apps/cadviewer/converter/content/redlines/
/apps/cadviewer/converter/content/redlines/v7/
```

**Scripts folder and files:**
```
/apps/cadviewer/converter/php/call-Api_Conversion_log.txt
/apps/cadviewer/converter/php/call-Api_Conversion.php
/apps/cadviewer/converter/php/save-file.php
```

### 3. Activate CADViewer

1.  Go to the applications menu of NextCloud and accept to use CADViewer as an untested application. 

2.  Activate the NextCloud application.

3.  ***Success!*** You can now visualize your AutoCAD DWG/DXF/DWF and MicroStation DGN files with a simple click in NextCloud!


### 4. Troubleshooting

In some cases the automated update of the ***.htaccess*** file is not done. This means that the CADViewer does not connect to the back-end scripts for CAD file conversion. The user experience is that the canvas is white and the "loading.." modal will keep appearing on the screen when attempting to load a file.

1. Go to the install folder of **NextCloud**, this is typically ***/var/www/nextcloud/*** (or where your installation is done), open **.htaccess** in a text editor.

2. Locate the rewrite rule in place:  ***RewriteRule . index.php [PT,E=PATH_INFO:$1]***

3. Add the rewrite condition ***before*** the rewrite rule: ***RewriteCond %{REQUEST_FILENAME} !/apps/cadviewer/converter/php/\*\\.\****

4. If needed, add both the RewriteRule and RewriteCond. 

5. If the server trace gives a Warning: fopen(call-Api_Conversion_log.txt): Failed to open stream: Permission denied , then likely the current permission settings are insufficient. This case can be seen when added nginx as reverse proxy and SSL certificates on a docker container. In that case the owner may have changed, therefore try inside /cadviewer/converter/php/ to give call-Api_Conversion.php full chmod 777 permission and check if call-Api_Conversion.txt has write permissions for the owner (if you are comfortable, you can give chmod 777 on that also.). If this moves further in the process, you must also then give /converter/converters/files folder, full write permission for all owners, and also give /converter/converters/ax2024/linux/ax2023_L64_xx_yy_zz full chmod 777 permissions to perform the CAD conversions for all owners.  



### CADViewer Features

CADViewer implements CAD viewing, markup and collaboration on the NextCloud platform for AutoCAD, MicroStation, PDF and advanced raster graphics. Following CADViewer features are available in NextCloud:

- **AutoCAD**: Support for DWG, DXF and DWF files.
- **MicroStation**: Support for DGN files.
- **PDF**: Support for Vector Graphics PDF files. (use ... menu)
- **TIFF**: Support for TIFF format. (use ... menu)
- **PNG, JPG, GIF**: Bitmap support. (use ... menu)
- **Annotation**: Full redlining interface of drawings where each user has individually associated redlines.
- **PDF Collaboration**: Redlines/Annotation on drawings can be shared as PDF to local *CADViewer-Markup* folder, where the user can then share internally/externally.
- **Download**: Direct download of SVG or PDF image with/without redlines/annotations.
- **Printing**: Printing of drawings to printer driver or as PDF.
- **Measurement**: Global scale matrix preserved in drawing for measurement and calibration methods.
- **Zoom**: Advanced zoom and pan controls.
- **Layers**: Retained layer structure for layer management.
- **Search**: Integrated text search method.
- **Compare**: Advanced compare of drawings.

### Instructions on Windows

***[Optional]*** If you are on Windows you will have to modify the file ***apps/cadviewer/converter/php/CADViewer_config.php*** to adapt the configuration to Windows (change executeable name and folders). You will also have to install the Windows back-end CAD converters. Pull the /converters/ax2024/windows/ tree from [cadviewer-script-library](https://github.com/CADViewer/cadviewer-script-library) and replace into the /apps/cadviewer/converter/converters/ tree. In ***apps/cadviewer/converter/php/CADViewer_config.php***, update ***$platform*** and ***$ax2024_executable***.





