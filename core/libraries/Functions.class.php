<?php
/**
* Functions classes stored basic functionalities of KANA, it is largely forked from YANA project
* https://github.com/ldleman/yana-server
* A lot of functions aren't used anymore and are only kept for legacy reasons
* I will probably remove them whenever I can
* @todo dispatch between new classes / remove unused functions
* @author Valentin CARRUESCO
**/

class Functions
{
	private $id;
	public $debug=0;

	/**
	 * Return Current IP
	 * @return String User's IP
	 */

	public static function secure($post,$get){
		//@todo add inspekt and refactor everywhere
		if(is_array($post) && is_array($get)){
			$_ = array_merge($post,$get);
		}
		else{
			if(is_array($post)){
				$_ = $post;
			}
			if(is_array($get)){
				$_ = $get;
			}
		}
		return $_;
	}

	public static function getIP(){
		if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];}
			elseif(isset($_SERVER['HTTP_CLIENT_IP'])){
				$ip = $_SERVER['HTTP_CLIENT_IP'];}
				else{ $ip = $_SERVER['REMOTE_ADDR'];}
				return $ip;
			}

	/**
	 * Return a truncated string of $limit caracters 
	 * @param<String> string to truncate
	 * @param<Integer> characters limits
	 * @return<String> truncated string
	 */
	public static function truncate($msg,$limit){
		if(mb_strlen($msg)>$limit){
			$fin='…' ;
			$nb=$limit-mb_strlen($fin) ;
		}else{
			$nb=mb_strlen($msg);
			$fin='';
		}
		return mb_substr($msg, 0, $nb).$fin;
	}


	/**
	 * Define if a given string exists inside the given reference
	 * @param unknown_type $string
	 * @param unknown_type $reference
	 * @return boolean true if the reference is found
	 */
	public static function contain($string,$reference){
		$return = true;
		$pos = strpos($reference,$string);
		if ($pos === false) {
			$return = false;
		}
		return strtolower($return);
	}

	/**
	 * Check if a string is an URL
	 * @todo Move to checker.class.php and use it
     * @return boolean
	 */
	public static function isUrl($url){
		$return =false;
		if (preg_match('/^(http|https|ftp)://([A-Z0-9][A-Z0-9_-]*(?:.[A-Z0-9][A-Z0-9_-]*)+):?(d+)?/?/i', $url)) {
			$return =true;
		}
		return $return;
	}

	/**
	 * Check if a string is a hexadecimal color
	 * @todo Move to checker.class.php and use it
	 */
	public static function isColor($color){
		$return =false;
		if (preg_match('/^#(?:(?:[a-fd]{3}){1,2})$/i', $color)) {
			$return =true;
		}
		return $return;
	}

	/**
	 * Check if a string is an email
	 * @todo Move to checker.class.php and use it
	 */
	public static function isMail($mail){
		$return =false;
		if (filter_var($mail, FILTER_VALIDATE_EMAIL)) {
			$return =true;
		}
		return $return;
	}

	/**
	 * Check if a string is an IP address
	 * @todo Move to checker.class.php
	 * @return boolean
	 */
	public static function isIp($ip){
		$return =false;
		if (preg_match('^(?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)(?:[.](?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)){3}$',$ip)) {
			$return =true;
		}
		return $return;
	}

	/**
	* Generate a cookie
	*
	*/
	public static function makeCookie($name, $value,$expire) {
		setcookie($name,$value,$expire,'/');
	}

	/**
	*
	* Destroy a cookie
	*
	*/

	public static function destroyCookie($name){
		setcookie($name, "", time()-3600,"/");
	}



	/*
		Secret Key Manager
		As we need to store password inside the database and decrypt it whenever we want to trigger actions
		We need a secretkey that we will stored inside CONFIG_DIR (default: /etc/kana/secretkey)
		This methods is a lot more unsecure then using hash but hash require user interaction which
		is not what we want.
		
		This is based on this post on stackoverflow:
		http://stackoverflow.com/questions/16600708/how-do-you-encrypt-and-decrypt-a-php-string
	*/

	/**
	 * Returns an encrypted & utf8-encoded
 	*/
	public static function encrypt($pure_string, $encryption_key) {
		$iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
		$encrypted_string = mcrypt_encrypt(MCRYPT_BLOWFISH, $encryption_key, utf8_encode($pure_string), MCRYPT_MODE_ECB, $iv);
		return $encrypted_string;
	}

	/**
	 * Returns decrypted original string
 	*/
	public static function decrypt($encrypted_string, $encryption_key) {
		$iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
		$decrypted_string = mcrypt_decrypt(MCRYPT_BLOWFISH, $encryption_key, $encrypted_string, MCRYPT_MODE_ECB, $iv);
		return $decrypted_string;
	}

	//Generate a secret key to a file
	public static function generate_secretkey($file){
		$iv = mcrypt_create_iv(16, MCRYPT_DEV_URANDOM);
		file_put_contents($file,$iv);
	}

	//Get a secret key from a file
	public static function get_secretkey($file){
		$key = file_get_contents($file);
		return $key;
	}


	/**
	* Convert a file size 
	**/
	public static function convertFileSize($bytes)
	{
		if($bytes<1024){
			return round(($bytes / 1024), 2).' o';
		}elseif(1024<$bytes && $bytes<1048576){
			return round(($bytes / 1024), 2).' ko';
		}elseif(1048576<$bytes && $bytes<1073741824){
			return round(($bytes / 1024)/1024, 2).' Mo';
		}elseif(1073741824<$bytes){
			return round(($bytes / 1024)/1024/1024, 2).' Go';
		}
	}

	//Make a relative address from two absolute address
	public static function relativePath($from, $to, $ps = '/') {
		$arFrom = explode($ps, rtrim($from, $ps));
		$arTo = explode($ps, rtrim($to, $ps));
		while(count($arFrom) && count($arTo) && ($arFrom[0] == $arTo[0])) {
			array_shift($arFrom);
			array_shift($arTo);
		}
		return str_pad("", count($arFrom) * 3, '..'.$ps).implode($ps, $arTo);
	}

	//Convert a date into a timestamp
	
	public static function totimestamp($date,$delimiter='/')
	{
		$explode=explode($delimiter,$date);
		return strtotime($explode[1].'/'.$explode[0].'/'.$explode[2]);


	}

	//Manage Plugin translation
	//$Path: Path of plugins (ex plugin/camera)
	public static function localize($plugin_path)
	{
		$locale_path = $plugin_path."/locale/".$_SESSION["LANGUAGE"].".php";
		if (DEBUG) error_log($locale_path);
		if (file_exists($locale_path))
		{
			if (DEBUG) {echo "Loaded translation file: ".$locale_path."<br><br>";}
			require($locale_path);
		}
	}

	//Launch a terminal command in background
	public static function launch_background($cmd,$nice=-20)
	{
		//Hack to speed up command (need nice in sudoers)
		//$cmd = "sudo nice -n ".$nice." ".$cmd;
		shell_exec(sprintf('%s > /dev/null 2>&1 &', $cmd));
	}

	//Verify if a process exists
	//$name: Name of program
	//return : 0 running, 1 not running
	public static function is_running($name){
		exec("pgrep ".$name,$output,$result);
		return $output;
	}

	public static function stop_process_with_socket($process_name,$object_name){
		$check_process_command = 'ps aux|grep "'.$process_name.'"|grep "'.$object_name.'"'."| grep -v grep";
		exec($check_process_command,$processes,$exitcode);
		foreach($processes as $process){
			$process = explode(" ",$process);	
			$socket = $process[count($process) -1];
			Functions::sendto_socket($socket,"stop\n");
			//echo "killing socket:".$socket;
		}
	}

	public static function get_socket($process_name,$object_name){
		$check_process_command = 'ps aux|grep "'.$process_name.'"|grep "'.$object_name.'"'."| grep -v grep";
		exec($check_process_command,$processes,$exitcode);
		foreach($processes as $process){
			$process = explode(" ",$process);	
			$socket[] = $process[count($process) -1];
		}
		if(isset($socket)){
			return $socket[0];
		}
		else
		{
			return false;
		}
	}


/*

Serial Functions

*/

	//Send a message to serial port
public static function send_serial($message,$port,$baudrate=DEFAULT_SERIAL_SPEED)
{
	$port = Functions::human2serial($port);
	$serial = new phpSerial();
	$serial->deviceSet($port);
	$serial->confBaudRate($baudrate);
	$serial->confStopBits(1);
	$serial->disable_dtr();
	$serial->deviceOpen();
	$serial->sendMessage($message);
	$serial->deviceClose();
}

//Receive a message from serial port and stop
public static function receive_serial($port,$timeout=5,$baudrate=DEFAULT_SERIAL_SPEED,$dataend=PHP_EOL){
	$waitingdata = True;
	$response = "";
	$data = "";
    //Configure PHP Listener for serial
	$serial = new phpSerial();
	$serial->deviceSet($port);
	$serial->confBaudRate($baudrate);
	$serial->confParity("none");
	$serial->confCharacterLength(8);
	$serial->confStopBits(1);
	$serial->deviceOpen();
	//$serial->deviceClose();
	//$serial->deviceOpen();

    //Flush port

	$serial->readPort(); 
	//sleep(1);

    $timeout = 5; //In seconds
    $time_started = time();
    
    //We acquired data from serial port
    while ($waitingdata)
    {
    	$current_time = (time() - $time_started);
    	if($current_time >= $timeout){$waitingdata = false;}

    	$read = $serial->readPort();
    	if($read !=""){
    		$data = $data.$read;
    		if (strstr($data,$dataend)){
    			$response = "RESPONSE-".$data;
    			$waitingdata = false;
    		}
    	}
    	/*
    	if (trim($read) != ""){
    		echo $read."<br>";
    		//Verify is data has not been truncated
    		if (strstr($read,$dataend)){
        //We quit when data is acquired
    			$response = "RESPONSE-".explode("\n",trim($read))[0];
    			$waitingdata = false;
    		}
    		else $read = "";
    	}
    	*/
    }
    $serial->deviceClose();
    return $response;
}

//Get a list of all Serial Port 
//The list is then convert into an human readable list
public static function get_serial(){
	$serial_port = array();
	
	//List serial port (TODO:)
	exec("ls /dev/ttyAMA* /dev/ttyUSB* /dev/ttyACM* /dev/ttyS* 2>/dev/null",$results,$out);
	
	foreach($results as $result){
		$serial_device = str_replace("/dev/tty", "", $result);
		$serial_name =  substr($serial_device,0,-1);
		$serial_id = substr($serial_device,-1);
		switch($serial_name){
			//GPIO
			case "AMA":
			array_push($serial_port, "GPIO");
			break;
			//MODEMUSB
			case "ACM":
			array_push($serial_port, "ARDUINO:".$serial_id);
			break;
			//USB
			case "USB":
			array_push($serial_port,"USB:".$serial_id);
			break;
			//UART SERIAL
			case "S":
			array_push($serial_port,"SERIAL:".$serial_id);
			break;
			default:
			array_push($serial_port,"/dev/tty".$serial_name.$serial_id);
			break;
		}
	}
	return $serial_port;
}

/* 
Listener Functions
*/

//Generate a command to start a listener
public static function listener_generate_command($name,$objectManager,$custom_vars=false){
	$listener_name = $objectManager->LISTENER_NAME;
	$table = $objectManager->name();
	$listener_port = $objectManager->LISTENER_PORT;
	if (DEBUG){
		$cmd = $name." ".$custom_vars." ".$listener_port." ".Functions::get_yanapath()." ".DB_NAME." ".$table." DEBUG";
		echo "Command:".$cmd;
	}
	else{
		$cmd = $name." ".$custom_vars." ".$listener_port." ".Functions::get_yanapath()." ".DB_NAME." ".$table;
	}
	return $cmd;
}
//Generate a command to start a listener with php-cgi
public static function listener_cgi($objectManager){
	$cmd = "php-cgi ".Functions::get_yanapath()."action.php action=".$objectManager->name()."_listener_change_state";
	return $cmd;
}

//Send a stop message to the listener
public static function listener_stop($objectManager){
	$listener_port = $objectManager->LISTENER_PORT;
	if(Functions::is_port_open($listener_port)){
		Functions::sendto_socket($listener_port,"stop");
		if (DEBUG){
			echo "Listener Stopped:".$listener_port;;
		}
		return true;
	}
	else
	{
		return false;
	}
}

//Send an update message to the listener
public static function listener_update($objectManager){

	$listener_port = $objectManager->LISTENER_PORT;
	if(Functions::is_port_open($listener_port)){
		Functions::sendto_socket($listener_port,"update");
		if (DEBUG){
			echo "Listener Updated:".$listener_port;
		}
	}
	else
	{
		if (DEBUG){
			echo "Listener is stopped no update:".$listener_port;
		}
	}
}

//Show a warning message if the listener is stopped in Triggers
public static function listener_warning($objectManager){
	$state = Functions::is_port_open($objectManager->LISTENER_PORT);

	if (!$state)
	{
		g::listener_warning($objectManager->fullname());
	}
}

//Check if trigger database is consistent (when you modified a specific object)
public static function update_triggers($id,$uid,$db){

}

//Send a message to a socket
public static function sendto_socket($port,$command){
	$socket = socket_create(AF_INET,SOCK_STREAM,SOL_TCP);

//Error message if socket cannot be opened
	if (($socket == false) && (DEBUG)){
		echo "Error: socket_create() failed: reason: " .
		socket_strerror(socket_last_error());
	}
// Connect to the socket
	$result = socket_connect($socket, 'localhost', $port);

//Error message if socket cannot be opened
	if (($result === false) && (DEBUG)){
		echo "Error: socket_connect() failed.\nReason: ($result) " .
		socket_strerror(socket_last_error($socket));
	}
	socket_write($socket, $command);
	sleep(1);
	socket_close($socket);
}

public static function receivefrom_socket($port){

	$socket = socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
//Error message if socket cannot be opened
	if (($socket == false) && (DEBUG)){
		echo "Error: socket_create() failed: reason: " .
		socket_strerror(socket_last_error($socket));
	}

// Connect to the socket
	$result = socket_connect($socket, 'localhost', $port);

	$out = "";

	while (($currentByte = socket_read($socket, 1)) != "\n") {
		$out .= $currentByte;
		
	}

	//@todo Error on the server socket (broken pipe/connection reset by peer should be investigate)

	echo $out;

	socket_close($socket);
}

public static function sendandreceive_socket($port,$command){
	$socket = socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
	
	//Error message if socket cannot be opened
	if (($socket == false) && (DEBUG)){
		echo "Error: socket_create() failed: reason: " .
		socket_strerror(socket_last_error());
	}
// Connect to the socket
	$result = socket_connect($socket, 'localhost', $port);

//Error message if socket cannot be opened
	if (($result === false) && (DEBUG)){
		echo "Error: socket_connect() failed.\nReason: ($result) " .
		socket_strerror(socket_last_error($socket));
	}
	sleep(1);
	socket_write($socket, $command);
	sleep(1);
	exit();
	$out = "";
	while (($currentByte = socket_read($socket, 1)) != "\n") {
		$out .= $currentByte;
	}
	echo $out;

	socket_close($socket);
}


//Verify if a port is open
public static function is_port_open($port){
	if($port){
		$connection = @fsockopen("127.0.0.1",$port);
		
		if (is_resource($connection)){
			fclose($connection);
			return true;
		}
		else
		{
			return false;
		}
	}
	else
	{
		return false;
	}
}

//Convert Humanize name of serial port to /dev/tty
public static function human2serial($port){
	$port = str_replace("GPIO","/dev/ttyAMA0",$port);
	$port = str_replace("ARDUINO:","/dev/ttyACM",$port);
	$port = str_replace("USB:","/dev/ttyUSB",$port);
	$port = str_replace("SERIAL:","/dev/ttyS",$port);
	return $port;
}

//Retrieve Page name
public static function get_pagename()
{
	$currentFile = $_SERVER["PHP_SELF"];
	$parts = Explode('/', $currentFile);
	return $parts[count($parts) - 1];
}

//Retrieve YANA absolute path
public static function get_yanapath(){
	if(isset($_SERVER['SCRIPT_FILENAME'])){
		$path_yana =  dirname($_SERVER['SCRIPT_FILENAME'])."/";
	}
	else //Path for CGI scripts (php-cgi)
	{
		$path = $_REQUEST;
		reset($path);
		$path_yana = dirname(key($path))."/";
	}
	return $path_yana;
}

//Array Pretty Debugging (use for Templates classes debugging)
public static function pretty_debug($array){
	foreach($array as $key=>$value){
		if (is_object($value)) {$value = get_class($value);}
		if(is_array($value)){
			$id = md5(rand());
			echo '[<a href="#" onclick="return expandParent(\''.$id.'\')">'.$key.'</a>]<br />';
			echo '<div id="'.$id.'" style="display:none;margin:10px;border-left:1px solid; padding-left:5px;">';

			Functions::pretty_debug($value);
			echo '</div>';
		} else {
			echo "<b>$key</b>: ".$value."<br />";
		}
	}
	echo '<script language="Javascript">
	function expandParent(id){toggle="block";if(document.getElementById(id).style.display=="block"){toggle="none"}document.getElementById(id).style.display=toggle};
</script>';
}

//Check Permissions of a file

public static function check_perm($file){

	$file = Functions::get_yanapath().$file;
		//Check if file exists
	if (file_exists($file))
	{
		$perm = fileperms($file);
		if ($perm == "36333"){
			if (DEBUG){echo "<br>Permissions</br><code>".$file." ".$perm."</code>";}
		}
		else
		{
			if (DEBUG){echo "<br>Permissions</br><code>".$file." ".$perm."</code>";}
				//Display how to fix the issue
			g::display_permissions_error();
		}

	}
	else
	{
		g::display_error($file.t(" don't exists, try reinstalling plugin"));
	}
}


//Generate a vocal command
public static function vocal_command($vocal,$command,$actionUrl){
	global $conf;
	return array(
		'command'=>$conf->get("VOCAL_ENTITY_NAME").", ".t($vocal),
		'url'=>$actionUrl.'?action='.$command,'confidence'=>$conf->get("confidence")
		);
}



//Get directory in an array without . and ..
public static function getdir($dir){
	//Display custom views
	$dir = scandir($dir);
	array_shift($dir);
	array_shift($dir);
	return $dir;
}

//Get recursive directory in an array without . and ..
public static function getdir_r($dir){
	while($dirs = glob($dir . '/*', GLOB_ONLYDIR)) {
		$dir .= '/*';
		if(!isset($d)) {
			$d=$dirs;
		} else {
			$d=array_merge($d,$dirs);
		}
	}
	return $d;
}


//Strip all accentuate characters
public static function remove_accents($str, $charset='utf-8')
{
	$str = htmlentities($str, ENT_NOQUOTES, $charset);

	$str = preg_replace('#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
    $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str); // for ligature e.g. '&oelig;'
    $str = preg_replace('#&[^;]+;#', '', $str); // delete other characters
    
    return $str;
}

public static function convert_quotes($str){
	$str = str_replace('&amp;amp;#039;',"'",$str);
	return $str;
}

public static function loadUrl($url){
	if(function_exists('curl_init')){
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$content = curl_exec($curl);
		curl_close($curl);
		return trim($content);
	}elseif(function_exists('file_get_contents')){
		return trim(file_get_contents($url));
	}else{
		return false;
	}
}

//Convert timestamp to nearest minutes
public static function timestamp_nearest_minutes($nearminutes){
	$time = time();
	$minutes = date("i");
	$nearminutes_half = $nearminutes / 2;
	$nearseconds = $nearminutes * 60;

	if($minutes[1] < $nearminutes_half){
		$time_new = ceil($time/$nearseconds)*$nearseconds - $nearseconds;
	}
	else{
		$time_new = ceil($time/$nearseconds)*$nearseconds;
	}
	return $time_new;
}

//Display readable time
public static function readableTime($seconds) {
	$y = floor($seconds / 60/60/24/365);
	$d = floor($seconds / 60/60/24) % 365;
	$h = floor(($seconds / 3600) % 24);
	$m = floor(($seconds / 60) % 60);
	$s = $seconds % 60;

	$string = '';

	if ($y > 0) {
		$years = t("years");
		$year = t("year");
		$yw = $y > 1 ? ' '.$years.' ' : ' '.$year.' ';
		$string .= $y . $yw;
	}

	if ($d > 0) {
		$day = t("day");
		$days = t("days");
		$dw = $d > 1 ? ' '.$days.' ' : ' '.$day.' ';
		$string .= $d . $dw;
	}

	if ($h > 0) {
		$hours = t("hours");
		$hour = t("hour");
		$hw = $h > 1 ? ' '.$hours.' ' : ' '.$hour.' ';
		$string .= $h . $hw;
	}

	if ($m > 0) {
		$minutes = t("minutes");
		$minute = t("minute");
		$mw = $m > 1 ? ' '.$minutes.' ' : ' '.$minute.' ';
		$string .= $m . $mw;
	}

	if ($s > 0) {
		$seconds = t("seconds");
		$second = t("second");
		$sw = $s > 1 ? ' '.$seconds.' ' : ' '.$second.' ';
		$string .= $s . $sw;
	}

	return preg_replace('/\s+/', ' ', $string);
}




}

?>
