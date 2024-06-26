<?php

namespace SiteCare;

class History extends Data
{

    public function __construct()
    {
    }

    public function init(): void
    {

        $kses_defaults = wp_kses_allowed_html('post');

        $svg_args = ['svg' => ['class' => true,
            'aria-hidden' => true,
            'aria-labelledby' => true,
            'role' => true,
            'xmlns' => true,
            'width' => true,
            'height' => true,
            'viewbox' => true],
            'g' => ['fill' => true],
            'title' => ['title' => true],
            'path' => ['d' => true,
                'fill' => true],];

        $allowed_tags = array_merge($kses_defaults, $svg_args);

        $this->display_header();

        ?>

        <div class="headline history-headline">

            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="none">
                <path fill="#000"
                      d="M4.016 4.016 2.422 2.422C1.714 1.714.5 2.216.5 3.214v5.161c0 .623.502 1.125 1.125 1.125h5.16c1.004 0 1.506-1.214.798-1.922L6.139 6.134A8.97 8.97 0 0 1 12.5 3.5c4.969 0 9 4.031 9 9s-4.031 9-9 9a8.953 8.953 0 0 1-5.142-1.613 1.503 1.503 0 0 0-2.09.37c-.479.68-.31 1.613.37 2.091A11.995 11.995 0 0 0 12.5 24.5c6.628 0 12-5.372 12-12s-5.372-12-12-12a11.964 11.964 0 0 0-8.484 3.516ZM12.5 6.5c-.623 0-1.125.502-1.125 1.125V12.5c0 .3.117.586.328.797l3.375 3.375a1.125 1.125 0 0 0 1.59-1.59l-3.048-3.046V7.625c0-.623-.501-1.125-1.125-1.125h.005Z"/>
            </svg>

            <div class="headline-text">
                <?php esc_html_e('Score History', 'sitecare-score') ?>
            </div>
        </div>

        <?php

        $history = $this->get_history();
        $body = json_decode($history['body']);
        echo wp_kses($body->html, $allowed_tags);

        $this->set_latest_report(
            $body->latest->report_hash,
            $body->latest->score,
            $body->latest->color
        );

        $this->display_footer();

    }

    public function get_history(): \WP_Error|array
    {

        $wp_admin_url = 'admin.php?page=sitecare-score';
        $remote_api_url = $this->get_server_url() . '/api/get-history';

        $headers = ['Content-Type' => 'application/json'];

        $data = [
            'url' => get_site_url(),
            'admin_url' => admin_url($wp_admin_url),
            'timezone' => wp_timezone_string(),
            'gmt_offset' => get_option('gmt_offset')
        ];

        $args = [
            'body' => wp_json_encode($data),
            'headers' => $headers,
            'timeout' => 60
        ];

        $response = wp_remote_post($remote_api_url, $args);

        return $response;

    }

}
