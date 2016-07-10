<?php
require_once 'core/init.php';
if (Input::exists('get')) {
	require_once 'functions/facebook-php-login/vendor/autoload.php';
	$fb = new Facebook\Facebook([
		'app_id' => Config::get('facebook/app_id'),
		'app_secret' => Config::get('facebook/app_secret'),
		'default_graph_version' => Config::get('facebook/default_graph_version'),
		]);
	$helper = $fb->getRedirectLoginHelper();
	$_SESSION['FBRLH_state']=$_GET['state'];
	try {
		$accessToken = $helper->getAccessToken();
	} catch(Facebook\Exceptions\FacebookResponseException $e) {
		exit("<p>".$e->getMessage()."</p>");
	} catch(Facebook\Exceptions\FacebookSDKException $e) {
		exit("<p>".$e->getMessage()."</p>");
	}

	if (isset($accessToken)) {

		$UserData = $fb->get('me?fields=id,name,email,gender,last_name,first_name,picture.height(200).width(200)', (string) $accessToken);
		$UserData = $UserData->getGraphNode()->asArray();
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
					'profile_pic_url' => $UserData['picture']['url'],
					'password' => '',
					'salt' => '',
					'joined' => date('Y-m-d H:i:s'),
					'user_type' => 'Facebook',
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
		unset($_SESSION['FBRLH_state']);
		exit();	
	}
} else {
	Session::flash('error');
	Redirect::to('404');
}
?>