<?php
require_once( dirname(__FILE__) . '../../../../../wp-load.php');
ob_start();
require('cjinfo.php');
global $wpdb, $cjtable;
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case $_GET['action']:
            $_GET['action']();
            break;
    }
}
function removeall(){
	global $wpdb, $cjtable;
	$sql = "DELETE FROM $cjtable";
	mysql_query($sql);
	echo '<strong>All links removed.</strong>';
}
function submitlink(){
	global $wpdb, $cjtable;
	$email = $_REQUEST['email'];
	$title = addslashes($_REQUEST['title']);
	$url = $_REQUEST['url'];
	$info = strip_tags(addslashes($_REQUEST['info']));
	$status = 0;
	$sql = "INSERT INTO $cjtable (email, title, url, info, status) VALUES ('$email','$title','$url','$info','$status')";
	mysql_query($sql);
	echo "Thank You, We have received your submission. Your link will be displayed once approved. You may close this window now.";
}
function approve(){
	global $wpdb, $cjtable;
	$id = $_GET['id'];
	$sql = "UPDATE $cjtable SET status = '1' where id = '$id' LIMIT 1";
	mysql_query($sql);
}
function remove(){
	global $wpdb, $cjtable;
	$id = $_GET['id'];
	$sql = "DELETE FROM $cjtable where id = '$id' LIMIT 1";
	mysql_query($sql);
}
?>