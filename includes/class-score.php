<?php

namespace SiteCare;

class Score extends Core
{

    private History $history;
    private Dashboard $dashboard;

    public function __construct()
    {

        add_action('admin_menu', [$this, 'plugin_menu']);
        add_action('in_admin_header', [$this, 'hide_admin_notices'], 99);

        new AjaxHandler();
        new CronHandler();
        new ToolBar();
        new Widget();

        $this->dashboard = new Dashboard();
        $this->history = new History();

    }

    public function plugin_menu(): void
    {
        $svg_icon = 'data:image/svg+xml;base64,' . base64_encode('<svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 173.91 173.96"><path d="M43.54 137.31h13.83v17.95c0 2.81 1.94 4.81 4.77 4.81 16.48 0 32.96-.02 49.45-.05 2.97 0 4.9-1.98 4.92-4.94.03-5.56-.06-11.13.08-16.68.28-10.45 8.67-19.94 19.04-21.44 3.01-.44 6.11-.39 9.17-.45 3.43-.07 6.87 0 10.31-.02 3.03 0 4.9-1.94 4.9-5.03V62.5c0-3.1-1.75-4.97-4.83-5.18-.33-.02-.65-.01-.98-.01H90.4c-2.58 0-5.15.23-7.56 1.27-3.54 1.53-6.01 4.03-6.49 7.96-.48 3.93 1.3 6.97 4.33 9.33 2.84 2.21 6.24 2.88 9.69 3.5 8.01 1.45 15.47 4.18 21.55 9.82 5.02 4.66 7.93 10.34 7.58 17.38-.33 6.45-3.38 11.52-8.22 15.57-5.47 4.57-11.95 6.81-18.92 7.69-3.07.39-6.19.51-9.29.52-23.39.04-46.79.02-70.18.02C5.51 130.35 0 124.86 0 117.5V56.27c0-7.42 5.74-12.91 13.16-12.8 7.07.11 14.15.08 21.23.01 5-.05 9.04-4.27 9.05-9.28.01-7.32-.04-14.64.05-21.96C43.57 5.76 48.93.38 55.43.06c.45-.02.9-.05 1.35-.05 20.41 0 40.82-.01 61.23 0 6.47 0 12.16 5.3 12.3 11.76.18 8.05.05 16.11.04 24.17 0 .16-.05.32-.09.57h-13.67c-.02-.49-.06-.97-.06-1.44V19.12c0-3.46-1.84-5.28-5.31-5.28h-48.1c-.57 0-1.15.03-1.71.11-2.43.35-4.01 2.17-4.01 4.63-.02 5.48.08 10.96-.08 16.44-.33 11.64-9.4 21.33-21.03 22.16-5.65.4-11.36.19-17.04.09-3.04-.06-5.35 1.86-5.35 4.89v49.57c0 2.82 1.87 4.73 4.67 4.73 22.17.01 44.34.01 66.5 0 2.7 0 5.27-.62 7.6-2.02 5.93-3.56 6.61-11.09 1.43-15.69-2.63-2.34-5.78-3.44-9.19-4-6.96-1.13-13.59-3.2-19.46-7.26-4.95-3.42-8.76-7.78-10.38-13.68-2.1-7.69-.01-14.39 5.37-20.09 4.9-5.18 11.16-7.95 18.06-9.16 3.96-.69 8.03-1.05 12.05-1.07 23.07-.1 46.14.02 69.2-.12 3.71-.02 6.8.96 9.61 3.17 3.02 2.38 4.51 5.58 4.52 9.38.03 20.65.04 41.31 0 61.96-.01 6.86-5.72 12.46-12.55 12.47-7.04 0-14.07-.01-21.1 0-4.78.01-8.55 2.86-9.57 7.24-.22.94-.29 1.94-.29 2.92-.02 6.83 0 13.66-.01 20.49 0 7.31-5.49 12.83-12.76 12.84-17.99.04-35.99.08-53.98.1-3.06 0-6.14.08-9.19-.19-5.79-.51-10.85-5.93-10.92-11.73-.1-8.06-.02-16.11-.02-24.17 0-.16.04-.31.07-.55" style="fill:#fff;stroke-width:0"/></svg>');

        add_menu_page(
            'SiteCare Score',
            'SiteCare Score',
            'manage_options',
            'sitecare-score',
            [$this->dashboard, 'init'],
            $svg_icon,
            6
        );

        if (!empty($this->report_exists())) {

            add_submenu_page(
                'sitecare-score',
                'Scan My Site',
                'Scan My Site',
                'manage_options',
                'sitecare-score',
                [$this->dashboard, 'init'],
            );

            add_submenu_page(
                'sitecare-score',
                'Score History',
                'Score History',
                'manage_options',
                'sitecare-history',
                [$this->history, 'init'],
            );

        }

    }

    public function hide_admin_notices(): void
    {

        $screen = get_current_screen();

        if (!str_contains($screen->id, 'sitecare-history') && !str_contains($screen->id, 'sitecare-score')) {
            return;
        }

        remove_all_actions('user_admin_notices');
        remove_all_actions('admin_notices');
    }

}
