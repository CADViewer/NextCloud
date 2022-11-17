$ServerLocation = "/var/www/html/apps/cadviewer/converter/";  // Server  Location  Path added here

$(document).ready(function () {
    function removeIframeContainerItem(){
        if(document.querySelector("#iframe_container")){
            // select the target element
            const e = document.querySelector("#iframe_container");
            // remove the list item
            e.parentElement.removeChild(e);
        }
    }
    function loadIframe(content_dir, filename){
        removeIframeContainerItem();
        //Create the element using the createElement method.
        var myDiv = document.createElement("div");
    
        //Set its unique ID.
        myDiv.id = 'iframe_container';
        myDiv.style = `
            position: absolute;
            width: 100vw;
            height: 100vh;
            background: white;
            z-index: 10000;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
        `
    
        //Add your content to the DIV
        myDiv.innerHTML = `
            <div style="height: 100%">
                <div style="display: flex; justify-content: flex-end;height: 30px; padding-right: 20px; padding-top: 5px">
                    <div style="width: 25px; cursor: pointer; height: 25px" id="close-iframe-button">
                        <?xml version="1.0" encoding="iso-8859-1"?>
                        <!-- Generator: Adobe Illustrator 19.0.0, SVG Export Plug-In . SVG Version: 6.00 Build 0)  -->
                        <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                            viewBox="0 0 496 496" style="enable-background:new 0 0 496 496; width: 25px; height: 25px" xml:space="preserve">
                        <g>
                            <g>
                                <g>
                                    <path d="M248,0C111.033,0,0,111.033,0,248s111.033,248,248,248s248-111.033,248-248C495.841,111.099,384.901,0.159,248,0z
                                        M248,480C119.87,480,16,376.13,16,248S119.87,16,248,16s232,103.87,232,232C479.859,376.072,376.072,479.859,248,480z"/>
                                    <path d="M361.136,134.864c-3.124-3.123-8.188-3.123-11.312,0L248,236.688L146.176,134.864c-3.069-3.178-8.134-3.266-11.312-0.197
                                        c-3.178,3.069-3.266,8.134-0.197,11.312c0.064,0.067,0.13,0.132,0.197,0.197L236.688,248L134.864,349.824
                                        c-3.178,3.07-3.266,8.134-0.196,11.312c3.07,3.178,8.134,3.266,11.312,0.196c0.067-0.064,0.132-0.13,0.196-0.196L248,259.312
                                        l101.824,101.824c3.178,3.07,8.242,2.982,11.312-0.196c2.995-3.1,2.995-8.016,0-11.116L259.312,248l101.824-101.824
                                        C364.259,143.052,364.259,137.988,361.136,134.864z"/>
                                </g>
                            </g>
                        </g>
                        </svg>
                    </div>
                </div>
                <iframe id="inlineFrameExample"
                    width="100%"
                    height="100%"
                    src="${window.location.origin}/apps/cadviewer/dist/index.html?ServerBackEndUrl=${window.location.origin}/apps/cadviewer/converter/&ServerLocation=$ServerLocation&ServerUrl=${window.location.origin}/&FileName=${content_dir}/${filename}">
                    <div style="display: flex; justify-content: center; align-items: center;height:50vh;">Chargement...</div>
                </iframe>
            </div>
        `;
    
        //Finally, append the element to the HTML body
        document.body.appendChild(myDiv);
        setTimeout(() => {
            $('#close-iframe-button').click(function(){
                if(document.querySelector("#iframe_container")){
                    // select the target element
                    const e = document.querySelector("#iframe_container");
                    // remove the list item
                    e.parentElement.removeChild(e);
                }
            });
        }, 200);

    }
	var actionsCadviewerDoe = {
		init: function () {
			var self = this;
			OCA.Files.fileActions.registerAction({
			    name: 'view_dwg',
                displayName: 'Visualiser',
                mime: 'application/octet-stream',
                permissions: OC.PERMISSION_NONE,
                type: OCA.Files.FileActions.TYPE_DROPDOWN,
                iconClass: 'icon-visibility-button',
                order: 1000,
                actionHandler: function (filename, context) {
                    console.log({filename, context})
                    var tr = context.fileList.findFileEl(filename);
                    context.fileList.showFileBusyState(tr, true);
                    var data = {
    					nameOfFile: filename,
    					directory: context.dir
    				};
                    $.ajax({
                        type: "POST",
                        async: "false",
                        url: OC.filePath('cadviewer', 'ajax', 'cadviewer.php'),
                        data: data,
                        success: function (element) {
                            context.fileList.showFileBusyState(tr, false);
                            console.log(element);
                            if(element.path)
                                loadIframe(element.path, filename);
                            else
                                OC.dialogs.alert(
                                    t('cadviewer', 'Impossible de visualiser ce fichier Autocad pour le moment') + filename,
                                    t('cadviewer', "Une erreur inconnue c'est produite"),
                                );
                        },
                        error : function(resultat){
                            context.fileList.showFileBusyState(tr, false);
                            OC.dialogs.alert(
                                t('cadviewer', 'Impossible de visualiser ce fichier Autocad pour le moment') + filename,
                                t('cadviewer', "Une erreur inconnue c'est produite"),
                            );
                        }
                    });
                }
		    });
	    
		},
	}

	actionsCadviewerDoe.init();
    OCA.Files.fileActions.setDefault("application/octet-stream", "view_dwg");

});

