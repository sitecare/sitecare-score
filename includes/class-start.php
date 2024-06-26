<?php

namespace SiteCare;

class Start
{

    public function __construct()
    {

    }

    public function init(): void
    {

        $nonce = wp_create_nonce('sitecare_nonce');

        ?>

        <div class="sitecare-start" style="text-align: center;">

            <div class="headline">
                <?php esc_html_e('Get My SiteCare Score', 'sitecare-score') ?>
            </div>

            <p>
                <?php esc_html_e('By clicking the "Scan my Website" button, you will initiate a multipoint WordPress site
            health diagnostic that will thoroughly assess the status of your website. This diagnostic tool will analyze
            various aspects of your WordPress site and server health and will provide you with recommended improvements
            to enhance its performance and security.', 'sitecare-score') ?>
            </p>

            <form action="<?php echo esc_url(admin_url('admin.php?page=sitecare-score')) ?>"
                  method="post">

                <div class="email-checkbox-container">
                    <div>
                        <input id="email_report" name="email_report" value="true" type="checkbox" checked>
                    </div>
                    <label for="email_report">
                        <?php esc_html_e('Please email me a report', 'sitecare-score') ?>
                    </label>
                </div>

                <div class="email-text-container">
                    <label for="email" style="display: block;">
                        <?php esc_html_e('Email the report to', 'sitecare-score') ?>:
                    </label>
                    <input
                            type="text"
                            id="email"
                            name="email"
                            class="email"
                            value="<?php echo esc_attr(get_option('admin_email')); ?>"
                    />
                </div>

                <input type="hidden" name="action" value="scan"/>
                <input type="hidden" name="_wpnonce" value="<?php echo esc_attr($nonce); ?>"/>
                <input type="submit" class="btn" value="Scan My Website"/>

                <div class="disclaimer">
                    <?php esc_html_e('Clicking "Scan my Website" allows us to track usage data to help improve future recommendations in our
                reports. ', 'sitecare-score'); ?><?php echo wp_kses_post("<a href=\"https://sitecare.com/usage-tracking/\" target=\"_blank\">More Info</a>."); ?>
                </div>

            </form>

        </div>

        <?php

    }

}
