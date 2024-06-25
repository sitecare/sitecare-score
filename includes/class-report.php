<?php

namespace Sitecare;

class Report extends Core
{

    public function __construct()
    {
        add_action('admin_enqueue_scripts', [$this, 'admin_enqueue_scripts']);
    }

    public function init(): void
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
        $data = $this->get_report(sanitize_text_field($_REQUEST['report_id']));
        $report = json_decode($data['body']);
        echo wp_kses($report->html, $allowed_tags);

    }

    public function get_report($hash): array
    {

        $remote_api_url = $this->get_server_url() . '/api/get-report';

        $headers = ['Content-Type' => 'application/json'];

        $data = [
            'site_url' => get_site_url(),
            'report_hash' => $hash
        ];

        $args = [
            'body' => wp_json_encode($data),
            'headers' => $headers,
            'timeout' => 60
        ];

        $response = wp_remote_post($remote_api_url, $args);

        return $response;

    }

    public function admin_enqueue_scripts(): void
    {

        $test = 1;
        $screen = get_current_screen();

        if (!str_contains($screen->id, 'sitecare-score')) {
            return;
        }

        if ('report' != $this->get_action()) {
            return;
        }

        $css_url = $this->get_server_url() . '/css/sitecare-score.css';

        wp_enqueue_style(
            'sitecare-report',
            $css_url,
            false,
            $this->get_current_plugin_version()
        );

        $js_url = $this->get_server_url() . '/js/sitecare-report.js';

        wp_enqueue_script(
            'sitecare-report',
            $js_url,
            ['jquery'],
            $this->get_current_plugin_version(),
            ['in_footer' => true]
        );

    }

}
