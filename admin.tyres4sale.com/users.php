<?php
session_start();
if (isset($_SESSION['username']) && ($_SESSION['usertype']!="T")) {

} else {
	header('Location: login.php') ;
}
?>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>LTC Tyres - Maintain Users</title>

    <!-- Bootstrap core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.2.2/datatables.min.css" rel="stylesheet" integrity="sha384-WMi+Ec+QE8hxW/3qKvuefShIddYjwMalSgy0MR4FZnl285C4HGYfISceaagw0Am3" crossorigin="anonymous">
    <!-- Custom styles for this template -->
    <link rel="stylesheet" href="css/toastr.min.css">
    <!-- Bootstrap core JavaScript -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.2.2/datatables.min.js" integrity="sha384-WcZXtPeSp12Ybwm08R/IL8F3bMhrj0WW6jKsqKXTqJSwCSkISe4unYVY8Vzc1RZc" crossorigin="anonymous"></script>
	<script src="js/users.js"></script>

</head>

<body>

    <div id="wrapper">

	<?php include("functions/menu.php"); ?>


        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
                <h3>Users</h3>
 				<div align="right">
					<button type="button" id="add_button" data-bs-toggle="modal" data-bs-target="#userModal" class="btn btn-info">Add</button>
				</div>
				<div class="table-responsive">
					<table id="usertable" class="table table-bordered table-striped" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th width="5%">ID</th>
							<th width="20%">Username</th>
							<th width="10%">Type</th>
							<th width="20%">Email</th>
							<th width="35%">Description</th>
							<th width="5%">Edit</th>
							<th width="5%">Delete</th>
						</tr>
					</thead>
					</table>
				</div>
            </div>
		</div>
        <!-- /#page-content-wrapper -->

    </div>
    <!-- /#wrapper -->


</body>

</html>

<div id="userModal" class="modal fade">
	<div class="modal-dialog">
		<form method="post" id="user_form" enctype="multipart/form-data">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Add User</h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
			</div>
			<div class="modal-body">
				<label>Username</label>
				<input type="text" name="username" id="username" class="form-control" required />
				<br>
				<label>Password</label>
				<input type="password" name="password" id="password" class="form-control"  />
				<br>
				<label>Confirm Password</label>
				<input type="password" name="password2" id="password2" class="form-control"  />
     			<br>
				<label>Description</label>
				<input type="text" name="description" id="description" class="form-control"  />
      			<br>
				<label>Email</label>
				<input type="text" name="email" id="email" class="form-control"  />
     			<br>
				<label>Type</label>
				<input type="text" name="usertype" id="usertype" class="form-control" required />
			</div>
			<div class="modal-footer">
				<input type="hidden" name="user_id" id="user_id" />
				<input type="hidden" name="operation" id="operation" value="Add"/>
				<input type="submit" name="action" id="action" class="btn btn-success" value="Add" />
			</div>
		</div>
		</form>
	</div>
</div>
