<?php
if (! defined('ABSPATH')) {
    exit;
} // Exit if accessed directly
if (! empty($mz_schedule_data['GetClassesResult']['Classes']['Class'])) {

   // $mb->debug();

    $mz_days = $mb->makeNumericArray($mz_schedule_data['GetClassesResult']['Classes']['Class']);

    $mz_days = sortClassesByDate($mz_days);

    $output = '';

    if ($type == 'week') {
        $output .= mz_mbo_schedule_nav($mz_date);
    }

    $output .= "<div id='mbo-schedule' class='mbo-schedule'>";
    $output .= '<dl>';

    foreach ($mz_days as $classDate => $mz_classes) {
        //$output .= "<tr class='mbo-table-head clearfix'>";
            $output .= '<dt><h5>'.esc_html(date_i18n($mz_date_display, strtotime($classDate))).'</h5></dt>';
            //$output .= "<th>" . __('Name') . "</th>";
            //$output .= "<th>" . __('Instructor') . "</th>";
            //$output .= "<th>" . __('Location') . "</th>";
        //$output .= "</tr>";

        foreach ($mz_classes as $class) {
            if (! $class['IsCanceled']) {
                $sDate = date_i18n('m/d/Y', strtotime($class['StartDateTime']));
                $sLoc = $class['Location']['ID'];
                $sTG = $class['ClassDescription']['Program']['ID'];

                $studioid = $class['Location']['SiteID'];
                $sclassid = $class['ClassScheduleID'];
                $classDescription = $class['ClassDescription']['Description'];

                $sType = -7;
                $linkURL = "https://clients.mindbodyonline.com/ws.asp?sDate={$sDate}&amp;sLoc={$sLoc}&amp;sTG={$sTG}&amp;sType={$sType}&amp;sclassid={$sclassid}&amp;studioid={$studioid}";
                $className = $class['ClassDescription']['Name'];
                $startDateTime = date_i18n('Y-m-d H:i:s', strtotime($class['StartDateTime']));
                $endDateTime = date_i18n('Y-m-d H:i:s', strtotime($class['EndDateTime']));
                $staffName = $class['Staff']['Name'];
                $sessionType = $class['ClassDescription']['SessionType']['Name'];
                $sessionID = $class['ClassDescription']['SessionType']['ID'];

                $sessionLoc = $class['Location']['Name'];

                if ($sessionLoc === 'Asheville Yoga Donation Studio') {
                    $sessionLoc = "<a href='https://www.google.com/maps/place/239+S+Liberty+St,+Asheville,+NC+28801/@35.6031022,-82.5515051,17z/data=!3m1!4b1!4m2!3m1!1s0x8859f4a8ac5ceea7:0xaf3b5ec34c7213a' target='_blank' >Donation Studio</a>";
                } else {
                    $sessionLoc = "<a href='https://www.google.com/maps/place/211+S+Liberty+St,+Asheville,+NC+28801/@35.602176,-82.551578,17z/data=!4m2!3m1!1s0x8859f4a8a3c682f1:0x6ea1f85bd88c4355' target='_blank' >AYC</a>";
                }



                $isAvailable = $class['IsAvailable'];
                $classStart = date_i18n('g:i a', strtotime($startDateTime));
                $classEnd = date_i18n('g:i a', strtotime($endDateTime));
                $trimmed = trim($classStart.' – '.$classEnd, '');

                $totalBooked = $class['TotalBooked'];
                $maxBooked = $class['MaxCapacity'];
                $spotsRemaining = $maxBooked - $totalBooked;

                $loadFilter = [
                    '*Gentle Flow',
		    '*Gentle Flow $8',
                    '*Gentle Kundalini',
                    '*Gentle Restorative',
                    '*Gentle Slow Flow Restorative',
                    '*Gentle Restorative & Yin $8',
                    '*Gentle Align & Flow',
                    '*Gentle Flow & Restorative',
                    '*Gentle Restorative & Yin',
		    '*Gentle Restorative & Yin $8',
                    '*Slow Flow',
                    '*Slow Flow $8',
                    'Slow Flow',
		    '*Slow Flow & Restorative $8',
		    '*Flow & Yin',
		    'WARM Yin',
		    '*WARM Yin $8',
		    '*Gentle Flow & Restorative $8',
                    'Back to Basics 6 Week Series (NO DROP INS)',
                    '*Yin',
                    '*Align & Flow $8',
                ];

                $cName = trim($className);

                if (in_array($cName, $loadFilter)) {
                    $output .= "<dd class='clearfix'> ".esc_html($trimmed);
                    $output .= " <a href='".esc_url($linkURL)."'>".esc_html($cName).'</a>';
                    $output .= ' with '.esc_html($staffName);
                    $output .= " @ {$sessionLoc}<br/>";
                    $output .= '</dd>';
                }
            }
        }
    }

    $output .= '</dl>';
    $output .= '</div>';
} elseif (! empty($mz_schedule_data['GetClassesResult']['Message'])) {
    $output .= '<div>';
    $output .= $mz_schedule_data['GetClassesResult']['Message'];
    $output .= '</div>';
} else {
    $output .= __('Error getting classes. Try re-loading the page.');
    $output .= '<br/>';
    $output .= '<pre>';
    $output .= print_r($mz_schedule_data, 1);
    $output .= '</pre>';
}
