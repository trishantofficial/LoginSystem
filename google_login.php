<?php
require_once 'core/init.php';
require_once 'functions/google-php-login/vendor/autoload.php';
$Client = new Google_Client();
$Client->setApplicationName(Config::get('google/ApplicationName'));
$Client->setClientId(Config::get('google/ClientId'));
$Client->setClientSecret(Config::get('google/ClientSecret'));
$Client->setRedirectUri('http://localhost/custom_site/google_login.php');
$Client->setScopes(array('https://www.googleapis.com/auth/plus.login', 'https://www.googleapis.com/auth/plus.me', 'https://www.googleapis.com/auth/userinfo.email'));
$me = new Google_Service_Oauth2($Client);
if(Input::exists('get')) {
	$Client->authenticate(Input::get('code'));
	$_SESSION['token'] = $Client->getAccessToken();
	//header('Location: ' . $Config['google']['login_url']);
}

if (isset($_SESSION['token'])) {
	$Client->setAccessToken($_SESSION['token']);
}

if ($Client->getAccessToken()) {

  // Get User data

	$UserData = array (
		'first_name' => $me->userinfo->get()->getGivenName(),
		'last_name' => $me->userinfo->get()->getFamilyName(),
		'email' =>  $me->userinfo->get()->getEmail(),
		'profile_image_url' => $me->userinfo->get()->getPicture(),
		'gender' => 'NoAccess',		/*Google doesn't permit access to user's gender*/
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
				'user_type' => 'Google',
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
	unset($_SESSION['token']);
	exit();

}
?>