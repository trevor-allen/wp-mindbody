<?php

if (! defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

function mZ_mindbody_show_schedule($atts)
{
    require_once MINDBODY_SCHEDULE_DIR.'inc/mz_mbo_init.inc';
    global $mbo_dir;

    extract(
	shortcode_atts(
	[
		'type' => 'week',
	], $atts));

    $mz_date = empty($_GET['mz_date']) ? date_i18n('Y-m-d') : mz_validate_date($_GET['mz_date']);

    if ($type === 'day') {
        $mz_timeframe = array_slice(mz_getDateRange($mz_date, 1), 0, 1);
        $mz_schedule_data = $mb->GetClasses(array_shift($mz_timeframe));
        include $mbo_dir.'template/day_classlist.php';
    } elseif ($type === 'newbie') {
        $mz_timeframe = array_slice(mz_getDateRange($mz_date, 7), 0, 1);
        $mz_schedule_data = $mb->GetClasses($mz_timeframe[0]);
        include $mbo_dir.'template/newbie_classlist.php';
    } else {
        $mz_timeframe = array_slice(mz_getDateRange($mz_date, 7), 0, 1);
        $mz_schedule_data = $mb->GetClasses($mz_timeframe[0]);
        include $mbo_dir.'template/classlist.php';
    }

    return $output;
}
