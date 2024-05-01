<?php

namespace Sitecare;

function display_sitecare_scan()
{

    ob_start();

    if (!empty($_REQUEST['action'])) {

        $email_report = false;
        if (!empty($_REQUEST['email_report'])) {
            $email_report = $_REQUEST['email_report'];
        }

        $settings = [
            'email_report' => sanitize_key($email_report),
            'email' => sanitize_email($_REQUEST['email']),
        ];

        update_option('sitecare_scan_settings', $settings);

    }

    ?>

    <div class="sitecare-scan" style="text-align: center;">

        <div class="mark">
            <?php echo file_get_contents(__DIR__ . "/../img/mark.svg"); ?>
        </div>

        <div class="text">
            <?php _e("Your website scan is underway.<br/>We'll have your SiteCare Score ready for you in a flash!", "sitecare-score") ?>
        </div>

        <div class="subtext">
            <?php _e("A thorough check-up takes a little time. Thanks for hanging tight!", "sitecare-score") ?>
        </div>

        <div class="loader-bar-container">
            <?php echo file_get_contents(__DIR__ . "/../img/loader.svg"); ?>
        </div>

        <div
                id="status-text"
                class="status-text"
        >
            <?php _e("Initializing scan", "sitecare-score") ?>
        </div>

    </div>

    <?php

    return ob_get_clean();

}
