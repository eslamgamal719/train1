<?php 


	/*
		Manage members page
		You Can Add | Delete | Edit Members From Here 
	*/

 session_start();
 $pageTitle = "Members";

if(isset($_SESSION['userName'])){

	include 'init.php';

$do = (isset($_GET['do'])) ? $_GET['do'] : 'Manage';

	    //Start Manage Page
	 if($do == 'Manage'){ 	//Manage Members Page

	 	$query = '';
	 	if(isset($_GET['page']) && $_GET['page'] == 'Pending') {
	 		$query = "AND RegStatus = 0";
	 	}



	 	//Select All Users Except Admins
	 	$stmt = $con->prepare("SELECT * FROM users WHERE GroupID !=1 ORDER BY UserID DESC $query");
	 	$stmt->execute();
	 	$members = $stmt->fetchAll();

	  
         if(!empty($members)){
    ?>         
	 <h2 class="text-center">Manage Members</h2>
	 <div class="container">
	 	<div class="table-responsive">
	 		<table class="main-table manage-members text-center table table-bordered">
	 			<tr>
	 				<td>#ID</td>
	 				<td>Avatar</td>
	 				<td>Username</td>
	 				<td>Email</td>
	 				<td>Full Name</td>
	 				<td>Registered Date</td>
	 				<td>Control</td>
	 			</tr>

	 			<?php
                  
                        foreach($members as $member){

                            echo "<tr>";
                                echo "<td>" . $member['UserID'] . "</td>" ;

                                echo "<td>";
                                	if(empty($member['Avatar'])) {
                                		 echo "<img class='img-responsive' src='uploads\avatars\index.jpg' alt=''>";
                               	    } else {
                               			 echo "<img src='uploads\avatars\\" . $member['Avatar'] . "' alt='' >";
                                    }
                                echo "</td>" ;
                                echo "<td>" . $member['Username'] . "</td>" ;
                                echo "<td>" . $member['Email'] . "</td>" ;
                                echo "<td>" . $member['FullName'] . "</td>";
                                echo "<td>" . $member['Date'] . "</td>";
                                echo "<td>
                                        <a href='members.php?do=Edit&userid=" . $member['UserID']  .							 "' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
                                        <a href='members.php?do=Delete&userid=" . $member['UserID']  .
                                         "' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete</a>";

                                         if( $member["RegStatus"] == 0) {

                                    echo "<a href='members.php?do=Activate&userid=" . $member['UserID']  .
                                         "' class='btn btn-info activate'><i class='fa fa-check'></i> Activate</a>";	

                                         }

                            echo "</td>";
                            echo "</tr>";

                        }
              
	 			?>
	 		
	 		</table>
	 	</div>
	 	 <a href='members.php?do=Add' class="btn btn-primary"><i class='fa fa-plus'></i> New Member</a>
	 </div>	
    <?php 
               }else {
                        echo "<div class='container'>";
                            echo "<div class='alert alert-info'>There Is No Users To Show</div>";
                            echo "<a href='members.php?do=Add' class='btn btn-primary'><i class='fa fa-plus'></i> New Member</a>";

                        echo"</div>";
                        
                  }
    ?>


<?php	 }elseif ($do == 'Add') { //Add Members Page ?>
	 

	 		<h2 class="text-center">Add New Member</h2>
		 	<div class="container">

		 		<form class="form-horizontal" action="?do=Insert" method="post" enctype="multipart/form-data">
		 			<!-- Start UserName Field -->
		 			<div class="form-group">
		 				<label class="col-sm-2 control-label">Username</label>
		 				<div class="col-sm-4">
		 					<input type="text" name="username" class="form-control" required='required' autocomplete="off" placeholder="Username To Login Shop" />
		 				</div>
		 			</div>
		 			<!-- End UserName Field -->
		 				<!-- Start Password Field -->
		 			<div class="form-group">
		 				<label class="col-sm-2 control-label">Password</label>
		 				<div class="col-sm-4">
	 					<input type="Password" name="password" class="password form-control" required="required" autocomplete="new-password" placeholder="Password Must Be Strong" />
	 					<i class="show-pass fa fa-eye fa-2x>"></i>
		 				</div>
		 			</div>
		 			<!-- End Password Field -->
		 				<!-- Start Email Field -->
		 			<div class="form-group">
		 				<label class="col-sm-2 control-label">Email</label>
		 				<div class="col-sm-4">
		 					<input type="email" name="email" required='required' class="form-control" placeholder="Email Must Be Valid" />
		 				</div>
		 			</div>
		 			<!-- End Email Field -->
		 				<!-- Start Full Name Field -->
		 			<div class="form-group">
		 				<label class="col-sm-2 control-label">Full Name</label>
		 				<div class="col-sm-4">
		 					<input type="text" name="full"  required='required' class="form-control" placeholder="Appear In Your Profile Page" />
		 				</div>
		 			</div>
		 			<!-- End Full Name Field -->
		 			<!-- Start Avatar Image Field -->
		 			<div class="form-group">
		 				<label class="col-sm-2 control-label">User Avatar</label>
		 				<div class="col-sm-4">
		 					<input type="file" name="avatar" required="required" class="form-control" />
		 				</div>
		 			</div>
		 			<!-- End Avatar Image Field -->
		 				<!-- Start Submit Field -->
		 			<div class="form-group">
		 				<div class="col-sm-offset-2 col-sm-10">
		 					<input type="submit" value="Add Member" class="btn btn-primary" />
		 				</div>
		 			</div>
		 			<!-- End Submit Field -->
		 		</form>	
		 	</div>

	<?php 

		} elseif ($do == 'Insert') { // Insert Member Page

			

			if( $_SERVER['REQUEST_METHOD'] == 'POST') {	//make sure That User Comes With POST From Form

				echo '<h2 class="text-center">Insert Member</h2>';
				echo "<div class= 'container'>";

				//Upload Variable 
			//	$avatar = $_FILES['avatar'];  (An Array Contains: name - temp_name - size - type)

				$avatarName = $_FILES['avatar']['name'];
				$avatarSize = $_FILES['avatar']['size'];
				$avatarTmp  = $_FILES['avatar']['tmp_name'];
				$avatarType = $_FILES['avatar']['type'];

				//List Of Allowed File Types To Upload 
				$avatarAllowedExtension = array("jpeg", "jpg", "png", "gif");

				//Get Avatar Extension 
				$avatarExt = explode(".", $avatarName);
				$avatarExtension = strtolower(end($avatarExt));

				

				//Get Variables From The Form
				$user 	 = $_POST['username'];
				$pass    = $_POST['password'];
				$email 	 = $_POST['email'];
				$name 	 = $_POST['full'];


				$hashPass = sha1($_POST['password']);

			
				//Validate The Form ((From Server Side))
			$formErrors = array();
			if(strlen($user) < 3){
				$formErrors[] = "UserName Can't Be Less Than <strong>3 Chars</strong>";
			}

			if(strlen($user) > 20){
				$formErrors[] = "UserName Can't Be More Than <strong>20 Chars</strong>";
			}

			if(empty($user)){
				$formErrors[] = "UserName Can't Be <strong>Empty</strong>";
			}

			if(empty($pass)){
				$formErrors[] = "Password Can't Be <strong>Empty</strong>";
			}

			if(empty($name)){
				$formErrors[] = ">Full Name Can't Be <strong>Empty</strong>";
			}

			if(empty($email)){
				$formErrors[] = "Email Can't Be <strong>Empty</strong>";
			}

			if(!empty($avatarName) && !in_array($avatarExtension, $avatarAllowedExtension)) {
				$formErrors[] = "This Extension In Not <strong>Allowed</strong>";
			}

			if(empty($avatarName)) {
				$formErrors[] = "Avatar Can't Be <strong>Empty</strong>";
			}


			if($avatarSize > 4194304) {
				$formErrors[] = "Avatar Can't Be Larger Than <strong>4MB</strong>";
			}



			//Loop Into Error Array And Echo Errors
			foreach($formErrors as $error){
				echo "<div class='alert alert-danger'>" .  $error ."</div>";
			}

				//Check If There Is No Errors Proceed The Update Operation	
	  if(empty($formErrors)){

	  	$avatar = rand(0 ,100000000) . "_" . $avatarName;

	  	move_uploaded_file($avatarTmp, "uploads\avatars\\" . $avatar);
			
			//check If User Is Exist In Database
		    $check = checkItem('Username', 'users', $user);	
		    if($check ==1) {
			$theMsg = "<div class='alert alert-danger'>Sorry, This Username Is Already Exist!</div>";
			redirectHome($theMsg, 'back');

			}else {

		  		// Insert User Info Into Database
				$stmt = $con->prepare("INSERT INTO
									 users(Username, Password, Email, FullName, RegStatus, Date, Avatar)
									 VALUES
									 (:zuser, :zpass, :zemail, :zname, 1, now(), :zavatar) ");

				$stmt->execute(array(
					'zuser' 	=> $user, 
					'zpass' 	=> $hashPass,
					'zemail'	=> $email,
					'zname' 	=> $name,
					'zavatar'   => $avatar
				));

						//Echo Sucess Message
				$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Record Inserted</div>";
	 			redirectHome($theMsg, 'back', 4);
				   }
			}   
			
		 } else {
				echo "<div class='container'>";
				$theMsg = "<div class='alert alert-danger'>You Can't Browse This Page Directly</div>";
				redirectHome($theMsg);
				echo "</div>";
		}

		echo "</div>";


			
		

		} elseif ($do == 'Edit'){ //Edit Page 

	 	//Check If Get Request userid exist and if it is numeric and Get The Int Value Of It
	  $userid = (isset($_GET['userid']) &&  is_numeric($_GET['userid'])) ?  intval($_GET['userid']) :  0;
	 
	 		$stmt = $con->prepare('SELECT * From users WHERE UserID = ? LIMIT 1');

		$stmt->execute(array($userid));
		$row = $stmt->fetch();		// get data From Database in array
		$count = $stmt->rowCount();    // how many rows found in database table


		if($count > 0) { ?>


		 	<h2 class="text-center">Edit Member</h2>

		 	<div class="container">
		 		<form class="form-horizontal" action="?do=update" method="post">
		 			<input type="hidden" name="userid" value="<?php echo $userid ?>">
		 			<!-- Start UserName Field -->
		 			<div class="form-group">
		 				<label class="col-sm-2 control-label">Username</label>
		 				<div class="col-sm-4">
		 					<input type="text" name="username" class="form-control" value="<?php echo $row['Username'] ?>" required='required' autocomplete="off" />
		 				</div>
		 			</div>
		 			<!-- End UserName Field -->
		 				<!-- Start Password Field -->
		 			<div class="form-group">
		 				<label class="col-sm-2 control-label">Password</label>
		 				<div class="col-sm-4">
		 					<input type="hidden" name="oldpassword" value="<?php echo $row['Password'] ?>" />

		 					<input type="Password" name="newpassword" class="form-control" autocomplete="new-password" placeholder="Leave blank If You Don't Need Change" />
		 				</div>
		 			</div>
		 			<!-- End Password Field -->
		 				<!-- Start Email Field -->
		 			<div class="form-group">
		 				<label class="col-sm-2 control-label">Email</label>
		 				<div class="col-sm-4">
		 					<input type="email" name="email" required='required' value="<?php echo $row['Email'] ?>" class="form-control" />
		 				</div>
		 			</div>
		 			<!-- End Email Field -->
		 				<!-- Start Full Name Field -->
		 			<div class="form-group">
		 				<label class="col-sm-2 control-label">Full Name</label>
		 				<div class="col-sm-4">
		 					<input type="text" name="full" value="<?php echo $row['FullName'] ?>" required='required' class="form-control" />
		 				</div>
		 			</div>
		 			<!-- End Full Name Field -->
		 				<!-- Start Submit Field -->
		 			<div class="form-group">
		 				<div class="col-sm-offset-2 col-sm-10">
		 					<input type="submit" value="Save" class="btn btn-primary" />
		 				</div>
		 			</div>
		 			<!-- End Submit Field -->
		 		</form>	
		 	</div>

	 <?php } else {
	 		echo "<div class='container'>";
	 		$theMsg = "<div class='alert alert-danger'>Sorry, This ID Not Found</div>";
	 		redirectHome($theMsg);
	 		echo "</div>";	
	    	}

	} elseif($do == 'update') {	//update Page

			echo '<h2 class="text-center">Update Member</h2>';
			echo "<div class= 'container'>";

			if( $_SERVER['REQUEST_METHOD'] == 'POST') {	//make sure That User Comes With POST From Form

				//Get Variables From The Form

				$id 	 = $_POST['userid'];
				$user 	 = $_POST['username'];
				$email 	 = $_POST['email'];
				$name 	 = $_POST['full'];

				// Password trick
			$pass = empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);

				//Validate The Form ((From Server Side))
			$formErrors = array();
			if(strlen($user) < 3){
				$formErrors[] = "UserName Can't Be Less Than <strong>3 Chars</strong>";
			}

			if(strlen($user) > 20){
				$formErrors[] = "UserName Can't Be More Than <strong>20 Chars</strong>";
			}

			if(empty($user)){
				$formErrors[] = "UserName Can't Be <strong>Empty</strong>";
			}

			if(empty($pass)){
				$formErrors[] = "Password Can't Be <strong>Empty</strong>";
			}

			if(empty($name)){
				$formErrors[] = ">Full Name Can't Be <strong>Empty</strong>";
			}

			if(empty($email)){
				$formErrors[] = "Email Can't Be <strong>Empty</strong>";
			}

			//Loop Into Error Array And Echo Errors
			foreach($formErrors as $error){
				echo "<div class='alert alert-danger'>" .  $error ."</div>";
			}

				//Check If There Is No Errors Proceed The Update Operation	
			    if(empty($formErrors)){
                   
                    $stmt2 = $con->prepare('SELECT
                                                    *
                                            FROM
                                                    users
                                            WHERE
                                                    Username= ?
                                            AND
                                                    UserID != ?');
                    
                    $stmt2->execute(array($user, $id));
                    $count = $stmt2->rowCount();
                  
                    if($count == 1) {
                        $theMsg = "<div class='alert alert-danger'>Sorry This User Is Already Exist</div>";
                        redirectHome($theMsg, 'back', 4);
                    }else {
                        
                        		// Update The Database With The New Info 
					$stmt = $con->prepare('UPDATE users SET Username = ?, Email = ?, FullName = ?, Password = ? WHERE UserID = ?');
					$stmt->execute(array($user, $email, $name, $pass, $id));

					//Echo Sucess Message
					$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Record Updated</div>";
 					
 					redirectHome($theMsg, 'back', 4);
                        
                    }
			   }

				
			} else {

				$errorMsg = "<div class='alert alert-danger'>You Can't Browse This Page Directly</div>";
				redirectHome($errorMsg);
			}

			echo "</div>";

	} elseif ($do == 'Delete') { //Delete Page

		echo '<h2 class="text-center">Delete Member</h2>';
		echo "<div class= 'container'>";


			  $userid = (isset($_GET['userid']) &&  is_numeric($_GET['userid'])) ?  intval($_GET['userid']) :  0;

			//Select All Data From db Depend On This ID  
		    $check = checkItem('UserID', 'users', $userid);
		 	if($check > 0) { 
				$stmt = $con->prepare('DELETE FROM users WHERE UserID = :zuser LIMIT 1');
				$stmt->bindParam(':zuser', $userid);
				$stmt->execute();

				$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Record Deleted</div>";
				redirectHome($theMsg, 'back');
			}else{
				$theMsg = "<div class='alert alert-danger'>not Exist</div>";
				redirectHome($theMsg);
			}

	echo "</div>";
	} elseif ($do == "Activate") { //Activate Members
		
        echo '<h2 class="text-center">Activate Member</h2>';
            echo "<div class= 'container'>";


                  $userid = (isset($_GET['userid']) &&  is_numeric($_GET['userid'])) ?  intval($_GET['userid']) :  0;

                //Select All Data From db Depend On This ID  
                $check = checkItem('UserID', 'users', $userid);
                if($check > 0) { 
                    $stmt = $con->prepare('UPDATE users SET RegStatus = 1 WHERE UserID = ?');
                    $stmt->execute(array($userid));

                    $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Record Activated</div>";
                    redirectHome($theMsg, 'back');
                }else{
                    $theMsg = "<div class='alert alert-danger'>Sorry,This ID Is not Exist</div>";
                    redirectHome($theMsg);
                }

	}


		include $tpl . "footer.php";
	
} else{
	header('Location: index.php');
	exit;
	}



