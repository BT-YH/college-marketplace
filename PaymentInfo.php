<?php
include("util.php"); 
session_start();
// Bernard
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Information</title>

<style>
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

    <h1>Update Account Information</h1>
    <?php genUpdatePaymentInfo(); ?>
    <?php userPaymentInfo($db); ?>
    
</body>
</html>