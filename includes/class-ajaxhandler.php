<?php

namespace SiteCare;

class AjaxHandler extends Data
{

    public function __construct()
    {
        add_action('wp_ajax_sitecare_score_scan', [$this, 'handle_ajax']);
        add_action('wp_ajax_nopriv_sitecare_score_scan', [$this, 'handle_ajax']);
        add_action('admin_enqueue_scripts', [$this, 'admin_enqueue_scripts']);
    }

    public function handle_ajax(): void
    {

        if (isset($_REQUEST['security'])) {
            $security_nonce = sanitize_text_field($_REQUEST['security']);
            if (!wp_verify_nonce($security_nonce, 'sitecare_nonce')) {
                wp_die('Nonce verification failed!', '', ['response' => 403]);
            }
        }

        $init = false;
        if (isset($_POST['data'])) {
            $init = sanitize_key($_POST['data']['init']);
            $hash = sanitize_key($_POST['data']['report_hash']);
        }

        $response = $this->send_data($init, $hash, 'ajax');

        if (is_wp_error($response)) {

            $error_message = $response->get_error_message();
            wp_send_json_error([
                'message' => "Something went wrong: $error_message"
            ]);

        } else {

            $response = json_decode(wp_remote_retrieve_body($response), true);

            $report_url = admin_url('admin.php?page=sitecare-score&action=report&report_id=' . $response['report_hash']);

            if ('complete' == $response['status']) {
                $this->set_latest_report(
                    $response['report_hash'],
                    $response['score'],
                    $response['color'],
                    $response['bg_color'],
                    $response['change'],
                    $response['issues'],
                    $response['label']
                );
            }

            wp_send_json_success([
                'status' => $response['status'],
                'message' => $response['message'],
                'report_hash' => $response['report_hash'],
                'url' => $report_url
            ]);
        }

    }

    public function admin_enqueue_scripts(): void
    {

        if ('scan' != $this->get_action()) {
            return;
        }

        // Add scanning script

        $path = plugin_dir_url(__FILE__) . 'assets/sitecare-scan.js';

        wp_enqueue_script(
            'sitecare-scan-script',
            $path,
            ['jquery'],
            $this->get_current_plugin_version(),
            true
        );

        wp_localize_script(
            'sitecare-scan-script',
            'SiteCarePluginAjax',
            [
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('sitecare_nonce'),
            ]
        );

    }

}
