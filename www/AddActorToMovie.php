<!DOCTYPE html>
<html>
<head>
	<title>Project 1B</title>

	<link rel="stylesheet" href="styles.css">
</head>
<body>
	<h1>Add New Actors to Movies</h1>

	<div class="topnav">
	  <a href="http://localhost:1438/~cs143/index.php">Search by Keyword</a>
	  <a href="http://localhost:1438/~cs143/AddActorDirector.php">Add a New Actor/Director</a>
	  <a href="http://localhost:1438/~cs143/AddMovie.php">Add a New Movie</a>
	  <a href="http://localhost:1438/~cs143/AddComments.php">Add a New Review</a>
	  <a class="active" href="http://localhost:1438/~cs143/AddActorToMovie.php">Add a New Actor to Movie</a>
	  <a href="http://localhost:1438/~cs143/AddDirectorToMovie.php">Add a New Director to Movie</a>
	  <a href="http://localhost:1438/~cs143/ShowActors.php">Search an Actor</a>
	  <a href="http://localhost:1438/~cs143/ShowMovies.php">Search a Movie</a>
	</div>

	<form action="" method="GET">
		Select Movie to add an actor to: 
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
		<br><br>
		Actor <br>
		Last name:<input type="text" name="actor_last">
		First name:<input type="text" name="actor_first">
		Role: <input type="text" name="role">
		
		<input type="hidden" name="ready" value=True>
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
		$db_sel = mysql_select_db("CS143", $db_connection);
		if (!$db_sel){
			echo mysql_error($db_sel);
			exit(1);
		}
		// obtain user inputs
		$title = "'".$_GET["title"]."'";
		$res = mysql_query("SELECT id FROM Movie WHERE title = $title;", $db_connection);
		$ids = mysql_fetch_row($res);
		$mid = $ids[0];
		$actor_last = "'".$_GET["actor_last"]."'";
		$actor_first = "'".$_GET["actor_first"]."'";
		$role = "'".$_GET["role"]."'";
		mysql_free_result($res);
		// search actor
		$query_search_actor = "SELECT id FROM Actor WHERE last = $actor_last AND first=$actor_first;";
		$res = mysql_query($query_search_actor, $db_connection);
		$num_rows = mysql_num_rows($res);
		// add actor to MovieActor
		if ($_GET["ready"]){
			if ($num_rows == 1){ // actor matched
				$actor_row = mysql_fetch_row($res);
				$aid = $actor_row[0];
				$res = mysql_query("INSERT INTO MovieActor VALUES($mid, $aid, $role);", $db_connection);
				if (!$res){
					echo "ERROR: " . mysql_error();
				}

				echo "<p>Actor successfully added</p><br>";

				echo "INSERT INTO MovieActor VALUES($mid, $aid, $role); . <br>";
			}
			else{
				echo "<p>ERROR: The actor doesn't exist in DB</p><br>";
			}
		}

		mysql_free_result($res);
		// close
		mysql_close();
	?>

</body>
</html>