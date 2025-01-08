<?php
session_start();
include("util.php");

//Barry
?>

<!DOCTYPE HTML>
<HTML lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Page</title>
    <STYLE> 
	 .header {
    background-color: #f0f0f0;
    padding: 10px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    }

        .product-container {
            max-width: 800px;
            margin: 20px auto;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        img {
            max-width: 100%;
            height: auto;
            margin-bottom: 20px;
        }

        h1, p {
            margin-bottom: 10px;
        }

        button {
            background-color: #4caf50;
            color: #fff;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }

        h2 {
            border-bottom: 2px solid #333;
            padding-bottom: 5px;
            margin-top: 20px;
        }

        ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        li {
            margin-bottom: 5px;
        }

        .details-section,
        .reviews-section {
            margin-top: 20px;
        }
	</STYLE>


<?php
$op = "main";

$username = $_SESSION['username']; 
$fullname = $_SESSION['fullname'];
$fname = $fullname['fname'];
$lname = $fullname['lname'];
$iid = $_GET['iid'];


if (isset($_GET['op'])) {
    $op = $_GET['op'];

    if ($op == "add") {
	    checkStatus($db, $iid);
    }
}


?>

</head>
<body>

<?php

	$iid = $_GET["iid"];
	$sql = "SELECT * FROM ITEM WHERE iid='$iid'";
    $res = $db->query($sql);
    $productInfo = array();
    if ($res != FALSE) {
    $row = $res->fetch();
    $productInfo["iid"]	    = $row["iid"];
    $productInfo["condition"]   = $row["item_condition"];
    $productInfo["size"]	    = $row["item_size"];
    $productInfo["category"]    = $row["category"];
    $productInfo["postdate"]    = $row["post_date"];
    $productInfo["description"] = $row["description"];
    $productInfo["picture"]     = $row["picture"];
    $productInfo["seller"] 	    = $row["seller_username"];
    $productInfo["price"] 	    = $row["price"];
    $productInfo["name"] 	    = $row["item_name"];
    
    $seller = $productInfo["seller"];
	}
?>

     
    
<div class="header">
        <div class="marketplace-icon">
       	    <table style="width:100%" > 
	    <tr>
	    <td align = "right" style="width:10%">
	    <a href="landingpage.php"  style = "text-decoration:none"><H1><i><span style="color: #002F6c";>Gettysburg</span><br/>
	    <span style="color:#E87722";> College </span><br/>
	    <span style="color: #002F6c";>Marketplace</span></i></H1></a>
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

    <div class="product-container">

        <img src=<?php echo $productInfo['picture']; ?>>

        <h1><?php echo  $productInfo['name']; ?></h1>
        <p>$<?php echo $productInfo['price']; ?></p>
        <br>
        <p>seller:  <?php echo "<a href='sellerpage.php?op=$seller'> $seller</a>"; ?></p>
        <br>
	<a href="?iid=<?php echo $iid; ?>&op=add">
        <button>Add to Cart</button>
	</a>
        <div class="details-section">
            <h4> <?php echo $productInfo['category']; ?> </h4>
            <p>Description: <?php echo $productInfo['description']; ?></p>
            <p>Post Date: <?php echo $productInfo['postdate']; ?> </p>
            <p>Condition: <?php echo $productInfo['condition']; ?></p>
            <h2>Product Details</h2>
            <p>Dimensions:<?php echo $productinfo['size']; ?></p>
        </div>

    </div>

