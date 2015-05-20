<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function mZ_mindbody_show_schedule( $atts ) {
	require_once MINDBODY_SCHEDULE_DIR .'inc/mz_mbo_init.inc';

  $mz_date = empty($_GET['mz_date']) ? date_i18n('Y-m-d') : mz_validate_date($_GET['mz_date']);

  $mz_timeframe = array_slice(mz_getDateRange($mz_date, 7), 0, 1);
	$mz_schedule_cache = "mz_schedule_week_cache";

	//While we still eed to support php 5.2 and can't use [0] on above
	$mz_timeframe = array_pop($mz_timeframe);

  // START caching
	$mz_cache_reset = isset($options['mz_mindbody_clear_cache']) ? "on" : "off";

	if ($mz_cache_reset == "on") {
		delete_transient( $mz_schedule_cache );
	}

	if (isset($_GET) || ( false === ($mz_schedule_data = get_transient($mz_schedule_cache)) ) ) {
	//Send the timeframe to the GetClasses class, unless already cached
	$mz_schedule_data = $mb->GetClasses($mz_timeframe);
	}
    //mz_pr($mz_schedule_data);
	//Cache the mindbody call for 1 hour
	// TODO make cache timeout configurable.
	set_transient($mz_schedule_cache, $mz_schedule_data, 60 * 60);
	// END caching

	global $mbo_dir;
	include($mbo_dir . 'template/classlist.php');
}
?>
