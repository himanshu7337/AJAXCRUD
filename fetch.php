<?php
	include("database_connection.php");
	$query = "SELECT * FROM tbl_sample";
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();							//result contain all the fetched data
	$total_row = $statement->rowCount();
	$output = '
	<table class="table table-striped table-bordered">
		<tr>
			<th width="40%">First Name</th>
			<th width="40%">Last Name</th>
			<th width="10%">Edit</th>
			<th width="10%">Delete</th>
		</tr>
	';
	if($total_row>0)
	{
		foreach($result as $row)
		{
			$output.='
			<tr>
				<td>'.$row["first_name"].'</td>								
				<td>'.$row["last_name"].'</td>
				<td>
					<button type="button" name="edit" class="btn btn-primary btn-xs edit" id="'.$row["id"].'">Edit</button>
				</td>
				<td>
					<button type="button" name="delete" class="btn btn-danger btn-xs delete" id="'.$row["id"].'">Delete</button>
				</td>
			</tr>
			';
		}
	}
	else
	{
		$output.='
		<tr>
			<td colspan="4" align="center">Data not found</td>
		</tr>
		';
	}
$output .= '</table>';							//for the last end of table
echo $output;									//send reponse of AJAX
?>