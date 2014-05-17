<?php
if (isset($_GET['settings'])) {
	$blog_settings_button = '
		Settings
		<style>
			#sidebar .snav li a.current:link,
			#sidebar .snav li a.current:visited,
			#sidebar .snav li a.current:active,
			#sidebar .snav li a.current:hover
				{background:#CF3805 !important;margin-left:13px !important;padding-left:15px !important;border-radius:3px !important;}
			#sidebar .snav li a.current ul li:hover {cursor:pointer;}
		</style>
		<ul style="font-size:9px;margin:5px 0 0 10px;">
			<li onClick="alert(\'General Settings\');">&bull; General Settings</li>
			<li onClick="alert(\'RSS Feeds\');">&bull; RSS Feeds</li>
			<li onClick="alert(\'Social Integration\');">&bull; Social Integration</li>
			<li onClick="alert(\'Advertising\');">&bull; Advertising</li>
			<li onClick="alert(\'Customisation\');">&bull; Customisation</li>
			<li onClick="alert(\'URL Config\');">&bull; URL Config</li>
		</ul>
	';
} else {
	$blog_settings_button = 'Settings';
}