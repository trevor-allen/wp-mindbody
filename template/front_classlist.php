<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php if(!empty($mz_schedule_data['GetClassesResult']['Classes']['Class'])) : ?>

    <!-- todo -->
    <?php //$mb->debug(); ?>

    <?php
    $mz_days = $mb->makeNumericArray($mz_schedule_data['GetClassesResult']['Classes']['Class']);
    $mz_days = sortClassesByDate($mz_days);
    if ($type=='week') {
        $return .= mz_mbo_schedule_nav($mz_date);
    }

    $count = 0;

    ?>

    <div id="mbo-schedule" class="mbo-schedule">
        <ul id="mbo-list" class="mbo-list">

            <?php foreach($mz_days as $classDate => $mz_classes): ?>
                <?php foreach($mz_classes as $class): ?>

                    <?php if (!$class['IsCanceled']): ?>

                    <?php
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
                            556, 557, 31, 22, 32, 34, 367, 366
                        );
                        ?>

                        <?php if(!in_array($sessionID, $doNotLoad)): ?>
                            <?php if($count < 5): ?>

                                <li class="clearfix" style="border-bottom: 1px solid gray;">
                                    <div style="width: 70%; float: left;">
                                        <h4><a href="<?php echo esc_url($linkURL); ?>" target="_blank"><?php echo esc_html($className); ?></a></h4>
                                        <h6><?php echo esc_html($staffName); ?></h6>
                                    </div>
                                    <p style="float: right"><?php echo esc_html($trimmed);?></p>
                                </li>

                                    <?php $count ++; ?>

                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endforeach; ?>

        </ul>
    </div>

    <?php elseif (!empty($mz_schedule_data['GetClassesResult']['Message'])) : ?>

        <div><?php echo $mz_schedule_data['GetClassesResult']['Message']; ?></div>

    <?php else: ?>

        <?php __('Error getting classes. Try re-loading the page.'); ?><br>
        <pre><?php print_r($mz_schedule_data,1); ?></pre>

    <?php endif?>
    <?php print_r($enrollmentData); ?>
