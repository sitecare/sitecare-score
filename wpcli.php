<?php

namespace Sitecare;

$test = 1;

if ( !defined( 'WP_CLI' ) ) {
    return;
}

if ( true !== WP_CLI ) {
    return;
}

if ( !class_exists( 'WP_CLI' ) ) {
    return;
}

\WP_CLI::add_command(
    'get_sitecare_remote_report',
    function ( $args, $assoc_args ) {
		get_sitecare_remote_report($args[0]);
    }
);

\WP_CLI::add_command(
	'getPluginData',
	function ( $args, $assoc_args ) {
		sitecare_get_plugin_data();
	}
);
