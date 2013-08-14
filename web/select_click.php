<?php	
		// Create connection
		$con=mysqli_connect("127.0.0.1","root","root", "sunburst");
		// Check connection
		if (mysqli_connect_errno())
		  {
		  echo "Failed to connect to MySQL: " . mysqli_connect_error();
		  }
		
		// Select
		$result = mysqli_query ($con,"SELECT name, author, year, institution, uri, type FROM document WHERE id=1");
		
		while ($row= mysqli_fetch_array ($result))
		{
			$variables = array(
			'name' => $row['name'],
			'uri' => $row['uri']
			);
			print json_encode($variables);
		}
		mysqli_close($con);	
?>
