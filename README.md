# CADViewer Integration for NextCloud

To enable viewing of DWG, DXF, DWF and DGN CAD files using ***[CADViewer](https://www.cadviewer.com)***, please proceed as follows:

### 1. Copy CADViewer to /apps/cadviewer

Copy the content of this cadviewer install folder and put it in the /apps/ folder of your nextcloud installation under /apps/cadviewer/.

### 2A. Excecute permission script

Navigate to the ***/apps/cadviewer/scripts/*** */ folder and execute the ***permission.sh*** script. This script will do the permission settings for the CADViewer folders. If preferred, the user can as an alternative set these permissions manually using the instructions below 

### 2B. Manually set permissions  - alternative to permission script 2A)

In the NextCloud ***/apps/*** */ folder-structure, navigate to ***/apps/cadviewer/converter/converters/ax2024/linux/** */. In this folder the executable, ax2023_L64_xx_yy_zz  needs to have chmod 777 permission for read, write and exe rights.

In the NextCloud /apps/ folder-structure, the following folders needs to have full read/write/exe permissions (chmod 777):

**Conversion:**
```
/apps/cadviewer/converter/converters/ax2024/linux/
/apps/cadviewer/converter/converters/files/
/apps/cadviewer/converter/converters/files/merged/
/apps/cadviewer/converter/converters/files/print/
/apps/cadviewer/converter/converters/files/pdf/
```
**Redlines:**
```
/apps/cadviewer/converter/content/redlines/
/apps/cadviewer/converter/content/redlines/v7/
```

Navigate to the PHP scripts folder: ***/apps/cadviewer/converter/php/*** , where the following files needs full read/write/exe permission (chmod 777)

```
call-Api_Conversion_log.txt
call-Api_Conversion.php
save-file.php
```

### 3. Activate CADViewer

1.  Go to the applications menu of NextCloud and accept to use CADViewer as an untested application. 

2.  Activate the NextCloud application.

3.  ***Success!*** You can now visualize your AutoCAD DWG/DXF/DWF and MicroStation DGN files with a simple click in NextCloud!


### 4. Troubleshooting

In some cases the automated update of the ***.htaccess*** file is not done. This means that the CADViewer does not connect to the back-end scripts for CAD file conversion. The user experience is that the canvas is white and the "loading.." modal will keep appearing on the screen when attempting to load a file.

1. Go to the install folder of **NextCloud**, this is typically ***/var/www/nextcloud/*** (or where your installation is done), open **.htaccess** in a text editor.

2. Locate the rewrite rule in place:  ***RewriteRule . index.php [PT,E=PATH_INFO:$1]***

3. Add the rewrite condition before the rewrite rule: RewriteCond %{REQUEST_FILENAME} !/apps/cadviewer/converter/php/\*\.\*

4. If needed, add both the RewriteRule and RewriteCond. 




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

***[Optional]*** If you are on Windows you will have to modify the file ***apps/cadviewer/converter/php/CADViewer_config.php*** to adapt the configuration to Windows (change executeable name and folders). You will also have to install the Windows back-end CAD converters. Pull the /converters/ax2023/windows/ tree from [cadviewer-script-library](https://github.com/CADViewer/cadviewer-script-library) and replace into the /apps/cadviewer/converter/converters/ tree. In ***apps/cadviewer/converter/php/CADViewer_config.php***, update ***$platform*** and ***$ax2023_executable***.





