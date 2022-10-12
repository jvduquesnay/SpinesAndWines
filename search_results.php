<?php
	$php_array = [
		"first_name" => "Tommy",
		"last_name" => "Trojan",
		"age" => 21,
		"phone" => [
			"cell" => "123-123-1234",

			"home" => "456-456-4567"
		],
	];
	// $_GET is an associative array that stores the info that the user gave us from search_form.php
	//var_dump($_GET);

	// STEP 1. Esatblish a Database Connection by storing database credentials
	$host = "303.itpwebdev.com";
	$user = "duquesna_db_user";
	$password = "cherryCoke12!";
	$db = "duquesna_book_db";

	$mysqli = new mysqli($host, $user, $password, $db);

	if ($mysqli->connect_errno) {
		// display the error message
		echo $mysqli->connect_error;
		// php stops running after this statement and does not run any subsequent code
		exit();
	}

	// STEP 2. Generate & Submit the SQL query
	$sql = "SELECT books.title, genres.genre, authors.first_name, authors.last_name, wines.wine, books.id
	 		FROM books 
			LEFT JOIN genres
				ON genres.id = books.genre_id
			LEFT JOIN authors
				ON authors.id = books.author_id
			LEFT JOIN wines
				ON wines.id = books.wine_id 
			WHERE  1=1 ";
	// If user provides a title search criteria append an AND statement to the base SQL statement
	if (isset($_GET["title"]) && !empty($_GET["title"]) ) {
		$sql = $sql . " AND books.title LIKE '%" . $_GET["title"] . "%'";
	}

	// If user provides a genre search criteria append an AND statement to the base SQL statement
	if (isset($_GET["genre"]) && !empty($_GET["genre"]) ) {
		$sql = $sql . " AND books.genre_id =" . $_GET["genre"];
	}
	

	// If user provides an author search criteria append an AND statement to the base SQL statement
	if (isset($_GET["author"]) && !empty($_GET["author"])) {
		$sql = $sql . " AND books.author_id = " . $_GET["author"]  ;
	}
	

	// If user provides an wine search criteria append an AND statement to the base SQL statement
	if (isset($_GET["wine"]) && !empty($_GET["wine"])) {
		$sql = $sql . " AND books.wine_id = " . $_GET["wine"];
	}


	$sql = $sql . ";";
	// Display the sql statement to double check the SQL statement
	// echo "<hr>";
	// echo $sql;
	 //echo "<br/>";

	// Submit the query
	$results = $mysqli->query($sql);
	if (!$results) {
		echo $mysqli->error;
		exit();
	}

	// Create an array that will be filled with results which will be sent to the front-end
	$results_array = [];

	// fill the results array with the results
	while( $row = $results->fetch_assoc()) {
		array_push($results_array, $row);
	}

	// Convert the results into a string
	echo json_encode($results_array);

	// STEP 4. Close the Database Connection
	$mysqli->close();
?>

