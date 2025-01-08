<?php
// Author: Barry, Bernard, Owen
session_start();
include_once("db_connect.php");



	function display_products($db) {

	    $sql = "SELECT * FROM ITEM
		    WHERE iid NOT IN (
				SELECT item_id
				FROM PURCHASES)";
	    $res = $db->query($sql);
	    
	    if ($res != FALSE) {
			while ($row = $res->fetch()) {
				display_product($row);
			}
	    }
	}


	function display_product($row) {
		$iid      = $row['iid'];
		$image    = $row['picture'];
		$price    = $row['price'];
		$name  	  = $row['item_name'];
		$category = $row['category']; 

		print <<< HTML
		<DIV class="product" data-price="$price" data-category="$category">
		<FORM method="POST" action="?op=addingToCart">
		<INPUT type=hidden name="iid" value="$iid">
		<A href="product.php?iid=$iid">
		<IMG src="$image" /> </A>
			<DIV class="product-info">
			<A href="product.php?iid=$iid">
			<H3> $name </H3>
			</A>
			<P>$$price </P>
			<P>Category: $category </P>
			<BUTTON type="submit">Add to Cart</BUTTON>
			</DIV>
		</FORM>
		</DIV>
		HTML;
	}

	function display_product_simple($row) {
		$iid      = $row['iid'];
		$image    = $row['picture'];
		$price    = $row['price'];
		$name  	  = $row['item_name'];
		$category = $row['category']; 

		print <<< HTML
		<DIV class="product" style="height: auto"; data-price="$price" data-category="$category">
		<A href="product.php?iid=$iid">
		<IMG src="$image" /> </A>
		<P> $name </P>
		<P>$$price </P>
		</DIV>
		HTML;
	}

	function display_product_cart($row) {
		$iid      = $row['iid'];
		$image    = $row['picture'];
		$price    = $row['price'];
		$name  	  = $row['item_name'];
		$category = $row['category']; 

		print <<< HTML
		<DIV class="product" data-price="$price" data-category="$category">
		<FORM method="POST" action="?op=removingFromCart">
		<INPUT type=hidden name="iid" value="$iid">
		<A href="product.php?iid=$iid">
		<IMG src="$image" /> </A>
			<DIV class="product-info">
			<A href="product.php?iid=$iid">
			<H3> $name </H3>
			</A>
			<P>$$price </P>
			<BUTTON type="submit">Remove From Cart</BUTTON>
			</DIV>
		</FORM>
		</DIV>
		HTML;
	}

	function display_products_simple($db, $iids) {
		
		foreach($iids as $iid ) {
			$sql = "SELECT * FROM ITEM
					 WHERE iid='$iid'";
			$res = $db->query($sql);
			if ($res != FALSE) {
				$row = $res->fetch(); 
				display_product_simple($row);

			} else {
				echo "failed!";
			}
		}
	}

	function display_products_cart($db, $iids) {
		
		foreach($iids as $iid ) {
			$sql = "SELECT * FROM ITEM
					 WHERE iid='$iid'";
			$res = $db->query($sql);
			if ($res != FALSE) {
				$row = $res->fetch(); 
				display_product_cart($row);

			} else {
				echo "failed!";
			}
		}
	}

	function checkStatus($db, $iid) {
		if (isset($_SESSION['username'])) {
			$uid = $_SESSION['username'];
			echo "Item id: " . $iid;
			echo "username: " . $uid; 
			addToCart($db, $iid, $uid);
			header("refresh:0.1; url=products.php?op=main");

		} else {
			echo '<script>alert("Please log in :)")</script>';
			header("refresh:0.1; url=landingpage.php?menu=main");
		}
	}

	function addToCart($db, $iid, $uid) {
		$sql = "INSERT INTO CART VALUES('$iid', '$uid')";
		$res = $db->query($sql);
		if ($res != FALSE) {
			echo '<script>alert("Added to Cart")</script>';
		} else {
			echo '<script>alert("Failed to add to Cart")</script>';
		}
	}

	function removeFromCart($db, $iid, $uid) {
		// echo "itemid: " . $iid;
		// echo "username: " . $uid;
		$sql = "DELETE FROM CART
				WHERE item_id=$iid AND buyer_username='$uid'";
		$res = $db->query($sql);
		// echo $res;
		if ($res != TRUE) {
			echo "Error in removeFromCart: " . $db->error;
		}
	}

	function retrieveCart($db, $uid) {
		$sql = "SELECT item_id 
				FROM CART
				WHERE buyer_username='$uid'";
		$res = $db->query($sql);
		$iids = array();
		if ($res != FALSE) {
			while ($row = $res->fetch()) {
				$iid = $row['item_id'];
				$iids[] = $iid;
			}
		} 
		return $iids;
	}

	function retrievePurchases($db, $uid) {
		$sql = "SELECT item_id
			FROM PURCHASES
			WHERE buyer_username='$uid'";
		$res = $db->query($sql);
		$iid = array();
		if ($res != FALSE) {
			while ($row = $res->fetch()) {
				$iid = $row['item_id'];
				$iids[] = $iid;
			}
		}
		return $iids;
	}

	function retrieveListings($db, $uid) {
		$sql = "SELECT iid
			FROM ITEM
			WHERE seller_username='$uid'";
		$res = $db->query($sql);
		$iid = array();
		if ($res != FALSE) {
			while ($row = $res->fetch()) {
					$iid = $row['iid'];
					$iids[] = $iid;
			}
        }
        return $iids;
	}

	function getTotalPrice($db, $uid) {
		$sql = "SELECT SUM(price) AS SUM
			FROM CART JOIN ITEM ON item_id=iid
			GROUP BY buyer_username
			HAVING buyer_username='$uid'";
		$res = $db->query($sql);
		if ($res != FALSE) {
			$row = $res->fetch();
			$sum = $row['SUM'];
		} else {
			echo "error in getTotalPrice";
		}
		return $sum;
	}


	function makePurchase($db, $uid) {
		$iids = retrieveCart($db, $uid);
	 	//delete from CART table
		$sql1 = "DELETE FROM CART WHERE buyer_username='$uid'";
		$res = $db->query($sql1);
		if ($res != TRUE) {
			echo "Error in makePurchase_del: " . $db->error;
		}

		$date = date('Y-m-d H:i:s');
		//add to PURCHASE tabel
		foreach($iids as $iid) {
			$sql2 = "INSERT INTO PURCHASES
				 VALUES ('$date', '$uid', '$iid')";
			$res = $db->query($sql2);
			if ($res != TRUE) {
				echo "error in makePurchase_purchase" . $db->error;
			}
		}

	}

	function addListing($db, $uid) {
		$name        = $_POST["l_name"];
		$price       = $_POST["l_price"];
		$size        = $_POST["l_size"];
		$category    = $_POST["l_category"];
		$condition   = $_POST["l_condition"];
		$description = $_POST["l_description"]; 
		$picture     = $_POST["l_filepath"];
		$date = date("Y-m-d H:i:s");
		$price = floatval($price);
/*
		echo "Name: $name, Type: " . gettype($name) . "<br>";
		echo "Price: $price, Type: " . gettype($price) . "<br>";
		echo "Size: $size, Type: " . gettype($size) . "<br>";
		echo "Category: $category, Type: " . gettype($category) . "<br>";
		echo "Condition: $condition, Type: " . gettype($condition) . "<br>";
		echo "Description: $description, Type: " . gettype($description) . "<br>";
		echo "Picture: $picture, Type: " . gettype($picture) . "<br>";
		echo "Date: $date, Type: " . gettype($date) . "<br>";
		echo "username: $uid, Type: " . gettype($uid) . "<br>";
*/
		$sql = "INSERT INTO ITEM 
			VALUES(NULL, '$condition', '$size', '$category', '$date', '$picture', '$description', '$uid', $price, '$name')";
		$res = $db->query($sql);
		if ($res != TRUE) {
			echo "Error uploading";
		}


	}

	function handleFileUpload($file) {

	    $validFile = 1;
	    if ($file["size"] > 5000000) {
 		echo "Sorry, your file is too large.";
		$validFile = 0;
	    }
	    
	   if (file_exists($file)) {
  		echo "Sorry, file already exists.";
		$validFile = 0;
	    }

	   
	    $targetDirectory = "./images/";
	    $fileName = basename($file["name"]);
	    $filePath = $targetDirectory . $fileName;
	    $fileTmp = $file["tmp_name"];
	    $imageFileType = strtolower(pathinfo($filePath,PATHINFO_EXTENSION));
	   
	   // echo "filePath is : " . $filePath . "\n";
	    //echo "Image file type is: " . $imageFileType;
	    // Allow certain file formats
	   if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
		&& $imageFileType != "gif" && $imageFileType != 'pdf' ) {
  		echo "Sorry, only JPG, JPEG, PNG, PDF & GIF files are allowed.";
	  	$validFile = 0; 
	  }

	  if  ($validFile==1) {
		   if (move_uploaded_file($fileTmp, $filePath) ) {
			echo "The file ". htmlspecialchars( basename($fileTmp)). " has been uploaded.";
			$_POST['l_filepath'] = $filePath;
		        return $filePath;
		    } else {
			echo "Sorry, error uploading the file.";
		        return "FALSE";
		    }
          } else {
		   return "FALSE";
	  }
	}



	// shows search bar and a drop down to choose the category for the search
	function showSearchForm($db){ ?>
			<FORM name='fmSearch' method='POST' action='?menu=search'>
		<INPUT type='text' name='search' required="required" size='20' placeholder='Search for anything' />
		<select id="categories" name="categories" style ="padding = 10px">
		<option value="All Categories">All Categories</option>
		<option value="Course Materials">Course Materials</option>
		<option value="Technology">Technology</option>
		<option value="Fashion">Fashion</option>
		<option value="Student Essentials">Student Essentials</option>
		</select>
		<INPUT type='submit' value='Search' style="margin-right: 10px" />
		</FORM> <?php
	}

	// takes data from search form and looks for all products that contain the words
	// entered in the search bar and displays them
	function search($db, $data){
		$search = $data['search'];
		$cat = $data['categories'];
		if ($cat == "All Categories") {
			$sql = "SELECT *
					FROM ITEM
					WHERE item_name like '%$search%'";
		}
		else {
			$sql = "SELECT *
					FROM ITEM
					WHERE item_name like '%$search%' AND category = '$cat'";
		}

		$res = $db->query($sql);
		if ($res->rowCount() == 0){ ?>
	<DIV style = "font-size: 40px; text-align: center; padding-top: 10px"> 
		There are no items listed with that name
		</DIV> <?php
		}
		else{
			if ($res != FALSE){ ?>
		<DIV class = "product-container"> <?php
				while($row = $res->fetch()){ 
					display_product($row);
				}
			}
		}

		?> </DIV> <?php
	}

	function filterSql($db, $cat) {
		$sql = "SELECT *
				FROM ITEM
				WHERE category='$cat'";
		$res = $db->query($sql);
		if ($res->rowCount() == 0){ ?>
		<DIV style = "font-size: 40px; text-align: center; padding-top: 10px"> 
			There are no items listed with that name
		</DIV> <?php
		}
		else{
			if ($res != FALSE){ 
				while($row = $res->fetch()){ 
					display_product($row);
				}
			}
		}

	}

	// shows all of the reviews of the product
	function showReviews($db, $username){ 
		$sql1 = "SELECT AVG(rating)
				FROM REVIEWS JOIN ITEM ON item_id = iid
				WHERE seller_username = '$username'";
		$res = $db->query($sql1);
		$row = $res->fetch();
		$rating = round($row['AVG(rating)'], 2); ?>
		<H2 style = 'padding-top: 10px'><?php print("$username ($rating")?>
		<img src = "https://i.pinimg.com/originals/e8/91/66/e891669c27c833ff0f2db2c083344117.png" width = "20" height = "20"></img> 
		<?php print(")"); ?></H2>

		<H4 style = 'padding-top: 10px'> Reviews: </H4> <?php
		$sql2 = "SELECT *
				FROM REVIEWS JOIN ITEM ON item_id = iid
				WHERE seller_username = '$username'";
		$res = $db->query($sql2);
		if($res != FALSE){ ?>
		<DIV> <?php
			while($row = $res->fetch()){
				$content = $row['rev_content'];
				$buyer = $row['buyer_username'];
				$date = $row['rev_date'];
				$item = $row['item_name'];
				print("Item: $item");
				echo "<br/>";
				print("Reviewer: $buyer");
				echo "<br/>";
				print("Review: $content ($date)"); 
				echo "<br/>"; ?>
		<br/> <?php
				}
		?> </DIV> <?php
		}
	}

	// returns the number of items a user has in their cart
	function retrieveCartCount($db, $uid) {
		$sql = "SELECT COUNT(*) 
				FROM CART
				WHERE buyer_username='$uid'";
		$res = $db->query($sql);
		$row = $res->fetch();
		$count = $row['COUNT(*)'];
		return $count;
	}


	// shows the username and password text boxes and the submit button to log in
	function showLoginForm($db) {
		?>
	<FORM name='fmLogin' method='POST' action='landingpage.php?menu=login'>
		<INPUT type='text' name='username' size='15' placeholder='Username' style="margin-right: 10px" />
		<br/>
		<INPUT type='password' name='password' size='15' placeholder='Password' style="margin-right: 10px" />
		<br/>
		<INPUT type='submit' name = 'button' value='Submit' style="margin-right: 10px"/>
		</FORM>

		<?php
	}

	// shows the logout button to log out
	function showLogoutForm() {
		?>
		<FORM name='fmLogout' method='POST' action='landingpage.php?menu=logout'>
		<INPUT type='submit' value='Logout' style="margin-right: 10px" />
		</FORM>
		<?php
	}



	// for admins only
	// lists all of the end users with a button next to them that allows the admin to ban them
	// shows all of the users on the website 
	function showBanForm($db){ ?>
		<FORM name = 'fmBan' method = 'POST' action = '?menu=ban'>
	</table> <?php
			$sql = "SELECT end_username
					FROM END_USER";
					$res = $db->query($sql);
					if ($res != FALSE){
						?>
	<TABLE> 
						<tr><th style = "padding-left: 15px; padding-right: 10px; padding-top :10px; padding-bottom: 10px">End Users</th></tr>
						<?php
								while($row=$res->fetch()){ ?>
						<tr> <td style = "padding-left: 15px; padding-right: 10px; padding-top :10px; padding-bottom: 10px"> <?php
								$enduser = $row['end_username']; print($enduser);?> </td><td> <?php
										$entry = "<INPUT type='submit' value = 'Ban' name = $enduser size='20'/>";
								printf($entry); ?>
								</td></tr> <?php
								} ?>
								</TABLE>
								<?php
					} ?>
								</FORM> <?php
	}


	// shows both a log in and create account button that lets the user choose which to do
	function logOrCreate(){
		?>
					<FORM name='fmLogOrCreate' method='POST' action='landingpage.php?menu=logOrCreate'>
		<INPUT type='submit' name = 'option' value='Log In' />
		<?php print("&nbsp&nbspOr&nbsp&nbsp" );?> 
		<INPUT type='submit' name = 'option' value='Create Account' style="margin-right: 10px" />
		</FORM>
		<?php
	}

	// takes the data from the login form and logs the user into the website
	function login($db, $data){
		$inUser = $data['username'];
		$sql1 = "SELECT password
				FROM USER
				WHERE username='$inUser'";
						$res1 = $db->query($sql1);
		$row = $res1->fetch();
		$md5pass = $row['password']; 
		if($res1 != FALSE){
			if(is_null($md5pass)){
				echo '<script>alert("This username does not exist")</script>';
			}
			else {
				if(md5($data['password']) == $md5pass){
					$_SESSION['username']=$inUser;
					$sql2 = "SELECT *
							FROM ADMIN
							WHERE admin_username = '$inUser'";
									$res2 = $db->query($sql2);

					$sql3 = "SELECT *
							FROM END_USER
							WHERE end_username = '$inUser'";
									$res3 = $db->query($sql3);


					if($res2->rowCount() > 0){
						$_SESSION['userType'] = 'Admin';
					}
					else if ($res3->rowCount() > 0){
						$_SESSION['userType'] = 'endUser';
					}
					else {
						$_SESSION['userType'] = 'Banned';
					}

				}
				else {
					echo '<script>alert("Incorrect Password")</script>';
				}
			}
		}
	}

	// shows all of the text boxes and buttons for the user to create an account
	function showCreateForm($db){
		?>
				<FORM name='fmLogin' method='POST' action='?menu=create'>
		<INPUT type='text' name='username' size='15' placeholder='Username' style="margin-right: 10px" />
		<br/>
		<INPUT type='password' name='password' size='15' placeholder='Password' style="margin-right: 10px" />
		<br/>
		<INPUT type='text' name='fname' size='15' placeholder='First Name' style="margin-right: 10px" />
		<br/>
		<INPUT type='text' name='lname' size='15' placeholder='Last Name' style="margin-right: 10px" />
		<br/>
		<INPUT type='text' name='address' size='15' placeholder='Address' style="margin-right: 10px" />
		<br/>
		<INPUT type='submit' name = 'button' value='Submit' style="margin-right: 10px"/>
		</FORM>

		<?php
	}

	// takes the data from the create account form and creates a user and end user in the db
	function createAccount($db, $data){
		$inUser = $data['username'];
		$md5pass = md5($data['password']);
		$inFname = $data['fname'];
		$inLname = $data['lname'];
		$inAddress = $data['address'];
		$sql1 = "INSERT INTO USER
				VALUES ('$inUser', '$inFname', '$inLname', '$md5pass')";
				$sql2 = "INSERT INTO END_USER
						VALUES ('$inUser', '$inAddress')";
						$res1 = $db->query($sql1);
		$res2 = $db->query($sql2);
		if($res1 != FALSE && $res2 != FALSE){
			echo '<script>alert("Account has been created. Please log in")</script>';
		}
		else {
			echo '<script>alert("Account could not be created. Your username may already be linked to an account")</script>';
		}
	}

	// gets the  name of the user entered in the parameter
	function getName($db, $username) {
		$sql = "SELECT fname, lname
				FROM   USER
				WHERE  username='$username'";
	
		$res = $db->query($sql);
	
		if ($res != FALSE && $res->rowCount() == 1) {
			$nameRow = $res->fetch();
			return $nameRow;
		}
		else {
			return "Unknown";
		}
	}

	// for admins only
	// when the ban button is clicked, banned user is added to bans table and removed from end user
	function banUser($db){
		$username = $_SESSION['username'];
		$sql = "SELECT end_username
				FROM END_USER";
				$res = $db->query($sql);
				if ($res != FALSE){
					while($row=$res->fetch()){ 
						$enduser = $row['end_username'];
						if(isset($_POST[$enduser])){
							$sql1 = "INSERT INTO BANS
									VALUES ('$username', '$enduser')";
									$res1 = $db->query($sql1);
							$sql2 = "DELETE FROM END_USER
									WHERE end_username = '$enduser'";
											$res2 = $db->query($sql2);
							print($username);
							return;
						}
					}
				}

	}


	function genComposeForm($db, $sender) {
		echo "<form name='compose' action='message.php?menu=send' method='post'>";
		
	

		echo "<SELECT name ='rid'>"; 
		echo "test";
		$sql = "SELECT * FROM USER"; 
		echo "test";
		$res = $db->query($sql);
		echo "test";


		if ($res != FALSE) {
			echo"hello"; 
			while ($row = $res->fetch()) {
				$recUsername = $row['username'];
				$recFullName = $row['fname']; 
				echo "<option value='$recUsername'>$recUsername</option>";
			}
		}
		else{
			echo"The was an error in you query"; 
		}
		echo "</select><br>";
		
		echo "<input type='hidden' name='sender' value='$sender'>";
		echo "Subject: <input type='text' name='subject' placeholder='type subject here'><br>";
		echo "Message: <textarea rows='5' cols='30' name='content'></textarea><br>";

		echo "<input type='submit' value='Send!'>";
		
		echo "</form>";
	}





	function sendMessage($db, $mailData){
		$receiver = $mailData['rid']; 
		$subject = $mailData['subject']; 
		$content = $mailData['content']; 
		$currentdate = date('Y-m-d'); 

		$user= $_SESSION['username']; 
		//echo "<p>user: $user</p>"; 

		// Corrected SQL query with single quotes around string values
		$sql = "INSERT INTO MESSAGES (msg_content, msg_date, msg_subject, rec_username, sender_username) " .
			"VALUES ('$content', '$currentdate', '$subject', '$receiver', '$user')";
			//echo "<p>sql: $sql</p>"; 

		$res = $db->query($sql); 

		if($res !== false){
			echo "<h3>Successfully sent message </h3>";
		}
		else{
			echo "<h3>Failed to send message </h3>";    
		}
	}





	function showInbox($db, $username) {
		$sql = "SELECT U2.username AS senderusername, MESSAGES.msg_date, MESSAGES.msg_subject 
				FROM MESSAGES 
				JOIN USER AS U1 ON MESSAGES.rec_username = U1.username
				JOIN USER AS U2 ON MESSAGES.sender_username = U2.username
				WHERE MESSAGES.rec_username = '$username'
				"; 
				

		$res = $db->query($sql);

		// CSS styles
		echo "<style>
				table {
					width: 100%;
					border-collapse: collapse;
				}
				th, td {
					padding: 8px;
					text-align: left;
					border-bottom: 1px solid #ddd;
				}
				th {
					background-color: #f2f2f2;
				}
				tr:hover {background-color: #f5f5f5;}
			</style>";

		echo "<table>"; 
		echo "<tr><th>From</th><th>Received</th><th>Subject</th></tr>";

		if ($res != FALSE) {
			while ($row = $res->fetch()) {
				$sender = $row['senderusername'];
				$subject = $row['msg_subject'];
				$date = $row['msg_date'];
				
				echo "<tr><td>$sender</td><td>$date</td><td>$subject</td></tr>";
			}
		} else {
			echo"<h3>Failed to execute</h3>"; 
		}
		echo "</table>";
	}


	// Show drop-down list of users for history
	function showHistoryForm($db, $username) {
		echo "<form name='fmHistory' action='message.php?menu=showHistory' method='POST'>";
		
		echo "<select name='fid'>";
		$sql = "SELECT username, fname, lname FROM USER WHERE username != '$username'";
		$res = $db->query($sql);

		if ($res != FALSE) {
			while ($row = $res->fetch()) {
				$recUsername = $row['username'];
				$recFullName = $row['fname'] . ' ' . $row['lname'];
				echo "<option value='$recUsername'>$recUsername</option>";
			}
		}
		echo "</select>";
		
		echo "<input type='submit' value='Show History'>";
		echo "</form>";
	}

	function showHistory($db, $historydata, $sender){
		$recipient = $historydata['fid']; 

		
		$sql1 = "SELECT USER1.fname AS fname, USER1.lname AS lname, USER2.fname AS sender_fname, USER2.lname AS sender_lname, MESSAGES.msg_subject, MESSAGES.msg_content, MESSAGES.msg_date
		FROM MESSAGES JOIN USER AS USER1 ON MESSAGES.sender_username = USER1.username
		JOIN USER AS USER2 ON MESSAGES.rec_username = USER2.username
		WHERE (rec_username='$recipient' AND sender_username= '$sender')
		OR (rec_username='$sender' AND sender_username= '$recipient')";

		

		
		?>
		<STYLE>
			.message-table {
				width: 100%;
				border-collapse: collapse;
			}

			.message-table th, .message-table td {
				border: 1px solid #DDD;
				padding: 8px;
				text-align: left;
			}
			.message-table th {
				background-color: #6495FD;
				color: white;
			}
			
			.message-table tr:nth-child(even) {
				background-color: #A0C0F0;
			}

			.message-table tr:nth-child(odd) {
				background-color: #A0F0C0;
			}
		</STYLE>
		<?php

		$res = $db->query($sql1);

		echo "<table class='message-table'>";
		echo "<tr><th>Recipient</th><th>Sender</th><th>Subject</th><th>Message</th><th>Date</th></tr>";

		while($row = $res->fetch()){
			$receiver = $row['sender_fname'] . " " . $row['sender_lname']; 
			$senderName = $row['fname']. " " .$row['lname']; 
			$subject = $row['msg_subject']; 
			$content = $row['msg_content'];
			$date = $row['msg_date'];

			echo "<tr>";
			echo "<td>$receiver</td>"; 
			echo "<td>$senderName</td>";
			echo "<td>$subject</td>";
			echo "<td>$content</td>";
			echo "<td>$date</td>";
			echo "</tr>";   
		}

		echo "</table>";
	}




	function genUpdateForm() {
		echo "<form name='updateInfo' action='AccountSettings.php' method='post'>";

		echo "First Name: <input type='text' name='firstname' placeholder='New First Name'><br>";
		echo "Last Name: <input type='text' name='lastname' placeholder='New Last Name'><br>";
		echo "Password: <input type='text' name='password' placeholder='New Password'><br>";  
		echo "<input type='submit' value='Update Info'>";
		echo "</form>";
	}

	function updateUserInfo($db) {
		session_start();
		$username = $_SESSION['username'];

		// Assign default values if POST variables are not set
		$firstname = '';
		if (isset($_POST['firstname'])) {
			$firstname = $_POST['firstname'];
		}

		$lastname = '';
		if (isset($_POST['lastname'])) {
			$lastname = $_POST['lastname'];
		}

		$password = '';
		if (isset($_POST['password'])) {
			$password = $_POST['password'];
		}

		$updates = [];
		if (!empty($firstname)) {
			$updates[] = "fname = '$firstname'";
		}
		if (!empty($lastname)) {
			$updates[] = "lname = '$lastname'";
		}
		if (!empty($password)) {
			$updates[] = "password = '" . md5($password) . "'";
		}

		if (count($updates) > 0) {
			$sql = "UPDATE USER SET " . implode(', ', $updates) . " WHERE username = '$username'";
			$res = $db->query($sql);

			if ($res !== false) {
				echo "<h3>Successfully updated user information</h3>";
			} else {
				echo "<h3>Failed to update user information</h3>";
			}
		} else {
			echo "<h3>No information to update</h3>";
		}
	}


	function genUpdatePaymentInfo(){
		echo '<form action="PaymentInfo.php" method="post">';
		echo '<label for="address">Enter new Address:</label>';
		echo ' <input type="text" name="address" placeholder="Enter new Address">';
		echo '<input type="submit" value="Update">';
		echo '</form>';
	}


	function userPaymentInfo($db){

		// print_r($_POST); 

		session_start(); 
		$username = $_SESSION['username']; 

		$address = ''; 

		if(isset($_POST['address'])){
			$address = $_POST['address']; 
		}

		// echo"address: 
		// $address"; 
		$updates = []; 
		if(!empty($address)){
			$updates[] = "end_address = '$address'";
			}

		if(isset($_POST['address'])){
			// $sql = "UPDATE END_USER SET " . implode(', ', $updates) . " WHERE end_username = '$username'";
			$sql = "UPDATE END_USER SET end_address = '$address' WHERE end_username = '$username'"; 
			$res = $db->query($sql); 

			if($res!==false){
				echo "<h3>Successfully updated user information</h3>";
			}
			else{
				echo "<h3>Failed to update user information</h3>";
			}
		}else{
			echo "<h3>No information to update</h3>";
		} 
	}


?>
