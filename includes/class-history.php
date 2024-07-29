<?php

namespace SiteCare;

class History extends Data
{

    public function __construct()
    {
        add_action('admin_enqueue_scripts', [$this, 'admin_enqueue']);
    }

    public function init(): void
    {

        $this->display_header();

        $history = $this->get_history();
        $data = json_decode($history['body']);
        $items = $data->data;

        if (!empty($data->latest)) {
            $this->set_latest_report(
                $data->latest->report_hash,
                $data->latest->score,
                $data->latest->color,
                $data->latest->bg_color,
                $data->latest->change,
                $data->latest->issues,
                $data->latest->label
            );
        }

        $this->history_header();
        $this->history_chart($items);
        $this->history_table($items);

    }

    public function history_header(): void
    {

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

    }

    public function history_chart($items): void
    {

        if (count($items) < 2) {
            return;
        }

        $labels = '[';
        $data = '[';

        $first = true;
        $current_score = '-1';

        foreach (array_reverse($items) as $item) {

            if ($current_score == $item->score) {
                continue;
            }

            if (!$first) {
                $labels .= ',';
                $data .= ',';
            }

            $dt = new \DateTime($item->local_datetime);
            $labels .= "'" . $dt->format('m/d/y') . "'";
            $data .= "'" . $item->score . "'";

            $first = false;
            $current_score = $item->score;

        }

        $labels .= ']';
        $data .= ']';

        ?>

        <canvas id="historyLineChart" height="220" aria-label="History Line Chart" role="img"></canvas>

        <script>
            var ctx = document.getElementById('historyLineChart').getContext('2d');
            var historyLineChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: <?php print_r($labels); ?>,
                    datasets: [{
                        label: 'SiteCare Score',
                        data: <?php print_r($data); ?>,
                        fill: false,
                        tension: 0.1,
                        borderColor: 'rgba(1, 98, 255, 1)',
                        backgroundColor: 'rgba(1, 98, 255, 1)'
                    }]
                },
                options: {
                    plugins: {
                        legend: {
                            display: false
                        },
                    },
                }
            });
        </script>

        <?php

    }

    public function history_table($items): void
    {

        ?>

        <table class="sitecare-history">
            <thead>
            <tr>
                <th>Score</th>
                <th>Date</th>
                <th>Type</th>
                <th>Link</th>
            </tr>
            </thead>
            <tbody id="history-body">

            <?php foreach ($items as $item) {

                $dt = new \DateTime($item->local_datetime);

                $report_type = 'Automatic';
                if ('ajax' == $item->report_type) {
                    $report_type = 'On Demand';
                }

                $report_url = admin_url('admin.php?page=sitecare-score&action=report&report_id=' . $item->hash);

                $score_style = 'background:' . $item->bgcolor . ';';
                $score_style .= 'border-color:' . $item->color . ';';
                $score_style .= 'border-left-color:' . $item->color;

                ?>

                <tr>
                    <td>
                        <div class="score" style="<?php echo $score_style; ?>"><?php echo $item->score; ?>
                            - <?php echo $item->label; ?></div>
                    </td>
                    <td>
                        <div class="sitecare-score-date score-date">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="14">
                                <path fill="#0D0E0F"
                                      d="M4.656.656A.655.655 0 0 0 4 0a.655.655 0 0 0-.656.656V1.75H2.25C1.285 1.75.5 2.535.5 3.5v8.75c0 .965.785 1.75 1.75 1.75H11c.965 0 1.75-.785 1.75-1.75V3.5c0-.965-.785-1.75-1.75-1.75H9.906V.656A.655.655 0 0 0 9.25 0a.655.655 0 0 0-.656.656V1.75H4.656V.656ZM1.813 5.25h9.624v7c0 .24-.196.438-.437.438H2.25a.439.439 0 0 1-.438-.438v-7Z"></path>
                            </svg>
                            <div class="date"><?php echo $dt->format('M d, Y'); ?></div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="14">
                                <path fill="#0D0E0F"
                                      d="M13.438 7A5.688 5.688 0 1 1 2.062 7a5.688 5.688 0 0 1 11.376 0ZM.75 7a7 7 0 1 0 14 0 7 7 0 0 0-14 0Zm6.344-3.719V7c0 .219.11.424.292.547l2.625 1.75c.301.202.709.12.91-.183a.654.654 0 0 0-.182-.91L8.406 6.65V3.281a.655.655 0 0 0-.656-.656.655.655 0 0 0-.656.656Z"></path>
                            </svg>
                            <div class="time"><?php echo $dt->format('g:i A'); ?></div>
                        </div>
                    </td>
                    <td><?php echo $report_type; ?></td>
                    <td>
                        <a href="<?php echo $report_url; ?>"
                           class="view-report">
                            <div class="link-text">View Report</div>
                        </a></td>
                </tr>

            <?php } ?>

            </tbody>
        </table>

        <?php

        $this->display_footer();

    }

    public function get_history()
    {

        $wp_admin_url = 'admin.php?page=sitecare-score';
        $remote_api_url = $this->get_server_url() . '/api/get-history-data';

        $headers = ['Content-Type' => 'application/json'];

        $data = [
            'url' => get_site_url(),
            'admin_url' => admin_url($wp_admin_url),
            'timezone' => wp_timezone_string(),
            'gmt_offset' => get_option('gmt_offset'),
            'plugin_version' => $this->get_current_plugin_version()
        ];

        $args = [
            'body' => wp_json_encode($data),
            'headers' => $headers,
            'timeout' => 60
        ];

        $response = wp_remote_post($remote_api_url, $args);

        return $response;

    }

    public function admin_enqueue(): void
    {

        if (!is_admin()) {
            return;
        }

        $screen = get_current_screen();

        if (!str_contains($screen->id, 'sitecare-history')) {
            return;
        }

        $this->enqueue_sitecare_styles();

        wp_enqueue_script(
            'chartjs',
            'https://cdn.jsdelivr.net/npm/chart.js',
            [],
            $this->get_current_plugin_version()
        );

    }

}
