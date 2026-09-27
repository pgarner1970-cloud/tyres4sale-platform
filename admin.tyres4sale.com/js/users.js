$(document).ready(function(){
	$("#wrapper").toggleClass("toggled");

	var dataTable;
	dataTable = $('#usertable').DataTable( {
		"processing": true,
		"serverSide": true,
		"order":[],
		"ajax": {
			url: "functions/usertable.php",
			method: "POST"
		},
		"columnDefs":[
			{
				"targets":[0, 1, 2, 3, 4],
				"orderable":false
			},
		],
	});
	
	$(document).on('submit', '#user_form', function(event){
		event.preventDefault();
		var regex = /^[0-9]+\.[0-9]{0,2}$/i;
		var operation = $('#operation').val();
		var username = $('#username').val();
		var description = $('#description').val();
		var email = $('#email').val();
		var userpass = $('#password').val();
		var userpass2 = $('#password2').val();
		if (userpass.length==0 && operation=='Add') {
			alert("A password is required");
			return false;
		}
		if (userpass !== userpass2) {
			alert("Passwords must match !");
			return false;
		}
		
		
		if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email)) {
		    emailvalid = true;
	    } else {
			alert("You have entered an invalid email address!");
			return false;
		}
		var usertype = $('#usertype').val();
		var arr=["A", "U", "T"];
        if (arr.indexOf(usertype) == -1) {
			alert("User type can only be (A)dmin, (U)ser or (T)rade");
			return false;
		}
		if(username != '' && usertype != '') {
			$.ajax({
				url: "functions/userfunctions.php",
				method: "POST",
				data: new FormData(this),
				contentType:false,
				processData:false,
				success:function(data) {
					alert(data);
					$('#user_form')[0].reset();
					$('#userModal').modal('hide');
					dataTable.ajax.reload();
				}
			});
		}
		else {
			alert("All fields are required");
		}
	});
	
	$(document).on('click', '.update', function(){
		var id = $(this).attr("id");
		$.ajax({
			url:"functions/userfetchsingle.php",
			method:"POST",
			data:{id:id},
			dataType:"json",
			success:function(data) {
			    console.log(data);
				$('#userModal').modal('show');
				$('#username').val(data.username);
				$('#usertype').val(data.usertype);
				$('#description').val(data.description);
				$('#email').val(data.email);
				$('.modal-title').text("Edit User");
				$('#user_id').val(id);
				$('#action').val("Edit");
				$('#operation').val("Edit");
			}
		});
	});
	
	$(document).on('click', '.delete', function(){
		var id = $(this).attr("id");
		if (confirm("Are you sure you want to delete this?")) {
			$.ajax({
			url:"functions/userdeletesingle.php",
			method:"POST",
			data:{id:id},
			success:function(data) {
				alert(data);
				dataTable.ajax.reload();
				}
			});		
		}
		else {
			return false;
		}

	});
});