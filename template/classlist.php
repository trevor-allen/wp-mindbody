<p>
  something basic
</p>
<?php if(!empty($mz_schedule_data['GetClassesResult']['Classes']['Class'])) : ?>

<!-- todo -->
<?php //if ($mb->debug())

$mz_days = $mb->makeNumericArray($mz_schedule_data['GetClassesResult']['Classes']['Class']);
$mz_days = sortClassesByDate($mz_days);
if ($type=='week') {
  $return .= mz_mbo_schedule_nav($mz_date);
}
?>

<div id="mbo-schedule" class="mbo-schedule">
  <table class="mbo-table table-striped">

    <?php foreach($mz_days as $classDate => $mz_classes) : ?>

      <tr>
        <th><?php echo date_i18n($mz_date_display, strtotime($classDate)) ?></th>
        <th><?php echo  __('Class Name'); ?></th>
        <th><?php echo  __('Instructor'); ?></th>
        <th><?php echo  __('Class Type'); ?></th>
      </tr>

      <?php foreach($mz_classes as $class): ?>
        <?php if (!(($class['IsCanceled'] == 'TRUE') && ($class['HideCancel'] == 'TRUE')) && ($class['Location']['ID'] == $location)) : ?>

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
          $isAvailable = $class['IsAvailable'];
          ?>

          <tr>
            <td>
              <?php
              //$classdate = date_i18n('g:i a', strtotime($startDateTime)) . ' - ' . date_i18n('g:i a', strtotime($endDateTime));
              $classStart = date_i18n('g:i a', strtotime($startDateTime));
              $classEnd =  date_i18n('g:i a', strtotime($endDateTime));
              $trimmed = trim($classStart . ' – ' . $classEnd," ");
              echo $trimmed;
               ?>
              <?php if ($isAvailable) : ?>
                <br><a class="btn" href="<?php echo $linkURL; ?>" target="_blank"><?php __('Sign-Up'); ?></a>
              <?php endif ?>
            </td>
            <td><a data-toggle="modal" data-target="#mzModal" href="#"><?php echo $className; ?></a></td>
          <td><?php echo $staffName; ?></td>
          <td><?php echo $sessionType; ?></td>
          </tr>

        <?php endif; ?>
      <?php endforeach; ?>
    <?php endforeach; ?>


  </table>
</div>

<?php elseif (!empty($mz_schedule_data['GetClassesResult']['Message'])) : ?>

  <div><?php echo $mz_schedule_data['GetClassesResult']['Message']; ?></div>

<?php else: ?>

<?php __('Error getting classes. Try re-loading the page.'); ?><br>
<pre><?php print_r($mz_schedule_data,1); ?></pre>

<?php endif?>
