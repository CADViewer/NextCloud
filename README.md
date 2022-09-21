# Cadviewer Intégration for nextcloud

To make this application cadviewer for nextcloud work you have to proceed as follows 

1. Copy this cadviewer folder and put it in the apps folder of your nextcloud installation 

2. Modify the file js/cadviewer.js to put the right value of the variable ServerLocation which corresponds to the location on the disk of the converter folder which is located in the application cadviewer
```
ServerLocation=/var/www/html/apps/cadviewer/converter
```
3. Go to the applications menu of nextcloud and accept to use the untested applications 

4. Activate the nextcloud application 

5. [Optional] If you are on Windows you will have to modify the file cadviewer/converter/php/CADViewer_config.php to adapt the configuration to Windows  

6. You can now visualize your autocad files with a simple click in nextcloud
