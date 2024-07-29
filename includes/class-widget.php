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
        if (empty($this->report_exists())) {
            return;
        }

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
        $score_circle .= '<div class="score" style="background:' . $settings['bg_color'] . '">';
        $score_circle .= $this->generate_svg_circle($settings['score'], $settings['color']);
        $score_circle .= '<div class="score-text-container">';
        $score_circle .= '<div class="score-text">';
        $score_circle .= $settings['label'];
        $score_circle .= '</div>';
        $score_circle .= '<div class="score-arrow">';
        $score_circle .= '<svg width="12" height="16" viewBox="0 0 12 16" fill="none" xmlns="http://www.w3.org/2000/svg">
  <path d="';

        if ('increase' == $settings['change']) {
            $score_circle .= 'M6 15L6 1M6 1L1 6M6 1L11 6';
        } else if ('decrease' == $settings['change']) {
            $score_circle .= 'M6 1L6 15M6 15L1 10M6 15L11 10';
        }

        $score_circle .= '" stroke="' . $settings['color'] . '" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
        $score_circle .= '</div>';
        $score_circle .= '</div>';
        $score_circle .= '</div>';
        $score_circle .= '</div>';

        // Issues
        $issues = '<div class="issues">';
        $issues .= '<div class="title">';

        $issues .= '<div class="svg">';
        $issues .= '<svg width="16" height="17" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#a)"><path d="M8 16.5A8 8 0 1 0 8 .499 8 8 0 0 0 8 16.5Zm0-12c.416 0 .75.334.75.75v3.5c0 .416-.334.75-.75.75a.748.748 0 0 1-.75-.75v-3.5c0-.416.334-.75.75-.75Zm-1 7a1 1 0 1 1 2 0 1 1 0 0 1-2 0Z" fill="#fff"/></g><defs><clipPath id="a"><path fill="#fff" transform="translate(0 .5)" d="M0 0h16v16H0z"/></clipPath></defs></svg>';
        $issues .= '</div>';

        $issues .= '<div class="text">Top Issues</div>';
        $issues .= '</div>';

        if (!empty($settings['issues'])) {
            $issues .= '<ul class="dashed">';
            foreach ($settings['issues'] as $issue) {
                $issues .= '<li>' . $issue . '</li>';
            }
            $issues .= '</ul>';
        }

        $issues .= '</div>';


        // Info
        $info = '<div class="sitecare-score-dashboard-widget-container">';
        $info .= '<div class="sitecare-score-dashboard-widget">';
        $info .= $score_circle;
        $info .= $issues;
        $info .= '</div>';

        $info .= '<div class="latest-report">';
        $info .= '<a href="' . esc_url($latest_url) . '">View Latest Report</a>';
        $info .= '</div>';

        $info .= '</div>';

        echo $info;


    }

}
