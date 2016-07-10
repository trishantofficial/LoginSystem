<?php
require_once 'core/init.php';
if(Input::exists()) {
	if(Token::check(Input::get('token'))){
		$validate = new Validate();
		$validation = $validate->check($_POST, array(
			'first_name' => array(
				'required' => true,
				'max' => 30
				),
			'last_name' => array(
				'required' => true,
				'max' => 30
				),
			'email' => array(
				'required' => true,
				'unique' => 'users'
				),
			'password' => array(
				'required' => true,
				'min' => 8
				),
			'confirm_password' => array(
				'required' => true,
				'matches' => 'password'
				),
			));
		if($validation->passed()) {
			$user = new User();
			$salt = Hash::salt(32);
			try {
				$user->create(array(
					'first_name' => Input::get('first_name'),
					'last_name' => Input::get('last_name'),
					'email' => Input::get('email'),
					'password' => Hash::make(Input::get('password'), $salt),
					'salt' => $salt,
					'joined' => date('Y-m-d H:i;s'),
					'user_type' => 'Register Link',
					'gender' => Input::get('gender'),
					'profile_pic_url' => '',
					));
				Session::flash('home', 'You have successfully been registered!');
				Redirect::to('index.php');
			} catch (Exception $e) {
				die($e->getMessage());
			}
		} else {
			foreach ($validation->errors() as $error) {
				echo $error,'<br>';
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

				<form action="" method="post" role="form">
					<div class="well well-sm">
						<label for="first_name">First Name
							<input type="text" name="first_name" value="<?php echo escape(Input::get('first_name')); ?>" autocomplete="off">		
						</label>
					</div>
					<div class="well well-sm">
						<label for="last_name">Last Name
							<input type="text" name="last_name" value="<?php echo escape(Input::get('last_name')); ?>" autocomplete="off">		
						</label>
					</div>
					<div class="well well-sm">
						<label for="email">Email
							<input type="email" name="email" value="<?php echo escape(Input::get('email')); ?>" autocomplete="off">		
						</label>
					</div>
					<div class="well well-sm">
						<label for="Password">Password
							<input type="password" name="password" value="" autocomplete="off">		
						</label>
					</div>
					<div class="well well-sm">
						<label for="Confirm_Password">Confirm Password
							<input type="password" name="confirm_password" value="" autocomplete="off">		
						</label>
					</div>
					<div class="well well-sm">
						<label for="Gender">Gender
							<input type="radio" name="gender" value="Male">Male
							<input type="radio" name="gender" value="Female">Female
						</label>
					</div>
					<!--   api for imgur to upload pic.       -->
					<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
					<input class="btn btn-primary btn-lg" type="submit" name="submit">
				</form>
			</div>
		</div>
	</div>
</body>				