<?php 
session_start();
include("util.php"); 
// bernard
?>

<!DOCTYPE html>
<HTML>
<HEAD>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<TITLE> ... </TITLE>


<STYLE>
.menuItem {
    border: solid 3px grey; 
    border-radius: 8px;
    text-align: center;
    font-size: 20px;
    background-color: lightblue; 
    color: ivory;
    flex: 1;
    margin: 0; 
}


.menu-row{
    display: flex;
    justify-content: center;
    gap: 10px; 
}

.menuItem:hover {
    background-color: skyblue; 
    color: orange
}

.main {
    /* margin-top: 20px; */
    border: solid 2px grey;
    padding: 10px;
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
</STYLE>


<?php

$menu = "inbox";
$username = $_SESSION['username']; 
$fullname = $_SESSION['fullname'];
$fname = $fullname['fname'];
$lname = $fullname['lname'];

if (isset($_GET['menu'])) {
    $menu = $_GET['menu'];
}
?>

</HEAD>

<BODY>

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

<DIV class="container">

<!-- banner -->


<div style="background-color: lightgrey ">

<DIV class="row">
<DIV class="col-4 "> 

</DIV>
</DIV>

<DIV class="container" style="background-color: lightgrey;">
    <DIV class="content" style="padding: 10px; margin: 10px; background-color: lightgrey;">
        <!-- Your content here -->
    </DIV>
</DIV>


<!-- navbar: menu -->
<DIV class="row">

<!-- href="?menu=inbox" access current file -->

<DIV class="menu-row" style="background-image: url('path_to_your_image.jpg');"> 
    <DIV class="menuItem"><A href="?menu=inbox">Inbox</A></DIV>
    <DIV class="menuItem"><A href="?menu=compose">Compose</A></DIV>
    <DIV class="menuItem"><A href="?menu=history">History</A></DIV>
</DIV>

</DIV>
</div>

<!-- actual content for each menu item -->
<DIV class="row main">

<DIV class="col-12">

<?php 

if ($menu == "compose") {
    genComposeForm($db, $username);
     
}
else if ($menu == "send") {
    sendMessage($db, $_POST);
}
else if($menu =="history"){
    showHistoryForm($db, $username); 
}
else if($menu == "inbox"){
    showInbox($db, $username); 
}
else if($menu == "showHistory"){
    showHistory($db, $_POST, $username); 
}
?>

</DIV> <!-- col-12-->

</DIV> <!-- row-->
</DIV>


</BODY>
</HTML>