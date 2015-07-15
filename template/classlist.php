<?php
if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if(!empty($mz_schedule_data['GetClassesResult']['Classes']['Class'])):

    $mz_days = $mb->makeNumericArray($mz_schedule_data['GetClassesResult']['Classes']['Class']);
    $mz_days = sortClassesByDate($mz_days);

    $output .= "<div id='mbo-schedule' class='mbo-schedule'>";
    $output .= "<table class='mbo-table table-striped'>";

    foreach($mz_days as $classDate => $mz_classes):
        $output .= "<tr class='mbo-table-head'>";
            $output .= "<th>" . esc_html(date_i18n($mz_date_display, strtotime($classDate))) . "</th>";
            $output .= "<th>" . __('Name') . "</th>";
            $output .= "<th>" . __('Instructor') . "</th>";
            $output .= "<th>" . __('Location') . "</th>";
        $output .= "</tr>";

        foreach($mz_classes as $class):
            if(!$class['IsCanceled']):

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
                $isAvailable = $class['IsAvailable'];
                $classStart = date_i18n('g:i a', strtotime($startDateTime));
                $classEnd =  date_i18n('g:i a', strtotime($endDateTime));
                $trimmed = trim($classStart . ' – ' . $classEnd,"");

                $totalBooked = $class['TotalBooked'];
                $maxBooked = $class['MaxCapacity'];
                $spotsRemaining = $maxBooked - $totalBooked;

                $doNotLoad = array(
                    556, 557, 31,
                    22, 32, 34,
                    367, 366
                );

                    if(!in_array($sessionID, $doNotLoad)):

                        $output .= "<tr>";
                            $output .= "<td>" . esc_html($trimmed) . "</td>";
                                $output .= "<td><a href='" . esc_url($linkURL) . "'>" . esc_html($className) . "</a></td>";
                                $output .= "<td>" . esc_html($staffName) . "</td>";
                                $output .= "<td>" . esc_html($sessionLoc) . "</td>";
                            $output .= "</tr>";
                        endif;

                    endif;
                endforeach;
            endforeach;

        $output .= "</table>";
    $output .= "</div>";

elseif (!empty($mz_schedule_data['GetClassesResult']['Message'])):

    $output .= "<div>";
    $output .= $mz_schedule_data['GetClassesResult']['Message'];
    $output .= "</div>";

else:

    $output .= __('Error getting classes. Try re-loading the page.');
    $output .= "<br/>";
    $output .= "<pre>";
    $output .= print_r($mz_schedule_data,1);
    $output .= "</pre>";

endif;
