<?php
// STEP 1. Esatblish a Database Connection by storing database credentials
$host = "303.itpwebdev.com";
$user = "duquesna_db_user";
$password = "cherryCoke12!";
$db = "duquesna_book_db";

$isDeleted = false;

// Make sure this file gets a book id. Otherwise, file doesn't know which book to delete
if ( !isset($_GET['book_id']) || empty($_GET['book_id']) 
		|| !isset($_GET['title']) || empty($_GET['title']) ) {
	$error = "Invalid Book Title.";
}
else {
	$mysqli = new mysqli($host, $user, $password, $db);
	if ($mysqli->connect_errno) {
		// display the error message
		echo $mysqli->connect_error;
		// php stops running after this statement and does not run any subsequent code
		exit();
	}

  
    $sql_book = "SELECT * from  books where id = " . $_GET["book_id"] . ";";

    // Submit this query to the db!
    $results_book = $mysqli->query($sql_book);
    if(!$results_book) {
        echo $mysqli->error;
        exit();
    }
    
    // We will get only ONE result back 
    $row_title = $results_book->fetch_assoc();
    echo "<hr>";
	//echo "<hr>" . $sql . "<hr>";
    echo $file_to_delete = $row_title["image_path"];
    unlink( $file_to_delete ); 

	// Generate the SQL statement // is this and below the same thing but written different ways?
	$sql = "DELETE FROM books
		    WHERE id = " . $_GET["book_id"] . ";";
	//echo "<hr>" . $sql . "<hr>";


	// OR prepared statement way
	$statement = $mysqli->prepare("DELETE FROM books WHERE books.id = ?");
	$statement->bind_param("i", $_GET["book_id"]);

	$executed = $statement->execute();
	if(!$executed) {
		echo $mysqli->error;
		exit();
	}

   
	// Check that only one row was affected
	if($statement->affected_rows == 1) {
		$isDeleted = true;
	}

	$statement->close();

	$mysqli->close();
}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Delete a Book | Book Database</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="index.php">Home</a></li>
		<li class="breadcrumb-item"><a href="search_form.php">Search</a></li>
		<li class="breadcrumb-item active">Delete</li>
	</ol>
	<div class="container">
		<div class="row">
			<h1 class="col-12 mt-4">Delete a Book</h1>
		</div> <!-- .row -->
	</div> <!-- .container -->
	<div class="container">
		<div class="row mt-4">
			<div class="col-12">

				<?php if ( isset($error) && !empty($error) ) : ?>
					<div class="text-danger">
						<?php echo $error; ?>
					</div>
				<?php endif; ?>

				<?php if ( $isDeleted ) :?>
					<div class="text-success"><span class="font-italic"><?php echo $_GET["title"]; ?></span> was successfully deleted.</div>
				<?php endif; ?>


			</div> <!-- .col -->
		</div> <!-- .row -->
		<div class="row mt-4 mb-4">
			<div class="col-12">
				<a href="search_form.php" role="button" class="btn btn-primary">Back to Search Form</a>
			</div> <!-- .col -->
		</div> <!-- .row -->
	</div> <!-- .container -->
    
</body>
</html>