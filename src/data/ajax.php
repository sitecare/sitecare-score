<?php

namespace Sitecare;

add_action('wp_ajax_init_sitecare_scan', function () {

    $nonce = $_POST['security'];

    if (!wp_verify_nonce($nonce, 'sitecare_nonce')) {
        wp_die('Nonce verification failed!', '', ['response' => 403]);
    }

    $data = $_POST['data'];
    $query_count = $data['query_count'];

    if ($query_count < 1) {
        $site_url = get_site_url();
        $current_datetime = date('Y-m-d H:i:s');
        $to_be_hashed = $site_url . $current_datetime;
        update_option('sitecare_report_id_hash', hash('sha256', $to_be_hashed));
    }

    $hash = get_option('sitecare_report_id_hash');

    $remote_api_url = get_sitecare_server_url() . '/api/send-wp-data';

    $headers = ['Content-Type' => 'application/json'];

    $data = [
        'query_count' => $query_count,
        'report_hash' => $hash,
        'site_data' => createData(($query_count == 0))
    ];

    $args = [
        'body' => json_encode($data),
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

    wp_die();

});

/*
add_action('wp_ajax_sitecare_check_process_completion', function () {

// Security check
    $nonce = $_POST['security'];
    if (!wp_verify_nonce($nonce, 'sitecare_nonce')) {
        wp_die('Nonce verification failed!', '', ['response' => 403]);
    }

    $option_value = ((get_option('sitecare_report_complete') == '1') ? true : false);
    wp_send_json_success(['complete' => $option_value]);

});
*/
