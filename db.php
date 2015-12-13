<?php

//We used minimal framework
include 'core/constants.inc'; //Global Constants
include 'core/schema/SQLKana.class.php';
include 'core/schema/Entity.class.php'; //SQL manager
include 'core/common.inc';
//var_dump($argv);
//@todo: Only local script should be able to send data this way
if (!isset($_SERVER['REMOTE_ADDR']) || DATA_REMOTE) {
    if (isset($_GET['data']) && isset($_GET['db'])) {
        $data = $_GET['data'];
        $db = $_GET['db'];
        if (isset($_GET['state'])) {
            $state = $_GET['state'];
        } else {
            $state = '';
        }
        $path = DATA_DIR.dirname($db);
        //echo $path;
        if (!is_dir($path)) {
            $folder = explode('/', dirname($db));
            //var_dump($folder);
            if (count($folder) == 2) {
                echo '2 folders'."\n\r<br>";
                mkdir(DATA_DIR.'/'.$folder[0]);
                mkdir(DATA_DIR.'/'.$folder[0].'/'.$folder[1]);
            } elseif (count($folder) == 1) {
                echo '1 folder'."\n\r<br>";
                mkdir(DATA_DIR.'/'.$folder[0]);
            }
        }
        $time = time();
        echo 'Save '.$data.' into '.$db.'.db at '.$time."\n\r<br>";

            //echo $data.";".$db.";".$time."\n\r<br>";
        if ($db) {
            //echo $db;
            Data::saveData('', $data, $db, $time, $state);
        } else {
            echo '?db=database&data=data';
        }
    } else {
        echo 'Not Authorized';
    }
}
