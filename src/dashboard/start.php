<?php

namespace Sitecare;

function display_sitecare_start()
{

    ?>

    <div class="sitecare-start" style="text-align: center;">

        <div class="headline">
            <?php _e("Get My SiteCare Score", "sitecare-score") ?>
        </div>

        <p>
            <?php _e("By clicking the \"<strong>Scan my Website</strong>\" button, you will initiate a multipoint WordPress site
            health diagnostic that will thoroughly assess the status of your website. This diagnostic tool will analyze
            various aspects of your WordPress site and server health and will provide you with recommended improvements
            to enhance its performance and security.", "sitecare-score") ?>
        </p>

        <form action="<?= admin_url('admin.php?page=sitecare-score') ?>" method="post">

            <div class="email-checkbox-container">
                <div>
                    <input id="email_report" name="email_report" value="true" type="checkbox" checked>
                </div>
                <label for="email_report">
                    <?php _e("Please email me a report", "sitecare-score") ?>
                </label>
            </div>

            <div class="email-text-container">
                <div> <?php _e("Email the report to", "sitecare-score") ?>:</div>
                <input type="text" id="email" name="email" class="email"
                       value="<?php echo get_option('admin_email'); ?>"/>
            </div>

            <input type="hidden" name="action" value="scan"/>
            <input type="submit" class="btn" value="Scan My Website"/>

            <div class="disclaimer">
                Clicking "Scan my Website" allows us to track usage data to help improve future recommendations in our reports. <a href="https://sitecare.com/usage-tracking/" target="_blank">More Info</a>.
            </div>

        </form>

    </div>

    <?php

}
