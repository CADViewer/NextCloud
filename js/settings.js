(function ($, OC) {

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
                    if (response && (response.licenceKey != null || demo)) {
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

    });

})(jQuery, OC);
