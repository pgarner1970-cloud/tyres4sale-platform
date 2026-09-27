<?php

include("functions/db.php");

if (isset($_POST["username"]) && isset($_POST["password"])) {

	//sanitise inputs!
	$username = $conn->real_escape_string($_POST["username"]);

	$query = $conn->query("SELECT usertype,hash FROM tblusers WHERE username='".$username."' AND usertype<>'T'");
	if($query->num_rows <= 0) {
		$error = "Invalid username or password entered.";
	} else {
		$result = $query->fetch_assoc();
		$goodPassword = password_verify($_POST["password"],$result["hash"]);
		if(!$goodPassword) {
			$error = "Invalid username or pasword entered.";
		} else {
			$token = bin2hex(random_bytes(16));
			/*setcookie("username", $username, time() + (86400 * 2), "/");
			setcookie("usertype", $result["usertype"], time() + (86400 * 2), "/");
		 	setcookie("tmp_token", $token, time() + (86400 * 2), "/"); */
			session_start();
			$_SESSION['username'] = $username;
			$_SESSION['usertype'] = $result["usertype"];
			$_SESSION['tmp_token'] = $token;
		 	header('Location:index.php');
		}
	}

}

?>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>LTC Tyres</title>

    <!-- Bootstrap core CSS -->
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Custom styles for this template -->
    <link href="css/simple-sidebar.css" rel="stylesheet">

</head>

<body>

	<div class="container" style="margin-top: 5%;">
		<div class="col-md-4 col-md-offset-4">
			<div class="panel panel-primary">
				<div class="panel-heading">Login</div>
				<div class="panel-body">

				<!-- Login Form -->
				<form role="form" method="post" action="login.php">

				<!-- Username Field -->
					<div class="row">
						<div class="form-group col-xs-12">
						<label for="username"><span class="text-danger" style="margin-right:5px;">*</span>Username:</label>
							<div class="input-group">
								<input class="form-control" id="username" type="text" name="username" placeholder="Username" required/>
								<span class="input-group-btn">
									<label class="btn btn-primary"><span class="glyphicon glyphicon-user" aria-hidden="true"></label>
								</span>
								</span>
							</div>
						</div>
					</div>

					<!-- Content Field -->
					<div class="row">
						<div class="form-group col-xs-12">
							<label for="password"><span class="text-danger" style="margin-right:5px;">*</span>Password:</label>
							<div class="input-group">
								<input class="form-control" id="password" type="password" name="password" placeholder="Password" required/>
								<span class="input-group-btn">
									<label class="btn btn-primary"><span class="glyphicon glyphicon-lock" aria-hidden="true"></label>
								</span>
								</span>
							</div>
						</div>
					</div>

					<!-- Login Button -->
					<div class="row">
						<div class="form-group col-xs-4">
							<button class="btn btn-primary" type="submit">Submit</button>
						</div>
					</div>
					<?php
						global $error;
						if(!empty($error)) {
							echo "<div class='alert alert-danger'>".$error."</div>";
						}
					?>
				</form>
				<!-- End of Login Form -->

			</div>
		</div>
	</div>

    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>


</body>

</html>
