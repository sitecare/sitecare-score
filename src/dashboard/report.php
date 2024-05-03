<?php

namespace Sitecare;

function display_sitecare_report()
{

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

    ?>

    <style>
        <?php
        $css_url = get_sitecare_server_url() . '/css/sitecare-report.css';
        $response = wp_remote_post($css_url);
        echo wp_kses_post($response['body']);
        ?>
    </style>

    <?php

    $data = get_sitecare_report($_REQUEST['report_id']);
    $report = json_decode($data['body']);
    echo wp_kses($report->html, $allowed_tags);

    ?>

    <script>
        <?php
        $css_url = get_sitecare_server_url() . '/js/sitecare-report.js';
        $response = wp_remote_post($css_url);
        echo wp_kses_post($response['body']);
        ?>
    </script>

    <?php

}
