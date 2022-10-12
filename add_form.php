<?php
// STEP 1. Esatblish a Database Connection by storing database credentials
$host = "303.itpwebdev.com";
$user = "duquesna_db_user";
$password = "cherryCoke12!";
$db = "duquesna_book_db";

// Create an instance of the mysqli class, mysqli class will attempt to connect to the database using the credentials provided
$mysqli = new mysqli($host, $user, $password, $db);

// Check for database connection errors, connect_erno returns an error number if there was an error. Will return false if no error was detected
if ($mysqli->connect_errno) {
	// display the error message
	echo $mysqli->connect_error;
	// php stops running after this statement and does not run any subsequent code
	exit();
}

// STEP 2. Generate & Submit the SQL query
$sql = "SELECT * FROM genres;";

// Submit the query to the database, query() will return information about the results
$results = $mysqli->query($sql);
// error checking
if (!$results) {
	// display the error message
	echo $mysqli->error;
	// php stops running after this statement and does not run any subsequent code
	exit();
}

// STEP 3. Display Result
// var_dump($results);
// echo "<hr>";
// echo "Number of results: " . $results->num_rows;

// fetch_assoc() returns one result as an associative array
// fetch_assoc() will return NULL when it gets to the last result
// $row is a temporary variable that will store the fetch_assoc() result for that iteration 
while ($row = $results->fetch_assoc()) {
	// echo "<hr>";
	// var_dump($row);
}

// When you want to "reset" fetch_assoc() to show the first of the results again (not NULL) use data_seek()
$results->data_seek(0);



// AUTHOR DROPDOWN
$sql_author = "SELECT * FROM authors;";
$results_author = $mysqli->query($sql_author);
// $results will be false if there were any errors in receiving the result
if (!$results_author) {
	//display the error message
	echo $mysqli->error;
	exit();
}

// WINE DROPDOWN
$sql_wine = "SELECT * FROM wines;";
$results_wine = $mysqli->query($sql_wine);
// $results will be false if there were any errors in receiving the result
if (!$results_wine) {
	//display the error message
	echo $mysqli->error;
	exit();
}


// STEP 4. Close the Database Connection
$mysqli->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Add Form | Spines & Wines</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- Reference Stylesheet -->
	<link rel="stylesheet" type="text/css" href="add_form.css">
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
		<li class="breadcrumb-item active">Add</li>
	</ol>

	<div class="container">
		<div class="row">
			<h1 class="col-12 mt-4 mb-4">Add a Book</h1>
		</div> <!-- .row -->
	</div> <!-- .container -->

	<div class="container">

	<form enctype="multipart/form-data"  action="add_confirmation.php" method="POST">

    <div class="form-group row">
				<!--<label for="title-id" class="col-sm-3 col-form-label text-sm-right">Book <Title:--></Title:--> <span class="text-danger">*</span>
				<div class="col-sm-9">
					<input type="text" class="form-control" id="title-id" name="title" placeholder="Add Book Title" style="color: #ffffff; background-color: #4e0101; border-radius: 18px">
				</div>
			</div> <!-- .form-group -->
			
			<div class="form-group row">
				<!--<label for="author-fname" class="col-sm-3 col-form-label text-sm-right">Author First Name:--> <span class="text-danger">*</span>
				<div class="col-sm-9">
                <input type="text" class="form-control" id="author-fname" name="authorfname" placeholder="Add Author Fist Name" style="color: #ffffff; background-color: #4e0101; border-radius: 18px">
				</div>
			</div> <!-- .form-group -->
            <div class="form-group row">
				<!--<label for="author-lname" class="col-sm-3 col-form-label text-sm-right">Author Last Name:--> 
				<span class="text-danger">*</span>
				<div class="col-sm-9">
                <input type="text" class="form-control" id="author-lname" name="authorlname" placeholder="Add Author Last Name" style="color: #ffffff; background-color: #4e0101; border-radius: 18px">
				</div>
			</div> <!-- .form-group -->
			<div class="form-group row">
				<!--<label for="genre-id" class="col-sm-3 col-form-label text-sm-right">Genre: -->
					<span class="text-danger">*</span>
				<div class="col-sm-9">
					<select name="genre" id="genre-id" class="form-control" style="color: #ffffff; background-color: #4e0101; border-radius: 18px">
						<option value="" selected>- Select a Genre -</option>

						<!-- Genre dropdown options here -->
						<!-- Recommended PHP Syntax -->
						<?php while ($row = $results->fetch_assoc()): ?>
							<option value="<?php echo $row["id"] ?>">
								<?php echo $row["genre"]; ?>
							</option>
						<?php endwhile; ?>

					</select>
				</div>
			</div> <!-- .form-group -->
			<div class="form-group row">
				<!--<label for="wine-id" class="col-sm-3 col-form-label text-sm-right">Wine:-->
					<span class="text-danger">*</span>
				<div class="col-sm-9">
					<select name="wine" id="wine-id" class="form-control" style="color: #ffffff; background-color: #4e0101; border-radius: 18px">
						<option value="" selected>- Select a Wine --</option>

						<!-- Label dropdown options here -->
						<!-- Recommended PHP Syntax -->
						<?php while ($row = $results_wine->fetch_assoc()): ?>
							<option value="<?php echo $row["id"] ?>">
								<?php echo $row["wine"]; ?>
							</option>
						<?php endwhile; ?>

					</select>
				</div>			
			</div> <!-- .form-group -->
            <div class="form-group row">
				<label for="fileToUpload" class="col-sm-3 col-form-label text-sm-right">Image Upload: <span class="text-danger">*</span></label>
				<div class="col-sm-9">
                 <input type="file" name="fileToUpload" id="fileToUpload">
                 </div>			
			</div> <!-- .form-group -->
         

            <div class="form-group row">
				<div class="ml-auto col-sm-9">
					<span class="text-danger font-italic">* Required</span>
				</div>
			</div> <!-- .form-group -->

			<div class="form-group row">
				<div class="col-sm-3"></div>
				<div class="col-sm-9 mt-2">
					<button type="submit" class="btn btn-primary">Submit</button>
					<button type="reset" class="btn btn-light">Reset</button>
				</div>
			</div> <!-- .form-group -->

		</form>
		

	</div> <!-- .container -->
	<!-- <script>
	// Some JS to pop up a message before user commits to deleting a track.
		let submitButtons = document.querySelectorAll(".submit-btn");

		for( let i = 0; i < submitButtons.length; i++ ) {
			submitButtons[i].onclick = function() {
				// Show a popup here
			return confirm("Is the information entered all correct?");
		}
	</script> -->
    <div id="footer"></div>
</body>
</html>