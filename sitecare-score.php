<?php

namespace SiteCare;

/**
 * Plugin Name: SiteCare Score
 * Plugin URI: https://github.com/sitecare/sitecare-score
 * Description: Find hidden technical debt and track your WordPress site health with our comprehensive scanning tool.
 * Author: SiteCare
 * Author URI: https://sitecare.com
 * Version: 1.1.2
 * Text Domain: sitecare-score
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

$version = '1.1.2';

if (!defined('ABSPATH')) {
    exit;
}

if (!defined('SITECARE_REMOTE_URL')) {
    define('SITECARE_REMOTE_URL', 'https://sitecarescore.zengy.com');
}

if (!defined('SITECARE_PLUGIN_URL')) {
    define('SITECARE_PLUGIN_URL', plugin_dir_url(__FILE__));
}

if (!defined('SITECARE_PLUGIN_DIR')) {
    define('SITECARE_PLUGIN_DIR', dirname(__FILE__));
}

if (!defined('SITECARE_PLUGIN_FILE')) {
    define('SITECARE_PLUGIN_FILE', __FILE__);
}

if (!defined('SITECARE_PLUGIN_VERSION')) {
    define('SITECARE_PLUGIN_VERSION', $version);
}

require_once plugin_dir_path(__FILE__) . 'includes/autoloader.php';

new Score();
