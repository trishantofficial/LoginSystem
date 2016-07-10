<?php
require_once 'core/init.php';
require_once 'functions/twitter-php-login/vendor/autoload.php';
$connection = new Abraham\TwitterOAuth\TwitterOAuth(Config::get('twitter/ConsumerKey'), Config::get('twitter/ConsumerSecret'), Config::get('twitter/AccessToken'), Config::get('twitter/AccessTokenSecret'));
$content = $connection->get("account/verify_credentials");
$name = explode(' ', $content->name, 2);
$fname = $name[0];
$lname = $name[1];
 // Get User data

$UserData = array (
	'first_name' => $fname,
	'last_name' => $lname,
	'email' =>  $content->screen_name . '@twiiter.com', /*Twitter doesn't permit access to user's email, and gender*/
	'profile_image_url' => $content->profile_image_url,
	'gender' => 'NoAccess',
	);
$user = new User();
if($user->find($UserData['email'])) {
	$ologin = $user->ologin($UserData['email']);
	if ($ologin) {
		Session::flash('home', 'You have successfully logged in.');
		Redirect::to('index.php');
	} else {
		echo '<p>Sorry, loggin in failed.</p>';
	}
} else {
	try {
		$user->create(array(
			'first_name' => $UserData['first_name'],
			'last_name' => $UserData['last_name'],
			'email' => $UserData['email'],
			'gender' => $UserData['gender'],
			'profile_pic_url' => $UserData['profile_image_url'],
			'password' => '',
			'salt' => '',
			'joined' => date('Y-m-d H:i:s'),
			'user_type' => 'Twitter',
			));
		Session::put('user', $UserData['email']);
		$user->setPassword();
		echo '<script>var window; setTimeout (window.close, 1);</script>';
				//Session::flash('home', 'You have successfully been registered!');
				//Redirect::to('index.php');
	} catch (Exception $e) {
		die($e->getMessage());
	}
}

?>