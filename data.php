<?php

//We used minimal framework
include 'core/constants.inc'; //Global Constants
include 'core/schema/SQLKana.class.php';
include 'core/schema/Entity.class.php'; //SQL manager

//@todo: Manage remote data reception with security token
if (isset($_GET['data'])) {
    if (!isset($_SERVER['REMOTE_ADDR']) || DATA_REMOTE) {
        $data = $_GET['data'];
        //$type = $_GET['type'];
        $dataExploded = explode("/", $data);
        //var_dump($dataExploded);
        if (count($dataExploded) > 1) {
            $type = $dataExploded[1];
            $collector_file = 'plugins/objects/'.$type.'/'.$type.'.collector';

            if (file_exists($collector_file)) {
                include 'core/common.inc';
                include 'plugins/objects/'.$type.'/'.$type.'.collector';
            } else {
                echo 'No collector available inside '.$collector_file;
            }
        }
    } else {
        echo 'nope';
    }
} else {
    echo 'data/type not set, example:&data=/radio/1234';
}
