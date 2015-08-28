<?php
//require_once 'session-check.php';
session_start();
if(!isset($_SESSION['uid']))
{
    echo "false";
    exit();
}
if(!isset($_POST['state']))
{
    echo "false";
    exit();
}
require_once 'db_init.php';


if($_POST['state']=="insert")
{
$params = array('state','title','category','vduration','vid','url');
$check = true;
foreach($params as $key=>$value) 
{
    if(!isset($_POST[$value]))
    {
        $check = false;
        break;
    }
}

if(!$check)
{
    echo "false";
    exit();
}
 /* $url=$_POST['url'];
 echo $url;
die(); */
/* $contents = file_get_contents($url, "r");
$pos = stripos($contents, 'name="keywords"'); */
$res=save_details($_POST['title'],$_POST['category'],$_POST['vduration'],$_POST['vid'], $_SESSION['uid'],$_POST['url']);

if($res != false)
{
    echo $res;
}
else
{
    echo "false";
}
}
else if($_POST['state']=="update")
{
$res=update_watched_duration($_POST['id'],$_POST['watched_time']);
if($res)
{
    echo "true";
}
else
{
    echo "false";
}
}

function update_watched_duration($id,$watched_time)
{
global $db;
    if($db){
    $watched_time=intval($watched_time);
        $query = $db->prepare("UPDATE video SET watched_duration=? WHERE id=?");
        $query->bindValue(1, $watched_time, PDO::PARAM_INT);
        $query->bindValue(2, $id, PDO::PARAM_INT);
        $result=$query->execute();
        if($result)
        {
            return true;
        }
    }
    return false;
}

function save_details($title,$category,$vduration,$vid,$uid,$url)
{
    global $db;
    if($db){
    
        $query = $db->prepare("INSERT INTO video(video_title,video_category,video_duration,watched_duration,video_id,user_id,url) values(?, ?, ?, ?, ?, ?, ?)");
        $query->bindValue(1, $title, PDO::PARAM_STR);
        $query->bindValue(2, $category, PDO::PARAM_STR);
        $query->bindValue(3, $vduration, PDO::PARAM_STR);
        $query->bindValue(4, 0, PDO::PARAM_INT);
        //$query->bindValue(5, $dt, PDO::PARAM_STR);
        $query->bindValue(5, $vid, PDO::PARAM_STR);
        $query->bindValue(6, $uid, PDO::PARAM_INT);
		$query->bindValue(7, $url, PDO::PARAM_INT);
        $result=$query->execute();
        if($result)
        {
			
            return $db->lastInsertId();
        }
    }
    return false;
}
?>