<?php

namespace SiteCare;

class CronHandler extends Core
{

    public function __construct()
    {
        add_action('wp', [$this, 'schedule_daily_event']);
        add_action('sitecare_daily_event', [$this, 'daily_task']);
        register_deactivation_hook(SITECARE_PLUGIN_FILE, [__CLASS__, 'deactivate']);
    }

    public function schedule_daily_event(): void
    {
        if (!wp_next_scheduled('sitecare_daily_event')) {
            wp_schedule_event(time(), 'daily', 'sitecare_daily_event');
        }
    }

    public function daily_task(): void
    {
        $test = 1;
    }

    public static function deactivate(): void
    {
        $timestamp = wp_next_scheduled('sitecare_daily_event');
        wp_unschedule_event($timestamp, 'sitecare_daily_event');
    }

}
