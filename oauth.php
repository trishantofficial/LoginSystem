<?php
require_once 'core/init.php';
if(Input::exists('get')) {
	if(Input::get('type') == 'facebook') {
		require_once 'functions/facebook-php-login/vendor/autoload.php';
		$fb = new Facebook\Facebook([
  			'app_id' => Config::get('facebook/app_id'),
			'app_secret' => Config::get('facebook/app_secret'),
  			'default_graph_version' => Config::get('facebook/default_graph_version'),
  		]);
  		$redirectUrl = $_SERVER['DOCUMENT_ROOT'] . '/LoginSystem/facebook_login.php';
		$helper = $fb->getRedirectLoginHelper();
		$permissions = ['email']; 
		Redirect::to($helper->getLoginUrl($redirectUrl, $permissions));
	} elseif (Input::get('type') == 'google') {
		require_once 'functions/google-php-login/vendor/autoload.php';
		$Client = new Google_Client();
		$Client->setApplicationName(Config::get('google/ApplicationName'));
		$Client->setClientId(Config::get('google/ClientId'));
		$Client->setClientSecret(Config::get('google/ClientSecret'));
		$Client->setRedirectUri($_SERVER['DOCUMENT_ROOT'] . '/LoginSystem/google_login.php');
		$Client->setScopes('email');
		Redirect::to($Client->createAuthUrl());
	} elseif(Input::get('type') == 'twitter') {
		Redirect::to('twitter_login.php');
	} else {
		Session::flash('error');
		Redirect::to('404');
	}
} else {
	Session::flash('error');
	Redirect::to('404');
}
?>