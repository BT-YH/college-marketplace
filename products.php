
<?php
session_start();
include("util.php");
// Barry
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>College Marketplace</title>
    <link rel="stylesheet" href="styles.css">
    <style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: white;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

	input {
	box-sizing:border-box;
	width: 70%;
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
$op = "main";

if (isset($_GET['op'])) {
    $op = $_GET['op'];

    if ($op == 'addingToCart') {
        $iid = $_POST["iid"];
	    checkStatus($db, $iid);
    }
}

$username = $_SESSION['username']; 
$fullname = $_SESSION['fullname'];
$fname = $fullname['fname'];
$lname = $fullname['lname'];
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
    <p> 

    
    </p>
    <div class="container">

        <!-- Sidebar -->
        <div class="sidebar">
            <h2>Filters</h2>
            <label for="lowerPrice">
                <input type="number" min='0' id="lowerPrice" placeholder='lower bound'">  
            </label>
            <label for="upperPrice">
                <input type="number" min='0' id="upperPrice" placeholder='upper bound'> 
            </label>
            <label for="priceFilter">
                <input type="checkbox" id="priceFilter"> Price
            </label>

            <br>


            <label for="category">
                <input type="text"  id="category" placeholder='category'> 
	    </label>
	    <br>
	    <label for="categoryFilter">
                <input type="checkbox" id="categoryFilter"> Category
            </label>
            <br>
            <br>
            <!-- Add more filter options as needed -->

            <button onclick="applyFilters()">Apply Filters</button>
        </div>

        <!-- Product Container -->
        <div class="product-container">
	
	<?php 

        if ($op=='main') {
	        display_products($db);
        } else if ($op=="CourseMaterials") {
            filterSql($db, "Course Materials");
        } else if ($op=="Technology") {
            filterSql($db, $op);
        } else if ($op=="Fashion") {
            filterSql($db, $op);
        } else if ($op=="StudentEssentials") {
            filterSql($db, "Student Essentials");
        } else if ($op=="Entertainment") {
            filterSql($db, $op);
        } 
	?>

    </div>





    <script>
        function applyFilters() {
            // Get selected filter options
            const priceFilter = document.getElementById('priceFilter').checked;
            const categoryFilter = document.getElementById('categoryFilter').checked;

            // Get all product elements
            const products = document.querySelectorAll('.product');

            // Apply filters
            products.forEach(product => {
            if (priceFilter) {
                    productPrice = parseInt(product.getAttribute('data-price'));
                    lower = parseInt(document.getElementById("lowerPrice").value);
                    upper = parseInt(document.getElementById("upperPrice").value);

                    if (lower > upper) {
                        [lower, upper] = [upper, lower];
                    }

                    if ((productPrice < lower) || (productPrice > upper)) {

                        product.style.display = 'none';
                    }
            } else if (categoryFilter) {
		            productCategory = product.getAttribute('data-category');
                    category = document.getElementById("category").value;
                    // console.log("Input Value: ", category);
		            if (category != productCategory) {
			            product.style.display = 'none';
                    }
		    } else {
                    product.style.display = 'flex';
                }
                });
            }
    </script>

</body>
</html>
