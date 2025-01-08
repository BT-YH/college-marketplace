<?php
include_once("util.php"); 
session_start();
//owen
?>
<!DOCTYPE html>
<HTML>
<HEAD>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<TITLE> Seller </TITLE>

<STYLE>
BODY{
font-family: georgia;
}
</STYLE>

<?php
$op = "main";


$username = $_SESSION['username']; 
$fullname = $_SESSION['fullname'];
$fname = $fullname['fname'];
$lname = $fullname['lname'];

?>
</HEAD>
<BODY>
<table style="width:100%"> 
		<tr>
		<td style="width:10%">
		<a href="landingpage.php"><img src = "https://www.gettysburg.edu/main/images/apple-touch-icon-152x152.png"></img></a>
		</td>
		<td style="width:15%"; align = "right">
		<a href="landingpage.php" style = "text-decoration:none"><H1><i><b><span style="color: #002F6c";>Gettysburg</span><br/>
		<span style="color:#E87722";> College </span><br/>
		<span style="color: #002F6c";>Marketplace</span></b></i></H1></a>
		</td>
		<td align = "right">
		<?php
		if (isset($_SESSION['username'])) {?>
		<H4 style="margin-right: 10px">Welcome, <a href="dashboard.php"><?php echo $fname?></a> <?php
			if ($userType == 'endUser'){ ?>
			&nbsp&nbsp&nbsp&nbsp&nbspCart|? items <img src ="https://www.clker.com/cliparts/z/Y/x/l/A/c/shopping-cart-navy-hi.png" width = "20" height = "20"></img></H4>
			<?php }
			showLogoutForm();
		}
		else {
			logOrCreate();
			if($_POST['option'] == 'Log In'){
				showLoginForm($db);
			}
			else if($_POST['option'] == 'Create Account'){
				showCreateForm($db);

			}
	    }?>

</table> 

<?php 
if (isset($_GET['op'])) {
    $op = $_GET['op'];
	// echo "value of op: " . $op;
    showReviews($db, $op);

}
?>
</BODY>
</HTML>