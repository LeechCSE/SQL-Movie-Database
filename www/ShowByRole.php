<!DOCTYPE html>
<html>
<head>
	<title>Project 1B</title>

	<link rel="stylesheet" href="styles.css">
</head>
<body>
	<h1>Based on the role</h1>

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

		$role = "'" . $_GET["role"] . "'";
		$res = mysql_query("SELECT mid, aid FROM MovieActor WHERE role = $role;");
		
		echo "<table border=1 cellspacing=1 cellpadding=2>\n";
		echo "<tr align=center><td colspan=2><b>Information on the role:" . $_GET["role"] . "</b></td></tr>";
		echo "<tr align=center>";
		echo "<td><b>Movie title</b></td>";
		echo "<td><b>Actor name</b></td>";
		echo "</tr>\n";

		while ($row = mysql_fetch_row($res)){
			echo "<tr align=center>";
			for ($i = 0; $i < mysql_num_fields($res); $i++){
				if ($i == 0){
					$mid = $row[$i];
					$res2 = mysql_query("SELECT title FROM Movie WHERE id = $mid;");
					$titles = mysql_fetch_row($res2);
					$title = $titles[0];
					$parsed_title = explode(" ", $title);
					$url_title = $parsed_title[0];
					for ($j = 1; $j < sizeof($parsed_title); $j++){
						$url_title .= "+" . $parsed_title[$j];
					}
					echo "<td><a href='http://localhost:1438/~cs143/ShowMovies.php?title=$url_title&ready=True'>$title</a></td>";
				}
				else{
					$aid = $row[$i];
					$res2 = mysql_query("SELECT first FROM Actor WHERE id = $aid;");
					$firsts = mysql_fetch_row($res2);
					$first = $firsts[0];
					$res2 = mysql_query("SELECT last FROM Actor WHERE id = $aid;");
					$lasts = mysql_fetch_row($res2);
					$last = $lasts[0];

					echo "<td><a href='http://localhost:1438/~cs143/ShowActors.php?actor_last=$last&actor_first=$first&ready=True'>$first $last</a></td>";
				}
			}
			echo "</tr>\n";
		}
		echo "</table>\n";

		mysql_free_result($res);
		mysql_free_result($res2);
		// close
		mysql_close();
		
	?>

</body>
</html>