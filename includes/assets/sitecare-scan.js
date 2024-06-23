jQuery(document).ready(function ($) {

    console.log('getSitecareScan loaded');

    var isProcessing = false;
    var query_count = 0;

    function getSitecareScan() {

        if (isProcessing) {
            console.log('Request already in process.');
            return;
        }

        console.log('getSitecareScan called');
        console.log('count: ' + query_count);

        isProcessing = true; // Set processing flag

        var data = {
            'query_count': query_count
        };

        query_count++;

        $.ajax({
            type: "POST",
            url: SiteCarePluginAjax.ajax_url,
            data: {
                action: "init_sitecare_scan",
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

    getSitecareScan();
});
