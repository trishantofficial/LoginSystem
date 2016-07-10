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
				'unique' => 'users',
				)
			));
		if ($validation->passed()) {
			try {
				$user->update(array(
					'first_name' => Input::get('first_name'),
					'last_name' => Input::get('last_name'),
					'email' => Input::get('email'),
					));
				Session::flash('home', 'Your details have been updated.');
				Redirect::to('index.php');
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
				<form action="" method="post" rol="form">
						<div class="well well-sm">
							<label>First Name
								<input type="text" name="first_name" id="first_name" value="<?php echo escape($user->data()->first_name); ?>" autocomplete="off">
							</label>
						</div>
						<div class="well well-sm">
							<label>Last Name
								<input type="text" name="last_name" id="last_name" value="<?php echo escape($user->data()->last_name); ?>" autocomplete="off">
							</label>
						</div>
						<div class="well well-sm">
							<label>Email
								<input type="email" name="email" id="email" value="<?php echo escape($user->data()->email); ?>" autocomplete="off">
							</label>
						</div>
						<input type="submit" value="Update" class="btn btn-success">
						<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
					</form>
				</div>
			</div>
		</div>
	</div>
</body>