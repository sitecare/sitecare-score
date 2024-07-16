<?php

namespace SiteCare;

class CronHandler extends Data
{

    public function __construct()
    {
        add_action('wp', [$this, 'schedule_daily_event']);
        add_action('sitecare_score_daily_event', [$this, 'daily_send_data']);
        register_deactivation_hook(SITECARE_PLUGIN_FILE, [__CLASS__, 'deactivate']);
    }

    public function schedule_daily_event(): void
    {
        if (!wp_next_scheduled('sitecare_score_daily_event')) {
            wp_schedule_event(time(), 'daily', 'sitecare_score_daily_event');
        }
    }

    public function daily_send_data(): void
    {
        $response = $this->send_data(true);
        $response = json_decode(wp_remote_retrieve_body($response), true);

        if ('complete' == $response['status']) {
            $this->set_latest_report(
                $response['report_hash'],
                $response['score'],
                $response['color'],
                $response['bg_color'],
                $response['change'],
                $response['issues'],
                $response['label']
            );
        }
    }

    public static function deactivate(): void
    {
        $timestamp = wp_next_scheduled('sitecare_score_daily_event');
        wp_unschedule_event($timestamp, 'sitecare_score_daily_event');
    }

}
