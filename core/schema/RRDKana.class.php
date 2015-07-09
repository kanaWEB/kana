<?php

/**
 * Class RRDKANA.
 *
 * @name: RRDKana
 * @author: Sarrailh Rémi <maditnerd@gmail.com>
 * @description: Manage RRDtool database
 */
class RRDKana
{
    private $db = false;
    private $schema = false;
    private $id = false;
    private $type = false;
    private $range = false;

    public function __construct($type, $id)
    {
        $this->db = DATA_DIR.$type.'/'.$id.'.rrd';
        $this->schema = USER_SENSORS.$type.'/db/rrd.md';
        $this->id = $id;
        $this->type = $type;

        if (!file_exists($this->db)) {
            echo $this->db." doesn't exists <br>\n";
            $this->create();
        }
    }

    public function get_schema()
    {
        $params = $this->params();
        //var_dump($params);
        if (isset($params['DATA1'])) {
            $i = 1;
            while (isset($params['DATA'.$i])) {
                $rrd_schema['RRA'][] = $params['DATA'.$i];
                ++$i;
            }
        }

        if (!is_dir(DATA_DIR.$this->type)) {
            //echo "Creating folder ".DATA_DIR.$this->type;
            mkdir(DATA_DIR.$this->type);
        }

        $rrd_schema['step'] = $params['update_every'] * 60;
        $rrd_schema['heartbeat'] = $rrd_schema['step'] * $params['heartbeat'];
        $rrd_schema['xff'] = $params['xff'];
        $rrd_schema['min'] = $params['min'];
        $rrd_schema['max'] = $params['max'];
        $rrd_schema['dst'] = 'GAUGE';
        $rrd_schema['id'] = $this->id;
        $rrd_schema['range'] = $params['range'];
        $this->range = $rrd_schema['range'];

        return $rrd_schema;
    }

    public function params()
    {
        $schema = $this->schema;
        $params = Variable::md2var($schema);

        return $params;
    }

    public function step()
    {
        $params = $this->params();

        return $params['update_every'];
    }

    public function range()
    {
        return $this->range;
    }

    public function create()
    {
        //echo "Reading schema: ".$this->schema. "<br>";
        if (file_exists($this->schema)) {
            $rrd_schema = $this->get_schema();
            echo 'Creating :'.$this->db.'<br>';

            //var_dump($rrd_schema);
                //Start / Steps
            $opts[] = '--start';
            $opts[] = time() - $rrd_schema['step'];
            $opts[] = '--step';
            $opts[] = $rrd_schema['step'];

                //DataStorage
            $opts[] = 'DS:'.$rrd_schema['id'].':'.$rrd_schema['dst'].':'.$rrd_schema['heartbeat'].':'.$rrd_schema['min'].':'.$rrd_schema['max'];

            foreach ($rrd_schema['RRA'] as $RRA) {
                $opts[] = 'RRA:AVERAGE:'.$rrd_schema['xff'].':'.$RRA;
                $opts[] = 'RRA:MIN:'.$rrd_schema['xff'].':'.$RRA;
                $opts[] = 'RRA:MAX:'.$rrd_schema['xff'].':'.$RRA;
            }

            $out = rrd_create($this->db, $opts);
        } else {
            echo 'No model for this data type';
        }
    }

    public function update($data)
    {
        echo 'Updating data on '.$this->db;
        $time = Functions::timestamp_nearest_minutes($this->step());
        $output_update = shell_exec('rrdtool updatev "'.$this->db.'" '.$time.':'.$data.' 2>&1');
        //$this->update_error($output_update,$data,$time);
    }

    private function update_error($output_update, $data, $time)
    {
        $output_update_array = explode(':', $output_update);
        $iserror = $output_update_array[0];
        $error = $output_update_array[2];
        $error_array = explode(' ', $error);

        if ($error_array[1] == 'illegal') {
            $error = 'Data was already saved at this time';
        }

        if ($iserror == 'ERROR') {
            $log_update = 'Value '.$data.' was not saved at '.date('H:i:s d-m-Y ').' '.$time."\n";
        } else {
            $log_update = 'Value '.$data.' was saved at '.date('H:i:s d-m-Y ').' '.$time."\n";
        }

        echo $log_update;
    }

    public function lastupdate()
    {
        $actual_data = shell_exec('rrdtool lastupdate '.$this->db);
        $last_data = explode(':', $actual_data);
        $lastupdate = $last_data[1];
        $lastupdate = trim($lastupdate);
        if ($lastupdate == 'U') {
            $lastupdate = '';
        }

        return $lastupdate;
    }

    public function xml($time)
    {
        switch ($time) {
            case '1h':
                $s = 'now-1h';
                $step = 100;
                break;

            case '3h':
                $s = 'now-3h';
                $step = 300;
                break;

            case '1d':
                $s = 'now-24h';
                $step = 900;
                break;

            case '2d':
                $s = 'now-48h';
                $step = 1800;
                break;

            case '1w':
                $s = 'now-8d';
                $step = 7200;
                break;

            case '1m':
                $s = 'now-1month';
                $step = 10800;
                break;

            case '3m':
                $s = 'now-3month';
                $step = 43200;
                break;

            case '1y':
                $s = 'now-1year';
                $step = 86400;
                break;

            default:
                $s = 'now-24h';
                $step = 900;
                break;
        }
        $command = 'rrdtool xport -s '.$s.' -e now --step '.$step.' DEF:a='.$this->db.':'.$this->id.':AVERAGE XPORT:a:"'.$this->id.'"';
    //var_dump($command);
        $xml = shell_exec($command);
        $xml = simplexml_load_string($xml);

        return $xml;
    }

//This function is use to parse RRDdata to Highcharts
    public function xml2json($time)
    {
        $xml = $this->xml($time);
        $i = 0;
        foreach ($xml->data->row as $data) {
            $json[$i][0] = (int) $data->t * 1000;
            if ($data->v != 'NaN') {
                $json[$i][1] = (float) $data->v;
            } else {
                $json[$i][1] = null;
            }
            ++$i;
        }

        return $json;
    }

    public function xml2csv($time)
    {
        $xml = $this->xml($time);
        $csv = "TimeStamp;Value \r\n";
        foreach ($xml->data->row as $data) {
            $DMY = date('d/m/Y h:i', (int) $data->t);
            $csv .= $DMY.';';
            if ($data->v != 'NaN') {
                $csv .= round((float) $data->v, 2)."\r\n";
            } else {
                $csv .= ''."\r\n";
            }
        }

        return $csv;
    }
}
