<?php

namespace Sitecare;

add_action('admin_menu', function () {

    $svg_content = file_get_contents(__DIR__ . "/img/icon.svg");
    $svg_data_uri = 'data:image/svg+xml;base64,' . base64_encode($svg_content);

    add_menu_page(
        'SiteCare Score',
        'SiteCare Score',
        'manage_options',
        'sitecare-score',
        __NAMESPACE__ . '\display_sitecare_score_dashboard',
        $svg_data_uri,
        6
    );

    $hook_suffix = add_submenu_page(
        'sitecare-score',
        'Scan My Site',
        'Scan My Site',
        'manage_options',
        'sitecare-score',
        __NAMESPACE__ . '\display_sitecare_score_dashboard'
    );

    $reports_hook_suffix = add_submenu_page(
        'sitecare-score',
        'Score History',
        'Score History',
        'manage_options',
        'sitecare-reports',
        __NAMESPACE__ . '\display_sitecare_history'
    );

    add_action('admin_enqueue_scripts', function ($hook) use ($hook_suffix, $reports_hook_suffix) {

        if ($hook != $hook_suffix && $hook != $reports_hook_suffix) {
            return;
        }

        $current_page = isset($_GET['page']) ? $_GET['page'] : '';

        if (('sitecare-reports' != $current_page) && ('sitecare-score' != $current_page)) {
            return;
        }


        $ver = get_current_plugin_version();

        wp_enqueue_style(
            'sitecare-admin-css',
            plugin_dir_url(__FILE__) . 'dashboard/sitecare-style.css',
            false,
            $ver
        );


        if (!isset($_REQUEST['action'])) {

            // Add start script

            $path = plugin_dir_url(__FILE__) . 'data/sitecare-start.js';

            wp_enqueue_script(
                'sitecare-start-script',
                $path,
                ['jquery'],
                null,
                true
            );

        }

        if ((isset($_REQUEST['action'])) && ($_REQUEST['action'] == 'scan')) {

            // Add scanning script

            $path = plugin_dir_url(__FILE__) . 'data/sitecare-scan.js';

            wp_enqueue_script(
                'sitecare-scan-script',
                $path,
                ['jquery'],
                null,
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

    });

});
