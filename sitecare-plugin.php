<?php

namespace Sitecare;

/**
 * Plugin Name: SiteCare
 * Plugin URI: https://github.com/sitecare/sitecare-score
 * Description: Site analysis plugin
 * Author: SiteCare
 * Author URI: https://sitecare.com
 * Version: 0.1.8
 * Text Domain: sitecare-score
 */

define( 'SITECARE_REMOTE_URL', 'https://sitecarescore.zengy.com' );
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
