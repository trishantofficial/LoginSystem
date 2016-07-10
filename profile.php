<?php
require_once 'core/init.php';
if (!$email = Input::get('email')) {
	Redirect::to('index.php');
} else {
	$user = new User($email);
	if (!$user->exists()) {
		Redirect::to(404);
	}
	else {
		$data = $user->data();
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
				<div class="well">
					<img src='<?php echo escape($data->profile_pic_url); ?>' height="200" width="200">
				</div>
				<div class="well well-sm">
					First Name: <?php echo escape($data->first_name); ?>
				</div>
				<div class="well well-sm">
					Last Name: <?php echo escape($data->last_name); ?>
				</div>
				<div class="well well-sm">
					Email: <?php echo escape($data->email); ?>
				</div>
				<div class="well well-sm">
					Date Joined: <?php echo escape($data->joined); ?>
				</div>
				<div class="well well-sm">
					User Type: <?php echo escape($data->user_type); ?>
				</div>
				<div class="well well-sm">
					Gender: <?php echo escape($data->gender); ?>
				</div>
			</div>
		</div>
	</div>
</body>
