<?php
function sortClassesByDate($mz_classes = [])
{
    $mz_classesByDate = [];

    foreach ($mz_classes as $class) {
        $classDate = date('Y-m-d', strtotime($class['StartDateTime']));

        if (! empty($mz_classesByDate[$classDate])) {
            $mz_classesByDate[$classDate] = array_merge($mz_classesByDate[$classDate], [$class]);
        } else {
            $mz_classesByDate[$classDate] = [$class];
        }
    }
    ksort($mz_classesByDate);

    foreach ($mz_classesByDate as $classDate => &$mz_classes) {
        usort($mz_classes, function ($a, $b) {
            if (strtotime($a['StartDateTime']) == strtotime($b['StartDateTime'])) {
                return 0;
            }

            return $a['StartDateTime'] < $b['StartDateTime'] ? -1 : 1;
        });
    }

    return $mz_classesByDate;
}
