<?php

namespace SiteCare;

class Core
{

    public function get_action(): string
    {

        if (!isset($_REQUEST['action'])) {
            return '';
        }

        return sanitize_text_field($_REQUEST['action']);

    }

    public function get_current_plugin_version()
    {
        $plugin_data = get_plugin_data(SITECARE_PLUGIN_FILE);
        return $plugin_data['Version'];
    }

    public function display_header()
    {

        ?>

        <div class="sitecare-dashboard">

        <div class="wrap">

        <div class="sitecare-dashboard-wrapper">

        <?php

    }

    public function display_footer()
    {

        ?>

        </div>

        </div>

        </div>

        <?php

    }

    public function display_banner(): void
    {

        $screen = get_current_screen();

        if (!str_contains($screen->id, 'sitecare-history') && !str_contains($screen->id, 'sitecare-score')) {
            return;
        }

        ?>

        <div class="sitecare-banner">

            <h1>
                Sitecare
            </h1>

            <a href="https://sitecare.com/contact/?contact=plugin-support"
               target="_blank" class="btn support-button">
                Support
            </a>

        </div>

        <?php
    }

    public function get_server_url()
    {

        if (defined('SITECARE_REMOTE_URL')) {
            $remote_url = SITECARE_REMOTE_URL;
        }

        if (defined('SITECARE_DEV_ENVIRONMENT')) {
            $remote_url = SITECARE_DEV_ENVIRONMENT;
        }

        return $remote_url;

    }


}
