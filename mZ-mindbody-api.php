<?php
/*
*	Plugin Name: MBO Interface - Schedule, Events, Staff	Display
*	Description: Wordpress shortcodes to use MindbodyOnline data
*	Version: 1.0
*	Author: Trevor Allen
*	Author URI: http://www.Quillflow.com/
*	Plugin URI: http://www.Quillflow.com/
*	Based on API written by Devin Crossman.
*/

/*
*	Assign global variables
*
*/

$plugin_url = WP_PLUGIN_URL . '/mZ-mindbody-api';
$options = array();
$mz_error = '';


function mz_soap_check() {

	require_once('System.php');
	global $mz_error;

	if (!extension_loaded('soap')) {
		$mz_error .= 'SOAP not installed! ';
		return false;
	}

	return true;

}

function mz_pear_check() {

	global $mz_error;

	if (!class_exists('System')) {
		$mz_error .= 'PEAR not installed! ';
		return false;
	}

	return true;

}

//define plugin path and directory
define( 'MZ_MINDBODY_SCHEDULE_DIR', plugin_dir_path( __FILE__ ) );
define( 'MZ_MINDBODY_SCHEDULE_URL', plugin_dir_url( __FILE__ ) );

//register uninstall, activation and deactivation hooks
register_activation_hook(__FILE__, 'mZ_mindbody_schedule_activation');
register_deactivation_hook(__FILE__, 'mZ_mindbody_schedule_deactivation');
register_uninstall_hook(__FILE__, 'mZ_mindbody_schedule_uninstall');

load_plugin_textdomain('mz-mindbody-api',false,'mz-mindbody-schedule/languages');

function mZ_mindbody_schedule_activation() {
	//Don't know if there's anything we need to do here.
}

function mZ_mindbody_schedule_deactivation() {
	// actions to perform once on plugin deactivation go here
}

function mZ_mindbody_schedule_uninstall(){
	//actions to perform once on plugin uninstall go here
	delete_option('mz_mindbody_options');
}

function mZ_mindbody_schedule_register_widget() {
	register_widget( 'mZ_Mindbody_day_schedule');
}//TODO Deal with conflict when $mb class get's called twice
add_action('widgets_init', 'mZ_mindbody_schedule_register_widget');


class mZ_Mindbody_day_schedule extends WP_Widget {

	function mZ_Mindbody_day_schedule() {
		$widget_ops = array(
			'classname' => 'mZ_Mindbody_day_schedule_class',
			'description' => 'Display class schedule for current day.'
		);
		$this->WP_Widget(
			'mZ_Mindbody_day_schedule',
			'Today\'s MindBody Schedule',
			$widget_ops
		);
	}

	function form($instance){
		$defaults = array('title' => 'Today\'s Classes');
		$instance = wp_parse_args((array) $instance, $defaults);
		$title = $instance['title'];
		?>
		<p>Title: <input class="widefat" name="<?php echo $this->get_field_name( 'title' ); ?>"
			type="text" value="<?php echo esc_attr( $title ); ?>" /></p>
			<?php
	}

	//save the widget settings
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		return $instance;
	}

	function widget($args, $instance) {
		extract($args);
		echo $before_widget;
		$title = apply_filters( 'widget_title', $instance['title'] );
		$arguments['type'] = 'day';
		if (!empty($title) ) {
			echo $before_title . $title . $after_title;
		};
		echo(mZ_mindbody_show_schedule($arguments));
		echo $after_widget;
	}

}

function mz_mindbody_settings_menu() {
	//create submenu under Settings
	add_options_page(
		'MZ Mindbody Settings',
		'MZ Mindbody',
		'manage_options',
		__FILE__,
		'mz_mindbody_settings_page'
	);
}
add_action ('admin_menu', 'mz_mindbody_settings_menu');



function mz_mindbody_settings_page() {

	if(!current_user_can('manage_options')) {
		wp_die( 'You do not have sufficient permissions to access this page.' );
	}

	global $plugin_url;
	global $options;

	if( isset( $_POST['mz_mindbody_form_submitted'] ) ) {

		$hidden_field = esc_html( $_POST['mz_mindbody_form_submitted']);

		if( $hidden_field == 'Y' ) {

			$mz_source_name = esc_html( $_POST['mz_source_name'] );

			$mz_mindbody_password = esc_html( $_POST['mz_mindbody_password'] );

			$mz_mindbody_siteID = esc_html( $_POST['mz_mindbody_siteID']);

			$mz_mindbody_eventID = esc_html( $_POST['mz_mindbody_eventID']);


			$options['mz_source_name'] 				= $mz_source_name;
			$options['mz_mindbody_password']  = $mz_mindbody_password;
			$options['mz_mindbody_siteID']		= $mz_mindbody_siteID;
			$options['mz_mindbody_eventID']		= $mz_mindbody_eventID;
			$options['last_updated']					= time();

			update_option( 'mz_mindbody_options', $options );

		}

	}

	$options = get_option( 'mz_mindbody_options' );

	if( $options != '' ) {

		$mz_source_name 			= $options['mz_source_name'];
		$mz_mindbody_password = $options['mz_mindbody_password'];
		$mz_mindbody_siteID 	= $options['mz_mindbody_siteID'];
		$mz_mindbody_eventID 	= $options['mz_mindbody_eventID'];

	}
		// $options = get_option('mz_mindbody_options',__('Option Not Set'));
		//
		// $mz_source_name = (isset($options['mz_source_name'])) ? $options['mz_source_name'] : _e('YOUR SOURCE NAME');
		//
		// $mz_mindbody_password = (isset($options['mz_mindbody_password'])) ? $options['mz_mindbody_password'] : _e('YOUR MINDBODY PASSWORD');
		//
		// $mz_mindbody_siteID = (isset($options['mz_mindbody_siteID'])) ? $options['mz_mindbody_siteID'] : _e('YOUR SITE ID');
		//
		// $mz_mindbody_eventID = (isset($options['mz_mindbody_eventID'])) ? $options['mz_mindbody_eventID'] : _e('Event Category IDs');

	require( 'inc/settings-page.php' );

}


function mz_mindbody_styles() {

	//wp_enqueue_style( 'mz_mindbody_styles', plugins_url( 'file.css' ) );

}
add_action( 'admin_head', 'mz_mindbody_styles' );

function mz_mindbody_debug_text() {
	//$mb->debug();
}
add_action( 'admin_init', 'mz_mindbody_debug_text' );



// Display and fill the form field
// function mz_mindbody_source_name() {
// 	// get option 'mz_source_name' value from the database
// 	$options = get_option( 'mz_mindbody_options',__('Option Not Set') );
// 	$mz_source_name = (isset($options['mz_source_name'])) ? $options['mz_source_name'] : _e('YOUR SOURCE NAME');
//
// }
//
// // Display and fill the form field
// function mz_mindbody_password() {
// 	$options = get_option( 'mz_mindbody_options',__('Option Not Set') );
// 	$mz_mindbody_password = (isset($options['mz_mindbody_password'])) ? $options['mz_mindbody_password'] : _e('YOUR MINDBODY PASSWORD');
//
// }
//
// // Display and fill the form field
// function mz_mindbody_siteID() {
// 	// get option 'text_string' value from the database
// 	$options = get_option( 'mz_mindbody_options',__('Option Not Set') );
// 	$mz_mindbody_siteID = (isset($options['mz_mindbody_siteID'])) ? $options['mz_mindbody_siteID'] : _e('YOUR SITE ID');
//
// }
//
// // Display and fill the form field
// function mz_mindbody_eventID() {
// 	// get option 'text_string' value from the database
// 	$options = get_option( 'mz_mindbody_options',__('Option Not Set') );
// 	$mz_mindbody_eventID = (isset($options['mz_mindbody_eventID'])) ? $options['mz_mindbody_eventID'] : _e('Event Category IDs');
//
// }

// Display and fill the form field
// function mz_mindbody_clear_cache() {
// 	$options = get_option( 'mz_mindbody_options','Option Not Set' );
// 	printf(
// 	'<input id="%1$s" name="mz_mindbody_options[%1$s]" type="checkbox" %2$s />',
// 	'mz_mindbody_clear_cache',
// 	checked( isset($options['mz_mindbody_clear_cache']) , true, false )
// );
// }

// Validate user input (we want text only)
// function mz_mindbody_validate_options( $input ) {
// 	foreach ($input as $key => $value) {
// 		$valid[$key] = wp_strip_all_tags(preg_replace( '/\s/', '', $input[$key] ));
// 		if($valid[$key] != $input[$key]) {
// 			add_settings_error(
// 				'mz_mindbody_text_string',
// 				'mz_mindbody_texterror',
// 				'Does not appear to be valid ',
// 				'error'
// 			);
// 		}
// 	}
// 	return $valid;
// }
//
// }
// else
// {// non-admin enqueues, actions, and filters
//
	add_action('init', 'myStartSession', 1);
	add_action('wp_logout', 'myEndSession');
	add_action('wp_login', 'myEndSession');

	function myStartSession() {
		if (phpversion() >= 5.4) {
			if (session_status() == PHP_SESSION_NONE) {
				session_start();
			}
		}else{
			if(!session_id()) {
				session_start();
			}
		}
	}
//
// 	function myEndSession() {
// 		session_destroy ();
// 	}
//
// 	add_action( 'wp_enqueue_script', 'load_jquery' );
// 	function load_jquery() {
// 		wp_enqueue_script( 'jquery' );
// 	}
//
// 	function mZ_mindbody_schedule_init() {
// 		wp_register_style('mZ_mindbody_schedule_bs', plugins_url('/bootstrap/css/bootstrap.min.css',__FILE__ ));
// 		wp_enqueue_style('mZ_mindbody_schedule_bs');
// 	}
// 	add_action( 'init','mZ_mindbody_schedule_init');
//
// 	add_action('init', 'enqueue_mz_mbo_scripts');
// 	function enqueue_mz_mbo_scripts() {
// 		wp_register_script( 'mz_mbo_bootstrap_script', plugins_url('/bootstrap/js/bootstrap.min.js', __FILE__), array( 'jquery' ),'3.1.1', true );
// 		wp_enqueue_script( 'mz_mbo_bootstrap_script' );
// 		wp_register_script( 'mz_mbo_modal_script', plugins_url('/js/mz_mbo_modal.js', __FILE__), array( 'jquery' ),'1', true );
// 		wp_enqueue_script( 'mz_mbo_modal_script' );
// 	}
//
// 	include_once(dirname( __FILE__ ) . '/mindbody-php-api/MB_API.php');
//
// 	foreach ( glob( plugin_dir_path( __FILE__ )."inc/*.php" ) as $file )
// 	include_once $file;
//
// 	add_shortcode('mz-mindbody-show-schedule', 'mZ_mindbody_show_schedule' );
// 	add_shortcode('mz-mindbody-show-events', 'mZ_mindbody_show_events' );
// 	add_shortcode('mz-mindbody-staff-list', 'mZ_mindbody_staff_listing' );
// 	add_shortcode('mz-mindbody-login', 'mZ_mindbody_login' );
// 	add_shortcode('mz-mindbody-logout', 'mZ_mindbody_logout' );
// 	add_shortcode('mz-mindbody-signup', 'mZ_mindbody_signup' );
// 	add_shortcode('mz-mindbody-activation', 'mZ_mindbody_activation' );
// }//EOF Not Admin

if (phpversion() >= 5.3) {
	include_once('php_variants/sort_newer.php');
}else{
	include_once('php_variants/sort_older.php');
}

function mz_getDateRange($date, $duration=7) {
	/*Gets a YYYY-mm-dd date and returns an array of four dates:
	start of requested week
	end of requested week
	following week start date
	previous week start date
	adapted from http://stackoverflow.com/questions/186431/calculating-days-of-week-given-a-week-number
	*/

	list($year, $month, $day) = explode("-", $date);

	// Get the weekday of the given date
	$wkday = date('l',mktime('0','0','0', $month, $day, $year));

	switch($wkday) {
		case 'Monday': $numDaysFromMon = 0; break;
		case 'Tuesday': $numDaysFromMon = 1; break;
		case 'Wednesday': $numDaysFromMon = 2; break;
		case 'Thursday': $numDaysFromMon = 3; break;
		case 'Friday': $numDaysFromMon = 4; break;
		case 'Saturday': $numDaysFromMon = 5; break;
		case 'Sunday': $numDaysFromMon = 6; break;
	}

	// Timestamp of the monday for that week
	$seconds_in_a_day = 86400;

	$monday = mktime('0','0','0', $month, $day-$numDaysFromMon, $year);
	$today = mktime('0','0','0', $month, $day, $year);
	if ($duration == 1){
		$rangeEnd = $today+($seconds_in_a_day*$duration);
	}else{
		$rangeEnd = $today+($seconds_in_a_day*($duration - $numDaysFromMon));
	}
	$previousRangeStart = $monday+($seconds_in_a_day*($numDaysFromMon - ($numDaysFromMon+$duration)));

	$return[0] = array('StartDateTime'=>date('Y-m-d',$today), 'EndDateTime'=>date('Y-m-d',$rangeEnd-1));
	//$return[1] = date('Y-m-d',$rangeEnd-1);
	$return[1] = date('Y-m-d',$rangeEnd+1);
	$return[2] = date('Y-m-d',$previousRangeStart);
	return $return;
}

function mz_mbo_schedule_nav($date, $period="Week", $duration=7)
{
	$sched_nav = '';
	$mz_schedule_page = get_permalink();
	//Navigate through the weeks
	$mz_start_end_date = mz_getDateRange($date, $duration);
	$mz_nav_weeks_text_prev = __('Previous')." ".$period;
	$mz_nav_weeks_text_current = __('Current')." ".$period;
	$mz_nav_weeks_text_following = __('Following')." ".$period;
	$sched_nav .= ' <a href='.add_query_arg(array('mz_date' => ($mz_start_end_date[2]))).'>'.$mz_nav_weeks_text_prev.'</a> - ';
	if (isset($_GET['mz_date']))
	$sched_nav .= ' <a href='.$mz_schedule_page.'>'.$mz_nav_weeks_text_current.'</a>  - ';
	$sched_nav .= '<a href='.add_query_arg(array('mz_date' => ($mz_start_end_date[1]))).'>'.$mz_nav_weeks_text_following.'</a>';

	return $sched_nav;
}


function mz_validate_date( $string ) {
	if (preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/',$string))
	{
		return $string;
	}
	else
	{
		return "mz_validate_weeknum error";
	}
}


//Format arrays for display in development
function mz_pr($data)
{
	echo "<pre>";
	print_r($data);
	echo "</pre>";
}
