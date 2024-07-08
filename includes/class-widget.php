<?php

namespace SiteCare;

class Widget extends Core
{

    public function __construct()
    {
        add_action('wp_dashboard_setup', [$this, 'add_dashboard_widgets']);
    }

    public function add_dashboard_widgets(): void
    {
        wp_add_dashboard_widget(
            'sitecare_score_dashboard_widget',
            'SiteCare Score',
            [$this, 'display_dashboard_widget']
        );
    }

    public function display_dashboard_widget(): void
    {

        $settings = get_option('sitecare_score_latest_report');

        if ('increase' == $settings['change']) {
            $points = '3,0 6,6 0,6';
        } else if ('decrease' == $settings['change']) {
            $points = '0,0 6,0 3,6';
        }

        $latest_url = admin_url('admin.php?page=sitecare-score&action=report&report_id=' . $settings['hash']);

        $info = '<div class="sitecare-score-dashboard-widget">';

        // Score
        $info .= '<div class="score-container">';
        $info .= '<div class="score-circle" style="border-color: ' . $settings['color'] . '">';
        $info .= '<div class="score"><div class="score-number" style="color: ' . $settings['color'] . ';">';
        $info .= $settings['score'];
        $info .= '</div>';

        if (!empty($settings['change'])) {
            $info .= '<div class="score-arrow">';
            $info .= '<svg width="6" height="6" xmlns="http://www.w3.org/2000/svg"><polygon points="' . $points . '" fill="' . $settings['color'] . '"/></svg>';
            $info .= '</div>';
        }

        $info .= '</div>';
        $info .= '</div>';

        // Links
        $info .= '<div class="links">';

        $info .= '<div class="latest">';
        $info .= '<a href="' . esc_url($latest_url) . '">View Latest Report</a>';
        $info .= '</div>';

        $info .= '<div class="history">';
        $info .= '<a href="' . esc_url(admin_url('admin.php?page=sitecare-history')) . '">View Score History</a>';
        $info .= '</div>';

        $info .= '</div>';

        echo 'xyz';

    }

}
