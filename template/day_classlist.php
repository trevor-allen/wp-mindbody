<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if(!empty($mz_schedule_data['GetClassesResult']['Classes']['Class'])):

    $mz_days = $mb->makeNumericArray($mz_schedule_data['GetClassesResult']['Classes']['Class']);
    $mz_days = sortClassesByDate($mz_days);
    $count = 0;

    $output .= "<div id='mbo-schedule' class='mbo-schedule'>";
        $output .= "<ul id='mbo-list' class='mbo-list'>";

            foreach($mz_days as $classDate => $mz_classes):
                foreach($mz_classes as $class):
                    if (!$class['IsCanceled']):

                        $sDate = date_i18n('m/d/Y', strtotime($class['StartDateTime']));
                        $sLoc = $class['Location']['ID'];
                        $sTG = $class['ClassDescription']['Program']['ID'];
                        $studioid = $class['Location']['SiteID'];
                        $sclassid = $class['ClassScheduleID'];
                        $classDescription = $class['ClassDescription']['Description'];
                        $sType = -7;

                        $linkURL = "https://clients.mindbodyonline.com/ws.asp?sDate={$sDate}&amp;sLoc={$sLoc}&amp;sTG={$sTG}&amp;sType={$sType}&amp;sclassid={$sclassid}&amp;studioid={$studioid}";
                        $escURL = esc_url($linkURL);

                        $className = $class['ClassDescription']['Name'];
                        $escClassName = esc_html($className);
                        $staffName = $class['Staff']['Name'];
                        $escStaffName = esc_html($staffName);

                        $startDateTime = date_i18n('Y-m-d H:i:s', strtotime($class['StartDateTime']));
                        $endDateTime = date_i18n('Y-m-d H:i:s', strtotime($class['EndDateTime']));
                        $classStart = date_i18n('g:i a', strtotime($startDateTime));
                        $classEnd =  date_i18n('g:i a', strtotime($endDateTime));
                        $trimmed = esc_html(trim($classStart . ' – ' . $classEnd,""));

                        $sessionType = $class['ClassDescription']['SessionType']['Name'];
                        $sessionID = $class['ClassDescription']['SessionType']['ID'];
                        $sessionLoc = $class['Location']['Name'];
                        $isAvailable = $class['IsAvailable'];

                        $totalBooked = $class['TotalBooked'];
                        $maxBooked = $class['MaxCapacity'];
                        $spotsRemaining = $maxBooked - $totalBooked;

                        $doNotLoad = array(
                            556,
                            557,
                            31,
                            22,
                            32,
                            34,
                            367,
                            366
                        );

                        if(!in_array($sessionID, $doNotLoad)):
                            if($count < 5):

                                $output .= "<li class='clearfix mbo-list-item'>";
                                    $output .= "<div>";
                                        $output .= "<h3 class='classtitle'><a href='{$escURL}' target='_blank'>{$escClassName}</a></h3>";
                                        $output .= "<h5 class='staffmember'>{$escStaffName}</h5>";
                                    $output.= "</div>";
                                    $output .= "<p>{$trimmed}</p>";
                                $output .= "</li>";

                                $count ++;

                            endif;
                        endif;
                    endif;
                endforeach;
            endforeach;

        $output .= "</ul>";
    $output .= "</div>";

    elseif (!empty($mz_schedule_data['GetClassesResult']['Message'])):

        $output = "<div>";
        $output .= $mz_schedule_data['GetClassesResult']['Message'];
        $output .= "</div>";

    else:

        $output = __('Error getting classes. Try re-loading the page.');
        $output .= "<br/>";
        $output .= "<pre>";
        //$output .= print_r($mz_schedule_data,1);
        $output .= "</pre>";

endif;
