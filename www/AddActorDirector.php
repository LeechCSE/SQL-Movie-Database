<!DOCTYPE html>
<html>
<head>
	<title>Project 1B</title>

	<link rel="stylesheet" href="styles.css">
</head>
<body>
	<h1> Add New Actor and/or Director </h1>

	<div class="topnav">
	  <a href="http://localhost:1438/~cs143/index.php">Search by Keyword</a>
	  <a class="active" href="http://localhost:1438/~cs143/AddActorDirector.php">Add a New Actor/Director</a>
	  <a href="http://localhost:1438/~cs143/AddMovie.php">Add a New Movie</a>
	  <a href="http://localhost:1438/~cs143/AddComments.php">Add a New Review</a>
	  <a href="http://localhost:1438/~cs143/AddActorToMovie.php">Add a New Actor to Movie</a>
	  <a href="http://localhost:1438/~cs143/AddDirectorToMovie.php">Add a New Director to Movie</a>
	  <a href="http://localhost:1438/~cs143/ShowActors.php">Search an Actor</a>
	  <a href="http://localhost:1438/~cs143/ShowMovies.php">Search a Movie</a>
	</div>

	<form action="" method="GET">
		Type: 
		<input type="radio" name="type" value="Actor" checked>Actor
		<input type="radio" name="type" value="Director">Director
		<br>
		First name: <input type="text" name="first_name">
		<br>
		Last name: <input type="text" name="last_name">
		<br>
		Sex:
		<input type="radio" name="sex" value="Female">Female
		<input type="radio" name="sex" value="Male">Male
		<input type="radio" name="sex" value="Trans">Transgender
		<br>
		Date of Birth:<input type="text" name="dob" placeholder="(YYYY-MM-DD)">
		<br>
		Date of Death:<input type="text" name="dod" placeholder="(YYYY-MM-DD)"> 
		<br><br>
		<input type="submit" value="Submit">
	</form>

	<?php
		// connect
		$db_connection = mysql_connect("localhost", "cs143", "");
		if (!$db_connection) {
			echo mysql_error($db_connection);
			exit(1);
		}
		else
			// echo "<p><small>Connected OK <br />";
		// select db
		$db_sel = mysql_select_db("CS143", $db_connection);
		if (!$db_sel){
			echo mysql_error($db_sel);
			exit(1);
		}
		else
			// echo "DB Selected OK\n</small></p>\n";

		// obtain user inputs
		$type = $_GET["type"]; // NOT NULL
		$first_name = $_GET["first_name"] ? "'".$_GET["first_name"]."'" : 'NULL';
		$last_name = $_GET["last_name"] ? "'".$_GET["last_name"]."'" : 'NULL';
		$sex = $_GET["sex"] ? "'".$_GET["sex"]."'" : 'NULL';
		$dob = "'".$_GET["dob"]."'"; // NOT NULL
		$dod = $_GET["dod"] ? "'".$_GET["dod"]."'" : 'NULL';

		// get current MaxPersonID
		$query_mpi = "SELECT * FROM MaxPersonID;";
		$res = mysql_query($query_mpi, $db_connection);
		$mpi_row = mysql_fetch_row($res);
		$mpi = $mpi_row[0];
		// advance once for assigning id
		$mpi++;
		// form INSERT query
		if ($type == "Actor")
			$query_insert = "INSERT INTO $type VALUES($mpi, $last_name, $first_name, $sex, $dob, $dod);"; 
		else // "Director"
			$query_insert = "INSERT INTO $type VALUES($mpi, $last_name, $first_name, $dob, $dod);";

		// echo $query_insert;

		// send query
		if (mysql_query($query_insert, $db_connection)){
			if(mysql_query("UPDATE MaxPersonID SET id = $mpi;", $db_connection)){
				echo "<p>$type Successfully added</p><br>\n";
			}
		}
		mysql_free_result($res);
		// close
		mysql_close();
	?>
</body>
</html>



