<?php
$db = false;
$DBUSER = 'root';
$DBPASS = 'root';
$DBHOST = '127.0.0.1';

$DBNAME = 'kidzvideomonitor';  
function db_init() {
	global $db;
	global $DBUSER;
	global $DBPASS;
	global $DBHOST;
	global $DBNAME;
	if ($db = new PDO('mysql:host=' . $DBHOST . ';dbname=' . $DBNAME.';charset=utf8', $DBUSER, $DBPASS)) {
		$db -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		try {
			$q = $db -> query('SELECT * FROM users;');
		} catch(PDOException $pe) {
			 print_r($pe);exit;
			return false;

		}
		return $db;
	}
	return false;
}

$db = db_init();
