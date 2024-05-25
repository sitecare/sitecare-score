<?php

namespace Sitecare;

/**
 * Plugin Name: SiteCare Score
 * Plugin URI: https://github.com/sitecare/sitecare-score
 * Description: Find hidden technical debt and track your WordPress site health with our comprehensive scanning tool.
 * Author: SiteCare
 * Author URI: https://sitecare.com
 * Version: 1.0.1
 * Text Domain: sitecare-score
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

define( 'SITECARE_REMOTE_URL', 'https://sitecarescore.zengy.com' );
define( 'SITECARE_PLUGIN_URL', plugin_dir_url(__FILE__) );
define( 'SITECARE_PLUGIN_DIR', dirname(__FILE__) );
define( 'SITECARE_PLUGIN_FILE', __FILE__ );

include 'src/init.php';
include 'src/data/ajax.php';
include 'src/data/data.php';
include 'src/data/wpcli.php';
include 'src/dashboard/global/banner.php';
include 'src/dashboard/global/header.php';
include 'src/dashboard/global/footer.php';
include 'src/dashboard/dashboard.php';
include 'src/dashboard/start.php';
include 'src/dashboard/scan.php';
include 'src/dashboard/report.php';
include 'src/dashboard/history.php';
