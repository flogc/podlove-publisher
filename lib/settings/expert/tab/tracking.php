<?php
namespace Podlove\Settings\Expert\Tab;
use \Podlove\Settings\Settings;
use \Podlove\Settings\Expert\Tab;
use \Podlove\Model;
use \Podlove\Geo_Ip;

class Tracking extends Tab {

	public function get_slug() {
		return 'tracking';
	}	

	public function init() {

		add_settings_section(
			/* $id 		 */ 'podlove_settings_episode',
			/* $title 	 */ __( '', 'podlove-podcasting-plugin-for-wordpress' ),	
			/* $callback */ function () {
				echo '<h3>' . __( 'Download Tracking & Analytics Settings', 'podlove-podcasting-plugin-for-wordpress' ) . '</h3>';
				?>
				<style type="text/css">
				.form-table .aligned-radio { display: table; margin-bottom: 10px; }
				.form-table .aligned-radio .row { display: table-row; }
				.form-table .aligned-radio .row > div { display: table-cell; }
				</style>
				<?php
			},
			/* $page	 */ Settings::$pagehook	
		);

		add_settings_field(
			/* $id       */ 'podlove_setting_tracking',
			/* $title    */ sprintf(
				'<label for="mode">%s</label>',
				__( 'Tracking Mode', 'podlove-podcasting-plugin-for-wordpress' )
			),
			/* $callback */ function () {
				?>

				<label class="aligned-radio">
					<div class="row">
						<div>
							<input name="podlove_tracking[mode]" type="radio" value="ptm_analytics" <?php checked( \Podlove\get_setting( 'tracking', 'mode' ), 'ptm_analytics' ) ?> />
						</div>
						<div>
							<?php echo sprintf(
								'<div><strong>%s</strong><br>%s</div>',
								__( 'Tracking URL Parameters &amp; Analytics', 'podlove-podcasting-plugin-for-wordpress' ),
								__( 'Instead of the original file URLs, users and clients see a link that points to the Publisher. 
									The Publisher logs the download intent and redirects the user to the original file. 
									That way the Publisher is able to generate download statistics. ', 'podlove-podcasting-plugin-for-wordpress' )
							); ?>
						</div>
					</div>
				</label>

				<label class="aligned-radio">
					<div class="row">
						<div>
							<input name="podlove_tracking[mode]" type="radio" value="ptm" <?php checked( \Podlove\get_setting( 'tracking', 'mode' ), 'ptm' ) ?> />
						</div>
						<div>
							<?php echo sprintf(
								'<div><strong>%s</strong><br>%s</div>',
								__( 'Tracking URL Parameters', 'podlove-podcasting-plugin-for-wordpress' ),
								__( 'Original file URLs are extended by tracking parameters before presenting them to users and clients. 
									This is useful if you are using your server log files for download analytics. 
									No download-data is tracked.', 'podlove-podcasting-plugin-for-wordpress' )
							); ?>
						</div>
					</div>
				</label>

				<label class="aligned-radio">
					<div class="row">
						<div>
							<input name="podlove_tracking[mode]" type="radio" value="0" <?php checked( \Podlove\get_setting( 'tracking', 'mode' ), 0 ) ?> />
						</div>
						<div>
							<?php echo sprintf(
								'<div><strong>%s</strong><br>%s</div>',
								__( 'No Tracking', 'podlove-podcasting-plugin-for-wordpress' ),
								__( 'Original file URLs are presented to users and clients. No download-data is tracked.', 'podlove-podcasting-plugin-for-wordpress' )
							); ?>
						</div>
					</div>
				</label>
				
				<?php
			},
			/* $page     */ Settings::$pagehook,  
			/* $section  */ 'podlove_settings_episode'
		);

		add_settings_field(
			/* $id       */ 'podlove_status_location_database',
			/* $title    */ sprintf(
				'<label for="mode">%s</label>',
				__( 'Geolocation Lookup', 'podlove-podcasting-plugin-for-wordpress' )
			),
			/* $callback */ function () {
				$file = \Podlove\Geo_Ip::get_upload_file_path();
				\Podlove\Geo_Ip::register_updater_cron();
				?>
				<?php if ( file_exists($file) ): ?>
					<p>
						<?php echo __("Geolocation database", 'podlove-podcasting-plugin-for-wordpress'); ?>:
						<code><?php echo $file ?></code>
					</p>
					<p>
						<?php echo __("Last modified", 'podlove-podcasting-plugin-for-wordpress'); ?>: 
						<?php echo date(get_option('date_format') . ' ' . get_option( 'time_format' ), filemtime($file)) ?>
					</p>
					<p>
						<?php echo sprintf(
							__("The database is updated automatically once a month. Next scheduled update: %s", 'podlove-podcasting-plugin-for-wordpress'),
							date(get_option('date_format') . ' ' . get_option( 'time_format' ), wp_next_scheduled('podlove_geoip_db_update'))
						) ?>
					</p>
					<p>
						<button name="update_geo_database" class="button button-primary" value="1"><?php echo __("Update Now", 'podlove-podcasting-plugin-for-wordpress') ?></button>
					</p>
				<?php else: ?>
					<p>
						<?php echo __("You need to download a geolocation-database for lookups to work.", 'podlove-podcasting-plugin-for-wordpress') ?>
					</p>
					<p>
						<button name="update_geo_database" class="button button-primary" value="1"><?php echo __("Download Now", 'podlove-podcasting-plugin-for-wordpress') ?></button>
					</p>
				<?php endif; ?>
				<p>
					<?php echo sprintf(__('Geo-Tracking is <em>%s</em>.', 'podlove-podcasting-plugin-for-wordpress'), Geo_ip::is_enabled() ? __('active', 'podlove-podcasting-plugin-for-wordpress') : __('inactive', 'podlove-podcasting-plugin-for-wordpress')); ?>
				</p>
				<p>
					<!-- This snippet must be included, as stated here: http://dev.maxmind.com/geoip/geoip2/geolite2/ -->
					<em>
						<?php echo sprintf(
							__('This product includes GeoLite2 data created by MaxMind, available from %s.', 'podlove-podcasting-plugin-for-wordpress'),
							'<a href="http://www.maxmind.com">http://www.maxmind.com</a>'
						) ?>
					</em>
				</p>
				<?php
			},
			/* $page     */ Settings::$pagehook,  
			/* $section  */ 'podlove_settings_episode'
		);

		add_settings_field(
			/* $id       */ 'podlove_debug_tracking',
			/* $title    */ sprintf(
				'<label for="mode">%s</label>',
				__( 'Debug Tracking', 'podlove-podcasting-plugin-for-wordpress' )
			),
			/* $callback */ function () {

				if (!\get_option('permalink_structure')) {
					?>
					<div class="error">
						<p>
							<b><?php echo __('Please Change Permalink Structure', 'podlove-podcasting-plugin-for-wordpress') ?></b>
							<?php
							echo sprintf(
								__('You are using the default WordPress permalink structure. 
								This may cause problems with some podcast clients when you activate tracking.
								Go to %s and set it to anything but default (for example "Post name") before activating Tracking.', 'podlove-podcasting-plugin-for-wordpress'),
								'<a href="' . admin_url('options-permalink.php') . '">' . __('Permalink Settings') . '</a>'
							);
							?>
						</p>
					</div>
					<?php
				}

				$media_file = Model\MediaFile::find_example();
				if (!$media_file)
					return;

				$episode = $media_file->episode();
				if (!$episode)
					return;

				$public_url = $media_file->get_public_file_url("debug");
				$actual_url = $media_file->get_file_url(); 

				?>
				<h4><?php __('Example Episode', 'podlove-podcasting-plugin-for-wordpress') ?></h4>
				<p>
					<?php echo $episode->full_title() ?>
				</p>
				<h4><?php __('Media File', 'podlove-podcasting-plugin-for-wordpress') ?></h4>
				<p>
					<h5><?php __('Actual Location', 'podlove-podcasting-plugin-for-wordpress') ?></h5>
					<code><?php echo $actual_url ?></code>
				</p>
				<p>
					<h5><?php __('Public URL', 'podlove-podcasting-plugin-for-wordpress') ?></h5>
					<code><?php echo $public_url ?></code>
				</p>
				<p>
					<h5><?php __('Validations', 'podlove-podcasting-plugin-for-wordpress') ?></h5>
					<ul>
						<li>
							<!-- check rewrite rules -->
							<?php if ( \Podlove\Tracking\Debug::rewrites_exist() ): ?>
								✔ <?php _e('Rewrite Rules Exist', 'podlove-podcasting-plugin-for-wordpress') ?>
							<?php else: ?>
								✘ <strong><?php _e('Rewrite Rules Missing', 'podlove-podcasting-plugin-for-wordpress') ?></strong>
								<!-- todo: repair button -->
							<?php endif; ?>
						</li>
						<li>
							<?php if ( \Podlove\Tracking\Debug::url_resolves_correctly($public_url, $actual_url) ): ?>
								✔ <?php _e('URL resolves correctly', 'podlove-podcasting-plugin-for-wordpress') ?>
							<?php else: ?>
								✘ <strong><?php _e('URL does not resolve correctly', 'podlove-podcasting-plugin-for-wordpress') ?></strong>
							<?php endif; ?>
						</li>
						<li>
							<!-- check http/https consistency -->
							<?php if ( \Podlove\Tracking\Debug::is_consistent_https_chain($public_url, $actual_url) ): ?>
								✔ <?php _e('Consistent protocol chain', 'podlove-podcasting-plugin-for-wordpress') ?>
							<?php else: ?>
								✘ <strong><?php _e('Protocol chain is inconsistent', 'podlove-podcasting-plugin-for-wordpress') ?></strong>: <?php _e('Your site uses SSL but the files are not served with SSL. Many clients will not allow to download episodes. To fix this, serve files via SSL or deactivate tracking.', 'podlove-podcasting-plugin-for-wordpress') ?>
							<?php endif; ?>
						</li>
						<li>
							<?php if (Geo_Ip::is_db_valid()): ?>
								✔ <?php _e('Geolocation database valid', 'podlove-podcasting-plugin-for-wordpress') ?>
								<?php Geo_Ip::enable_tracking(); ?>
							<?php else: ?>
								<?php Geo_Ip::disable_tracking(); ?>
								✘ <strong><?php _e('Geolocation database invalid or outdated', 'podlove-podcasting-plugin-for-wordpress') ?></strong>:
								<?php echo sprintf(
									__('Try updating it using the button above. If that doesn\'t work, delete it manually: %s, then redownload it in the section above. If that fails, you can download it with your web browser, unzip it, and upload it to WordPress using sFTP: %s', 'podlove-podcasting-plugin-for-wordpress'),
									'<code>' . esc_html(Geo_Ip::get_upload_file_path()) . '</code>',
									'<a href="' . esc_url(Geo_Ip::SOURCE_URL) . '" download>' . esc_html(Geo_Ip::SOURCE_URL) . '</a>'
								) ?>
							<?php endif ?>
						</li>
					</ul>
				</p>
				<?php
			},
			/* $page     */ Settings::$pagehook,  
			/* $section  */ 'podlove_settings_episode'
		);

		register_setting(
			Settings::$pagehook,
			'podlove_tracking',
			function($args)
			{
				if (isset($_REQUEST['update_geo_database']))
					\Podlove\Geo_Ip::update_database();

				\Podlove\Cache\TemplateCache::get_instance()->setup_purge();

				return $args;
			}
		);
	}

}
