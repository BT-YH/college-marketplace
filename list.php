<?php
include 'util.php';
session_start();
//Barry
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Upload Form</title>
    <style>
    body {
   	    font-family: Arial, sans-serif;
    	    background-color: #f4f4f4;
    	    margin: 0;
	}

	.container {
	    max-width: 600px;
	    margin: 50px auto;
	    padding: 20px;
	    background-color: #fff;
	    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
	}

	h2 {
	    color: #333;
	}

	form {
	    margin-top: 20px;
	}

	label {
	    display: block;
	    margin-bottom: 5px;
	}

	input[type="file"], textarea {
	    width: 100%;
	    padding: 8px;
	    margin-bottom: 15px;
	}

	input[type="submit"] {
	    background-color: #4CAF50;
	    color: white;
	    padding: 10px 15px;
	    border: none;
	    border-radius: 5px;
	    cursor: pointer;
	}

	input[type="submit"]:hover {
	    background-color: #45a049;
	}

	.header {
    background-color: #f0f0f0;
    padding: 10px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.marketplace-icon {
            max-width: 150px;
            max-height: 150px;
            test-align: left;
	}
    </style> 
<?php
$username = $_SESSION['username'];
$fullname = $_SESSION['fullname'];
$fname = $fullname['fname'];
$lname = $fullname['lname'];


$op = 'main';
if (isset($_GET['op'])) {
    $op = $_GET['op'];
    if ($op == "uploading") {
	    if (isset($_FILES["l_image"])) {
		    echo "File exists! \n";
		    $filepath = handleFileUpload($_FILES["l_image"]);
		    if ($filepath != "FALSE") {
			addListing($db, $username);
		        echo "<h2>Uploaded Information:</h2>";
		        echo "<img src='{$filepath}' alt='Uploaded Image' width='300'>";
		//	header('refresh:15; url=?menu=main');
		    } else {
		        echo "Upload Error";
		    }
	    } else {
		   echo "File not found";
	    }
    }
}


?>

</head>
<body>


<div class="header">
        <div class="marketplace-icon">
       	    <table style="width:100%" > 
	    <tr>
	    <td align = "left" style="width:10%">
	    <a href="landingpage.php">
        <img src = "https://www.gettysburg.edu/main/images/apple-touch-icon-152x152.png"></img>
        </a>
	    </td>
	    <td align = "right" style="width:10%">
	    <H1><i><span style="color: #002F6c";>Gettysburg</span><br/>
	    <span style="color:#E87722";> College </span><br/>
	    <span style="color: #002F6c";>Marketplace</span></i></H1>
	    </td> 
	    </tr>
	    </table>
	 </div>

        <div class="user-info">
            <!-- Display username and id dynamically here --> 
            <p> <a href="dashboard.php"><?php echo $fname . " " . $lname;  ?></a></p>
            <p>Username: <?php echo $username;  ?></p>
        </div>
</div>


<div class="container">
    <h2>Upload Item Information</h2>

    <form action="?op=uploading" method="post" enctype="multipart/form-data">
        <label for="image">Select Image:</label>
        <input type="file" name="l_image" id="l_image" required>
        <br>

	<label for="Name">Name:</label>
        <input type="text" name="l_name" id="l_name" required>
        <br><br>

	<label for="price">Price:</label>
        <input type="number" name="l_price" id="l_price" required>
        <br><br>

	<label for="size">Size:</label>
        <input type="text" name="l_size" id="l_size" required>
        <br><br>

	<label for="category">Category:</label>
        <input type="text" name="l_category" id="l_category" required>
        <br><br>

	<label for="condition">Condition:</label>
        <input type="text" name="l_condition" id="l_condition" required>
        <br><br>

        <label for="description">Description:</label>
        <textarea name="l_description" id="l_description" rows="4" required></textarea>
        <br><br>

        <input type="submit" value="Upload" name="l_submit">
    </form>
</div>

</body>
</html>
