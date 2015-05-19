<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php if(!empty($enrollmentInfo['GetClassesResult']['Classes']['Class'])) : ?>

<!-- todo -->
<?php $mb->debug(); ?>

<?php
  $mz_days = $mb->makeNumericArray($enrollmentInfo['GetClassesResult']['Classes']['Class']);
  $mz_days = sortClassesByDate($mz_days);
  if ($type=='week') {
    $return .= mz_mbo_schedule_nav($mz_date);
  }
?>

<div id="mbo-schedule" class="mbo-schedule">
  <table class="mbo-table table-striped">

    <?php foreach($mz_days as $classDate => $mz_classes) : ?>
      <tr class="mbo-table-head">
        <th><?php echo esc_html(date_i18n($mz_date_display, strtotime($classDate))) ?></th>
        <th><?php echo  __('Name'); ?></th>
        <th><?php echo  __('Instructor'); ?></th>
        <th><?php echo  __('Location'); ?></th>
      </tr>

      <?php foreach($mz_classes as $class): ?>
        <?php //if (!(($class['IsCanceled'] == 'TRUE') && ($class['HideCancel'] == 'TRUE')) && ($class['Location']['ID'] == $location)) : ?>
        <?php if (!$class['IsCanceled']) : ?>

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
            532, 534, 535,
            536, 537, 533,
            534, 14, 17,
            20, 24, 538,
            27, 16, 21,
            22, 18, 26,
            412, 15, 25,
            495, 440
          );

           ?>
           <?php if(!in_array($sessionID, $doNotLoad)) : ?>

          <tr>
            <td><?php echo esc_html($trimmed); if ($isAvailable) : ?><br><a class="btn" href="<?php echo esc_url($linkURL); ?>" target="_blank"><?php __('Sign-Up'); ?></a><?php endif ?></td>
            <td><a data-toggle="modal" data-target="#mzModal" href="#"><?php echo esc_html($className); ?></a></td>
            <td><?php echo esc_html($staffName); ?></td>
            <td><?php echo esc_html($sessionLoc); ?><?php echo esc_html($spotsRemaining); ?></td>
          </tr>
        <?php endif; ?>

        <?php endif; ?>
      <?php endforeach; ?>
    <?php endforeach; ?>

  </table>
</div>

<?php elseif (!empty($enrollmentInfo['GetClassesResult']['Message'])) : ?>

  <div><?php echo $enrollmentInfo['GetClassesResult']['Message']; ?></div>

<?php else: ?>

<?php __('Error getting classes. Try re-loading the page.'); ?><br>
<pre><?php print_r($enrollmentInfo,1); ?></pre>

<?php endif?>
<?php print_r($enrollmentData); ?>
