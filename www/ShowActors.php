<!DOCTYPE html>
<html>
<head>
	<title>Project 1B</title>

	<link rel="stylesheet" href="styles.css">
</head>
<body>
	<h1>Search Actors</h1>

	<div class="topnav">
	  <a href="http://localhost:1438/~cs143/index.php">Search by Keyword</a>
	  <a href="http://localhost:1438/~cs143/AddActorDirector.php">Add a New Actor/Director</a>
	  <a href="http://localhost:1438/~cs143/AddMovie.php">Add a New Movie</a>
	  <a href="http://localhost:1438/~cs143/AddComments.php">Add a New Review</a>
	  <a href="http://localhost:1438/~cs143/AddActorToMovie.php">Add a New Actor to Movie</a>
	  <a href="http://localhost:1438/~cs143/AddDirectorToMovie.php">Add a New Director to Movie</a>
	  <a class="active" href="http://localhost:1438/~cs143/ShowActors.php">Search an Actor</a>
	  <a href="http://localhost:1438/~cs143/ShowMovies.php">Search a Movie</a>
	</div>

	<form action="" method="GET">
		Actor <br>
		Last name: <input type="text" name="actor_last">
		First name: <input type="text" name="actor_first">
		<br>

		<input type="hidden" name="ready" value=True>
		<br><br>
		<input type="submit" value="Submit">
		<br><br>
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

		// obtain user query
		$actor_last = "'".$_GET["actor_last"]."'";
		$actor_first = "'".$_GET["actor_first"]."'";
		// form search query
		$query_search_actor = "SELECT * FROM Actor WHERE last=$actor_last AND first=$actor_first;";
		// Actor info table
		$res = mysql_query($query_search_actor, $db_connection);
		$num_rows = mysql_num_rows($res);
		$aid = 0;
		if ($_GET["ready"]){
			if ($num_rows != 0){
				echo "<table border=1 cellspacing=1 cellpadding=2>\n";
				echo "<tr align=center><td colspan='5'><b>Actor information</b></td></tr>";
				echo "<tr align=center>";

				for ($i = 1; $i < mysql_num_fields($res); $i++){
					$field = mysql_fetch_field($res, $i);
					echo "<td><b>" . $field->name . "</b></td>";
				}
				echo "</tr>\n";

				while ($row = mysql_fetch_row($res)){
					echo "<tr align=center>";
					for ($i = 1; $i < mysql_num_fields($res); $i++){
						$aid = $row[0];
						if ($row[$i] != "")
							echo "<td>" . $row[$i] . "</td>";
						else
							echo "<td>" . "N/A" . "</td>";
					}
					echo "</tr>\n";
				}
				echo "</table>\n";

				mysql_free_result($res);
			}
			else{
				echo "<p>ERROR: The actor doesn't exist in DB</p><br>";
			}
		}
		// Movie and Role table
		$query_search_movieActor = "SELECT * FROM MovieActor WHERE aid = $aid;";
		$res = mysql_query($query_search_movieActor, $db_connection);
		$num_rows = mysql_num_rows($res);
		if ($_GET["ready"]){
			if ($num_rows != 0){
				echo "<table border=1 cellspacing=1 cellpadding=2>\n";
				echo "<tr align=center><td colspan='2'><b>Actor's movies and roles</b></td></tr>";
				echo "<tr align=center>";
				echo "<td><b>Movie title</b></td> <td><b>Role</b></td>";
				echo "</tr>\n";

				while ($row = mysql_fetch_row($res)){
					echo "<tr align=center>";
					for ($i = 0; $i < mysql_num_fields($res); $i++){
						$mid = $row[0];
						if ($i == 0){ // convert mid -> movie title
							$res2 = mysql_query("SELECT title FROM Movie WHERE id = $mid", $db_connection);
							$titles = mysql_fetch_row($res2);
							$title = $titles[0];
							$parsed_title = explode(" ", $title);
							$url_title = $parsed_title[0];
							for ($j = 1; $j < sizeof($parsed_title); $j++){
								$url_title .= "+" . $parsed_title[$j];
							}
							echo "<td><a href='http://localhost:1438/~cs143/ShowMovies.php?title=$url_title&ready=True'>$title</a></td>"; // TITLE LINK: show the movie info
						}
						else if ($i == 1) // skip aid
							continue;
						else{ // role
							$aid = $row[1];
							$parsed_role = explode(" ", $row[$i]);
							$url_role = $parsed_role[0];
							for ($j = 1; $j < sizeof($parsed_role); $j++){
								$url_role .= "+" . $parsed_role[$j];
							}
							if ($row[$i] != "")
								echo "<td><a href='http://localhost:1438/~cs143/ShowByRole.php?role=$url_role'>" . $row[$i] . "</a></td>"; // ROLE LINK: show (movie, actor) for the role
							else
								echo "<td>" . "N/A" . "</td>";
						}
					}
					echo "</tr>\n";
				}
				echo "</table>\n";

				mysql_free_result($res);
				mysql_free_result($res2);
			}
			else{ // no movie casting, DO NOTHING
				echo "<p>No movies for the actor</p><br>";
			}
		}
		mysql_free_result($res);
		mysql_free_result($res2);
		// close
		mysql_close();
	?>
</body>
</html>