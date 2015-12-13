<?php

class data
{
    //We saved the data inside a custom database
    //State is not mandatory
    public static function saveData($data_id, $data, $type, $time, $state = '', $maxdata = false)
    {
        //$dir = "/etc/kana/data/";

        if (!is_dir(DATA_DIR)) {
            mkdir(DATA_DIR);
        }

        $dir = DATA_DIR;
        //echo $dir;
        //We save the data with a timestamp


        //We generate a table to store data
        $table = $dir.$type.'.db';
        //echo $_["data"];

        if (!file_exists($table)) {
            $data_db = new Entity('data', $type);
            $data_db->create();
        }

        $data_db = new Entity('data', $type);
        $count_data = $data_db->rowCount();

        //We don't want to store too much codes so we reset the table every 10 recorded codes
        if ($maxdata) {
            if ($count_data > $maxdata) {
                $data_db->drop();
                $data_db = new Entity('data', $type);
            }
        }

        $data_db->setData($data);
        $data_db->setState($state);
        $data_db->setData_id($data_id);
        $data_db->setTimestamp(time());
        $data_db->save();
    }

    //Check the state of a data (if there is a state)
    public static function checkState($state_to_check, $state)
    {
        //var_dump($state);
        //var_dump($state_to_check);
        if ($state_to_check == '') {
            return true;
            echo "No state \n";
        }

        if ($state_to_check == $state) {
            echo "State corresponding \n";

            return true;
        } else {
            echo "State not corresponding \n";

            return false;
        }
    }

    public static function checkSensor($sensor, $time)
    {
        var_dump($sensor);
        $dbSensors = new Entity('core', 'Sensors');
        $sensorToDatabase = $dbSensors->load([
            'sensor_id' => $sensor['id'],
            ]);

        //var_dump($sensorToDatabase);
        self::checkTriggers($sensor['id'], 'sensors', $time, $sensor['value']);

        if (!$sensorToDatabase) {
            $dbSensors->setsensor_id($sensor['id']);
            $dbSensors->setsensor_type('unknown');
            $dbSensors->setsensor_save(0);
            $dbSensors->setsensor_name($sensor['id']);
            $dbSensors->setsensor_battery($sensor['battery']);
            $dbSensors->setsensor_lastvalue($sensor['value']);
            $dbSensors->setsensor_timestamp(time());
            $dbSensors->save();
            echo 'New sensor founded! save it';

            return false;
        } else {
            $dbSensors->change(
                [
                'sensor_lastvalue' => $sensor['value'],
                'sensor_battery' => $sensor['battery'],
                'sensor_timestamp' => time(),
                ],
                [
                'sensor_id' => $sensor['id'],
                ]
            );
            echo "Save to kana.db\n";

            return $sensorToDatabase;
        }
    }

    //@todo Check schedule
    public static function checkTriggers($data, $type, $time, $state = '')
    {


        //Check if a code has a trigger
        $db_triggers = new Entity('core', 'Triggers');

        //Check one code triggers
        $triggers = $db_triggers->loadAll([
            //"code" => $data,
            //"trigger" => "one",
            'trigger_object' => $type,
            'trigger_state' => 1,
        ]);



        //Check all code triggers
        $alltriggers = $db_triggers->loadAll([
            'trigger' => 'all',
            'trigger_object' => $type,
            'trigger_state' => 1,
        ]);

        echo 'Checking triggers for '.$type.' - '.$state."\n ";
        //if there is a trigger

        //var_dump($trigger);
        if ($triggers) {
            foreach ($triggers as $trigger) {
                //If there is a trigger linked with the data
                if ($data == $trigger['code']) {
                    //If there is a scenario linked with the trigger
                    $db_scenario = new Entity('core', 'Scenario');
                    $scenario = $db_scenario->load([
                        'id_trigger' => $trigger['id'],
                    ]);

                    if ($scenario) {
                        echo "Scenario founded for Trigger ONE \n";
                        $trigger_state_check = self::checkState($trigger['state'], $state);

                        //If the data have the state asked (also true if no state)
                        if ($trigger_state_check) {
                            //Check timeout time
                            //@todo let's users define the timeout
                            $timeout_time = $trigger['timestamp'] + $trigger['timeout'];
                            echo $type.' - '.$data.' - '.$state." \n ";
                            echo 'Timeout: '.$time - $timeout_time." \n ";
                            echo $scenario['action_tag']." \n ";

                            //If timeout is ok
                            if (($timeout_time < $time) || ($time = 0)) {
                                echo " Launching action \n";
                                //Change timeout
                                $db_triggers->change(array('timestamp' => $time), array('id' => $trigger['id']));
                                //Launch action
                                Functions::launchBackground("/do/kana/action '".$scenario['action_tag']."'");
                            }
                        }
                    }
                }
            }
        }

            //If there is an trigger for all actions
            //@todo refactor
        if ($alltriggers) {
            foreach ($alltriggers as $alltrigger) {
                $db_scenario = new Entity('core', 'Scenario');
                $scenario = $db_scenario->load([
                    'id_trigger' => $alltrigger['id'],
                    ]);

                $alltrigger_state_check = self::checkState($alltrigger['state'], $state);

                if ($alltrigger_state_check) {
                    //Check if a scenario is link to the trigger
                    if ($scenario) {
                        echo "\nScenario found for trigger ALL! \n";
                        $timeout_time = $alltrigger['timestamp'] + $alltrigger['timeout'];
                        echo $type.' - '.$data.' - '.$state.' - ';
                        echo 'Timeout: '.($time - $timeout_time)." \n";
                        echo $scenario['action_tag']." \n";
                        if ($timeout_time < $time) {
                            echo 'Time out OK!';
                            $db_triggers->change(array('timestamp' => $time), array('id' => $alltrigger['id']));
                            Functions::launchBackground("./action.py '".$scenario['action_tag']."'");
                        }
                    }
                }
            }
        }
    }

    public static function dbAvailable($type)
    {
        if (file_exists(USER_SENSORS.$type.'/db/')) {
            return Functions::getdir(USER_SENSORS.$type.'/db/');
        } else {
            return false;
        }
    }
}
