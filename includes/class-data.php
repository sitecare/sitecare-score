<?php

namespace SiteCare;

class Data extends Core
{
    public function get_data($init = false): array
    {

        global $wpdb, $wp_version;

        $settings = get_option('sitecare_score_settings');
        delete_option('sitecare_score_settings');

        $data = ['url' => get_site_url()];

        if (!$init) {
            return $data;
        }

        $user = get_user_by('email', get_option('admin_email'));

        $data['email_report'] = $settings['email_report'];
        $data['email'] = $settings['email'];

        $data['plugin_version'] = $this->get_current_plugin_version();
        $data['admin_email'] = get_option('admin_email');
        $data['admin_first_name'] = $user->first_name;
        $data['admin_last_name'] = $user->last_name;
        $data['admin_display_name'] = $user->display_name;
        $data['site_name'] = get_bloginfo('name');
        $data['wp_version'] = $wp_version;
        $data['permalink_structure'] = esc_html(get_option('permalink_structure'));
        $data['php_version'] = phpversion();
        $data['server_info'] = $wpdb->db_server_info();
        $data['server_software'] = sanitize_text_field($_SERVER['SERVER_SOFTWARE']);
        $data['https'] = sanitize_text_field($_SERVER['HTTPS']);
        $data['db_type'] = $wpdb->db_version();
        $data['db_version'] = $wpdb->db_version();
        $data['php_uname'] = php_uname();
        $data['content_dir_writable'] = $this->check_directory_status(WP_CONTENT_DIR);
        //  $data['ssl_certificate_status'] = check_ssl_certificate_status( get_site_url() );
        $data['blog_public'] = get_option('blog_public');
        $data['plugins'] = [];
        $data['themes'] = [];
        $data['active_theme'] = [];
        $data['user_count'] = count_users();
        $data['admin_count'] = $this->get_admin_user_count();
        $data['admin_user_exists'] = $this->get_username_admin_exists();
        $data['smtp'] = $this->get_smtp_exists();

        $data['disk_total_space'] = 'not available';
        if (function_exists('disk_total_space')) {
            $data['disk_total_space'] = disk_total_space(ABSPATH);
        }

        $data['disk_free_space'] = 'not available';
        if (function_exists('disk_free_space')) {
            $data['disk_free_space'] = disk_free_space(ABSPATH);
        }

        $url = 'admin.php?page=sitecare-score';
        $data['admin_url'] = admin_url($url);
        $data['timezone'] = wp_timezone_string();
        $data['gmt_offset'] = get_option('gmt_offset');
        $data['local_datetime'] = current_time('mysql');

        foreach (get_plugins() as $path => $plugin) {

            $key = $plugin['TextDomain'];
            if (empty($key)) {
                $key = $path;
            }

            $plugin_data = [];
            $plugin_data['active'] = is_plugin_active($path);
            $plugin_data['name'] = $plugin['Name'];
            $plugin_data['uri'] = $plugin['PluginURI'];
            $plugin_data['version'] = $plugin['Version'];
            $plugin_data['textdomain'] = $plugin['TextDomain'];
            $data['plugins'][$key] = $plugin_data;
        }

        foreach (wp_get_themes() as $path => $theme) {

            $key = $theme['TextDomain'];
            if (empty($key)) {
                $key = $path;
            }

            $active = 0;
            if ($key === wp_get_theme()->get('TextDomain')) {
                $active = 1;
            }

            $parent = null;
            if (!empty($theme->parent())) {
                $parent = $theme->parent()->get_template();
            }

            $theme_data = [];
            $theme_data['active'] = $active;
            $theme_data['name'] = $theme['Name'];
            $theme_data['uri'] = $theme['ThemeURI'];
            $theme_data['version'] = $theme['Version'];
            $theme_data['textdomain'] = $theme['TextDomain'];
            $theme_data['theme_template'] = $theme->get_template();
            $theme_data['theme_parent'] = $parent;
            $data['themes'][$key] = $theme_data;
        }

        if (!empty(wp_get_theme())) {

            $parent = wp_get_theme()->parent();
            $theme_parent = '';
            if (!empty($parent)) {
                $theme_parent = wp_get_theme()->parent()->get_template();
            }

            $data['active_theme'] = [
                'name' => wp_get_theme()->get('Name'),
                'uri' => wp_get_theme()->get('ThemeURI'),
                'version' => wp_get_theme()->get('Version'),
                'textdomain' => wp_get_theme()->get('TextDomain'),
                'theme_template' => wp_get_theme()->get_template(),
                'theme_parent' => $theme_parent
            ];

        }

        return $data;

    }

    public function send_data($init = false, $hash = '', $type = 'cron')
    {

        $headers = ['Content-Type' => 'application/json'];

        $data = [
            'report_hash' => $hash,
            'report_type' => $type,
            'site_data' => $this->get_data(($init))
        ];

        $args = [
            'body' => wp_json_encode($data),
            'headers' => $headers,
            'timeout' => 60
        ];

        $remote_api_url = $this->get_server_url() . '/api/send-wp-data';
        return wp_remote_post($remote_api_url, $args);

    }

    public function check_directory_status($dir_path): string
    {

        global $wp_filesystem;
        require_once ABSPATH . 'wp-admin/includes/file.php';
        WP_Filesystem();

        if ($wp_filesystem->is_writable($dir_path)) {
            return 'true';
        }

        return 'false';
    }

    public function check_ssl_certificate_status($url)
    {

        if (strpos($url, 'https') === false) {
            return 'false';
        }

        $orignal_parse = wp_parse_url($url, PHP_URL_HOST);
        $get = stream_context_create(array("ssl" => array("capture_peer_cert" => TRUE)));
        $read = stream_socket_client("ssl://" . $orignal_parse . ":443", $errno, $errstr,
            30, STREAM_CLIENT_CONNECT, $get);

        if (empty($read)) {
            return 'false';
        }

        $cert = stream_context_get_params($read);
        $certinfo = openssl_x509_parse($cert['options']['ssl']['peer_certificate']);

        return $certinfo;
    }

    public function get_admin_user_count(): int
    {
        $args = [
            'role' => 'Administrator',
            'fields' => 'ID'
        ];

        $user_query = new \WP_User_Query($args);

        // The total number of users with the 'Administrator' role
        $admin_count = $user_query->get_total();

        return $admin_count;
    }

    public function get_username_admin_exists(): bool
    {
        if (username_exists('admin')) {
            return true;
        } else {
            return false;
        }
    }

    public function get_smtp_exists(): bool
    {

        global $phpmailer;

        if (!empty($phpmailer)) {

            $mailer = $phpmailer->Mailer;
            if ('smtp' === $mailer) {
                return true;
            }

            if (!empty($phpmailer->getSMTPInstance())) {
                return true;
            }

        }

        return false;

    }

}
