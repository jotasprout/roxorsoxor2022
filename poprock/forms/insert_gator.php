<?php
session_start();

require_once 'class.gator.php';
require_once 'stylesAndSuch.php';
require_once 'navbar.php';
$user = new USER();

if(!$user->areTheyLoggedIn()) {
	$user->redirect('https://www.roxorsoxor.com/stakeout/login_form.php');
}
else {
	$jefe = $_SESSION['jefe'];
	if ($jefe == 1) {
		echo "<script>console.log('You are an admin.')</script>";
	} else {
		$user->redirect('index.php');
	}
	$username = $_SESSION['username'];
	echo "<script>console.log('" . $username . " is logged in.')</script>";
}

if(isset($_POST['submit'])) {
	$uname = trim($_POST['username']);
	$forename = trim($_POST['forename']);
	$surname = trim($_POST['surname']);
	$email = trim($_POST['email']);
	$code = md5(uniqid(rand()));
	$stmt = $user->runQuery("SELECT * FROM user_creds WHERE email=:email_id");
	$stmt->execute(array(":email_id"=>$email));
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	if($stmt->rowCount() > 0) {
		$msg = "<div class='alert alert-error'>
				<button class='close' data-dismiss='alert'>&times;</button>
					<strong>Poop!</strong> Somebody already used that email. Try another one, slick.
			  </div>";
	}
	else {
		if($user->register($uname,$forename,$surname,$email,$code)) {
			$id = $user->lasdID();
			$key = base64_encode($code);
			$code = $key;
			$message = "Welcome to Stakeout, $forename!<br/>
						Click the link below to complete your registration.<br/>
						<a href='https://www.roxorsoxor.com/stakeout/verifyAndSetPW.php?id=$id&code=$code'>Click here to activate</a>.<br />
						Thanks,";
			$subject = "Confirm Registration";
			// changing send_mail on next line to message_bottle
			$user->message_bottle($email,$message,$subject);
			$msg = "<div class='alert alert-success'>
						<button class='close' data-dismiss='alert'>&times;</button>
						<strong>Almost there!</strong> We've sent an email to $email.
                    They must click the link in the email to verify their account and create a password.</div>";
		}
		else {
			// echo "Crap. Query not executed.";
			$msg = "<div class='alert alert-error'>
				<button class='close' data-dismiss='alert'>&times;</button>
					<strong>Crap!</strong> Query not executed.
			  </div>";			
		}
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Add Investigator</title>
	<?php echo $stylesAndSuch; ?>
</head>
<body>
	<div class="container">
		<?php if(isset($msg)) echo $msg;  ?>
		
		<nav class='navbar navbar-default'>	
			<div id='header' class='container-fluid'>		
				<h1 class="hide"><a href="index.php">Stakeout</a></h1>
		
<?php 
	if ($jefe == 1) {
		echo $navbarJefe;
	}
	else {
		echo $navbarGator;
	}
?>	
	</div> <!-- /container-fluid -->   
</nav> <!-- /navbar -->	

	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3 class="panel-title">New Investigator</h3>
		</div>
		<div class="panel-body"> 

		<form class="form-horizontal" method="post">
			<fieldset>
				<div class="form-group"><!-- Row 1 -->
					<!-- Column 1 -->
					<label class="col-lg-2 control-label" for="forename">First Name</label>
					<!-- Column 2 -->
					<div class="col-lg-4">
						<input class="form-control" type="text" name="forename" placeholder="First Name" required>
					</div>
				</div><!-- /Row 1 -->
				<div class="form-group"><!-- Row 2 -->
					<!-- Column 1 -->
					<label class="col-lg-2 control-label" for="surname">Last Name</label>
					<!-- Column 2 -->
					<div class="col-lg-4">
						<input class="form-control" type="text" name="surname" placeholder="Last Name" required>
					</div>
				</div><!-- /Row 2 -->
				<div class="form-group"><!-- Row 3 -->
					<!-- Column 1 -->
					<label class="col-lg-2 control-label" for="username">Username</label>
					<!-- Column 2 -->
					<div class="col-lg-4">
						<input class="form-control" type="text" name="username" placeholder="username" required>
					</div>
				</div><!-- /Row 3 -->
				
				<div class="form-group"><!-- Row 5 -->
					<!-- Column 1 -->
					<label class="col-lg-2 control-label" for="email">Email</label>
					<!-- Column 2 -->
					<div class="col-lg-4">
						<input class="form-control" type="email" name="email" placeholder="you@domain.com" required>
					</div>
				</div><!-- /Row 5 -->
				<div class="form-group"><!-- Last Row -->
					<div class="col-lg-4 col-lg-offset-2">
						<button class="btn btn-primary" type="submit" name="submit">Add Investigator</button>
					</div>
				</div>
				<!-- /Last Row -->
			</fieldset>
		</form>

		</div> <!-- /panel-body -->
	</div> <!-- /panel-primary -->

	</div> <!-- /container -->
	<?php echo $scriptsAndSuch; ?>
</body>
</html>