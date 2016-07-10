<?php
require_once 'core/init.php';
if(Input::exists()) {
	if (Token::check(Input::get('token'))) {
		$validate = new Validate();
		$validaton = $validate->check($_POST, array(
			'email' => array('required' => true),
			'password' => array('required' => true)
			));
		if ($validaton->passed()) {
			$user = new User();
			$remember = (Input::get('remember') === 'on') ? true : false;
			$login = $user->login(Input::get('email'), Input::get('password'), $remember);
			if ($login) {
				Session::flash('home', 'You have successfully logged in.');
				Redirect::to('index.php');
			} else {
				echo '<p>Sorry, loggin in failed.</p>';
			}
		} else {
			foreach ($validaton->errors() as $error) {
				echo $error, '<br>';
			}
		}
	}
}
?>
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<link rel="stylesheet" href="includes/css/style.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<body>
	<div class="container-fluid">
		<div class="jumbotron">
			<div class="text-center">
				<div class="a">
					<form action="" method="post" role="form">
						<div class="well well-sm">
						<label for="email"><font face="Arial">Email</font></label>
							<input type="email" name="email" id="email" autocomplete="off" placeholder="Email" autofocus>
						</div>
						<div class="well well-sm">
							<label for="password"><font face="Arial">Password</font>
								<input type="password" name="password" id="password" autocomplete="off" placeholder="Password">
							</label>
						</div>
						<div class="form-group" style="margin-right: 10px;">
							<label for="remember">
								<input type="checkbox" name="remember" id="remember"> Remember me
							</label>
							<input class ="btn btn-lg btn-success" style="margin-left: 30px;" class="" type="submit" value="Submit">
						</div>
						<div class="form-group">
							<a href='oauth.php?type=google'><img src="includes/images/login_with_google.png" height="40px" width="200px"></a>
						</div>
						<div class="form-group">
							<a href='oauth.php?type=facebook'><img src="includes/images/login_with_facebook.png" height="40px" width="200px"></a>
						</div>
						<div class="form-group">
							<a href='oauth.php?type=twitter'><img src="includes/images/login_with_twitter.png" height="40px" width="200px"></a>
						</div>
						<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
					</form>
				</div>
			</div>
		</div>
	</div>
</body>