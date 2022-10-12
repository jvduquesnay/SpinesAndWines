<?php

$isInserted = "";

// Check that all the required fields are completed
if ( !isset($_POST['title']) || $_POST['title'] ==''
    || !isset($_POST['genre']) || empty($_POST['genre'])
    || !isset($_POST['authorfname']) || empty($_POST['authorfname'])
    || !isset($_POST['authorlname']) || empty($_POST['authorlname'])
    || !isset($_POST['wine']) || empty($_POST['wine']) 
	|| !isset($_FILES['fileToUpload']) || empty($_FILES['fileToUpload']) ) {

	// Missing required fields.
	$error = "Please fill out all required fields.";

} else {

// STEP 1. Esatblish a Database Connection by storing database credentials
$host = "303.itpwebdev.com";
$user = "duquesna_db_user";
$password = "cherryCoke12!";
$db = "duquesna_book_db";
$mysqli = new mysqli($host, $user, $password, $db);
	if ( $mysqli->errno ) {
		echo $mysqli->error;
		exit();
	}
$target_dir = "images/";
$filename = basename($_FILES["fileToUpload"]["name"]);
$target_file = $target_dir . $filename;
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
  if($check !== false) {
    //$error =  "File is an image - " . $check["mime"] . ".";
    $uploadOk = 1;
  } else {
    $error .=  "File is not an image.";
    $uploadOk = 0;
  }


// Check if file already exists
if (file_exists($target_file)) {
    $error .=  "Sorry, file already exists.";
}

// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" && $imageFileType != "webp" ) {
    $error =  "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
  	$uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    $error .=  "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} 
else {
  if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {


    $title = $_POST['title'];
  
	
	$sql = "SELECT id FROM authors WHERE first_name =? and last_name= ?"; // SQL with parameters
    $stmt = $mysqli->prepare($sql); 
    $stmt->bind_param("ss", $_POST['authorfname'],$_POST['authorlname']);
    $stmt->execute();
    $result = $stmt->get_result(); // get the mysqli result
    $data = $result->fetch_assoc(); // fetch data  
	
    if($data) {
    $authorid = $data['id'];

    } else {

    // insert to author table
    $stmt = $mysqli->prepare("INSERT INTO authors (first_name, last_name) VALUES (?, ?)");
    $stmt->bind_param("ss", $_POST['authorfname'],$_POST['authorlname']);
    $stmt->execute();
    $authorid = $stmt->insert_id;

    }
	$filepath = "images/".htmlspecialchars( $filename);

	// Using Prepared Statements
	$statement = $mysqli->prepare("INSERT INTO books (title, genre_id, author_id, wine_id, image_path) VALUES(?,?,?,?,?)");
	$statement->bind_param("siiis", $_POST["title"], $_POST["genre"], $authorid, $_POST["wine"],$filepath );
	

	$executed = $statement->execute();
	if(!$executed) {
		echo $mysqli->error;
		exit();
	}

	// Check that only one row was affected
	if($statement->affected_rows == 1) {
		$isInserted = true;
	}

	$statement->close();
	$mysqli->close();


    //echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
  } else {
    $error .= "Sorry, there was an error uploading your file.";
  }

}
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Add Confirmation | Book Database</title>
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
		<li class="breadcrumb-item"><a href="add_form.php">Add</a></li>
		<li class="breadcrumb-item active">Confirmation</li>
	</ol>
	<div class="container">
		<div class="row">
			<h1 class="col-12 mt-4">Add a Book</h1>
		</div> <!-- .row -->
	</div> <!-- .container -->
	<div class="container">
		<div class="row mt-4">
			<div class="col-12">

				<?php if ( isset($error) && !empty($error) ) : ?>

				<div class="text-danger">
					<?php echo $error; ?>
				</div>

				<?php else : ?>
					<div class="text-success">
						<span class="font-italic"><?php echo $_POST['title']; ?></span> was successfully added.
					</div>
				<?php endif; ?>

			</div> <!-- .col -->
		</div> <!-- .row -->
		<div class="row mt-4 mb-4">
			<div class="col-12">
				<a href="add_form.php" role="button" class="btn btn-primary">Back to Add Form</a>
			</div> <!-- .col -->
		</div> <!-- .row -->
	</div> <!-- .container -->
</body>
</html>