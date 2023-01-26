(function ($, OC) {

    function checkAutoExchangeLicenceKey  () {
        $(".section-cadviewer").addClass("icon-loading");

        $.ajax({
            method: "GET",
            url: OC.generateUrl("apps/" + OCA.Cadviewer.AppName + "/ajax/settings/autoexchange-verify"),
            success: function onSuccess(response) {
                $(".section-cadviewer").removeClass("icon-loading");
                if (response && (response.domaine_url != null)) {
                    document.getElementById("verification-value").style.display = "block"
                    let installationUrl = response.domaine_url;
                    let verifyOutput = response.output;
                    let instanceID = response.instance_id;
                    
                    $("#installationUrl").html(installationUrl);
                    $("#verifyOutput").html(verifyOutput);
                    $("#instanceID").html(instanceID);
                }
            }
        });
    }

    $(document).ready(function () {
        OCA.Cadviewer = _.extend({}, OCA.Cadviewer);

        if (!OCA.Cadviewer.AppName) {
            OCA.Cadviewer = {
                AppName: "cadviewer"
            };
        }

        $("#cadviewerSave").click(function () {
            $(".section-cadviewer").addClass("icon-loading");

            var cadviewerLicenceKey = $("#cadviewerLicenceKey").val().trim();

            $.ajax({
                method: "PUT",
                url: OC.generateUrl("apps/" + OCA.Cadviewer.AppName + "/ajax/settings/common"),
                data: {
                    licenceKey: cadviewerLicenceKey,
                },
                success: function onSuccess(response) {
                    $(".section-cadviewer").removeClass("icon-loading");
                    if (response && (response.licenceKey != null)) {
                        $("#cadviewerLicenceKey").val(response.licenceKey);

                        var versionMessage = response.version ? (" (" + t(OCA.Cadviewer.AppName, "version") + " " + response.version + ")") : "";

                        if (response.error) {
                            OCP.Toast.error(t(OCA.Cadviewer.AppName, "Error when trying to connect") + " (" + response.error + ")" + versionMessage);
                        } else {
                            OCP.Toast.success(t(OCA.Cadviewer.AppName, "Settings have been successfully updated") + versionMessage);
                        }
                    }
                }
            });
        });

        var axlicFile;
        
        $('#uploadaxlic').change(function() {
            axlicFile = this.files[0];
            $("#uploadaxlicName").html(axlicFile.name);
        });

        // upload the licence key file when click on save button
        $("#cadviewerSaveAxlic").click(function () {
            if (!axlicFile) return;
            $(".section-cadviewer").addClass("icon-loading");

            // Create a new FormData object
            let formData = new FormData();

            // Add the file to the FormData object
            formData.append('file', axlicFile);

            // Send the file data using an AJAX POST request
            $.ajax({
                method: "POST",
                url: OC.generateUrl("apps/" + OCA.Cadviewer.AppName + "/ajax/settings/autoexchange-save-axlic"),
                data: formData,
                processData: false,
                contentType: false,
                success: function onSuccess(response) {
                    $(".section-cadviewer").removeClass("icon-loading");
                    var versionMessage = response.version ? (" (" + t(OCA.Cadviewer.AppName, "version") + " " + response.version + ")") : "";

                    if (response.error) {
                        OCP.Toast.error(t(OCA.Cadviewer.AppName, "Error when trying to save licence key") + " (" + versionMessage+ ")");
                    } else {
                        checkAutoExchangeLicenceKey();
                        OCP.Toast.success(t(OCA.Cadviewer.AppName, "AutoExchange licence key have been successfully saved") + versionMessage);
                    }
                }
            });
        });

        // get the status of AutoExchange licence

        $("#getLicenseKeyInfo").click(function () {
            checkAutoExchangeLicenceKey();
        });


    });

})(jQuery, OC);
