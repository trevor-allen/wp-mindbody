<?php
function mZ_mindbody_activation()
{
    require_once MINDBODY_SCHEDULE_DIR.'inc/mz_mbo_init.inc';

    $data = $mb->GetActivationCode();
    $return = $mb->debug();

    return $return;
}//EOF mZ_mindbody_activation
