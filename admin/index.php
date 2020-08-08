<?php
session_start();
$noNavBar = '';
$pageTitle = 'Login';
if(isset($_SESSION['userName'])){
	header('Location: dashboard.php');	//Redirect to Dashboard Page
	exit;
}

 include "init.php";	


 //Check If User Comming From Http Post Request
	 if( $_SERVER['REQUEST_METHOD'] == 'POST' ){

	 		$userName = $_POST['user'];
	 		$password = $_POST['pass'];
	 		$hashedPass = sha1($password);

// Check If The User Exist In Database	 

		$stmt = $con->prepare('SELECT 
									UserID, Username, Password 
							   From
							   	    users
							   WHERE 
									Username = ?
							    AND 
									Password = ?
							    AND 
									GroupID = 1
								LIMIT 1');

		$stmt->execute(array($userName, $hashedPass));
		$row = $stmt->fetch();		// get data From Database in array
		$count = $stmt->rowCount();    // how many rows found in database table

			if($count > 0 ) {		//if Count > 0 This Is Mean Database Contaning That Username
				$_SESSION['userName'] = $userName;  // Register Session Name
				$_SESSION['ID'] = $row['UserID'];	//Register Session ID	
				header('Location: dashboard.php');	//Redirect to Dashboard Page
				exit;
			}

	  }

  ?>

 	<form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
 		<h4 class="text-center">Admin Login</h4>
 		<input class="form-control" type="text" name="user" placeholder="UserName" autocomplete="off" />
 		<input class="form-control" type="password" name="pass" placeholder="Password" autocomplete="new-password" />
 		<input class="btn btn-primary btn-block" type="submit" value="Login" />
 		
 	</form>


 
<?php include $tpl . "footer.php"; ?>

 