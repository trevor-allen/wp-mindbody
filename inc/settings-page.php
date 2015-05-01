	<?php if(!mz_soap_check() || !mz_pear_check()): ?>
		<div class="error">
			<?php
			global $mz_error;
			echo $mz_error . 'MZ Mindbody API requires SOAP and PEAR. Please contact your hosting provider or enable via your CPANEL of php.ini file.'; ?>
		</div>

	<?php endif; ?>

	<?php if(mz_soap_check() && mz_pear_check()): ?>

		<div style="width:99%; padding: 5px;">
			<?php esc_attr_e('Congratulations. Your server appears to be configured to integrate with mindbodyonline.'); ?>
		</div>

	<?php endif; ?>


	<div class="wrap">
		<div id="icon-options-general" class="icon32"></div>
		<h2><?php esc_attr_e( 'MBO Settings' ); ?></h2>
		<div id="poststuff">
			<div id="post-body" class="metabox-holder columns-2">
				<!-- main content -->
				<div id="post-body-content">
					<div class="meta-box-sortables ui-sortable">
						<div class="postbox">
							<h3><span><?php esc_attr_e( 'Account and Event options', 'wp_admin_style' ); ?></span></h3>
							<div class="inside">

								<?php screen_icon(); ?>

								<p><?php esc_attr_e('Enter your mindbody credentials below.'); ?></p>
								<p><?php esc_attr_e('If you do not have them yet, visit the'); ?> <a href="https://api.mindbodyonline.com/Home/LogIn"><?php esc_attr_e('MindBodyOnline developers website'); ?></a>
									<?php esc_attr_e('and register for developer credentials.'); ?></p>

								<form name="mz_mindbody_form" action="" method="post">

									<input type="hidden" name="mz_mindbody_form_submitted" value="Y">

									<p><strong><label for='mz_source_name'><?php esc_attr_e('Sourcename: '); ?></label></strong><br>
										<input id='mz_source_name' name='mz_source_name' class="regular-text" type='text' value='<?php
										if(isset($mz_source_name) && $mz_source_name != '') echo $mz_source_name;  ?>' /></p>

										<p><strong><label for='mz_mindbody_password'><?php esc_attr_e('Key: '); ?></label></strong><br>
											<input id='mz_mindbody_password' name='mz_mindbody_password' class="regular-text" type='text' value='<?php if(isset($mz_mindbody_password) && $mz_mindbody_password != '') echo $mz_mindbody_password; ?>' /></p>

											<p><strong><label for='mz_mindbody_siteID'><?php esc_attr_e('Site ID: '); ?></label></strong><br>
											<input id='mz_mindbody_siteID' name='mz_mindbody_siteID' class="regular-text" type='text' value='<?php if(isset($mz_mindbody_siteID) && $mz_mindbody_siteID != '') echo $mz_mindbody_siteID; ?>' /></p>

											<p><strong><label for='mz_mindbody_eventID'><?php esc_attr_e('Event IDs: '); ?></label></strong><br>
											<input id='mz_mindbody_eventID' name='mz_mindbody_eventID' class="regular-text" type='text' value='<?php if(isset($mz_mindbody_eventID) && $mz_mindbody_eventID != '') echo $mz_mindbody_eventID; ?>' /><span class="description"><?php esc_attr_e(' eg: 25, 17'); ?></span></p>

											<input class="button-primary" type="submit" name="submit" value="<?php esc_attr_e('Save Changes'); ?>" />
										</form>

									</div>
									<!-- .inside -->
								</div>
								<!-- .postbox -->
							</div>
							<!-- .meta-box-sortables .ui-sortable -->
						</div>
						<!-- post-body-content -->
						<!-- sidebar -->
						<div id="postbox-container-1" class="postbox-container">
							<div class="meta-box-sortables">
								<div class="postbox">
									<h3><span><?php esc_attr_e(
									'Shortcodes', 'wp_admin_style'
								); ?></span></h3>
								<div class="inside">
									<p><?php esc_attr_e('Add to a page or post with one of the shortcodes:'); ?>
										<ul>
											<li><?php esc_attr_e('[mz-mindbody-show-schedule]'); ?></li>
											<li><?php esc_attr_e('[mz-mindbody-show-events]'); ?></li>
											<li><?php esc_attr_e('[mz-mindbody-staff-list]'); ?></li>
											<li><?php esc_attr_e('[mz-mindbody-show-schedule type=day location=1]'); ?></li>
										</ul>
									</div>
									<!-- .inside -->
								</div>
								<!-- .postbox -->
							</div>
							<!-- .meta-box-sortables -->
						</div>
						<!-- #postbox-container-1 .postbox-container -->
					</div>
					<!-- #post-body .metabox-holder .columns-2 -->
					<br class="clear">
				</div>
				<!-- #poststuff -->
			</div> <!-- .wrap -->
