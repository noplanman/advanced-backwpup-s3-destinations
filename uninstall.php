<?php

// Make sure the deletion is legit.
defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

if ( ! current_user_can( 'activate_plugins' ) ) {
	return;
}

check_admin_referer( 'bulk-plugins' );

// Delete all traces of this plugin.
delete_site_option( 'advanced_backwpup_s3_destinations' );
delete_site_option( 'advanced_backwpup_s3_destinations_append' );
