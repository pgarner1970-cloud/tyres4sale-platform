	$(document).ready(function(){
		$("#wrapper").toggleClass("toggled");
		$.ajax({
			url: 'functions/list_width.php',
			type: 'post',
			success:function(response){

				var len = response.length;

				$("#width").empty();
				for( var i = 0; i<len; i++) {
					var widthDesc = response[i]['widthDesc'];

					$("#width").append("<option value='"+widthDesc+"'>"+widthDesc+"</option>");

				}
			}
		});
		$.ajax({
			url: 'functions/list_profile.php',
			type: 'post',
			success:function(response){

				var len = response.length;

				$("#profile").empty();
				for( var i = 0; i<len; i++){
					var profileDesc = response[i]['profileDesc'];

					$("#profile").append("<option value='"+profileDesc+"'>"+profileDesc+"</option>");

				}
			}
		});
		$.ajax({
			url: 'functions/list_rim.php',
			type: 'post',
			success:function(response){

				var len = response.length;

				$("#rim").empty();
				for( var i = 0; i<len; i++){
					var rimDesc = response[i]['rimDesc'];

					$("#rim").append("<option value='"+rimDesc+"'>"+rimDesc+"</option>");

				}
			}
		});
		$.ajax({
			url: 'functions/list_speed.php',
			type: 'post',
			success:function(response){

				var len = response.length;

				$("#speed").empty();
				$("#speed").append("<option value='*'>ALL</option>");
				for( var i = 0; i<len; i++){
					var speedDesc = response[i]['speedDesc'];

					$("#speed").append("<option value='"+speedDesc+"'>"+speedDesc+"</option>");

				}
			}
		});
		$.ajax({
			url: 'functions/list_manufacturers.php',
			type: 'post',
			success:function(response){

				var len = response.length;

				$("#manufacturer").empty();
				$("#manufacturer").append("<option value='*'>** ALL **</option>");
				for( var i = 0; i<len; i++){
					var Manufacturer = response[i]['Manufacturer'];

					$("#manufacturer").append("<option value='"+Manufacturer+"'>"+Manufacturer+"</option>");

				}
			}
		});		
		$("#search").submit(function(event) {
			$("#resultsDiv").empty();
			
			var formData = {
				't'		: 'enquiry',
				'w'		: $('#width option:selected').val(),
				'p'		: $('#profile option:selected').val(),
				'r'		: $('#rim option:selected').val(),
				's'		: $('#speed option:selected').val(),
				'f'		: $('#fuel option:selected').val(),
				'wg'	: $('#wetgrip option:selected').val()
			};
			console.log(formData);
			$.ajax({
				url: 'https://tyres4sale.com/_api/apiSearch.php',
				type: 'get',
				data: formData,
				dataType: 'json'
			})

				.done(function(data) {

				// log data to the console so we can see
				console.log(data); 
				var len = data.length;
				console.log(len);
				
				$("#resultsDiv").empty();
				var resultsHtml = "<table cellspacing=2 cellpadding=2 class='table table-striped'>";
				resultsHtml += '<thead><tr bgcolor=#cecece><td>EAN</td><td>Manufacturer</td><td>Description</td><td>Class</td><td>Fuel</td><td>Wet</td><td>Noise</td></td><td>Trade (exc VAT)</td><td></td></tr></thead>';
				for(var i = 0; i<len; i++) {
					if (data[i]['Manufacturer'] != $('#manufacturer option:selected').val() && $('#manufacturer option:selected').val() != '*') {
						continue;
					}
					resultsHtml += '<tr><td width="12%">' + data[i]['EAN'] + '</td>';
					resultsHtml += '<td width="15%">' + data[i]['Manufacturer'] + '</td>';
					resultsHtml += '<td width="35%">' + data[i]['TyreDesc'] + '</td>';
					resultsHtml += '<td width="5%">' + data[i]['LTCClass'] + '</td>';
					resultsHtml += '<td width="5%">' + data[i]['RollingRes'] + '</td>';
					resultsHtml += '<td width="5%">' + data[i]['WetGrip'] + '</td>';
					resultsHtml += '<td width="5%">' + data[i]['NoisePerf'] + '</td>';
					resultsHtml += '<td width="5%">' + data[i]['UnitTrade'] + '</td>';
					resultsHtml += '<td width="5%"><button type="button" name="buy" id="' + data[i]['EAN'] + '~' + data[i]['Supplier'] + '" class="btn btn-info btn-sm buy">Buy</button></td></tr>';
				} 
				resultsHtml += "</table>";
				$("#resultsDiv").html(resultsHtml);
			});
			event.preventDefault();
		});
		$(document).on('click', '.buy', function(){
			var id = $(this).attr("id");
			$.ajax({
			url:"functions/addbasket.php",
				method:"POST",
				data:{id:id},
				success:function(data) {
					//alert(data);
					toastr.success(data);
					dataTable.ajax.reload();
					}
				});		
		});
	});
