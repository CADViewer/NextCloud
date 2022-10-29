# CADViewer Integration for NextCloud

To enable viewing of DWG, DXF, DWF and DGN CAD files using ***[CADViewer](https://www.cadviewer.com)***, please proceed as follows:

1. Copy the content of this cadviewer install folder and put it in the /apps/ folder of your nextcloud installation under /apps/cadviewer/.

2.  Navigate to /apps/cadviewer/converter/converters/ax2023/linux/. In this folder the executable, ax2023_L64_xx_yy_zz  needs to have chmod 777 permission.

3. The folder /apps/cadviewer/converter/converters/ax2023/linux/ itself also needs to have full read/write/exe permissions (777).

4. The folder /apps/cadviewer/converter/converters/files/ also needs to have full read/write/exe permissions (777).

5. The folders  /apps/cadviewer/converter/converters/files/merge/, /apps/cadviewer/converter/converters/files/print/, /apps/cadviewer/converter/converters/files/pdf/ also need full read/write permissions for everyone (777).

6. The file /apps/cadviewer/converter/php/call-Api_Conversion_log.txt  needs read write permissions for everyone (777).

7. Modify the file js/cadviewer.js to put the right value of the variable ServerLocation which corresponds to the location on the disk of the converter folder which is located in the application cadviewer
```
ServerLocation=/var/www/html/apps/cadviewer/converter
```
8. Go to the applications menu of NextCloud and accept to use CADViewer as an untested application. 

9. Activate the NextCloud application

10. [Optional] If you are on Windows you will have to modify the file cadviewer/converter/php/CADViewer_config.php to adapt the configuration to Windows and you will have to install the Windows back-end CAD converters.   

11. You can now visualize your AutoCAD and MicroStation files with a simple click in NextCloud!




