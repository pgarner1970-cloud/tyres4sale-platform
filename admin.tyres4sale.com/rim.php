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

    <title>LTC Tyres - Maintain Rim/Markup Data</title>

    <!-- Bootstrap core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.2.2/datatables.min.css" rel="stylesheet" integrity="sha384-WMi+Ec+QE8hxW/3qKvuefShIddYjwMalSgy0MR4FZnl285C4HGYfISceaagw0Am3" crossorigin="anonymous">
    <!-- Custom styles for this template -->
    <link rel="stylesheet" href="css/toastr.min.css">
    <!-- Bootstrap core JavaScript -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.2.2/datatables.min.js" integrity="sha384-WcZXtPeSp12Ybwm08R/IL8F3bMhrj0WW6jKsqKXTqJSwCSkISe4unYVY8Vzc1RZc" crossorigin="anonymous"></script>
    <script src="js/rim.js"></script>
<body>

    <div id="wrapper">

	<?php include("functions/menu.php"); ?>


        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
                <h3>Rim/Markup Data</h3>
 				<div align="right">
					<button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#rimModal" >Add</button>
				</div>

				<div class="table-responsive">
					<table id="rimtable" class="table table-bordered table-striped" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th width="20%">ID</th>
							<th width="25%">Rim Size</th>
							<th width="25%">Markup (£.p)</th>
							<th width="25%">Markup Trade (£.p)</th>
							<th width="20%">Type</th>
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



    <!-- Menu Toggle Script -->
    <script>
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });
    </script>

<div id="rimModal" class="modal fade">
	<div class="modal-dialog">
		<form method="post" id="rim_form" enctype="multipart/form-data">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Add Rim</h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
			</div>
			<div class="modal-body">
				<label>Rim Size</label>
				<input type="text" name="rim_desc" id="rim_desc" class="form-control" required />
				<br>
				<label>Markup (£.p)</label>
				<input type="text" name="rim_markup" id="rim_markup" class="form-control" required />
				<br>
				<label>Markup Trade (£.p)</label>
				<input type="text" name="rim_markupt" id="rim_markupt" class="form-control" required />
     			<br>
				<label>Type</label>
				<input type="text" name="rim_type" id="rim_type" class="form-control" required />
			</div>
			<div class="modal-footer">
				<input type="hidden" name="rim_id" id="rim_id" />
				<input type="hidden" name="operation" id="operation" value="Add"/>
				<input type="submit" name="action" id="action" class="btn btn-success" value="Add" />
			</div>
		</div>
		</form>
	</div>
</div>
</body>

</html>


