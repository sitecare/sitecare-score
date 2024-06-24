jQuery(document).ready(function ($) {

    console.log('getSitecareScan loaded');

    var isProcessing = false;

    function getSitecareScan($init = false) {

        if (isProcessing) {
            console.log('Request already in process.');
            return;
        }

        console.log('getSitecareScan called');

        isProcessing = true; // Set processing flag

        var data = {
            'init': $init
        };

        $.ajax({
            type: "POST",
            url: SiteCarePluginAjax.ajax_url,
            data: {
                action: "sitecare_score_scan",
                security: SiteCarePluginAjax.nonce,
                data: data
            },
            success: function (response) {
                console.log("Operation succeeded:", response);
                console.log("Status:", response.data.status);
                isProcessing = false;
                if ('processing' === response.data.status) {
                    $('#status-text').text(response.data.message);
                    setTimeout(getSitecareScan, 1500);
                } else if ('complete' === response.data.status) {
                    window.location.href = response.data.url;
                }
            },
            error: function (xhr, status, error) {
                console.error("Operation failed:", error);
                isProcessing = false; // Reset processing flag
            }
        });
    }

    getSitecareScan(true);
});
