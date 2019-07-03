<!DOCTYPE html>
<html>
<head>
	<title>PHP Ajax CRUD using jquery UI dialog box</title>
	<link rel="stylesheet" type="text/css" href="jquery-ui.css">
	<link rel="stylesheet" type="text/css" href="bootstrap.min.css">
	<script type="text/javascript" src="jquery.min.js"></script>
	<script type="text/javascript" src="jquery-ui.js"></script>
</head>
<body>
	<div class="container">
		<br />
		<h3 align="center">Welcome to Brainstorm Force Registration !</h3><br />
		<div align="right" style="margin-bottom:5px;">
			<button type="button" name="add" id="add" class="btn btn-success btn-xs">Add</button>
		</div> 
		<div id="user_data" class="table-responsive">
				<!-- output data will be displayed here -->
		</div>
		<br />
	</div>
	<div id="user_dialog" title="Add Data">
			<form method="post" id="user_form">
				<div class="form-group">
					<label>Enter First Name</label>
					<input type="text" name="first_name" id="first_name" class="form-control" />
					<span id="error_first_name" class="text-danger"></span>
				</div>
				<div class="form-group">
					<label>Enter Last Name</label>
					<input type="text" name="last_name" id="last_name" class="form-control" />
					<span id="error_last_name" class="text-danger"></span>
				</div>
				<div class="form-group">
					<input type="hidden" name="action" id="action" value="insert" />
					<input type="hidden" name="hidden_id" id="hidden_id" />
					<input type="submit" name="form_action" id="form_action" class="btn btn-info" value="Insert" />
				</div>
			</form>
	</div>

	<div id="action_alert" title="Action">
			
	</div>
		
	<div id="delete_confirmation" title="Confirmation">
		<p>Are you sure you want to Delete this data?</p>
	</div>
</body>
</html>

<script>
$(document).ready(function(){

		// Display data ka code
		load_data();
		function load_data()										//fetch ka code
		{
			$.ajax({
				url : "fetch.php",
				method : "POST",
				success : function(data){								//data is the output
					$("#user_data").html(data);
				}
			});
		}

		$("#user_dialog").dialog({
			autoOpen:false,
			width:400      
		});

		// Insert data ka code
		$('#add').click(function(){											//insert ke prereq
			$('#user_dialog').attr('title', 'Add Data');					//design the dialog box heading
			$('#action').val('insert');										//set action as insert
			$('#form_action').val('Insert');
			$('#user_form')[0].reset();
			$('#form_action').attr('disabled', false);
			$("#user_dialog").dialog('open');
		});

		$('#user_form').on('submit', function(event){
			event.preventDefault();
			$('#form_action').attr('disabled', 'disabled');			//submit data
			var form_data = $(this).serialize();					//convert data to string
			$.ajax({												//insert ka code
				url:"action.php",
				method:"POST",
				data:form_data,
				success:function(data)
				{
					$('#user_dialog').dialog('close');				//fade out
					$('#action_alert').html(data);
					$('#action_alert').dialog('open');
					load_data();
					$('#form_action').attr('disabled', false);
				}
			});
		});

		$('#action_alert').dialog({									//display success msg
			autoOpen:false
		});


		// Update data ka code
		$(document).on("click",".edit",function(){
			$('#user_dialog').attr('title', 'Edit Data');	
			var id = $(this).attr("id");
			var action = 'fetch_single';
			$.ajax({
				url : "action.php",
				method : "POST",
				data : {id:id, action:action},
				dataType : "json",
				success : function(data){
					$('#first_name').val(data.first_name);
					$('#last_name').val(data.last_name);
					$("#user_dialog").attr('title',"Edit data");
					$("#action").val('update');
					$("#hidden_id").val(id);
					$("#form_action").val("Update");
					$("#user_dialog").dialog("open");
				}
			});
		});


		// Delete ka code

		$(document).on('click', '.delete', function(){
			var id = $(this).attr("id");
			$('#delete_confirmation').data('id', id).dialog('open');
		});
		$('#delete_confirmation').dialog({
		autoOpen:false,
		modal: true,
		buttons:{
			Ok : function(){
				var id = $(this).data('id');
				var action = 'delete';
				$.ajax({
					url:"action.php",
					method:"POST",
					data:{id:id, action:action},
					success:function(data)
					{
						$('#delete_confirmation').dialog('close');
						$('#action_alert').html(data);
						$('#action_alert').dialog('open');
						load_data();
					}
				});
			},
			Cancel : function(){
				$(this).dialog('close');
			}
		}	
		});

});
</script>