<?php
include("core/common.inc");

/*
TOKEN should be push to another file (to easily add/modify way to check in)
*/

if (isset($_["token"])) {
    $currentUser = new User;
    $currentUser->check_token($_["token"]);
    if (!$currentUser->id()) {
        $affirmation = "Token invalid";
        
        $tokenlog_db = new Entity("core", "TokenLog");
        $ip_selected = $tokenlog_db->load([
            "ipaddress" => $_SERVER['REMOTE_ADDR'],
            "token" => $_["token"]
            ]);

        //If there was already a request from this ip increment it
        if ($ip_selected) {
            $nbrequest = $ip_selected["nbrequest"] + 1;
            $tokenlog_db->change(
                [
                "nbrequest" => $nbrequest,
                "timestamp" => time()
                ],
                ['id'=>$ip_selected["id"]]
            );

        } else {
            $tokenlog_db->SetIpaddress($_SERVER['REMOTE_ADDR']);
            $tokenlog_db->SetTimeStamp(time());
            $tokenlog_db->SetToken($_["token"]);
            $tokenlog_db->SetNbrequest(1);
            $tokenlog_db->Save();
        }

        $answer["type"] = "Forbidden";
        $answer["code"] =  "403";
        $answer["message"] = "Access denied for this token";

        $json = json_encode($answer);
        echo $json;
            //@todo to refactor
        exit();
    }
}


/*
Users verifications
VERIFICATION IS BYPASSED IF SCRIPT IS CALLED LOCALLY

*/

if ($currentUser->isuser() || !isset($_SERVER['REMOTE_ADDR'])) {
    //@todo check user right to do actions
    if (isset($_["type"])) {
        $type = $_["type"];
    } else {
        $type = "action";
    }

    if (file_exists("core/actions/".$type.".actions")) {
        include("core/actions/".$type.".actions");
    } else {
        echo "core/actions/".$type.".actions does not exists";
    }
} else {
    $answer["type"] = "Forbidden";
    $answer["code"] =  "403";
    $answer["message"] = "Access denied for this token";

    $json = json_encode($answer);
    echo $json;
    //echo Draw::ajax_notify("NOT LOGGED","error");
}
