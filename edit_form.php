<?php
// STEP 1. Establish a Database Connection by storing database credentials
$host = "303.itpwebdev.com";
$user = "duquesna_db_user";
$password = "cherryCoke12!";
$db = "duquesna_book_db";

// Check that all the required fields have been given to this page
if ( !isset($_GET['title']) || empty($_GET['title']) 
    || (!isset($_GET['book_id']) || empty($_GET['book_id']))) {

	echo "Invalid book title id";
    //var_dump($_GET);
	exit();
}


// DB Connection.
$mysqli = new mysqli($host, $user, $password, $db);
	if ($mysqli->connect_errno) {
		// display the error message
		echo $mysqli->connect_error;
		// php stops running after this statement and does not run any subsequent code
		exit();
	}

$mysqli->set_charset('utf8');

// Get the details of this book and use it to prepoulate the form
$sql_book = "SELECT a.* , b.first_name,b.last_name from  books a , authors b WHERE a.author_id=b.id and a.id = " . $_GET["book_id"] . ";";

// Submit this query to the db!
$results_book = $mysqli->query($sql_book);
if(!$results_book) {
	echo $mysqli->error;
	exit();
}

// We will get only ONE result back 
$row_title = $results_book->fetch_assoc();
// echo "<hr>";
//var_dump($row_title);


// Genres:
$sql_genre = "SELECT * FROM genres;";
$results_genres = $mysqli->query($sql_genre);
if ( $results_genres == false ) {
	echo $mysqli->error;
	exit();
}

// Authors:
$sql_author = "SELECT * FROM authors;";
$results_author = $mysqli->query($sql_author);
if ( $results_author == false ) {
	echo $mysqli->error;
	exit();
}

// Wines:
$sql_wine = "SELECT * FROM wines;";
$results_wine = $mysqli->query($sql_wine);
if ( $results_wine == false ) {
	echo $mysqli->error;
	exit();
}


// Close DB Connection
$mysqli->close();

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Edit Form | Book Database</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	<!-- Reference Stylesheet -->
	<link rel="stylesheet" type="text/css" href="edit_form.css">
	<!-- Special Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Cedarville+Cursive&display=swap" rel="stylesheet">
	<style>
		.form-check-label {
			padding-top: calc(.5rem - 1px * 2);
			padding-bottom: calc(.5rem - 1px * 2);
			margin-bottom: 0;
		}
	</style>
</head>
<body>

	<ol class="breadcrumb">
		<a class="navbar-brand">Spines & Wines</a>
		<li class="breadcrumb-item"><a href="index.php">Home</a></li>
		<li class="breadcrumb-item"><a href="search_form.php">Search</a></li>
        <!--
		<li class="breadcrumb-item"><a href="search_results.php?title=<?php echo $_GET['book_id'];?>">Results</a></li>
    -->
		<li class="breadcrumb-item active">Edit</li>
	</ol>

	<div class="container">
		<div class="row">
			<h1 class="col-12 mt-4 mb-4">Edit a Book</h1>
		</div> <!-- .row -->
	</div> <!-- .container -->

	<div class="container">

	
			<?php if ( isset($error) && !empty($error) ) : ?>	
				<div class="col-12 text-danger">
					<?php echo $error; ?>
			</div>
			<?php endif; ?>

			<form action="edit_confirmation.php" method="POST">

				<div class="form-group row">
					<label for="title-id" class="col-sm-3 col-form-label text-sm-right">Book Title: <span class="text-danger">*</span></label>
					<div class="col-sm-9">
					<input type="text" class="form-control" id="name-id" name="title" style="background-color: #4e0101; color: #ffffff; border-radius: 18px" value="<?php echo $row_title['title'] ?>">
                    <input name="book_id" id="book_id" type="hidden" value="<?=$row_title["id"]?>" />
					</div>
				</div> <!-- .form-group -->

                <div class="form-group row">
					<label for="genre-id" class="col-sm-3 col-form-label text-sm-right">Genre: <span class="text-danger">*</span></label>
					<div class="col-sm-9">
						<select name="genre_id" id="genre-id" class="form-control" style="background-color: #4e0101; color: #ffffff; border-radius: 18px">
							<option value="" selected>-- Select One --</option>

							<?php while( $row = $results_genres->fetch_assoc() ): ?>

								<?php if( $row['id'] == $row_title['genre_id']) : ?>
									<!-- Add 'selected' attribute to the genre of this track. This will make this option show up first. -->
									<option selected value="<?php echo $row['id']; ?>">
										<?php echo $row['genre']; ?>
									</option>

								<?php else : ?>
									<!-- All other dropdown options are still shown, but does not have the 'selected' attribute -->
									<option value="<?php echo $row['id']; ?>">
										<?php echo $row['genre']; ?>
									</option>
								<?php endif; ?>

							<?php endwhile; ?>

						</select>
					</div>
			    </div> <!-- .form-group -->

			<div class="form-group row">
				<label for="author-id" class="col-sm-3 col-form-label text-sm-right">Author:<span class="text-danger">*</span></label>
				<div class="col-sm-9">
					<select name="author" id="author-id" class="form-control" style="background-color: #4e0101; color: #ffffff; border-radius: 18px">
						<option value="" selected>-- Select One --</option>

						<!-- Author dropdown options here -->
						<!-- Recommended PHP Syntax -->
						<?php while ($row = $results_author->fetch_assoc()): ?>

                            <?php if( $row['id'] == $row_title['author_id']) : ?>
									<!-- Add 'selected' attribute to the genre of this track. This will make this option show up first. -->
									<option selected value="<?php echo $row['id']; ?>">
										<?php echo $row["first_name"] . " " . $row["last_name"]; ?>
									</option>
                            
                            <?php else : ?>
									<!-- All other dropdown options are still shown, but does not have the 'selected' attribute -->
									<option value="<?php echo $row['id']; ?>">
										<?php echo $row["first_name"] . " " . $row["last_name"]; ?>
									</option>
							<?php endif; ?>
								
						<?php endwhile; ?>

					</select>
				</div>
			</div> <!-- .form-group -->

				<div class="form-group row">
					<label for="wine-id" class="col-sm-3 col-form-label text-sm-right">Wine: <span class="text-danger">*</span></label>
					<div class="col-sm-9">
                        <select name="wine_id" id="wine-id" class="form-control" style="background-color: #4e0101; color: #ffffff; border-radius: 18px">
							<option value="" selected>-- Select One --</option>
							<?php while( $row = $results_wine->fetch_assoc() ): ?>

								<?php if( $row["id"] == $row_title["wine_id"]) : ?>
									<!-- Add 'selected' attribute to the label of this track. This will make this option show up first. -->
									<option selected value="<?php echo $row["id"]; ?>">
										<?php echo $row["wine"]; ?>
									</option>

								<?php else : ?>
									<!-- All other dropdown options are still shown, but does not have the 'selected' attribute -->
									<option value="<?php echo $row["id"]; ?>">
										<?php echo $row["wine"]; ?>
									</option>
								<?php endif; ?>

							<?php endwhile; ?>

						</select>
					</div>
				</div> <!-- .form-group -->

				<div class="form-group row">
					<div class="ml-auto col-sm-9">
						<span class="text-danger font-italic">* Required</span>
					</div>
				</div> <!-- .form-group -->

				<!-- Create a HIDDEN input in order to pass the book_title_id to edit_confirmation.php -->
				<input type="hidden" name="id" value="<?php echo $row_title['title']; ?>"/>

				<div class="form-group row">
					<div class="col-sm-3"></div>
					<div class="col-sm-9 mt-2">
						<button type="submit" class="btn btn-primary">Submit</button>
						<button type="reset" class="btn btn-light">Reset</button>
					</div>
				</div> <!-- .form-group -->

			</form>

	</div> <!-- .container -->
	<div id="footer"></div>
</body>
</html>