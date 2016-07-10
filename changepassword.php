<?php
require_once 'core/init.php';
$user = new User();
if (!$user->isLoggedIn()) {
	Redirect::to('index.php');
}
if (Input::exists()) {
	if (Token::check(Input::get('token'))) {
		$validate = new Validate();
		$validation = $validate->check($_POST, array(
			'current_password' => array(
				'required' => true,
				'min' => 8
				),
			'new_password' => array(
				'required' => true,
				'min' => 8
				),
			'password_again' => array(
				'required' => true,
				'matches' => 'new_password',
				'min' => 8
				)
			));
		if ($validation->passed()) {
			try {
				if(Hash::make(Input::get('current_password'), $user->data()->salt) != $user->data()->password) {
					echo 'Invalid current password.';
				} else {
					$salt = Hash::salt(32);
					$user->update(array(
						'password' => Hash::make(Input::get('new_password'), $salt),
						'salt' => $salt
						));
					Session::flash('home', 'Your password have been updated.');
					Redirect::to('index.php');
				}
			} catch (Exception $e) {
				die($e->getMessage());
			}
		} else {
			foreach ($validation->errors() as $error) {
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
							<label for="current_password">Current Password
								<input type="password" name="current_password" id="current_password" value="" autocomplete="off">
							</label>
						</div>
						<div class="well well-sm">
							<label for="new_password">New password
								<input type="password" name="new_password" id="new_password" value="" autocomplete="off">
							</label>
						</div>
						<div class="well well-sm">
							<label for="password_again">Password again
								<input type="password" name="password_again" id="password_again" value="" autocomplete="off">
							</label>
						</div>
						<input class="btn btn-danger btn-lg" type="submit" value="Change">
						<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
					</form>
				</div>
			</div>
		</div>
	</div>
</body>