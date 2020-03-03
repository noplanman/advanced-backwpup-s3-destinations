<?php
/**
 * Plugin name: Advanced S3 Destinations for BackWPup
 * Plugin URI:  https://git.feneas.org/noplanman/advanced-backwpup-s3-destinations
 * Description: Easily add custom S3 destinations for <a href="https://backwpup.com">BackWPup</a>.
 * Version:     1.1.1
 * Author:      Armando Lüscher
 * Author URI:  https://noplanman.ch
 * Text Domain: advanced-backwpup-s3-destinations
 * Network:     true
 * License:     GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

/**
 * Copyright 2019 Armando Lüscher (email: armando@noplanman.ch)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

namespace NPM\AdvancedBackWPupS3Destinations;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Make sure BackWPup is installed and activated.
 */
add_action( 'admin_init', function () {
	if ( current_user_can( 'activate_plugins' )
		&& ! is_plugin_active( 'backwpup/backwpup.php' )
		&& ! is_plugin_active( 'backwpup-pro/backwpup.php' )
	) {
		deactivate_plugins( plugin_basename( __FILE__ ) );

		add_action( 'admin_notices', 'NPM\AdvancedBackWPupS3Destinations\admin_notice_missing_plugin' );
		add_action( 'network_admin_notices', 'NPM\AdvancedBackWPupS3Destinations\admin_notice_missing_plugin' );
	}
} );

/**
 * Admin notice to alert about missing BackWPup plugin.
 */
function admin_notice_missing_plugin() {
	?>
	<div class="notice notice-error is-dismissible">
		<p><strong><?php _e( 'BackWPup or BackWPup Pro must be installed and activated!', 'advanced-backwpup-s3-destinations' ); ?></strong></p>
		<p><em><?php _e( 'Advanced S3 Destinations for BackWPup has been deactivated.', 'advanced-backwpup-s3-destinations' ); ?></em></p>
	</div>
	<?php
	unset( $_GET['activate'] );
}

/**
 * Fetch the saved S3 destinations.
 *
 * @return array
 */
function get_s3_destinations() {
	return json_decode( get_site_option( 'advanced_backwpup_s3_destinations', '[]' ), true ) ?: array();
}

/**
 * Save the passed S3 destinations.
 *
 * @param array $s3_destinations
 */
function save_s3_destinations( $s3_destinations ) {
	update_site_option( 'advanced_backwpup_s3_destinations', json_encode( $s3_destinations ) );
}


/**
 * Add a new tab to the "BackWPup -> Settings" page.
 */
add_filter( 'backwpup_page_settings_tab', function ( $tabs ) {
	$tabs['s3-destinations'] = esc_html__( 'S3 Destinations', 'advanced-backwpup-s3-destinations' );

	return $tabs;
} );

/**
 * Output the form on the settings page.
 */
add_action( 'backwpup_page_settings_tab_content', function () {
	// Fetch saved destinations.
	$s3_destinations = get_s3_destinations();

	// Always set an extra one to allow expansion.
	$s3_destinations['new-s3-destination'] = $default = array(
		'label'                  => __( 'New S3 destination', 'advanced-backwpup-s3-destinations' ),
		'endpoint'               => '',
		'region'                 => '',
		'multipart'              => false,
		'only_path_style_bucket' => false,
		'version'                => '',
		'signature'              => '',
	);
	?>

	<div class="table ui-tabs-hide" id="backwpup-tab-s3-destinations">

		<h3 class="title"><?php _e( 'Custom S3 destinations', 'advanced-backwpup-s3-destinations' ); ?></h3>

		<label for="s3_destinations_append">
			<input name="s3_destinations_append" type="checkbox" id="s3_destinations_append" value="1" <?php checked( get_site_option( 'advanced_backwpup_s3_destinations_append', true ) ); ?> />
			<?php esc_html_e( 'Append to the default S3 destinations list', 'advanced-backwpup-s3-destinations' ); ?>
		</label>

		<?php foreach ( $s3_destinations as $id => $s3_destination ):
			$name = "s3_destinations[{$id}]";

			// Fill in defaults for missing fields.
			$s3_destination += $default;
			?>
			<div class="card">
				<table class="form-table">
					<tr>
						<th scope="row">
							<label for="s3_destination_id_<?php echo $id; ?>"><?php esc_html_e( 'Unique ID', 'advanced-backwpup-s3-destinations' ); ?> <p class="description"><?php _e( '(required)' ); ?></p></label>
						</th>
						<td>
							<input type="text" name="<?php echo $name; ?>[id]" id="s3_destination_id_<?php echo $id; ?>" value="<?php echo $id; ?>" class="regular-text"/>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="s3_destination_label_<?php echo $id; ?>"><?php esc_html_e( 'Label', 'advanced-backwpup-s3-destinations' ); ?> <p class="description"><?php _e( '(required)' ); ?></p></label>
						</th>
						<td>
							<input type="text" name="<?php echo $name; ?>[label]" id="s3_destination_label_<?php echo $id; ?>" value="<?php echo $s3_destination['label']; ?>" class="regular-text"/>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="s3_destination_endpoint_<?php echo $id; ?>"><?php esc_html_e( 'Endpoint', 'advanced-backwpup-s3-destinations' ); ?> <p class="description"><?php _e( '(required)' ); ?></p></label>
						</th>
						<td>
							<input type="url" name="<?php echo $name; ?>[endpoint]" id="s3_destination_endpoint_<?php echo $id; ?>" value="<?php echo $s3_destination['endpoint']; ?>" placeholder="https://..." class="regular-text"/>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="s3_destination_region_<?php echo $id; ?>"><?php esc_html_e( 'Region', 'advanced-backwpup-s3-destinations' ); ?></label>
						</th>
						<td>
							<input type="text" name="<?php echo $name; ?>[region]" id="s3_destination_region_<?php echo $id; ?>" value="<?php echo $s3_destination['region']; ?>" class="regular-text"/>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Multipart', 'advanced-backwpup-s3-destinations' ); ?></th>
						<td>
							<fieldset>
								<legend class="screen-reader-text">
									<span><?php _e( 'Multipart', 'advanced-backwpup-s3-destinations' ); ?></span>
								</legend>
								<label for="s3_destination_multipart_<?php echo $id; ?>">
									<input name="<?php echo $name; ?>[multipart]" type="checkbox" id="s3_destination_multipart_<?php echo $id; ?>" value="1" <?php checked( $s3_destination['multipart'] ); ?> />
									<?php esc_html_e( 'Destination supports multipart', 'advanced-backwpup-s3-destinations' ); ?>
								</label>
							</fieldset>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Pathstyle-Only Bucket', 'advanced-backwpup-s3-destinations' ); ?></th>
						<td>
							<fieldset>
								<legend class="screen-reader-text">
									<span><?php _e( 'Pathstyle-Only Bucket', 'advanced-backwpup-s3-destinations' ); ?></span>
								</legend>
								<label for="s3_destination_only_path_style_bucket_<?php echo $id; ?>">
									<input name="<?php echo $name; ?>[only_path_style_bucket]" type="checkbox" id="s3_destination_only_path_style_bucket_<?php echo $id; ?>" value="1" <?php checked( $s3_destination['only_path_style_bucket'] ); ?> />
									<?php esc_html_e( 'Destination provides only Pathstyle buckets', 'advanced-backwpup-s3-destinations' ); ?>
								</label>
							</fieldset>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="s3_destination_version_<?php echo $id; ?>"><?php esc_html_e( 'Version', 'advanced-backwpup-s3-destinations' ); ?></label>
						</th>
						<td>
							<input type="text" name="<?php echo $name; ?>[version]" id="s3_destination_version_<?php echo $id; ?>" value="<?php echo $s3_destination['version']; ?>" placeholder="latest"/>
							<p class="description"><?php _e( 'The S3 version for the API like "2006-03-01"', 'advanced-backwpup-s3-destinations' ); ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="s3_destination_signature_<?php echo $id; ?>"><?php esc_html_e( 'Signature', 'advanced-backwpup-s3-destinations' ); ?></label>
						</th>
						<td>
							<input type="text" name="<?php echo $name; ?>[signature]" id="s3_destination_signature_<?php echo $id; ?>" value="<?php echo $s3_destination['signature']; ?>" placeholder="v4"/>
							<p class="description"><?php _e( 'The signature for the API like "v4"', 'advanced-backwpup-s3-destinations' ); ?></p>
						</td>
					</tr>
				</table>
			</div>
		<?php endforeach; ?>

	</div>

	<?php
} );

/**
 * Handle S3 destinations saving.
 */
add_action( 'backwpup_page_settings_save', function () {
	update_site_option( 'advanced_backwpup_s3_destinations_append', ! empty( $_POST['s3_destinations_append'] ) );

	if ( empty( $_POST['s3_destinations'] ) || ! is_array( $_POST['s3_destinations'] ) ) {
		return;
	}

	$s3_destinations = array();

	foreach ( $_POST['s3_destinations'] as $s3_destination ) {
		$s3_destination = array_filter( array_map( 'trim', (array) $s3_destination ) );

		// Skip invalid entries.
		if ( empty( $s3_destination['id'] )
			|| empty( $s3_destination['label'] )
			|| empty( $s3_destination['endpoint'] ) ) {
			continue;
		}

		$id = sanitize_title( $s3_destination['id'] );
		unset( $s3_destination['id'] );

		// Checkboxes to boolean.
		$s3_destination['multipart']              = ! empty( $s3_destination['multipart'] );
		$s3_destination['only_path_style_bucket'] = ! empty( $s3_destination['only_path_style_bucket'] );

		$s3_destinations[ $id ] = $s3_destination;
	}

	save_s3_destinations( $s3_destinations );
} );

/**
 * Display S3 destinations in the job.
 */
add_filter( 'backwpup_s3_destination', function ( $destinations ) {
	if ( ! get_site_option( 'advanced_backwpup_s3_destinations_append', true ) ) {
		$destinations = array();
	}

	return array_merge( $destinations, get_s3_destinations() );
} );
