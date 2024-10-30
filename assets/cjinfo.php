<?php
ob_start();
// Pre-2.6 compatibility
if ( !defined('WP_CONTENT_URL') )
    define( 'WP_CONTENT_URL', get_option('siteurl') . '/wp-content');
if ( !defined('WP_CONTENT_DIR') )
    define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
// Guess the location
$clpath = WP_CONTENT_URL.'/plugins/'.plugin_basename(dirname(__FILE__)).'/';
/**** PLUGIN INFO ********/
$cjtable = $wpdb->prefix.'cjcommunitylinks';
$plugin_url = "http://www.cssjockey.com/freebies/community-links-feed-wordpress-plugin";
$clshortname = 'cjcl';
$settingsname = 'community_links';
$optionspageheading = "Configure Plugin Settings";
$optionspagemsg = 'This plugin will install the Community Links Feed widget where users can submit links to your website.';
?>