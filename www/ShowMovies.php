<!DOCTYPE html>
<html>
<head>
	<title>Project 1B</title>

	<link rel="stylesheet" href="styles.css">
</head>
<body>
	<h1>Search Movies</h1>

	<div class="topnav">
	  <a href="http://localhost:1438/~cs143/index.php">Search by Keyword</a>
	  <a href="http://localhost:1438/~cs143/AddActorDirector.php">Add a New Actor/Director</a>
	  <a href="http://localhost:1438/~cs143/AddMovie.php">Add a New Movie</a>
	  <a href="http://localhost:1438/~cs143/AddComments.php">Add a New Review</a>
	  <a href="http://localhost:1438/~cs143/AddActorToMovie.php">Add a New Actor to Movie</a>
	  <a href="http://localhost:1438/~cs143/AddDirectorToMovie.php">Add a New Director to Movie</a>
	  <a href="http://localhost:1438/~cs143/ShowActors.php">Search an Actor</a>
	  <a class="active" href="http://localhost:1438/~cs143/ShowMovies.php">Search a Movie</a>
	</div>

	<div class="content">
		<form action="" method="GET">
			Select a movie:
			<select name="title">
				<?php
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
					$res = mysql_query("SELECT title FROM Movie ORDER BY title;", $db_connection);
					if (!$res) {
					    echo 'ERROR: failed to retrive movies: ' . mysql_error();
					    exit;
					}

					while ($titles = mysql_fetch_row($res)){
						echo "<option> " . $titles[0] . " </option>\n";
					}

					mysql_close();
				?>
			</select>

			<input type="hidden" name="ready" value=True>
			<br><br>
			<input type="submit" value="Search">
			<br><br>
		</form>
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

		// obtain user query
		$title = "'".$_GET["title"]."'";
		// form search query
		$query_search_movie = "SELECT * FROM Movie WHERE title = $title;";
		// Movie info table
		$res = mysql_query($query_search_movie, $db_connection);
		// movie info
		$mid = 0;
		if ($_GET["ready"]){
			$row = mysql_fetch_row($res);

			echo "<table border=1 cellspacing=1 cellpadding=2>\n";

			echo "<tr align=center><td colspan='2'><b>Movie Information</b></td></tr>";

			echo "<tr align=center>";
			echo "<td><b>Title</b></td> <td>" . $row[1] . "</td>";
			echo "</tr>";

			echo "<tr align=center>";
			echo "<td><b>Year</b></td> <td>" . $row[2] . "</td>";
			echo "</tr>";

			echo "<tr align=center>";
			echo "<td><b>MPAA Rating</b></td> <td>" . $row[3] . "</td>";
			echo "</tr>";

			echo "<tr align=center>";
			echo "<td><b>Company</b></td> <td>" . $row[4] . "</td>";
			echo "</tr>";

			echo "<tr align=center>";
			echo "<td><b>Genre</b></td> <td>";
			

			$mid = $row[0];
			$res_gen = mysql_query("SELECT genre FROM MovieGenre WHERE mid = $mid;", $db_connection);
			while ($row_gen = mysql_fetch_row($res_gen)){
				echo $row_gen[0] . " ";
			}

			echo "</td>";

			echo "</tr>";
			echo "</table>";
			mysql_free_result($res_gen);
		}
		// actor info
		if ($_GET["ready"]){
			$res = mysql_query("SELECT * FROM MovieActor WHERE mid = $mid;", $db_connection);
			$num_rows = mysql_num_rows($res);

			if ($num_rows != 0){
				echo "<table id='table' border=1 cellspacing=1 cellpadding=2>\n";
				echo "<tr align=center><td colspan='2'><b>Movie's actors</b></td></tr>";
				echo "<tr align=center>";
				echo "<td><b>Name<b></td>";
				echo "<td><b>Role<b></td>";
				echo "</tr>\n";

				while ($row = mysql_fetch_row($res)){
					echo "<tr align=center>";
					for ($i = 1; $i < mysql_num_fields($res); $i++){
						if ($i == 0)
							continue;
						else if ($i == 1){
							$aid = $row[1];
							$res2 = mysql_query("SELECT first FROM Actor WHERE id = $aid", $db_connection);
							$fnames = mysql_fetch_row($res2);
							$first = $fnames[0];
							$res2 = mysql_query("SELECT last FROM Actor WHERE id = $aid", $db_connection);
							$lnames = mysql_fetch_row($res2);
							$last = $lnames[0];

							echo "<td><a href='http://localhost:1438/~cs143/ShowActors.php?actor_last=$last&actor_first=$first&ready=True'>$first $last</a></td>"; // actor LINK: show the actor info
						}
						else{
							if ($row[$i] != ""){
								$parsed_role = explode(" ", $row[$i]);
								$url_role = $parsed_role[0];
								for ($j = 1; $j < sizeof($parsed_role); $j++){
									$url_role .= "+" . $parsed_role[$j];
								}
								echo "<td><a href='http://localhost:1438/~cs143/ShowByRole.php?role=$url_role'>" . $row[$i] . "</a></td>"; // role link: show (movie, actor) for the role
							}
							else
								echo "<td>" . "N/A" . "</td>";
						}
					}
					echo "</tr>\n";
				}
				echo "</table><br>\n";
			}
			else
				echo "No actors in the movie<br>";
		}
		// avg. score
		if ($_GET["ready"]){
			echo "<table border=1 cellspacing=1 cellpadding=2>\n";
			echo "<tr align=center><td colspan='2'><b>Worth to watch?</b></td></tr>";
			echo "<tr align=center>";
			echo "<td><b>Average Rating</b></td>";
			echo "<td>";

			$res = mysql_query("SELECT CAST(AVG(rating) AS DECIMAL(10, 2)) FROM Review WHERE mid = $mid;");
			$row = mysql_fetch_row($res);
			$avg = $row[0];
			echo $avg . " / 5.00";

			echo "</td>";
			echo "</tr>";
			echo "</table>";
		}

		// user comments
		if ($_GET["ready"]){
			$res = mysql_query("SELECT name, time, rating, comment FROM Review WHERE mid = $mid;");
			$num_rows = mysql_num_rows($res);
			if ($num_rows != 0){
				echo "<table border=1 cellspacing=1 cellpadding=2>\n";
				echo "<tr align=center><td colspan='4'><b>Reviews</b></td></tr>";
				echo "<tr align=center>";
				for ($i = 0; $i < mysql_num_fields($res); $i++){
					$field = mysql_fetch_field($res, $i);
					echo "<td><b>" . $field->name . "</b></td>";
				}
				echo "</tr>\n";

				while ($row = mysql_fetch_row($res)){
					echo "<tr align=center>";
					for ($i = 0; $i < mysql_num_fields($res); $i++){

						if ($i == 2){
							if ($row[$i] != "")
								echo "<td>" . $row[$i] . " / 5" . "</td>";
							else
								echo "<td>" . "N/A" . "</td>";
						}
						else{
							if ($row[$i] != "")
								echo "<td>" . $row[$i] . "</td>";
							else
								echo "<td>" . "N/A" . "</td>";
						}
					}
					echo "</tr>\n";
				}
				echo "</table>\n";
			}
		}
	?>
		<form>
			<input class="button" type="button" value="Write a New Review" onclick="location.href='http://localhost:1438/~cs143/AddComments.php'"/>
		</form>
	<?php
		mysql_free_result($res);
		mysql_free_result($res2);
		// close
		mysql_close();
	?>
</body>
</html>