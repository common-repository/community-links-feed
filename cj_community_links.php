<?php
/*
Plugin Name: Community Links Feed
Plugin URI: http://www.cssjockey.com/freebies/community-links-feed-wordpress-plugin
Description: This plugin will install the Community Links Feed widget where users can submit links to your website. <br /> See <a href="options-general.php?page=community-links.php">Configuration Panel</a> to configure this plug-in. Visit our <a href="http://support.cssjockey.com/forum/community-links-feed" target="_blank">Community Forum</a> for support, report bugs and request more features. 
Version: 1.05
Author: CSSJockey
Author URI: http://www.cssjockey.com
/*  Copyright 2009 CSSJockey.com  (email : admin@cssjockey.com)
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
*/
require_once( dirname(__FILE__) . '../../../../wp-load.php');
ob_start();
global $wpdb, $cjtable, $clpath, $plugin_url, $settingsname, $clshortname;
require('assets/cjinfo.php');
// INITIATE PLUGIN AND CREATE TABLES
register_activation_hook(__FILE__, 'clActivate');
function clActivate(){
	global $wpdb, $cjtable;
	$structure = "CREATE TABLE IF NOT EXISTS $cjtable (
	        id INT(4) NOT NULL AUTO_INCREMENT,
			email VARCHAR(80) NOT NULL,
			title VARCHAR(200) NOT NULL,
			url VARCHAR(250) NOT NULL,
			info TEXT NOT NULL,
			status VARCHAR(10) NOT NULL,
			PRIMARY KEY id (id)
	    );";
	$wpdb->query($structure);
}
// ADD ADMIN MENU AND PAGES
add_action('admin_menu', 'cladminmenu');
function cladminmenu(){
	add_submenu_page('options-general.php', 'Community Links', 'Community Links', 8, 'community-links', 'cl_options');
}
function cl_options(){
	include('assets/cjoptions.php');
}
add_action('admin_head', 'cjcl_admin_scripts');
function cjcl_admin_scripts(){
	global $clpath;
	echo '
	<link rel="stylesheet" type="text/css" media="screen" href="'.$clpath.'cladmin.css" />
	<script type="text/javascript" src="'.$clpath.'cladmin.js"></script>
	';
}
/*** PLUGIN SETTINGS ******/
/**** GET SAVED VALUES **************************/
function cltop($mykey){
	global $wpdb, $settingsname, $clshortname;
	$sopt = get_option($settingsname);
	$mykey = $clshortname.$mykey;
	foreach($sopt as $key=>$opt){
		if($key == $mykey){
			return $opt;
		}
	}
}
/**** Show Feeds **************************/
function clinit($content) {
	global $wpdb, $cjtable, $clpath;
		$clpage_id = cltop('clpage_id');
        if(!is_page($clpage_id)) {
        	echo "";
		}else{
			$follow = cltop('follow_links');
			$newwindow = cltop('link_window');
			$cutlink = cltop('clcutlink');
			if($follow == "Yes"){ $followlink = ''; }else{ $followlink = 'rel="nofollow"'; }
			if($newwindow == "Yes"){ $target = 'target="_blank"'; }else{ $target = ''; }
			$content.= '
            <span class="claddlink"><a class="showform" href="#addlink">Add Link</a></span> 
			<span class="clall"><a href="'.get_bloginfo('home').'?page_id='. cltop('clpage_id').'" title="">More</a></span>
			<span class="clfeed"><a href="'. $clpath.'feed.php" title="">Feed</a></span>
			';
            $links = $wpdb->get_results("SELECT * FROM $cjtable where status = 1 ORDER BY ID DESC", ARRAY_A);
			$showinfo = cltop('show_info');
            if ($links == "") {
                $content.= '<ul class="cjul"><li>No link found, Submit a link.</li></ul>';
            } else {
                foreach ($links as $linkinfo) {
                    if ($showinfo == "Hide") {
                        $content.= '<ul class="cjul"><li><a '.$target.' '.$followlink.' href="'.$linkinfo['url'].'" title=""><b>'.$linkinfo['title'].'</b></a></li></ul>';
                    } else {
                        $content.= '<ul class="cjul"><li><a '.$target.' '.$followlink.' href="'.$linkinfo['url'].'" title=""><b>'.$linkinfo['title'].'</b></a><p>'.$linkinfo['info'].'</p></li></ul>';
                    }
                }
            }
        }
        return $content;
}
add_filter ('the_content', 'clinit');
// ADD STYLESHEETS / SCRIPTS
add_action('wp_head', 'clscripts');
function clscripts(){
	global $clpath;
	echo '<link rel="stylesheet" href="'.$clpath.'theme.css" media="screen" />'."\n";
	echo '<script type="text/javascript" src="'.$clpath.'jquery-1.3.2.min.js"></script>'."\n";
	echo '<script type="text/javascript" src="'.$clpath.'theme.js"></script>'."\n";
	echo '
	<style type="text/css">
	.clfeed{
		background:url('.$clpath.'/images/rss.png) no-repeat;
		padding:0 5px 3px 19px;
	}
	.claddlink{
		background:url('.$clpath.'/images/addlink.png) no-repeat;
		padding:0 5px 3px 17px;
	}
	.clall{
		background:url('.$clpath.'/images/more.png) no-repeat;
		padding:0 5px 3px 17px;
	}
	</style>
	'."\n";
}
add_action('wp_footer', 'clshowform');
function clshowform(){ 
global $clpath;
?>
<div id="clbody" class="clhidden"></div>
<div id="cjsubmitform">
    <h2 class="cjhead">Submit your link<img id="closeform" src="<?php echo $clpath; ?>images/close.jpg" alt="Close" /></h2>
    <div id="cjresponse">
    </div>
    <div id="cjerror">
    </div>
    <form class="aform" action="<?php echo $clpath; ?>cjfunctions.php?action=submitlink#cjresponse" method="post" id="submitlink">
        <p>
            Link Title<br />
            <input type="text" name="title" id="cltitle" value="" /> <small>(required)</small>
        </p>
        <p>
            Link URL<br />
            <input type="text" name="url" id="clurl" value="" /> <small>(required)</small>
        </p>
        <p>
        	Your Email Address <br />
            <input type="text" name="email" id="clemail" value="" /> <small>(will not be published) (required)</small>
        </p>
        <p>
            Link Description  <small>(required)</small><br />
            <textarea name="info" rows="5" cols="30" id="clinfo" onKeyDown="limitText(this,<?php echo cltop('clcuttext'); ?>);" onKeyUp="limitText(this,<?php echo cltop('clcuttext'); ?>);"></textarea>
			<br />
			(Maximum <?php echo cltop('clcuttext'); ?> characters.)
        </p>
		<p>
			Are you human?  <small>(required)</small><br />
			2 + 2 = 
			<input type="text" name="answer" id="clanswer" value="" />
		</p>
        <p>
            <input type="submit" name="submitlink" value="Submit Link" />
        </p>
    </form>
</div><!-- /cl submitform -->
<?php } ?>
<?php
/** CUSTOM SIDEBAR WIDGET **/
function cj_community_links($args){
	global $wpdb, $clpath, $cjtable, $plugin_url;
	extract($args); echo $before_widget; echo $before_title . cltop('clwidget_heading') . $after_title; ?>
    <div id="clwidget">
        <div class="cjlinks">
            <span class="claddlink"><a class="showform" href="#addlink">Add Link</a></span> 
			<span class="clall"><a href="<?php bloginfo('home'); ?>?page_id=<?php echo cltop('clpage_id'); ?>" title="">More</a></span>
			<span class="clfeed"><a href="<?php echo $clpath; ?>feed.php" title="">Feed</a></span>
			<noscript><p style="padding:7px 0 0 0; color:red;">Javascript must be enabled.</p></noscript>
        </div>
        <!-- /cjlinks -->
        <div id="cjlinkfeed">
            <ul id="cjul">
                <?php
				$limitlinks = cltop('cllink_number');
				$showinfo = cltop('show_info');
				$follow = cltop('follow_links');
				$newwindow = cltop('link_window');
				$cutlink = cltop('clcutlink');
				if($follow == "Yes"){ $followlink = ''; }else{ $followlink = 'rel="nofollow"'; }
				if($newwindow == "Yes"){ $target = 'target="_blank"'; }else{ $target = ''; }
				$links = $wpdb->get_results("SELECT * FROM $cjtable where status = 1 ORDER BY ID DESC LIMIT $limitlinks", ARRAY_A);
				if($links == ""){
					echo "<li>No link found, Submit a link.</li>";
				}else{
	                foreach ($links as $linkinfo) {
	                	if(strlen($linkinfo['title']) > $cutlink){
	                		$link_title = substr($linkinfo['title'],0,$cutlink).'..';
	                	}else{
	                		$link_title = $linkinfo['title'];
	                	}
	                	if($showinfo == "Hide"){
							echo '<li><a '.$followlink.' '.$target.' title="'.$linkinfo['title'].'" href="'.$linkinfo['url'].'">'.$link_title.'</a></li>';	                		
	                	}else{
	                		echo '<li><a '.$followlink.' '.$target.' title="'.$linkinfo['title'].'" href="'.$linkinfo['url'].'">'.$link_title.'</a><p>'.$linkinfo['info'].'</p></li>';
						}
	                }
				}
                ?>
            </ul>
            	<?php $creditlink = cltop('clcreditlink'); 
				if($creditlink == "Remove Link"){
					echo '
					<p style="display:none" align="center">
		                <small>
		                	<a href="'.$plugin_url.'" title="Community Links Feed Plugin">Get This Widget</a>
		                </small>
					</p>
					';
				}else{
					echo '
					<p style="padding:7px;" align="center">
		                <small>
		                	<a href="'.$plugin_url.'" title="Community Links Feed Plugin">Get This Widget</a>
		                </small>
					</p>
					';					
				}
				?>
        </div>
        <!-- /cjlinkfeed -->
    </div><!-- /cl widget -->
<?php echo $after_widget;} register_sidebar_widget('Community Links', 'cj_community_links'); ?>
<?php
// Update version 1.02
// ADD ADMIN DASHBOARD PANEL
/**
 * use hook, to integrate new widget
 */
add_action('wp_dashboard_setup', 'sp_dashboard_setup');

/**
 * add Dashboard Widget via function wp_add_dashboard_widget()
 */
function sp_dashboard_setup() {
	wp_add_dashboard_widget( 'sp_wp_dashboard', __( 'Community Links Feed' ), 'sp_dashboard' );
}
function sp_dashboard(){ ?>
<?php 
global $wpdb, $cjtable, $clpath;
$links = $wpdb->get_results("SELECT * FROM $cjtable where status = 0 ORDER BY ID DESC", ARRAY_A);
if ($links == "") {
    echo "<p>No new links </p>";
	echo '<p><a href="'.get_bloginfo('wpurl').'/wp-admin/options-general.php?page=community-links">Configure Plugin</a></p>';
} else {
	echo '<table width="100%">';
    foreach ($links as $data) {
        echo '
	        <tr id="'.$data['id'].'">
	            <td class="pending clborder">
					<div>
		             <a href="'.$data['url'].'"><b>'.$data['title'].'</b></a><small> - <i>'.$data['email'].'</i></small>
					 <p>'.$data['info'].'</p>
					 <p class="useraction">
					 <a class="ajaxlink" href="'.$clpath.'cjfunctions.php?action=approve&id='.$data['id'].'#'.$data['id'].'">Approve</a> 
					 | 
					 <a class="ajaxlink" href="'.$clpath.'cjfunctions.php?action=remove&id='.$data['id'].'#'.$data['id'].'">Delete</a>
					 </p>
					 </div>
	            </td>
	        </tr>
			';
    }
	echo "</table>";
	echo '<p><a href="'.get_bloginfo('wpurl').'/wp-admin/options-general.php?page=community-links">Configure Plugin</a></p>';
}
?>
<?php } ?>