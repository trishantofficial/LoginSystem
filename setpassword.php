<?php
require_once 'core/init.php';
$user = new User();
if (Input::exists()) {
	if (Token::check(Input::get('token'))) {
		$validate = new Validate();
		$validation = $validate->check($_POST, array(
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
				$salt = Hash::salt(32);
				if($user->find(Session::get('user'))) {
					$user->update(array(
						'password' => Hash::make(Input::get('new_password'), $salt),
						'salt' => $salt
						));
					Session::delete('user');
					$user->ologin($user->data()->email);
					Session::flash('home', 'Your have successfully registered.');
					Redirect::to('index.php');
				} else {
					// echo 'lol';

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
<form action="" method="post">
	<div class="field">
		<label for="new_password">New password
			<input type="password" name="new_password" id="new_password" value="" autocomplete="off">
		</label>
	</div>
	<div class="field">
		<label for="password_again">Password again
			<input type="password" name="password_again" id="password_again" value="" autocomplete="off">
		</label>
	</div>
	<input type="submit" value="Set">
	<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
</form>