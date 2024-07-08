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
            $points = '6,0 12,12 0,12';
        } else if ('decrease' == $settings['change']) {
            $points = '0,0 12,0 6,12';
        }

        $latest_url = admin_url('admin.php?page=sitecare-score&action=report&report_id=' . $settings['hash']);

        // Score Circle
        $score_circle = '<div class="score-container">';
        $score_circle .= '<div class="score-circle" style="border-color: ' . $settings['color'] . '">';
        $score_circle .= '<div class="score"><div class="score-number" style="color: ' . $settings['color'] . ';">';
        $score_circle .= $settings['score'];
        $score_circle .= '</div>';
        if (!empty($settings['change'])) {
            $score_circle .= '<div class="score-arrow">';
            $score_circle .= '<svg width="12" height="12" xmlns="http://www.w3.org/2000/svg"><polygon points="' . $points . '" fill="' . $settings['color'] . '"/></svg>';
            $score_circle .= '</div>';
        }
        $score_circle .= '</div>';
        $score_circle .= '</div>';
        $score_circle .= '</div>';

        // Issues
        $issues = '<div class="issues">';
        $issues .= '<h3>Top Issues:</h3>';
        if (!empty($settings['issues'])) {
            $issues .= '<ul>';
            foreach ($settings['issues'] as $issue) {
                $issues .= '<li>' . $issue . '</li>';
            }
            $issues .= '</ul>';
        }

        $issues .= '<a href="' . esc_url($latest_url) . '">View Latest Report</a>';

        $issues .= '</div>';

        // Info
        $info = '<div class="sitecare-score-dashboard-widget">';
        $info .= $score_circle;
        $info .= $issues;
        $info .= '</div>';

        echo $info;


    }

}
