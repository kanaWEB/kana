<?php

//This file is used to emulate the functionalities of yana server
//For more information on yana server / yana client
//Please go to http://blog.idleman.fr/ (fr)

//We include basic functionalities
include 'core/common.inc';

if (isset($_['token'])) {
    $token = $_['token'];
} else {
    $token = md5(rand());
}

//Check if the token exists and is associated with an user
$currentUser = new User();
$currentUser->check_token($token);

//If token is associated to no user, log it
if (!$currentUser->id()) {
    $tokenlog_db = new Entity('core', 'TokenLog');
    $ip_selected = $tokenlog_db->load([
        'ipaddress' => $_SERVER['REMOTE_ADDR'],
        'token' => $token,
        ]);

    //If there was already a request from this ip increment it
    //@todo Ban brute force request
    if ($ip_selected) {
        $nbrequest = $ip_selected['nbrequest'] + 1;
        $tokenlog_db->change(
            [
            'nbrequest' => $nbrequest,
            'timestamp' => time(),
            ],
            ['id' => $ip_selected['id']]
        );

        $answer['type'] = 'Not Authorized';
        $answer['code'] = '401';
        $answer['message'] = 'Validate your token';
    } else {
        //If this is the first request then save it
        $tokenlog_db->SetIpaddress($_SERVER['REMOTE_ADDR']);
        $tokenlog_db->SetTimeStamp(time());
        $tokenlog_db->SetToken($token);
        $tokenlog_db->SetNbrequest(1);
        $tokenlog_db->Save();

        $answer['type'] = 'Created';
        $answer['code'] = '201';
        $answer['message'] = 'Token saved but not validate';
    }
} else {
    $answer['type'] = 'OK';
    $answer['code'] = '200';
    $answer['message'] = 'Token is validated';
}

echo json_encode($answer);
