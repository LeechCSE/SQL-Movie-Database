<!DOCTYPE html>
<html>
<head>
	<title>Project 1B</title>

	<link rel="stylesheet" href="styles.css">
</head>
<body>
	<h1>Search by Keyword</h1>
	<div class="topnav">
	  <a class="active" href="http://localhost:1438/~cs143/Search.php">Search by Keyword</a>
	  <a href="http://localhost:1438/~cs143/AddActorDirector.php">Add a New Actor/Director</a>
	  <a href="http://localhost:1438/~cs143/AddMovie.php">Add a New Movie</a>
	  <a href="http://localhost:1438/~cs143/AddComments.php">Add a New Review</a>
	  <a href="http://localhost:1438/~cs143/AddActorToMovie.php">Add a New Actor to Movie</a>
	  <a href="http://localhost:1438/~cs143/AddDirectorToMovie.php">Add a New Director to Movie</a>
	  <a href="http://localhost:1438/~cs143/ShowActors.php">Search an Actor</a>
	  <a href="http://localhost:1438/~cs143/ShowMovies.php">Search a Movie</a>
	</div>
	<br><br>

	<form action="" method="GET">
		Keyword: <input type="text" name="input">
		<input type="hidden" name="ready" value=True>
		<br><br>
		<input type="submit" name="Submit">
	</form>

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
		// obtain input
		$input_raw = preg_replace('/\s+/', ' ', $_GET["input"]);

		if ($_GET["ready"]){
			// actor table
			$res_name = mysql_query("SELECT * FROM Actor WHERE CONCAT(first, ' ', last) LIKE '%$input_raw%';", $db_connection);

			$num_rows_name = mysql_num_rows($res_name);
			if ($num_rows_name != 0){
				echo "<table border=1 cellspacing=1 cellpadding=2>\n";
				echo "<tr align=center><td colspan=6><b>Matched Actors</b></td></tr>";
				echo "<tr align=center>";
				echo "<td><b>Name</b></td>";
				echo "<td><b>sex</b></td>";
				echo "<td><b>dob</b></td>";
				echo "<td><b>dod</b></td>";
				echo "</tr>\n";

				while ($row = mysql_fetch_row($res_name)){
					echo "<tr align=center>";
					echo "<td><a href='http://localhost:1438/~cs143/ShowActors.php?actor_last=$row[1]&actor_first=$row[2]&ready=True'>" . $row[2] . " " . $row[1] . "</a></td>";
					echo "<td>" . $row[3] . "</td>";
					echo "<td><a href='http://localhost:1438/~cs143/ShowByDob.php?dob=$row[4]'>" . $row[4] . "</a></td>";
					if ($row[5] != "")
						echo "<td><a href='http://localhost:1438/~cs143/ShowByDod.php?dod=$row[5]'>" . $row[5] . "</a></td>";
					else
						echo "<td>" . "N/A" . "</td>";
					echo "</tr>\n";
				}
				echo "</table><br><br>\n";
			}
			else
				echo "<p>No matching actor</p>";
			// movie table
			$res_movie = mysql_query("SELECT * FROM Movie WHERE title LIKE '%$input_raw%';", $db_connection);
			$num_rows_movie = mysql_num_rows($res_movie);
			if ($num_rows_movie != 0){
				echo "<table border=1 cellspacing=1 cellpadding=2>\n";
				echo "<tr align=center><td colspan=4><b>Matched Movies</b></td></tr>";
				echo "<tr align=center>";
				for ($i = 1; $i < mysql_num_fields($res_movie); $i++){
					$field = mysql_fetch_field($res_movie, $i);
					echo "<td><b>" . $field->name . "</b></td>";
				}
				echo "</tr>\n";

				while ($row = mysql_fetch_row($res_movie)){
					echo "<tr align=center>";
					for ($i = 1; $i < mysql_num_fields($res_movie); $i++){
						if ($i == 1){ // title
							if ($row[$i] != ""){
								$parsed_title = explode(" ", $row[$i]);
								$url_title = $parsed_title[0];
								for ($j = 1; $j < sizeof($parsed_title); $j++){
									$url_title .= "+" . $parsed_title[$j];
								}
								echo "<td><a href='http://localhost:1438/~cs143/ShowMovies.php?title=$url_title&ready=True'>" . $row[$i] . "</a></td>";
							}
							else
								echo "<td>" . "N/A" . "</td>";
						}
						else if ($i == 2){ // year
							if ($row[$i] != ""){
								$year = $row[$i];
								echo "<td><a href='http://localhost:1438/~cs143/ShowByYear.php?year=$year'>" . $row[$i] . "</a></td>";
							}
							else
								echo "<td>" . "N/A" . "</td>";
						}
						else if ($i == 3){ // rating
							if ($row[$i] != ""){
								$rating = $row[$i];
								echo "<td><a href='http://localhost:1438/~cs143/ShowByRating.php?rating=$rating'>" . $row[$i] . "</a></td>";
							}
							else 
								echo "<td>" . "N/A" . "</td>";
						}
						else if ($i == 4){ // company
							if ($row[$i] != ""){
								$parsed_company = explode(" ", $row[$i]);
								$url_company = $parsed_company[0];
								for ($j = 1; $j < sizeof($parsed_company); $j++){
									$url_company .= "+" . $parsed_company[$j];
								}
								echo "<td><a href='http://localhost:1438/~cs143/ShowByCompany.php?company=$url_company'>" . $row[$i] . "</a></td>";
							}
							else
								echo "<td>" . "N/A" . "</td>";
						}
					}
					echo "</tr>\n";
				}
				echo "</table>\n";	
			}
			else
				echo "<p>No matching movie</p>";
		}
		mysql_free_result($res);
		// close
		mysql_close();
	?>

</body>
</html>