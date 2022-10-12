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
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Book Search</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	 <!-- Reference Stylesheet -->
	<link rel="stylesheet" type="text/css" href="search_form.css">
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
		<li class="breadcrumb-item active">Search</li>
	</ol>
	
	<div class="container">
		<div class="row">
			<h1 class="col-6 mt-2 mb-2">Search the Book Collection</h1>
		</div> <!-- .row -->
	</div> <!-- .container -->
	<div class="container">
		<form action="search_results.php" method="GET">
			<div class="form-group row">
				<!--<label for="title-id" class="col-sm-3 col-form-label text-sm-right">Book Title:</label>-->
				<div class="col-sm-9">
					<input type="text" class="form-control" id="title-id" name="title" placeholder="Search Book Title" style="color: #ffffff; background-color: #4e0101; border-radius: 18px">
				</div>
			</div> <!-- .form-group -->
			<div class="form-group row">
				<!--<label for="genre-id" class="col-sm-3 col-form-label text-sm-right"></label>-->
				<div class="col-sm-9">
					<select name="genre" id="genre-id" class="form-control" style="color: #ffffff; background-color: #4e0101; border-radius: 18px">
						<option value="" selected>- Search by Genre -</option>

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
				<!--<label for="author-id" class="col-sm-3 col-form-label text-sm-right"></label>-->
				<div class="col-sm-9">
					<select name="author" id="author-id" class="form-control" style="color: #ffffff; background-color: #4e0101; border-radius: 18px">
						<option value="" selected>- Search by Author -</option>

						<!-- Author dropdown options here -->
						<!-- Recommended PHP Syntax -->
						<?php while ($row = $results_author->fetch_assoc()): ?>
							<option value="<?php echo $row["id"];?>" >							<!-- Append the first name and last name columns with a space in between -->
								<?php echo $row["first_name"] . " " . $row["last_name"]; ?>
							</option>
						<?php endwhile; ?>

					</select>
				</div>
			</div> <!-- .form-group -->
			<div class="form-group row">
				<!--<label for="wine-id" class="col-sm-3 col-form-label text-sm-right"></label>-->
				<div class="col-sm-9">
					<select name="wine" id="wine-id" class="form-control" style="color: #ffffff; background-color: #4e0101; border-radius: 18px">
						<option value="" selected>- Search by Wine -</option>

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
				<div class="col-sm-3"></div>
				<div class="col-sm-9 mt-2">
					<button type="submit" class="btn btn-primary">Search</button>
					<button type="reset" class="btn btn-light">Reset</button>
				</div>
			</div> <!-- .form-group -->
		</form>
		<div class="row">
		<div class="col-12 mt-4">
			Showing <span id="resultcount"></span> result(s).
		</div>

		<table class="table table-responsive table-striped col-12 mt-3">
			<thead>
				<tr>
					<th></th>
					<th>Title</th>
					<th>Genre</th>
					<th>Author</th>
					<th>Wine</th>
				</tr>
			</thead>
			<tbody>
				<tr>
				</tr>
			</tbody>
		</table>
		<div id="showme" style="display:none">
			Don't see what you're looking for? Add to the Collection <a class="btn btn-primary" href="add_form.php">Add</button></a>
			<br>
			<br>
	
		</div> <!-- showme -->

	</div> <!-- .row -->
	</div> <!-- .container -->
	<script>

		// Function declaration for ajax GET requests
		function ajaxGet(endpointUrl, returnFunction){
			var xhr = new XMLHttpRequest();
			xhr.open('GET', endpointUrl, true);
			xhr.onreadystatechange = function(){
				if (xhr.readyState == XMLHttpRequest.DONE) {
					if (xhr.status == 200) {
						// When ajax call is complete, call this function, pass a string with the response
						returnFunction( xhr.responseText );
					} else {
						alert('AJAX Error.');
						console.log(xhr.status);
					}
				}
			}
			xhr.send();
		};
		function ajaxPost(endpointUrl, postdata, returnFunction){
			var xhr = new XMLHttpRequest();
			xhr.open('POST', endpointUrl, true);
			// POST requests needs us to specifcy how the content will be sent
			xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xhr.onreadystatechange = function(){
				if (xhr.readyState == XMLHttpRequest.DONE) {
					if (xhr.status == 200) {
						returnFunction( xhr.responseText );
					} else {
						alert('AJAX Error.');
						console.log(xhr.status);
					}
				}
			}
			// for POST requests, must send parameters using .send() function
			xhr.send(postdata);
		};
		// Call the post ajax function
		ajaxPost("search_results.php", "term=love&limit=10", function() {
			
		});


		// ---- Form handling
		document.querySelector("form").onsubmit = function(event) {
			// prevent the form from actually being submitted
			event.preventDefault();
			const searchBtn = document.querySelector("form button.btn-primary").style.transform = "rotate(360deg)";

			// Get the user's search input
			let genre = [...document.querySelectorAll('select[id^="genre-id"]')].map(sel => sel.value);
			let booktitle = document.querySelector("#title-id").value.trim();
			let author = [...document.querySelectorAll('select[id^="author-id"]')].map(sel => sel.value);
			let wine = [...document.querySelectorAll('select[id^="wine-id"]')].map(sel => sel.value);

			// Call the ajax function, pass in the search input, log out results
			ajaxGet("search_results.php?title="+booktitle+"&author="+author+"&wine="+wine+"&genre=" + genre, function(results) {


				// this function runs when we get a response from search_results.php
				console.log(results);

				// convert the data into js object
				let jsResults = JSON.parse(results);
				console.log(jsResults);

				let resultsList = document.querySelector("tbody");

				// Clear the previous list of results
				resultsList.replaceChildren();
				document.getElementById("resultcount").innerHTML=jsResults.length;
				document.getElementById("showme").style.display="block";
				// Run through the list of results and dynamically create a <li> tag for each of the result
				
				for(let i = 0; i < jsResults.length; i++) {
					
					let htmlString = `
					<tr>
						<td>
							<a href="edit_form.php?book_id=${jsResults[i].id}&title=${jsResults[i].title}" class="btn btn-outline-warning">Edit</button>
							</a>
							<a href="delete.php?book_id=${jsResults[i].id}&title=${jsResults[i].title}" class="delete-btn btn btn-outline-danger">Delete</button>
							</a>
						</td>	
							
						<td>
							${jsResults[i].title}
						</td>

						<td>
							${jsResults[i].genre}
						</td>

						<td>
							${jsResults[i].first_name} ${jsResults[i].last_name} 
						</td>
						<td>
							${jsResults[i].wine}
						</td>
						<td>
						
					</tr>
        			`;

					// Append to the <ol> tag
					resultsList.innerHTML += htmlString;
				}
				function searchBtn() {
        			document.querySelector("form button.btn-primary").style.transform = "rotate(90deg)";
      			}
				// Some JS to pop up a message before user commits to deleting a track.
				let deleteButtons = document.querySelectorAll(".delete-btn");

				for( let i = 0; i < deleteButtons.length; i++ ) {
					deleteButtons[i].onclick = function() {
						// Show a popup here
					return confirm("Are you sure you want to delete this Book?");
				}
			}

			});
			
		}
	let editButtons = document.querySelectorAll(".info-btn");
	
		

		
	</script>
	<div id="footer"></div>


</body>
</html>