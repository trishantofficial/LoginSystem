<?php

session_start();
$GLOBALS['config'] = array(
	'mysql' => array(
		'host' => '127.0.0.1',
		'username' => 'root',
		'password' => '',
		'db' => 'custom_login'
	),
	'remember' => array(
		'cookie_name' => 'hash',
		'cookie_expiry' => 604800
	),
	'session' => array(
		'session_name' => 'user',
		'token_name' => 'token'
	),
	'facebook' => array(
		'app_id' => '1738918823016435', // Replace {app-id} with your app id
		'app_secret' => '06840dd6ce6b6f9e4a3e57e6abdda6ee',
  		'default_graph_version' => 'v2.6',
	),
	'google' => array(
		'ApplicationName' => 'Custom_Login_System',
		'ClientId' => '128446928289-207tkllpda1ap1jek3iuad230llcjq50.apps.googleusercontent.com',
		'ClientSecret' => 'b8u_AZ2JJHb2s_zqNDtizla-',
	),
	'twitter' => array(
		'ConsumerKey' => 'IDA3a2UcaVC1PCZYmG7PUziqM',
		'ConsumerSecret' => 'JmeROQDoSBRRcoyQrhpZWEjiZIGrUVxVhcHvplZy3YWBEKIf2S',
		'AccessToken' => '2343847066-LBC4Dqej647KHVWWfzqcDbam3VRprAmoPFkGyvZ',
		'AccessTokenSecret' => 'zgu0Y5DQda6vZO7otp5XEhyifD7P2vYnPzMwog51YIFMV',
	)
);
spl_autoload_register(function($class) {	
	require_once ($_SERVER['DOCUMENT_ROOT'] . '/Custom_site/classes/' . $class . '.php');
});
require_once($_SERVER['DOCUMENT_ROOT'] . '/Custom_site/functions/sanitize.php');
if(Cookie::exists(Config::get('remember/cookie_name')) && !Session::exists(Config::get('session/session_name'))) {
	$hash = Cookie::get(Config::get('remember/cookie_name'));
	$hashCheck = DB::getInstance()->get('users_session', array('hash', '=', $hash));
	if ($hashCheck->count()) {
		$user = new User($hashCheck->first()->user_id);
		$user->login();
	}
}
?>