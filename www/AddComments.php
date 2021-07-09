<!DOCTYPE html>
<html>
<head>
	<title>Project 1B</title>

	<link rel="stylesheet" href="styles.css">
</head>
<body>
	<h1>Add New Comments to Movies</h1>

	<div class="topnav">
	  <a href="http://localhost:1438/~cs143/index.php">Search by Keyword</a>
	  <a href="http://localhost:1438/~cs143/AddActorDirector.php">Add a New Actor/Director</a>
	  <a href="http://localhost:1438/~cs143/AddMovie.php">Add a New Movie</a>
	  <a class="active" href="http://localhost:1438/~cs143/AddComments.php">Add a New Review</a>
	  <a href="http://localhost:1438/~cs143/AddActorToMovie.php">Add a New Actor to Movie</a>
	  <a href="http://localhost:1438/~cs143/AddDirectorToMovie.php">Add a New Director to Movie</a>
	  <a href="http://localhost:1438/~cs143/ShowActors.php">Search an Actor</a>
	  <a href="http://localhost:1438/~cs143/ShowMovies.php">Search a Movie</a>
	</div>

	<form action="" method="GET">
		Username: <input type="text" name="name">
		<br>
		Movie:
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
		<br>
		Rating: 
		<select name="rating">
			<option>0</option>
			<option>1</option>
			<option>2</option>
			<option>3</option>
			<option>4</option>
			<option>5</option>
		</select>
		<br>
		comment: <br>
		<textarea name="comment" cols="60" rows="8"></textarea>
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
		// obtain user inputs
		$type = "Review";
		$name = "'".$_GET["name"]."'";
		$time = "default"; // time attribute has default of CURRENT_TIMESTAMP
		$title = "'".$_GET["title"]."'";
		$res = mysql_query("SELECT id FROM Movie WHERE title = $title;", $db_connection);
		if (!$res) {
		    echo 'ERROR: failed to retrive movies: ' . mysql_error();
		    exit;
		}
		$mid_row = mysql_fetch_row($res);
		$mid = $mid_row[0];
		$rating = $_GET["rating"];
		$comment = $_GET["comment"] ? "'".$_GET["comment"]."'" : 'N/A';
		// form INSERT query
		$query_insert = "INSERT INTO $type VALUES($name, $time,$mid, $rating, $comment);";

		// echo $query_insert;

		if(mysql_query($query_insert, $db_connection)){
			echo "<p>$type Successfully added</p><br>\n";
		}

		mysql_free_result($res);
		// close
		mysql_close();
	?>
</body>
</html>