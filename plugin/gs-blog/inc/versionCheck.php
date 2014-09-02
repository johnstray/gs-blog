<?php if(!defined('IN_GS')){die('You cannot load this file directly!');} // Security Check
/**
* Version Checking Function
* Returns the latest version of the plugin from Extend API
* 
* @return array 
* [0] = Status Message                (string)  current, update, beta, unknown
* [1] = Current GS Blog Version       (float)   version
* [2] = Version on Extend             (float)   version
* [3] = Update message if available.  (string)  message
*/
function blog_version_check() {
	$current_version = BLOGVERSION;
	define('LANGFILE',BLOGFILE);
	
	// Let's pre-create the array, in case anything goes horribly wrong...
	$return = array();
	$return[0] = 'unknown';
	$return[1] = $current_version;
	$retuen[2] = '0.0.0';
	$return[3] = i18n_r(BLOGFILE.'/VERSION_NOMESSAGE');
	$return[4] = NULL;
	
	// Call to Extend API for information
	if (function_exists('file_get_contents')) {
		$api_call = file_get_contents('http://get-simple.info/api/extend/?id='.BLOGEXTENDID);
		if($api_call == NULL) { # API call failed :(
			$api_response->status = 'internal_error';
			$return[3] = i18n_r(LANGFILE.'/VERSION_NORESPONSE');
		} else { # API call successful :)
			$api_response = json_decode($api_call);
		}
	} else { # file_get_contents function doesn't exist? :|
		$api_response->status = 'internal_error';
		$return[3] = i18n_r(LANGFILE.'/VERSION_NOFUNCTION');
	}
	
	// Let's check for any update messages. (To be implemented at a later date!)
	function blog_update_message() {
    GLOBAL $current_version;
		$var = file_get_contents('http://update.johnstray.com/index.php?app=gs-blog&ver='.$current_version);
    return (!empty($var)) ? $var : NULL;
	}
	
	// If API call successful...
	if($api_response->status = 'successful') {
		$api_version = $api_response->version;
		$return[2] = $api_version;
	
		// What is the status? Are we up to date?
		if(version_compare($api_version, $current_version) == '1') {
			# An update is available! Please update me.
			$return[0] = 'update';
			define('BLOGVERSIONCLASS', 'WARNmsg');
			$return[3] = i18n_r(LANGFILE.'/VERSION_UPDATEAVAILABLE').' <a href="http://get-simple.info/extend/plugin/getsimple-blog/810/" target="_new">'.i18n_r(LANGFILE.'/DOWNLOAD').' v'.(string)$api_version.'</a>.';
			$return[4] = blog_update_message();
		}
		elseif (version_compare($api_version, $current_version) == '0') {
			# Currently up to date. There are no new version available.
			$return[0] = 'current';
			$return[3] = i18n_r(LANGFILE.'/VERSION_UPTODATE');
			define('BLOGVERSIONCLASS', 'OKmsg');
		}
		elseif (version_compare($api_version, $current_version) == '-1') {
			# Using a newer version than what is available. Must be a Beta...
			$return[0] = 'beta';
			$return[3] = i18n_r(LANGFILE.'/VERSION_BETA');
			define('BLOGVERSIONCLASS', 'INFOmsg');
		}
		
		// Shouldn't get here, but just in case...
		else {
			$return[0] = 'unknown';
			$return[3] = i18n_r(LANGFILE.'/VERSION_FAILEDCOMPARE');
			define('BLOGVERSIONCLASS', 'ERRmsg');
		}
		
	// API call wasn't successful... :(
	} elseif ($api_response->status != 'internal_error')  {
		$return[0] = 'unknown';
		$return[2] = '0.0.0';
		$return[3] = i18n_r(LANGFILE.'/VERSION_APIFAIL');
		define('BLOGVERSIONCLASS', 'ERRmsg');
	} else {
		// If we are here, an error message should already be set. But just in case...
		if($response[3] != NULL) {
			$response[3] = i18n_r(LANGFILE.'/VERSION_INTERNALERROR');
		}
		define('BLOGVERSIONCLASS', 'ERRmsg');
	}
	// OK, let's return all that information now.
	ksort($return);
	return $return;
}

/**
* Version Check Admin Page
* Update check page for the admin area.
*
* @return void
*/
function show_update_admin() {
	$current_version = BLOGVERSION;
	$update_data = blog_version_check();
	if($update_data[0] == 'current') {
		$updclass = 'OKmsg';
		$updstat = 'OK';
		$lvstat = 'OK';
		$lvclass = 'hint';
	} elseif ($update_data[0] == 'update') {
		$updclass = 'WARNmsg';
		$updstat = 'Update!';
		$lvstat = 'OK';
		$lvclass = 'hint';
	} elseif ($update_data[0] == 'beta') {
		$updclass = 'INFOmsg';
		$updstat = 'OK';
		$lvstat = 'OK';
		$lvclass = 'hint';
	} else {
		$updclass = 'hint';
		$updstat = 'OK';
		$lvstat = 'Error!';
		$lvclass = 'ERRmsg';
	}
?>
<h3 class="floated" style="float:left;"><?php i18n(LANGFILE."/VERSION_STATUS"); ?></h3>
<div class="edit-nav">
  <p class="text 1">
    &nbsp;
  </p>
  <div class="clear"></div>
</div>
<p class="text 2"><?php i18n(LANGFILE."/VERSION_STATUS_DESC"); ?></p>
<table class="highlight" style="margin-bottom:20px;">
	<tbody>
		<tr>
			<td colspan="2" style="text-align:center;"><span class="hint"><?php echo $update_data[3]; ?></span></td>
		</tr>
		<tr>
			<td><?php i18n(LANGFILE.'/VERSION_UPDATESTATUS'); ?></td>
			<td style="width:100px;text-align:center;">
				<span class="<?php echo $updclass; ?>"><?php echo $update_data[0]; ?></span><br />
			</td>
		</tr>
		<tr>
			<td><?php i18n(LANGFILE.'/VERSION_CURRENTVER'); ?></td>
			<td style="text-align:center;"><span class="<?php echo $updclass; ?>"><strong><?php echo $update_data[1]; ?></strong> - <?php echo $updstat; ?></span></td>
		</tr>
		<tr>
			<td><?php i18n(LANGFILE.'/VERSION_LATESTVER'); ?></td>
			<td style="text-align:center;"><span class="<?php echo $lvclass; ?>"><strong><?php echo $update_data[2]; ?></strong> - <?php echo $lvstat; ?></span></td>
		</tr>
	</tbody>
</table>
<div id="version_update_information" style="border:1px solid #eee;padding:20px;">
  <?php if ($update_data[4] != NULL) {echo $update_data[4];} ?>
</div>
<?php

}
