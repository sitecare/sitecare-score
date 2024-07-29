<?php

namespace SiteCare;

class Core
{

    public function get_action($str = ''): string
    {

        if (!isset($_REQUEST['action'])) {
            return '';
        }

        return sanitize_text_field($_REQUEST['action']);

    }

    public function get_current_plugin_version()
    {
        return SITECARE_PLUGIN_VERSION;
    }

    public function display_header(): void
    {

        $this->display_banner();

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

    public function get_server_url(): string
    {

        if (defined('SITECARE_REMOTE_URL')) {
            $remote_url = SITECARE_REMOTE_URL;
        }

        if (defined('SITECARE_DEV_ENVIRONMENT')) {
            $remote_url = SITECARE_DEV_ENVIRONMENT;
        }

        return $remote_url;

    }

    public function generate_svg_circle($score, $color): string
    {
        $radius = 44;
        $strokeWidth = 12;
        $circumference = 2 * M_PI * $radius;
        $dashArray = $circumference;
        $dashOffset = $circumference * (1 - $score / 100);

        return "
        <div class='score-svg'>
            <svg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'>
                <circle cx='50' cy='50' r='$radius' fill='none' stroke='#e7e7e7' stroke-width='$strokeWidth' />
                <circle cx='50' cy='50' r='$radius' fill='none' stroke='$color' stroke-width='$strokeWidth'
                    stroke-linecap='round' transform='rotate(-90 50 50)'
                    stroke-dasharray='$dashArray' stroke-dashoffset='$dashOffset' />
                        <text x='50%' y='55%' text-anchor='middle' dominant-baseline='middle' fill='black' font-size='42'  font-weight='bold'>$score</text>
            </svg>
        </div>";
    }

    public function get_allowed_tags()
    {

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

        return array_merge($kses_defaults, $svg_args);

    }

    public function enqueue_sitecare_styles(): void
    {

        wp_enqueue_style(
            'sitecare-admin-css',
            plugin_dir_url(__FILE__) . 'assets/sitecare-style.css',
            false,
            $this->get_current_plugin_version()
        );

    }

    public function set_latest_report($hash, $score, $color, $bgColor, $change, $issues, $label)
    {

        $report_url = admin_url('admin.php?page=sitecare-score&action=report&report_id=' . $hash);

        $latest_report = [
            'hash' => $hash,
            'report_url' => $report_url,
            'score' => $score,
            'label' => $label,
            'color' => $color,
            'bg_color' => $bgColor,
            'change' => $change,
            'issues' => $issues,
        ];

        update_option('sitecare_score_latest_report', $latest_report);

    }

    public function report_exists()
    {
        $latest = get_option('sitecare_score_latest_report');
        if (empty($latest['hash'])) {
            return false;
        }
        return true;
    }

}
