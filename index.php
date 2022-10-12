<?php
	// STEP 1. Establish a Database Connection by storing database credentials
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
	// Default
	$sql = "SELECT books.image_path, books.title, authors.first_name, authors.last_name
			FROM books
			LEFT JOIN authors
			ON books.author_id = authors.id";

	// STEP 3. Submit the query
	$results = $mysqli->query($sql);
	if (!$results) {
		echo $mysqli->error;
		exit();
	}

	// STEP 4. Close the Database Connection
	$mysqli->close();
?>



<!DOCTYPE html>
<html>
<head>
	 <!-- Bootstrap's css -->
	 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	 <!-- Reference Stylesheet -->
	 <link rel="stylesheet" type="text/css" href="index.css">
	<title>Spines & Wines</title>
	<!-- Must have this meta tag to make a web page responsive -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Special Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Cedarville+Cursive&display=swap" rel="stylesheet">
</head>
<body>
	<!-- Nav Bar -->
    <nav class="navbar navbar-expand-md navbar-light bg-light">
        <div class="container-fluid">
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <a class="navbar-brand" href="index.php">Spines & Wines</a>
          <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="index.php">Home</a>
              </li>
			</li>
			<li class="nav-item">
			  <a class="nav-link" href="search_form.php">Search</a>
			</li>
			<li class="nav-item">
			  <a class="nav-link" href="about.php">About</a>
			</li>
            </ul>
          </div>
        </div>
    </nav>

	<!-- header section - see index.css for background image details -->
	<div id="header"></div>

	<!-- Book Collection  -->
	<div id="all">
		<h2>Book Collection</h2>
		<div class="row" >
		<!-- <div class="col-4 col-md-3 col-lg-2"> -->
	
			<!-- PHP to loop through books stored in database and display their cover images -->
				<?php while($row = $results->fetch_assoc()):?>
					<div class="col-4 col-md-3 col-lg-2" onmouseover="mouseOut()">
					<div class="item">
						<!-- append book title id and book author name -->
						<div><img src="<?php echo $row['image_path']; ?>" /></div>
						<div class="title"><?php echo $row["title"]?></div>
						<div class="author"><?php echo $row["first_name"] . " " . $row["last_name"];?></div>
					</div>
				</div>
				<?php endwhile;?>

			</div>
			
		</div>
	</div>
	<!-- Bootstrap JavaScript Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">


      function onmouseover() {
        document.querySelector("#img").style.transform = "rotate(90deg)";
      }


	</script>

	<div id="footer">
		Spines & Wines ©2022
	</div>

</body>
</html>