<?php

namespace SiteCare;

class Ajax extends Core
{

    private Data $data;

    public function __construct()
    {
        add_action('wp_ajax_sitecare_score_scan', [$this, 'handle_ajax']);
        add_action('wp_ajax_nopriv_sitecare_score_scan', [$this, 'handle_ajax']);
        add_action('admin_enqueue_scripts', [$this, 'admin_enqueue_scripts']);
        $this->data = new Data();
    }

    public function handle_ajax(): void
    {

        if (isset($_REQUEST['security'])) {
            $security_nonce = sanitize_text_field($_REQUEST['security']);
            if (!wp_verify_nonce($security_nonce, 'sitecare_nonce')) {
                wp_die('Nonce verification failed!', '', ['response' => 403]);
            }
        }

        $query_count = 0;

        if (isset($_POST['data'])) {
            $query_count = sanitize_key($_POST['data']['query_count']);
            $query_count = intval($query_count);
        }

        if ($query_count < 1) {
            $site_url = get_site_url();
            $current_datetime = gmdate('Y-m-d H:i:s');
            $to_be_hashed = $site_url . $current_datetime;
            update_option('sitecare_report_id_hash', hash('sha256', $to_be_hashed));
        }

        $hash = get_option('sitecare_report_id_hash');

        $remote_api_url = $this->get_server_url() . '/api/send-wp-data';

        $headers = ['Content-Type' => 'application/json'];

        $data = [
            'query_count' => $query_count,
            'report_hash' => $hash,
            'site_data' => $this->data->init(($query_count == 0))
        ];

        $args = [
            'body' => wp_json_encode($data),
            'headers' => $headers,
            'timeout' => 60
        ];

        $response = wp_remote_post($remote_api_url, $args);

        if (is_wp_error($response)) {

            $error_message = $response->get_error_message();
            wp_send_json_error([
                'message' => "Something went wrong: $error_message"
            ]);

        } else {

            $response = json_decode(wp_remote_retrieve_body($response), true);

            wp_send_json_success([
                'status' => $response['status'],
                'message' => $response['message'],
                'url' => admin_url('admin.php?page=sitecare-score&action=report&report_id=' . $response['report_hash'])
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
