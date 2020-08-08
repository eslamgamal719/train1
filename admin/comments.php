<?php 


	/*
		Manage Cmments page
		You Can  Delete | Edit | Approve Comment From Here 
	*/

 session_start();
 $pageTitle = "Comments";

if(isset($_SESSION['userName'])){

	include 'init.php';

$do = (isset($_GET['do'])) ? $_GET['do'] : 'Manage';

	    //Start Manage Page
	 if($do == 'Manage'){ 	//Manage Comments Page

	 
	 	//Select All Users Except Admins
	 	$stmt = $con->prepare("SELECT 
                                    comments.*, items.Name AS Item_Name, users.Username 
                              FROM 
                                    comments
                              INNER JOIN
                                    items
                              ON
                                    items.Item_ID = comments.Item_id
                              INNER JOIN
                                    users
                              ON
                                    users.UserID = comments.User_id
                              ORDER BY 
                                    C_ID
                              DESC            
                              
                              ");
	 	$stmt->execute();
	 	$comments = $stmt->fetchAll();
         
         if(!empty($comments)){
	  ?>
	 
	 <h2 class="text-center">Manage Comments</h2>
	 <div class="container">
	 	<div class="table-responsive">
	 		<table class="main-table text-center table table-bordered">
	 			<tr>
	 				<td>ID</td>
	 				<td>Comment</td>
	 				<td>Item Name</td>
                    <td>User Name</td>
                    <td>Added Date</td>
	 				<td>Control</td>
	 			</tr>

	 			<?php

	 				foreach($comments as $comment){

	 					echo "<tr>";
	 						echo "<td>" . $comment['C_ID'] . "</td>" ;
	 						echo "<td>" . $comment['Comment'] . "</td>" ;
	 						echo "<td>" . $comment['Item_Name'] . "</td>" ;
	 						echo "<td>" . $comment['Username'] . "</td>";
	 						echo "<td>" . $comment['Comment_Date'] . "</td>";
	 						echo "<td>
	 								<a href='comments.php?do=Edit&comid=" . $comment['C_ID'] . "' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
	 								<a href='comments.php?do=Delete&comid=" . $comment['C_ID'] .
	 								 "' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete</a>";

	 								 if( $comment["Status"] == 0) {

	 							echo "<a href='comments.php?do=Approve&comid=" . $comment['C_ID']  .
	 								 "' class='btn btn-info activate'><i class='fa fa-check'></i> Approve</a>";	

	 								 }

	 					echo "</td>";
	 					echo "</tr>";

	 				}
	 			?>
	 		
	 		</table>
	 	</div>

	 </div>	 
          <?php 
               }else {
                        echo "<div class='container'>";
                            echo "<div class='alert alert-info'>There Is No Comments To Show</div>";
                        echo"</div>";
                        
                  }
            ?>


			
		
<?php
		} elseif ($do == 'Edit'){ //Edit Page 

	 	//Check If Get Request comid exist and if it is numeric and Get The Int Value Of It
	  $comid = (isset($_GET['comid']) &&  is_numeric($_GET['comid'])) ?  intval($_GET['comid']) :  0;
	 
	 		$stmt = $con->prepare('SELECT * From comments WHERE C_ID = ?');

		$stmt->execute(array($comid));
		$row = $stmt->fetch();		// get data From Database in array
		$count = $stmt->rowCount();    // how many rows found in database table


		if($count > 0) { ?>


		 	<h2 class="text-center">Edit Comment</h2>

		 	<div class="container">
		 		<form class="form-horizontal" action="?do=update" method="post">
		 			<input type="hidden" name="comid" value="<?php echo $comid ?>">
		 			<!-- Start Comment Field -->
		 			<div class="form-group">
		 				<label class="col-sm-2 control-label">Comments</label>
		 				<div class="col-sm-4">
                            <textarea class="form-control" name="comment"><?php echo $row['Comment'] ?></textarea>
		 				</div>
		 			</div>
		 			<!-- End Comment Field -->
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

			echo '<h2 class="text-center">Update Comment</h2>';
			echo "<div class= 'container'>";

			if( $_SERVER['REQUEST_METHOD'] == 'POST') {	//make sure That User Comes With POST From Form

                    //Get Variables From The Form

                    $id 	  = $_POST['comid'];
                    $comment  = $_POST['comment'];


                  // Update The Database With The New Info 
                $stmt = $con->prepare('UPDATE comments SET Comment = ? WHERE C_ID = ?');
                $stmt->execute(array($comment, $id));

                 //Echo Sucess Message
                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Record Updated</div>";

                redirectHome($theMsg, 'back', 4);
         

			} else {

				$errorMsg = "<div class='alert alert-danger'>You Can't Browse This Page Directly</div>";
				redirectHome($errorMsg);
            }
         echo "</div>";
         
     

	} elseif ($do == 'Delete') { //Delete Page

		echo '<h2 class="text-center">Delete Comment</h2>';
		echo "<div class= 'container'>";


			  $comid = (isset($_GET['comid']) &&  is_numeric($_GET['comid'])) ?  intval($_GET['comid']) :  0;

			//Select All Data From db Depend On This ID  
		    $check = checkItem('C_ID', 'comments', $comid);
         
		 	if($check > 0) { 
				$stmt = $con->prepare('DELETE FROM comments WHERE C_ID = :zcomment');
				$stmt->bindParam(':zcomment', $comid);
				$stmt->execute();

				$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Comment Deleted</div>";
				redirectHome($theMsg, 'back');
			}else{
				$theMsg = "<div class='alert alert-danger'>not Exist</div>";
				redirectHome($theMsg);
			}

	echo "</div>";
	} elseif ($do == "Approve") { //Activate Members
		
        echo '<h2 class="text-center">Approve Comment</h2>';
            echo "<div class= 'container'>";


                  $comid = (isset($_GET['comid']) &&  is_numeric($_GET['comid'])) ?  intval($_GET['comid']) :  0;

                //Select All Data From db Depend On This ID  
                $check = checkItem('C_ID', 'comments', $comid);
                if($check > 0) { 
                    $stmt = $con->prepare('UPDATE comments SET Status = 1 WHERE C_ID = ?');
                    $stmt->execute(array($comid));

                    $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Comment Approved</div>";
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





