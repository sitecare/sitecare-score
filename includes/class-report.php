<?php

namespace SiteCare;

class Report extends Core
{

    public function __construct()
    {
        add_action('admin_enqueue_scripts', [$this, 'admin_enqueue']);
    }

    public function init(): void
    {

        if (empty($_REQUEST['report_id'])) {
            return;
        }

        $report = $this->get_report(sanitize_text_field($_REQUEST['report_id']));
        $data = json_decode($report['body']);
        $report_data = $data->data;
        $items = json_decode($report_data->score_data);

        $dt = new \DateTime($report_data->local_datetime);

        ?>

        <div class="sitecare-report">

            <?php
            if (!empty($data->report_limit_message)) {

                $max = '';
                if ($data->report_limit->current >= $data->report_limit->max) {
                    $max = ' max';
                }

                ?>
                <div style="text-align: center;">
                    <div class="sitecare-report-limit-container<?php echo $max; ?>">
                        <div class="sitecare-report-limit-inner">
                            <div class="sitecare-report-limit">
                                <?php if ($data->report_limit->current >= $data->report_limit->max) { ?>

                                    <svg width="16" height="17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <g clip-path="url(#a)">
                                            <path d="M8 16.5A8 8 0 1 0 8 .499 8 8 0 0 0 8 16.5Zm0-12c.416 0 .75.334.75.75v3.5c0 .416-.334.75-.75.75a.748.748 0 0 1-.75-.75v-3.5c0-.416.334-.75.75-.75Zm-1 7a1 1 0 1 1 2 0 1 1 0 0 1-2 0Z"
                                                  fill="#E00099"/>
                                        </g>
                                        <defs>
                                            <clipPath id="a">
                                                <path fill="#E00099" transform="translate(0 .5)" d="M0 0h16v16H0z"/>
                                            </clipPath>
                                        </defs>
                                    </svg>
                                <?php } ?>

                                <div class="text">
                                    <?php echo $data->report_limit_message; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <div class="sitecare-report-summary">
                <div class="content">
                    <div class="headline">SiteCare Score</div>
                    <div class="sitecare-score-date">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="14">
                            <path fill="#0D0E0F"
                                  d="M4.656.656A.655.655 0 0 0 4 0a.655.655 0 0 0-.656.656V1.75H2.25C1.285 1.75.5 2.535.5 3.5v8.75c0 .965.785 1.75 1.75 1.75H11c.965 0 1.75-.785 1.75-1.75V3.5c0-.965-.785-1.75-1.75-1.75H9.906V.656A.655.655 0 0 0 9.25 0a.655.655 0 0 0-.656.656V1.75H4.656V.656ZM1.813 5.25h9.624v7c0 .24-.196.438-.437.438H2.25a.439.439 0 0 1-.438-.438v-7Z"></path>
                        </svg>
                        <div class="date"><?php echo $dt->format('M d, Y'); ?></div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="14">
                            <path fill="#0D0E0F"
                                  d="M13.438 7A5.688 5.688 0 1 1 2.062 7a5.688 5.688 0 0 1 11.376 0ZM.75 7a7 7 0 1 0 14 0 7 7 0 0 0-14 0Zm6.344-3.719V7c0 .219.11.424.292.547l2.625 1.75c.301.202.709.12.91-.183a.654.654 0 0 0-.182-.91L8.406 6.65V3.281a.655.655 0 0 0-.656-.656.655.655 0 0 0-.656.656Z"></path>
                        </svg>
                        <div class="time"><?php echo $dt->format('H:i A'); ?></div>
                    </div>
                    <div class="">
                        Your SiteCare Score is <span class="secondary-color"><?php echo $report_data->score; ?></span>.
                        Read our expert recommendations
                        below to learn how to ensure your website is secure and following best practices.
                    </div>

                    <a href="<?php echo esc_url(admin_url('admin.php?page=sitecare-history')); ?>"
                       class="score-history">
                        <div class="score-history-inner">
                            <div class="">
                                View Past Reports
                            </div>
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M5 10H15M15 10L10 5M15 10L10 15" stroke="#E00099" stroke-width="2"
                                      stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </div>
                    </a>

                </div>
                <div class="score" style="background: <?php echo $data->score_bgcolor; ?>">
                    <?php echo $this->generate_svg_circle($report_data->score, $data->score_color); ?>
                    <div class="score-text">
                        <?php echo $data->score_label; ?>
                    </div>
                </div>
            </div>
            <div id="" class="sitecare-report-score-accordion">

                <?php foreach ($items as $key => $val) {

                    $status = $val->score_label;
                    if (('warning' == $val->score_label) || ('undefined' == $val->score_label)) {
                        $status = 'alert';
                    }
                    $status_text = strtoupper($status);

                    ?>

                    <div class="sitecare-report-score-accordion-item-container">
                        <div class="sitecare-report-score-accordion-item">
                            <h4 class="sitecare-report-score-accordion-heading">
                                <button aria-expanded="false" class="sitecare-report-score-accordion-trigger"
                                        aria-controls="sitecare-report-score-accordion-block-<?php echo $val->id; ?>"
                                        type="button"><span class="title"><?php echo $val->label; ?></span><span
                                            class="badge <?php echo $status; ?>"><?php echo $status_text; ?></span><span
                                            class="icon"></span></button>
                            </h4>
                            <div id="sitecare-report-score-accordion-block-<?php echo $val->id; ?>"
                                 class="sitecare-report-score-accordion-panel" hidden="hidden">
                                <div class="content"><p><?php echo $val->content; ?></p>
                                </div>
                                <div class="quick-tip"><p><?php echo $val->quick_tip; ?></p></div>
                            </div>
                        </div>
                    </div>

                <?php } ?>

            </div>

            <div class="sitecare-report-contact">

                <p>If you’d like to speak with a WordPress expert to help resolve these issues, complete the form below
                    and a SiteCare representative will reach out to offer tailored guidance.</p>

                <a href="https://sitecare.com/contact/?contact=plugin-support" target="_blank"
                   class="btn contact-button">Contact SiteCare</a>

            </div>

        </div>

        <?php

    }

    public function get_report($hash): array
    {

        $remote_api_url = $this->get_server_url() . '/api/get-report-data';

        $headers = ['Content-Type' => 'application/json'];

        $data = [
            'site_url' => get_site_url(),
            'report_hash' => $hash,
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

        $screen = get_current_screen();

        if (!str_contains($screen->id, 'sitecare-score')) {
            return;
        }

        if ('report' != $this->get_action()) {
            return;
        }

        wp_enqueue_script(
            'sitecare-report',
            plugin_dir_url(__FILE__) . 'assets/sitecare-report.js',
            [],
            $this->get_current_plugin_version(),
            ['in_footer' => true]
        );

    }

}
