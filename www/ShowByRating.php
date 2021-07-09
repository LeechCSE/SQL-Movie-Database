<!DOCTYPE html>
<html>
<head>
	<title>Project 1B</title>

	<link rel="stylesheet" href="styles.css">
</head>
<body>
	<h1>Based on the MPAA Rating</h1>
	<div class="topnav">
	  <a href="http://localhost:1438/~cs143/index.php">Search by Keyword</a>
	  <a href="http://localhost:1438/~cs143/AddActorDirector.php">Add a New Actor/Director</a>
	  <a href="http://localhost:1438/~cs143/AddMovie.php">Add a New Movie</a>
	  <a href="http://localhost:1438/~cs143/AddComments.php">Add a New Review</a>
	  <a href="http://localhost:1438/~cs143/AddActorToMovie.php">Add a New Actor to Movie</a>
	  <a href="http://localhost:1438/~cs143/AddDirectorToMovie.php">Add a New Director to Movie</a>
	  <a href="http://localhost:1438/~cs143/ShowActors.php">Search an Actor</a>
	  <a href="http://localhost:1438/~cs143/ShowMovies.php">Search a Movie</a>
	</div>

	<?php
		// connect
		$db_connection = mysql_connect("localhost", "cs143", "");
		if (!$db_connection) {
			echo mysql_error($db_connection);
			exit(1);
		}
		$db_sel = mysql_select_db("CS143", $db_connection);
		if (!$db_sel){
			echo mysql_error($db_sel);
			exit(1);
		}

		$rating = "'" . $_GET["rating"] . "'";
		$res = mysql_query("SELECT title, year, company FROM Movie WHERE rating = $rating;", $db_connection);

		echo "<table border=1 cellspacing=1 cellpadding=2>\n";
		echo "<tr align=center><td colspan=3><b>Movies with MPAA rating of $rating</b></td></tr>";
		echo "<tr align=center>";
		for ($i = 0; $i < mysql_num_fields($res); $i++){
			$field = mysql_fetch_field($res, $i);
			echo "<td><b>" . $field->name . "</b></td>";
		}
		echo "</tr>\n";

		while ($row = mysql_fetch_row($res)){
			echo "<tr align=center>";
			for ($i = 0; $i < mysql_num_fields($res); $i++){
				if ($row[$i] != "")
					echo "<td>" . $row[$i] . "</td>";
				else
					echo "<td>" . "N/A" . "</td>";
			}
			echo "</tr>\n";
		}
		echo "</table>\n";


		mysql_free_result($res);
		// close
		mysql_close();
	?>

</body>
</html>