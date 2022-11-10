# CADViewer Integration for NextCloud

To enable viewing of DWG, DXF, DWF and DGN CAD files using ***[CADViewer](https://www.cadviewer.com)***, please proceed as follows:

1. Copy the content of this cadviewer install folder and put it in the /apps/ folder of your nextcloud installation under /apps/cadviewer/.

2.  In the NextCloud /apps/ folder-structure, navigate to /apps/cadviewer/converter/converters/ax2023/linux/. In this folder the executable, ax2023_L64_xx_yy_zz  needs to have chmod 777 permission for read, write and exe rights.

3. In the NextCloud /apps/ folder-structure, the following folders needs to have full read/write/exe permissions (777):

```
/apps/cadviewer/converter/converters/ax2023/linux/
/apps/cadviewer/converter/converters/files/
/apps/cadviewer/converter/converters/files/merge/
/apps/cadviewer/converter/converters/files/print/
/apps/cadviewer/converter/converters/files/pdf/
/apps/cadviewer/converter/content/redlines/
/apps/cadviewer/converter/content/redlines/v7/
```


4. The file /apps/cadviewer/converter/php/call-Api_Conversion_log.txt  needs read write permissions for everyone (777).

5. Modify the file js/cadviewer.js to put the right value of the variable ServerLocation which corresponds to the location on the disk of the converter folder which is located in the application cadviewer
```
ServerLocation=/var/www/html/apps/cadviewer/converter
```
6. Go to the applications menu of NextCloud and accept to use CADViewer as an untested application. 

7. Activate the NextCloud application

8. You can now visualize your AutoCAD and MicroStation files with a simple click in NextCloud!


9. ***[Optional]*** If you are on Windows you will have to modify the file ***cadviewer/converter/php/CADViewer_config.php*** to adapt the configuration to Windows (change executeable name and folders). You will also have to install the Windows back-end CAD converters. Pull the /converters/ax2023/windows/ tree from [cadviewer-script-library](https://github.com/CADViewer/cadviewer-script-library) and replace into the /apps/cadviewer/converter/converters/ tree. In ***cadviewer/converter/php/CADViewer_config.php***, update ***$platform*** and ***$ax2023_executable***.





