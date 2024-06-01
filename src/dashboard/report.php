<?php

namespace Sitecare;

function display_sitecare_report()
{

    if ('report' != get_sitecare_action()) {
        return;
    }

    if (empty($_REQUEST['report_id'])) {
        return;
    }

    $kses_defaults = wp_kses_allowed_html('post');

    $svg_args = [
        'svg' => [
            'class' => true,
            'aria-hidden' => true,
            'aria-labelledby' => true,
            'role' => true,
            'xmlns' => true,
            'width' => true,
            'height' => true,
            'viewbox' => true
        ],
        'g' => ['fill' => true],
        'title' => ['title' => true],
        'path' => [
            'd' => true,
            'fill' => true
        ],
        'circle' => [
            'cx' => true,
            'cy' => true,
            'fill' => true,
            'r' => true,
            'stroke' => true,
            'stroke-dasharray' => true,
            'stroke-width' => true,
            'stroke-dashoffset' => true,
            'stroke-linecap' => true,
            'transform' => true,
        ],
        'text' => [
            'x' => true,
            'y' => true,
            'text-anchor' => true,
            'dominant-baseline' => true,
            'fill' => true,
            'font-size' => true,
            'font-weight' => true,
        ]
    ];

    $allowed_tags = array_merge($kses_defaults, $svg_args);
    $data = get_sitecare_report(sanitize_text_field($_REQUEST['report_id']));
    $report = json_decode($data['body']);
    echo wp_kses($report->html, $allowed_tags);

}

add_action('admin_enqueue_scripts', function () {

    $screen = get_current_screen();

    if (!str_contains($screen->id, 'sitecare-score')) {
        return;
    }

    if ('report' != get_sitecare_action()) {
        return;
    }

    $css_url = get_sitecare_server_url() . '/css/sitecare-report.css';

    wp_enqueue_style(
        'sitecare-report',
        $css_url,
        false,
        get_current_plugin_version()
    );

    $js_url = get_sitecare_server_url() . '/js/sitecare-report.js';

    wp_enqueue_script(
        'sitecare-report',
        $js_url,
        ['jquery'],
        get_current_plugin_version(),
        ['in_footer' => true]
    );

});
