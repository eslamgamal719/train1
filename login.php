<?php

session_start();

$pageTitle = 'Login';
  if(isset($_SESSION['user'])){
	header('Location: index.php');	//Redirect to Dashboard Page
	exit;
  }

include "init.php";

 //Check If User Comming From Http Post Request
	 if( $_SERVER['REQUEST_METHOD'] == 'POST' ){

	 		if(isset($_POST['login'])) { // if you make login 

		 		$user = $_POST['username'];
		 		$pass = $_POST['password'];
		 		$hashedPass = sha1($pass);

			 		
				// Check If The User Exist In Database	 

				$stmt = $con->prepare('SELECT 
											UserID, Username, Password 
									   From
									   	    users
									   WHERE 
											Username = ?
									    AND 
											Password = ?');

				$stmt->execute(array($user, $hashedPass));

				$get = $stmt->fetch();

				$count = $stmt->rowCount();    // how many rows found in database table

					if($count > 0 ) {		//if Count > 0 This Is Mean Database Contaning That Username
						
						$_SESSION['user'] = $user;  // Register Session Name

						$_SESSION['uid'] = $get['UserID'];  // Register Userid In Session
						
						header('Location: index.php');	//Redirect to Dashboard Page
						exit;
					}

			} else {  // it mean than there is no post login but there is post signup (if you make sign up)
			
			    $formErrors = array();

			    $username 	= $_POST['username'];
			    $password 	= $_POST['password'];
			    $password2  = $_POST['password2'];
			    $email 		= $_POST['email'];


				if(isset($username)) {
				     $filteredUser = filter_var($username, FILTER_SANITIZE_STRING); //filter user name from tags or any harm things

					if(strlen($filteredUser) < 4) {
						$formErrors[] = "Username Must Be More Than 4 Characters";
					}
				}



		    	if(isset($password) && isset($password2)) {

		    		  if( empty($password) ) {

					$formErrors[] = "Password Can't Be Empty";

				     }
				
			    

				    if(sha1($_POST['password'])  !==  sha1($_POST['password2'])) {

					$formErrors[] = "Sorry, Password Is Not Match";
				     }
					
					

			    }




				if(isset($email)) {
				$filteredEmail = filter_var($email, FILTER_SANITIZE_EMAIL); //filter user name from tags or any harm things

					if(filter_var($filteredEmail, FILTER_VALIDATE_EMAIL) != true) { 
						$formErrors[] = "This Email Is Not Valid";
					}
			}

	//Check If There Is No Errors Proceed The User Add 	
	  if(empty($formErrors)){
			
			//check If User Is Exist In Database
		    $check = checkItem('Username', 'users', $username);	
		    if($check ==1) {
			$formErrors[] = "Sorry, This Username Is Already Exist";
			
			} else {

		  		// Insert User Info Into Database
				$stmt = $con->prepare("INSERT INTO
									 users(Username, Password, Email, RegStatus, Date)
									 VALUES
									 (:zuser, :zpass, :zemail, 0, now())");

				$stmt->execute(array(
					'zuser' => $username, 
					'zpass' => sha1($password),
					'zemail'=> $email
				));

						$successMsg = "Congrats You Are Now Registerd";

				   }
			} 


		}		

    }

?>

<div class="container login-page">
	<h2 class="text-center">
		<span class="selected" data-class='login'>Login</span> |
		 <span data-class="signup">Signup</span>
	</h2>
	<!-- Start Login Form -->
	<form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method='POST'>
		<div class="input-container">
	   		 <input
	   		
	   		  class="form-control"
	   		  type="text"
	   		  name="username"
	   		  autocomplete="off"
	   		  placeholder="Type Your Username"
	   		  required />
		</div>
		<div class="input-container">
	    <input class="form-control" type="password" name="password" autocomplete="new-password" placeholder="Type Your password"  />
	   	</div>
	    <input class="btn btn-primary btn-block" type="submit" value="Login"  name='login'/>
	</form>
	<!-- End Login Form -->
	<!-- Start Signup Form -->
		<form class="signup" action="<?php echo $_SERVER['PHP_SELF'] ?>" method='POST' >
		<div class="input-container">
	         <input 
	         pattern=".{4,}"
	   		 title="Username Must Be 4 Chars"
	         class="form-control" 
	         type="text" 
	         name="username" 
	         autocomplete="off" 
	         placeholder="Type Your Username" 
	         required/>
	    </div>
	    <div class="input-container">
	    	<input 
	    	minlength="4" 
	    	class="form-control" 
	    	type="password" 
	    	name="password" 
	    	autocomplete="new-password" 
	    	placeholder="Type a complex password"
	    	required  />
		</div>
	    <div class="input-container">
	    <input 
	    minlength="4" 
	    class="form-control" 
	    type="password" 
	    name="password2" 
	    autocomplete="new-password" 
	    placeholder="Type the password again"
	    required  />
		</div>
		<div class="input-container">
	    <input class="form-control" type="email" name="email" placeholder="Type Your e-mail"  />
		</div>

	    <input class="btn btn-success btn-block" type="submit" value="Signup" name='signup'/>
	</form>
	<!-- End Signup Form -->

	<div class="the-errors text-center">
		<?php
			if(!empty($formErrors)) {

				foreach( $formErrors as $error ) {
					echo $error . '<br>';
				}
			}

			if(isset($successMsg)) {

				echo '<div class="msg success">' . $successMsg . '</div>';
			}

		  ?>
	</div>	
</div>


<?php
include  $tpl . "footer.php";
?>