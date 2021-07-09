<!DOCTYPE html>
<html>
<head>
	<title>Project 1B</title>

	<link rel="stylesheet" href="styles.css">
</head>
<body>
	<h1> Add New Movie </h1>

	<div class="topnav">
	  <a href="http://localhost:1438/~cs143/index.php">Search by Keyword</a>
	  <a href="http://localhost:1438/~cs143/AddActorDirector.php">Add a New Actor/Director</a>
	  <a class="active" href="http://localhost:1438/~cs143/AddMovie.php">Add a New Movie</a>
	  <a href="http://localhost:1438/~cs143/AddComments.php">Add a New Review</a>
	  <a href="http://localhost:1438/~cs143/AddActorToMovie.php">Add a New Actor to Movie</a>
	  <a href="http://localhost:1438/~cs143/AddDirectorToMovie.php">Add a New Director to Movie</a>
	  <a href="http://localhost:1438/~cs143/ShowActors.php">Search an Actor</a>
	  <a href="http://localhost:1438/~cs143/ShowMovies.php">Search a Movie</a>
	</div>

	<form action="" method="GET">
		Title: <input type="text" name="title">
		<br>
		Year: <input type="text" name="year" placeholder="(YYYY)">
		<br><br>
		Director <br>
		Last name:<input type="text" name="director_last">
		First name:<input type="text" name="director_first">
		<br><br>
		Actor <br>
		Last name:<input type="text" name="actor_last">
		First name:<input type="text" name="actor_first">
		Role: <input type="text" name="role">
		<br><br>
		MPAA Rating: 
		<select name="rating">
			<option>Rating</option> 
			<option>G</option>
			<option>PG</option>
			<option>PG-13</option>
			<option>R</option>
			<option>NC-17</option>
		</select>
		<br>
		Genre:
		<div>
			Comedy<input type="checkbox" name="Comedy" value=True>
			Romance<input type="checkbox" name="Romance" value=True>
			Drama<input type="checkbox" name="Drama" value=True>
			Crime<input type="checkbox" name="Crime" value=True>
			Horror<input type="checkbox" name="Horror" value=True>
			Mystery<input type="checkbox" name="Mystery" value=True>
			Thriller<input type="checkbox" name="Thriller" value=True>
			Action<input type="checkbox" name="Action" value=True>
			Adventure<input type="checkbox" name="Adventure" value=True>
			<br>
			Fantasy<input type="checkbox" name="Fantasy" value=True>
			Documentary<input type="checkbox" name="Documentary" value=True>
			Family<input type="checkbox" name="Family" value=True>
			Sci-Fi<input type="checkbox" name="Sci-Fi" value=True>
			Animation<input type="checkbox" name="Animation" value=True>
			Musical<input type="checkbox" name="Musical" value=True>
			War<input type="checkbox" name="War" value=True>
			Western<input type="checkbox" name="Western" value=True>
			Adult<input type="checkbox" name="Adult" value=True>
			Short<input type="checkbox" name="Short" value=True>
		</div>
		<br>
		Company: <input type="text" name="company">
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

		// get current MaxPersonID
		$query_mpi = "SELECT id FROM MaxMovieID;";
		$res = mysql_query($query_mpi, $db_connection);
		if (!$res){
			echo "ERROR: " . mysql_error();
			exit;
		}
		$mmi_row = mysql_fetch_row($res);
		$mmi = $mmi_row[0];
		// advance once for assigning id
		$mmi++;
		// get current MaxPersonID
		$query_mpi = "SELECT * FROM MaxPersonID;";
		$res = mysql_query($query_mpi, $db_connection);
		$mpi_row = mysql_fetch_row($res);
		$mpi = $mpi_row[0];
		// advance once for assigning id
		$mpi++;
		mysql_free_result($res);

		// obtain user inputs
		$title = "'".$_GET["title"]."'";
		$year = $_GET["year"] ? $_GET["year"] : 'NULL';
		$director_last = "'".$_GET["director_last"]."'";
		$director_first = "'".$_GET["director_first"]."'";
		$actor_last = "'".$_GET["actor_last"]."'";
		$actor_first = "'".$_GET["actor_first"]."'";
		$role = "'".$_GET["role"]."'";
		$rating = $_GET["rating"] == "Rating" ? 'NULL' : "'".$_GET["rating"]."'";
		$company = $_GET["company"] ? "'".$_GET["company"]."'" : 'NULL';

		// INSERT query - Movie
		$query_insert_movie = "INSERT INTO Movie VALUES($mmi, $title, $year, $rating, $company);";

		// search Director
		$query_search_director = "SELECT id FROM Director WHERE last = $director_last AND first=$director_first;";
		
		// serach Actor
		$query_search_actor = "SELECT id FROM Actor WHERE last = $actor_last AND first=$actor_first;";

		// insert
		$genres = ["Comedy", "Romance", "Drama", "Crime", "Horror", 
		"Mystery", "Thriller", "Action", "Adventure", "Fantasy", 
		"Documentary", "Family", "Sci-Fi", "Animation", "Musical", 
		"War", "Western", "Adult", "Short"];

		// insert Movie
		if ($_GET["ready"]){
			if (mysql_query($query_insert_movie, $db_connection)){
				if (mysql_query("UPDATE MaxMovieID SET id = $mmi;", $db_connection)){
					foreach ($genres as $gen){
						if ($_GET[$gen]){
							$res = mysql_query("INSERT INTO MovieGenre VALUES($mmi, '$gen');", $db_connection);
							if (!$res){
								echo "ERROR: " . mysql_error();
								exit;
							}

							mysql_free_result($res);
						}
					}
					echo "<p>Movie Successfully added</p><br>\n";
				}
			}
		}
		// insert MovieDirector
		$res = mysql_query($query_search_director, $db_connection);
		$num_rows = mysql_num_rows($res);
		if ($_GET["ready"]){
			if ($num_rows == 1){ // director matched
				$director_row = mysql_fetch_row($res);
				$did = $director_row[0];
				$res = mysql_query("INSERT INTO MovieDirector VALUES($mmi, $did);", $db_connection);
				if (!$res){
					echo "MovieDirector ERROR: " . mysql_error();
					
				}
				mysql_free_result($res);

				echo "INSERT INTO MovieDirector VALUES($mmi, $did); . <br>";
			}
			else{
				echo "<p>WARNING: Director doesn't exist in DB</p><br>";
				mysql_free_result($res);
			}
		}
		// insert MovieActor
		$res = mysql_query($query_search_actor, $db_connection);
		$num_rows = mysql_num_rows($res);
		if ($_GET["ready"]){
			if ($num_rows == 1){ // actor matched
				$actor_row = mysql_fetch_row($res);
				$aid = $actor_row[0];
				$res = mysql_query("INSERT INTO MovieActor VALUES($mmi, $aid, $role);", $db_connection);
				if (!$res){
					echo "MovieActor ERROR: " . mysql_error();
					
				}
				mysql_free_result($res);

				echo "INSERT INTO MovieActor VALUES($mmi, $aid, $role); . <br>";
			}
			else{
				echo "<p>WARNING: Actor doesn't exist in DB</p><br>";
				mysql_free_result($res);
			}
		}

		mysql_free_result($res);
		// close
		mysql_close();
	?>

</body>
</html>