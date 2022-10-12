<?php
// STEP 1. Esatblish a Database Connection by storing database credentials 
$host = "303.itpwebdev.com";
$user = "duquesna_db_user";
$password = "cherryCoke12!";
$db = "duquesna_book_db";

$isUpdated = false;
//var_dump($_POST);

// Check that the require fields are given
if ( !isset($_POST['title']) || empty($_POST['title'])
    || !isset($_POST['genre_id']) || empty($_POST['genre_id'])
    || !isset($_POST['author']) || empty($_POST['author'])
    || !isset($_POST['wine_id']) || empty($_POST['wine_id']) ) {

	// Missing required fields.
	$error = "Please fill out all required fields.";
}
else {

	// Connect to the database to update the song
	// DB Connection.
	$mysqli = new mysqli($host, $user, $password, $db);
	if ($mysqli->connect_errno) {
		// display the error message
		echo $mysqli->connect_error;
		// php stops running after this statement and does not run any subsequent code
		exit();
	}


	// Use Prepared Statements
	// 1. prepare sql query with user inputs as placeholders
	$statement = $mysqli->prepare("UPDATE books SET title = ?, author_id = ?, genre_id = ?, wine_id = ? WHERE id = ?");


	// 2. Bind variables to the placeholders by stating each variable, along with its data type
	// 1st arg: data types
	// 2nd+ arg: alll the user inputs, in order
	$statement->bind_param("siiii", $_POST["title"], $_POST["author"], $_POST["genre_id"], $_POST["wine_id"], $_POST["book_id"]);
    // var_dump($_POST);

	// 3. Execute the statement!
	// execute() will return false if an error occurred
	$executed = $statement->execute();
    //print_r($executed);
	if(!$executed) {
		echo $mysqli->error;
	}

	// Similar to insert, check affected_rows to see if update was succesful
	// echo "<hr>" . $statement->affected_rows;
//print_r($statement->affected_rows );
	if( $statement->affected_rows == 1 ) {
		$isUpdated = true;
	} else {
        $error ="No changes made!";
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
	<title>Edit Confirmation | Book Database</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	<!-- Reference Stylesheet -->
	<link rel="stylesheet" type="text/css" href="confirmation.css">
	<!-- Special Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Cedarville+Cursive&display=swap" rel="stylesheet">
</head>
<body>
	<ol class="breadcrumb">
		<a class="navbar-brand">Spines & Wines</a>
		<li class="breadcrumb-item"><a href="index.php">Home</a></li>
		<li class="breadcrumb-item"><a href="search_form.php">Search</a></li>
		<li class="breadcrumb-item"><a href="edit_form.php?book_id=<?php echo $_POST['book_id']; ?>&title=<?php echo $_POST['title'];?>">Edit</a></li>
		<li class="breadcrumb-item active">Confirmation</li>
	</ol>
	<div class="container">
		<div class="row">
			<h1 class="col-12 mt-4">Edit a Book</h1>
		</div> <!-- .row -->
	</div> <!-- .container -->
	<div class="container">
		<div class="row mt-4">
			<div class="col-12">

			<?php if ( isset($error) && !empty($error) ) : ?>
				<div class="text-danger font-italic">
					<?php echo $error; ?>
				</div>
			<?php endif; ?>

			<?php if ($isUpdated) : ?>
				<div class="text-success">
					<span class="font-italic"><?php echo $_POST['title']; ?></span> was successfully edited.
				</div>
			<?php endif; ?>
		

			</div> <!-- .col -->
		</div> <!-- .row -->
		<div class="row mt-4 mb-4">
			<div class="col-12">
				<a href="edit_form.php?book_id=<?php echo $_POST['book_id']; ?>&title=<?php echo $_POST['title'];?>" role="button" class="btn btn-primary">Back to Edit Form</a>
			</div> <!-- .col -->
		</div> <!-- .row -->
	</div> <!-- .container -->
</body>
</html>