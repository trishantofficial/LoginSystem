<?php
require_once 'core/init.php';
if(Session::exists('home')) {
	echo '<p>' . Session::flash('home') . '</p>';
}
$user = new User();
if ($user->isLoggedIn()) {
	?>
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
	<link rel="stylesheet" href="includes/css/style.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<body>
		<div class="container-fluid">
			<div class="jumbotron">
				<div class="text-center" style="margin-top:130px;margin-bottom:180px">
					<p>Hello <a href="profile.php?email=<?php echo escape($user->data()->email); ?>"><?php echo escape($user->data()->first_name); ?></a></p>

					<a href="logout.php"><button class="btn btn-lg btn-danger">Log out</button></a>
					<a href="update.php"><button class="btn btn-lg btn-info">Update</button></a>
					<a href="changepassword.php"><button class="btn btn-lg btn-warning">Change Password</button></a>

				</div>
			</div>
		</div>
	</body>
	<?php
} else {
	?>
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
	<link rel="stylesheet" href="includes/css/style.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<body>
		<div class="container-fluid">
			<div class="jumbotron">
				<div class="text-center">
					<div class="a">
						<p>
							<h1>You need to</h1>
							<br>
							<a href="login.php">
								<button class="btn btn-lg btn-info">
									Log in
								</button>
							</a> 
							or 
							<a href="register.php">
								<button class="btn btn-lg btn-warning">
									Register
								</button>
							</a>
						</p>
					</div>
				</div>
			</div>
		</div>
	</body>
	<?php
}
?>