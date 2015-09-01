<?php
$url=parse_url(getenv("CLEARDB_DATABASE_URL"));
$db = false;
$DBUSER = 'root';
$DBPASS = 'root';
$DBHOST = '10.0.10.17';

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
