<?php 
include_once("util.php"); 
include("bootstrap.php");
// Owen
?>
<!DOCTYPE html>
<HTML>
<HEAD>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<TITLE> Landing </TITLE>
<STYLE>
BODY{
	font-family: georgia;
}

header {
	background-color: #333;
	color: #fff;
	text-align: center;
	padding: 1em;
	width: 100%;
}

.container {
	width: 100%;
	display: flex;
	margin: auto;
	flex-grow: 1;
	overflow: hidden;
	justify-content: flex-start; 
}

.sidebar {
	width: 20%;
	background: #ddd;
	padding: 1em;
	margin: 1em;
	box-sizing: border-box;
	border-radius: 5px;
}

.product-container {
	flex-grow: 1;
	display: flex;
	flex-wrap: wrap;
	justify-content: flex-start;
	padding: 1em;
}

.product-container-landing {
	flex-grow: 1;
	display: flex;
	flex-wrap: wrap;
	justify-content: space-around;
	padding: 1em;
}

.product {
	box-sizing: border-box;
	margin: 1.5%;
	background: #fff;
	border: 1px solid #ddd;
	border-radius: 5px;
	width: 15em;
	height: 30em;
	display: flex;
	flex-direction: column;
	position: relative; 
	overflow: hidden; 
}


.product img {
	display: block;
	margin: 0 auto;
	width: 70%;
	height: auto; 
	padding: 1em;

}

.product-info {
	padding: 1em;
	flex-grow: 1;
	position: absolute;
	bottom: 0;
}

.product h3 {
	margin-top: 0;
}

.product p {
	color: #888;
}



.nav-link {
	color:black;
	font-size:20px;
}
.nav-link:hover {
	color: #E87722;
}

nav {
	margin-top: 10px;
}
img{
	padding-bottom:10px;
}
.product-container-landing {
	flex-grow: 1;
	display: flex;
	flex-wrap: wrap;
	justify-content: space-around;
	padding: 1em;
}

</STYLE>


<?php
		$menu = "main";
if (isset($_GET['menu'])) {
	$menu = $_GET['menu'];
    // if user clicked log in button, log them in
	if ($menu == 'login') {
		login($db, $_POST);
	}
    // if user clicks logout, log them out
	else if ($menu == 'logout') {
		unset($_SESSION['username']);
		unset($_SESSION['userType']);
	}
    // if user finalizes creating an account, create their account
	else if ($menu == 'create'){
		createAccount($db, $_POST);
	}
    // if an admin clicks to ban a user, ban the user
	else if ($menu == 'ban'){
		banUser($db, $username);
	}
}

$username = $_SESSION['username'];
$fullname = getName($db, $username);
$_SESSION['fullname'] = $fullname;
$fname = $fullname['fname'];
$lname = $fullname['lname'];

$userType = $_SESSION['userType'];
?>

</HEAD>
<BODY> <?php
// if user is banned give them a message
		if ($userType == 'Banned'){ ?>
</table>
		<DIV style = "text-align: center; font-size: 40px; color: red; padding-top: 30px;">
		<H2>Your account has been banned</H2> </DIV>

		<?php
		}
		else { 
        // header for the website
        ?>
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
				&nbsp&nbsp&nbsp&nbsp&nbspCart | <?php print(retrieveCartCount($db, $username));?> items <img src ="https://www.clker.com/cliparts/z/Y/x/l/A/c/shopping-cart-navy-hi.png" width = "20" height = "20"</img></H4>
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
				}
        // admin page
		if ($userType == 'Admin') {
			showBanForm($db);
		}
        // end user page with search box, navbar of different categories, and the featured products
		else {
			?>
				<br/>
			<br/>
			<br/> <?php
					showSearchForm($db); ?>
			</td>
					</tr>
					</table>
                    
					<nav class="nav nav-pills nav-fill border-top 2px border-bottom 2px">
					<a class="nav-item nav-link" href="products.php?op=main">All Products</a>
					<a class="nav-item nav-link" href="products.php?op=CourseMaterials">Course Materials</a>
					<a class="nav-item nav-link" href="products.php?op=Technology">Technology</a>
					<a class="nav-item nav-link" href="products.php?op=Fashion">Fashion</a>
					<a class="nav-item nav-link" href="products.php?op=StudentEssentials">Student Essentials</a>
					<a class="nav-item nav-link" href="products.php?op=Entertainment">Entertainment</a>  
					</nav> <?php
							if ($menu == 'search'){
								search($db, $_POST);
							}
							else{ ?>
					<DIV class="row">
							<H2 style = "padding-top: 10px; padding-left: 20px"><b>Featured Products</b></H2>
							</DIV> 
							<DIV class = "product-container-landing"> <?php
									$sql = "SELECT *
											FROM ITEM
											WHERE iid NOT IN (SELECT item_id
															  FROM PURCHASES
																)
											LIMIT 4";
											$res = $db->query($sql);
							display_product_simple($res->fetch());
							display_product_simple($res->fetch());
							display_product_simple($res->fetch());
							display_product_simple($res->fetch());
							} 
		} 
		}
?>
</BODY>
</HTML>
