<?php
require_once( dirname(__FILE__) . '../../../../../wp-load.php');
ob_start();
global $wpdb, $clshortname, $settingsname, $optionspageheading, $optionspagemsg;
require('cjinfo.php');
$cjoptions = array(
		    array(  
				"oid" => $clshortname."basic_configuration",
				"oclass" => "cl_basic_config",
				"oname" => "Plugin Settings &raquo;",
				"oinfo" => '',
				"otype" => "heading",
			    "ovalue" => 'Plugin Settings &raquo;'),
			array(
				"oid" => $clshortname."clpage_id",
				"oclass" => "cl_basic_config clhidden",
				"oname" => "Select More Links Page",
				"oinfo" => 'Enter the page ID where you want to show all the links. e.g. 2',
				"otype" => "text",
			    "ovalue" => 'enter page id'),
			array(
				"oid" => $clshortname."clwidget_heading",
				"oclass" => "cl_basic_config clhidden",
				"oname" => "Sidebar Widget Heading",
				"oinfo" => 'Specify the sidebar widget heading.',
				"otype" => "text",
			    "ovalue" => 'Widget Heading'),
			array(
				"oid" => $clshortname."clcutlink",
				"oclass" => "cl_basic_config clhidden",
				"oname" => "Limit Link Title",
				"oinfo" => 'Enter the number of allowed characters for link title.',
				"otype" => "text",
			    "ovalue" => '25'),
			array(
				"oid" => $clshortname."clcuttext",
				"oclass" => "cl_basic_config clhidden",
				"oname" => "Limit Link Description",
				"oinfo" => 'Enter the number of allowed characters for link description.',
				"otype" => "text",
			    "ovalue" => '250'),
			array(
				"oid" => $clshortname."follow_links",
				"oclass" => "cl_basic_config clhidden",
				"oname" => "Remove 'nofollow'",
				"oinfo" => 'Would you like to remove rel="nofollow" from the links?',
				"otype" => "radiobox",
			    "ovalue" => array("Yes", "No")),
			array(
				"oid" => $clshortname."link_window",
				"oclass" => "cl_basic_config clhidden",
				"oname" => "Open Links in New Window",
				"oinfo" => 'Would you like to open the links in New Window?',
				"otype" => "radiobox",
			    "ovalue" => array("Yes", "No")),
			array(
				"oid" => $clshortname."show_info",
				"oclass" => "cl_basic_config clhidden",
				"oname" => "Show Link Description",
				"oinfo" => 'Show or Hide Link Description',
				"otype" => "radiobox",
			    "ovalue" => array('Show', 'Hide')),
			array(
				"oid" => $clshortname."cllink_number",
				"oclass" => "cl_basic_config clhidden",
				"oname" => "Limit number of links<br />Sidebar widget",
				"oinfo" => 'Specify how many links you want to show in the sidebar widget.',
				"otype" => "text",
			    "ovalue" => '5'),
			array(
				"oid" => $clshortname."clcreditlink",
				"oclass" => "cl_basic_config clhidden",
				"oname" => "Credit Link",
				"oinfo" => 'We request you to keep the credit link in the sidebar widget to support us. However you can choose to remove the link.',
				"otype" => "select",
			    "ovalue" => array('Keep Supporting', 'Remove Link')),
);
/***************************************************/
/**** INNITIATE SETTINGS ***************************/
/***************************************************/
foreach($cjoptions as $value){
	$saveoptions[$value['oid']] = $value['ovalue'];
}
add_option($settingsname, $saveoptions);
/**** SAVE SETTINGS ***************************/
if (isset($_REQUEST['cjsave'])) {
    foreach ($cjoptions as $value) {
        $saveoptions[$value['oid']] = $_REQUEST[$value['oid']];
    }
update_option($settingsname, $saveoptions);
echo '<div id="message" class="updated fade"><p><strong>Settings updated.</strong></p></div>';
}
/**** RESET SETTINGS / DELETE OPTIONS ***************************/
if(isset($_REQUEST['cjreset'])){
delete_option($settingsname);
foreach($cjoptions as $value){
	$saveoptions[$value['oid']] = $value['ovalue'];
}
update_option($settingsname, $saveoptions);
echo '<div id="message" class="updated fade"><p><strong>Settings updated.</strong></p></div>';
}
/**** GET SAVED VALUES **************************/
function gop($mykey){
global $settingsname;
$sopt = get_option($settingsname);
	foreach($sopt as $key=>$opt){
		if($key == $mykey){
			return $opt;
		}
	}
}
?>
<div class="wrap cjwrap">
<div class="icon32" id="icon-options-general"><br></div>
<h2><?php echo $optionspageheading; ?></h2>
<p>
	<?php echo $optionspagemsg; ?>
</p>
<form action="" method="post">
<table class="widefat post fixed" cellspacing="0">
<thead>

<?php
foreach($cjoptions as $key){ ?>
<?php if($key['otype'] == "heading"){ ?>
	<tr>
	<th id="<?php echo $key['oclass']; ?>" class="cjadminhead"><?php echo $key['ovalue']; ?></th>
	</tr>	
<?php } ?>

<?php if($key['otype'] == "text"){ ?>
	<tr class=" <?php echo $key['oclass']; ?>"><td>
		<label class="cfield"><?php echo $key['oname']; ?></label>
		<input size="35" name="<?php echo $key['oid']; ?>" type="text" value="<?php echo gop($key['oid']); ?>" />
		<span class="cdesc"><?php echo $key['oinfo']; ?></span>
	</td></tr>	
<?php } ?>

<?php if($key['otype'] == "info"){ ?>
	<tr class=" <?php echo $key['oclass']; ?>"><td>
		<label class="cfield"><?php echo $key['oname']; ?></label>
		<span class="cdesc"><?php echo $key['ovalue']; ?></span>
	</td></tr>	
<?php } ?>

<?php if($key['otype'] == "textarea"){ ?>
	<tr class=" <?php echo $key['oclass']; ?>"><td>
		<label class="cfield"><?php echo $key['oname']; ?></label>
		<textarea rows="5" cols="50" name="<?php echo $key['oid']; ?>"><?php echo gop($key['oid']); ?></textarea>
		<span class="cdesc"><?php echo $key['oinfo']; ?></span>
	</td></tr>	
<?php } ?>

<?php if($key['otype'] == "select"){ ?>
	<tr class=" <?php echo $key['oclass']; ?>"><td>
		<label class="cfield"><?php echo $key['oname']; ?></label>
		<?php $soptions = $key['ovalue']; ?>
		<select class="cjselect" name="<?php echo $key['oid']; ?>">
			<option value="Please Select">Please Select</option>
			<?php
			foreach($soptions as $svalue){ ?>
				<option <?php if($svalue == gop($key['oid'])){echo 'selected="selected"';} ?> value="<?php echo $svalue ?>"><?php echo $svalue ?></option>
			<?php }
			?>
		</select>
		<span class="cdesc"><?php echo $key['oinfo']; ?></span>
	</td></tr>	
<?php } ?>

<?php if($key['otype'] == "radiobox"){ ?>
	<tr class="cjhidden <?php echo $key['oclass']; ?>"><td>
		<label class="cfield"><?php echo $key['oname']; ?></label>
		<?php $roptions = $key['ovalue']; ?>
			<?php
			foreach($roptions as $rvalue){ ?>
				<input type="radio" name="<?php echo $key['oid']; ?>" <?php if($rvalue == gop($key['oid'])){echo 'checked="checked"';} ?> value="<?php echo $rvalue; ?>" /> <?php echo $rvalue; ?>
			<?php }
			?>

		<span class="cdesc"><?php echo $key['oinfo']; ?></span>
	</td></tr>	
<?php } ?>

<?php } //Main Loop Ends ?>
<tr>
	<td style="padding:10px;">
		<input name="cjsave" class="button-primary" type="submit" value="Save Settings" />
		<input name="cjreset" class="button" type="submit" value="Restore Defaults" />
	</td>
</tr>
</thead>
</table>
</form>
<br />

<div id="icon-tools" class="icon32"><br></div>
<h2 class="title">Manage User Submissions</h2>
<table class="widefat post fixed" cellspacing="0">
    <thead>
        <tr>
            <th>
                Pending Approval &raquo;
            </th>
        </tr>
<?php 
$links = $wpdb->get_results("SELECT * FROM $cjtable where status = 0 ORDER BY ID DESC", ARRAY_A);
if ($links == "") {
    echo "<tr><td>No new links.</td></tr>";
} else {
    foreach ($links as $data) {
        echo '
	        <tr id="'.$data['id'].'">
	            <td class="pending">
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
}
?>
    </thead>
</table>


<br />

<table class="widefat post fixed" cellspacing="0">
    <thead>
        <tr>
            <th id="approvedlinks">
                Approved Links &raquo;
            </th>
        </tr>
<?php 
$links = $wpdb->get_results("SELECT * FROM $cjtable where status = 1 ORDER BY ID DESC", ARRAY_A);
if ($links == "") {
    echo "<tr><td>No new links.</td></tr>";
} else {
    foreach ($links as $data) {
        echo '
	        <tr class="approvedlinks clhidden" id="'.$data['id'].'">
	            <td class="pending">
				<div>
	             <a href="'.$data['url'].'"><b>'.$data['title'].'</b></a><small> - <i>'.$data['email'].'</i></small>
				 <p>'.$data['info'].'</p>
				 <p class="useraction">
				 <a class="ajaxlink" href="'.$clpath.'cjfunctions.php?action=remove&id='.$data['id'].'#'.$data['id'].'">Delete</a>
				 </p>
				 </div>		    
	            </td>
	        </tr>
			';
    }
}
?>
<tr>
	<td style="padding:15px 10px 10px 10px; color:#990000;">
		<a class="removelinks button" href="<?php echo $clpath;  ?>cjfunctions.php?action=removeall#removedall" class="button">Remove All Links</a>
		<div id="removedall"><b>Note:</b> This will remove all saved links. Be Careful!</div>
	</td>
</tr>
    </thead>
</table>



<div class="cldonate">
<p style="width:40%; text-align:left; float:left; margin:0px !important; ">
<a target="_blank" href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=8906676" title="Donate">
	<img src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" alt="PayPal - The safer, easier way to pay online!" />
</a>
</p>
<p style="width:40%; float:right; text-align:right; margin:0px !important; ">
<a href="http://www.cssjockey.com" title="CSSJockey">
	<img src="http://www.cssjockey.com/files/cj-logo.png" alt="CSSJockey" height="60" />
</a>
</p>
</div>

<br /><br /><br /><br />
</div><!-- /wrap -->