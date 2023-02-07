# CADViewer Integration for NextCloud

To enable viewing of DWG, DXF, DWF and DGN CAD files using ***[CADViewer](https://www.cadviewer.com)***, please proceed as follows:

1. Copy the content of this cadviewer install folder and put it in the /apps/ folder of your nextcloud installation under /apps/cadviewer/.

2.  In the NextCloud /apps/ folder-structure, navigate to /apps/cadviewer/converter/converters/ax2023/linux/. In this folder the executable, ax2023_L64_xx_yy_zz  needs to have chmod 777 permission for read, write and exe rights.

3. In the NextCloud /apps/ folder-structure, the following folders needs to have full read/write/exe permissions (chmod 777):

Conversion:
```
/apps/cadviewer/converter/converters/ax2023/linux/
/apps/cadviewer/converter/converters/files/
/apps/cadviewer/converter/converters/files/merge/
/apps/cadviewer/converter/converters/files/print/
/apps/cadviewer/converter/converters/files/pdf/
```
Redlines:
```
/apps/cadviewer/converter/content/redlines/
/apps/cadviewer/converter/content/redlines/v7/
```

4. Navigate to the PHP scripts folder: /apps/cadviewer/converter/php/, where the following files needs full read/write/exe permission (chmod 777)

```
call-Api_Conversion_log.txt
call-Api_Conversion.php
save-file.php
```

5. Go to the applications menu of NextCloud and accept to use CADViewer as an untested application. 

6. Activate the NextCloud application

7. ***Success!*** You can now visualize your AutoCAD DWG/DXF/DWF and MicroStation DGN files with a simple click in NextCloud!

8. CADViewer implements CAD viewing, markup and collaboration on the NextCloud platform for AutoCAD, MicroStation, PDF and advanced raster graphics. Following CADViewer features are available in NextCloud:

- **AutoCAD**: Support for DWG, DXF and DWF files.
- **MicroStation**: Support for DGN files.
- **PDF**: Support for Vector Graphics PDF files. (use ... menu)
- **TIFF**: Support for TIFF format. (use ... menu)
- **PNG, JPG, GIF**: Bitmap support. (use ... menu)
- **Annotation**: Full redlining interface of drawings where each user has individually associated redlines.
- **Collaboration**: Redlines/Annotation on drawings are shared to global "CADViewer-Markup" folder as PDF, where users can share internally/externally.
- **Download**: Direct download of SVG or PDF image with/without redlines/annotations.
- **Printing**: Printing of drawings to printer driver or as PDF.
- **Measurement**: Global scale matrix preserved in drawing for measurement and calibration methods.
- **Zoom**: Advanced zoom and pan controls.
- **Layers**: Retained layer structure for layer management.
- **Search**: Integrated text search method.


9. ***[Optional]*** If you are on Windows you will have to modify the file ***cadviewer/converter/php/CADViewer_config.php*** to adapt the configuration to Windows (change executeable name and folders). You will also have to install the Windows back-end CAD converters. Pull the /converters/ax2023/windows/ tree from [cadviewer-script-library](https://github.com/CADViewer/cadviewer-script-library) and replace into the /apps/cadviewer/converter/converters/ tree. In ***cadviewer/converter/php/CADViewer_config.php***, update ***$platform*** and ***$ax2023_executable***.





