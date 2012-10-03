<?php
	$preload_settings = array("start_page", "default_language", "head_title", "head_description", "head_keywords");
	$allowed_uploads = array("jpg", "jpeg", "gif", "png", "pdf", "doc", "xls", "zip", "txt");
	$supported_languages = array(
		"en" => "English");

	$months_of_year = array("january", "february", "march", "april", "may", "june",
		"july", "august", "september", "october", "november", "december");
	$days_of_week = array("monday", "tuesday", "wednesday", "thursday", "friday",
		"saturday", "sunday");

	/* SINGLE SIGN-ON
	 *
	 * Create a user which has access to /system/sso. The variable $sso_username
	 * must be set to the username of this user. All users that want to use Single
	 * Sign-On must have access to /system/sso. The variable $sso_servers must
	 * contain the IP addresses of the webservers of the other SSO websites. Fill
	 * $sso_websites with the information of the other SSO websites.
	 */
	$sso_username = null;
	$sso_servers = array();
	$sso_websites = array(
/*
		array(
			"ipaddr"   => "IP address of other webserver",
			"server"   => "www.banshee_based_website.net",
			"username" => "user with access to ${server}/system/sso",
			"password" => "password",
			["ssl"     => true | false],
			["port"    => port number]),
		array(...),
*/
	);
?>
