<?php
require_once( dirname(__FILE__) . '../../../../../wp-load.php');
ob_start();
global $wpdb, $cjtable;
$cjtable = $wpdb->prefix.'cjcommunitylinks';
header ("Content-type: text/xml");
echo '<?xml version="1.0" encoding="utf-8" ?>';
echo '<rss version="2.0">';
$links = $wpdb->get_results("SELECT * FROM $cjtable where status = 1 ORDER BY ID DESC", ARRAY_A);
echo '<channel>';
echo '
<title>'.get_bloginfo('title').' Community Links Feed</title>
<link>'.get_bloginfo('home').'</link>
<description>'.get_bloginfo('description').'</description>
';
foreach ($links as $data) {
echo '
  <item>
    <title>'.$data['title'].'</title>
    <link>'.$data['url'].'</link>
    <description>'.strip_tags($data['info']).'</description>
  </item>
';
}
echo '</channel>';
echo '</rss>';
?>