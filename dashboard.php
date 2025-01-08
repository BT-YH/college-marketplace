<?php
// Barry, Bernard
session_start();
include("util.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Portal</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: georgia;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

	.placeholder{
	    width:100%;
	    height:0;
	    visibility:hidden;
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

        .user-info {
            text-align: right;
        }
	
	.nav-bar {
	    height: 3%;
            background-color: white;
            padding: 1%;
            font-size 27px;
            color: black;
	}

        .user-portal {
            flex: 1;
            display: flex;
        }

        .cart {
            display: flex;
	        flex: 0 0 30%;
	        flex-wrap: wrap;
            background-color: #e0e0e0;
            overflow-y: auto;
            padding: 10px;
	        flex-direction: row;
	}


        .content {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .orders,
        .listings {
            flex: 1;
	    display: flex;
	    flex-wrap: wrap;
            overflow-y: auto;
            padding: 10px;
	    justify-content: flex-start;
            flex-direction: row;
        }
	
        button {
            background-color: skyblue;
            color: #fff;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            font-size: 16px;
	    border-radius: 10%;

        /* Add any additional styling or media queries as needed */
    </style>
<?php 

$username = $_SESSION['username'];
$fullname = $_SESSION['fullname'];
$fname = $fullname['fname'];
$lname = $fullname['lname'];

$op = "main";

if (isset($_GET['op'])) {
	$op = $_GET['op'];
	
	if ($op == 'purchase') {
		makePurchase($db, $username);
		header("refresh:3; url=dashboard.php?op=main");
	}

    if ($op == 'removingFromCart') {
        $iid = $_POST['iid'];
        removeFromCart($db, $iid, $username);
        header("refresh:3; url=dashboard.php?op=main");
    }
}

if (!isset($_SESSION['username'])) {
	header("refresh:0.1; url=landingpage.php?menu=main");
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
            <p> <?php echo $fname . " " . $lname;  ?></p>
            <p>Username: <?php echo $username;  ?></p>
        </div>
    </div>

    <div class="nav-bar">
                <a href="AccountSettings.php" style="text-decoration: none; color: black;">Account Settings</a> &nbsp;
                <a href="PaymentInfo.php" style="text-decoration: none; color: black;">Payment Info</a> &nbsp;
                <a href="message.php" style="text-decoration: none; color: black;">Messages</a>
    </div>

    <div class="user-portal">

        <div class="content">
            <div class="orders">
                <!-- Display user's orders here -->
                <h2>User's Orders</h2>
           <DIV class="placeholder"></DIV> 
	   <?php
	    $item_ids = retrievePurchases($db, $username);
	    display_products_simple($db, $item_ids);
	   ?>
            </div>

            <div class="listings">
                <h2>User's Listings</h2>
                <DIV class='placeholder'></DIV>
        <?php
		$listings = retrieveListings($db, $username);

		    echo "<br>";
		    display_products_simple($db, $listings);
		    echo "<DIV class='placeholder'></DIV><br>";
		    echo "<a href='list.php' style='text-decoration: none; color: black;'>";
		    echo "<button>Upload</button>";
		    echo "</a>";

		?>
            </div>
        </div>

        <div class="cart">
	<h2>Shopping Cart</h2>
    <DIV class='placeholder'></DIV>
	<?php
	    $item_ids = retrieveCart($db, $username);
	    display_products_cart($db, $item_ids);
	    $price = getTotalPrice($db, $username);

	    if ($price != 0) {
            echo "<FORM method='POST' action='?op=purchase' style='width: 100%;'>";
        	echo "<DIV class='placeholder'></DIV><br>";
	        echo "<H3>Total price $$price</H3>";
            echo "<DIV class='placeholder'></DIV><br>";
		    echo "<BUTTON type='submit' style='padding: 5%; font-size: 16px; cursor: pointer;'> Purchase </BUTTON>";
            echo "</FORM>";
	    } else {
		    echo "<h4>What are you waiting for? go </h4>&nbsp";
		    echo "<a href='landingpage.php' style='text-decoration: underline; color: blue;'><h4>shopping!</h4></a>";
	    }
	?>
        </div>
    </div>
</body>

</html>
