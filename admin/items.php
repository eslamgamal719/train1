<?php

/*
=================================================
================ Items Page =====================
=================================================	
*/

	ob_start();

	session_start();

	$pageTitle = 'Items';

if(isset($_SESSION['userName'])){

	include 'init.php';

  $do = (isset($_GET['do'])) ? $_GET['do'] : 'Manage';

	    
	 if($do == 'Manage'){
	

	 	$stmt = $con->prepare("SELECT
                                      items.*,
                                      categories.Name AS Category_Name,
                                      users.Username
                               FROM
                                      items
                               INNER JOIN
                                      categories 
                               ON
                                      categories.ID = items.Cat_ID
                               INNER JOIN
                                      users
                               ON
                                      users.UserID = items.Member_ID
                               ORDER BY 
                                      Item_ID 
                               DESC
                                ");
	 	$stmt->execute();
	 	$items = $stmt->fetchAll();
         
         if(!empty($items)) {
	  ?>
	 
	 <h2 class="text-center">Manage Items</h2>
	 <div class="container">
	 	<div class="table-responsive">
	 		<table class="main-table text-center table table-bordered">
	 			<tr>
	 				<td>#ID</td>
	 				<td>Name</td>
	 				<td>Description</td>
	 				<td>Price</td>
	 				<td>Adding Date</td>
                    <td>Category</td>
                    <td>Username</td>
	 				<td>Control</td>
	 			</tr>

            <?php

                foreach($items as $item){

                    echo "<tr>";
                        echo "<td>" . $item['Item_ID'] . "</td>" ;
                        echo "<td>" . $item['Name'] . "</td>" ;
                        echo "<td>" . $item['Description'] . "</td>" ;
                        echo "<td>" . $item['Price'] . "</td>";
                        echo "<td>" . $item['Add_Date'] . "</td>";
                        echo "<td>" . $item['Username'] . "</td>";
                        echo "<td>" . $item['Category_Name'] . "</td>";
                        echo "<td>
                                <a href='items.php?do=Edit&itemid=" . $item['Item_ID'] . "' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
                                <a href='items.php?do=Delete&itemid=" . $item['Item_ID']  .
                                 "' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete</a>";

                                if( $item["Approve"] == 0) {

                                    echo "<a href='items.php?do=Approve&itemid=" . $item['Item_ID']  .
                                 "' class='btn btn-info activate'><i class='fa fa-check'></i> Approve</a>";	

                                 }

                                 }

                    echo "</td>";
                    echo "</tr>";


            ?>
	 		
	 		</table>
	 	</div>
	 	 <a href='items.php?do=Add' class="btn btn-primary"><i class='fa fa-plus'></i> New Item</a>
	 </div>	
        <?php  
                 }else {
                        echo "<div class='container'>";
                            echo "<div class='alert alert-info'>There Is No Items To Show</div>";
                            echo "<a href='items.php?do=Add' class='btn btn-primary'><i class='fa fa-plus'></i> New Item</a>";

                        echo"</div>";
                        
                  }
        ?>

    
	<?php
	 } elseif ($do == 'Add') { ?>

	 		<h2 class="text-center">Add New Item</h2>
		 	<div class="container">

		 		<form class="form-horizontal" action="?do=Insert" method="post">
		 			<!-- Start Name Field -->
		 			<div class="form-group">
		 				<label class="col-sm-2 control-label">Name</label>
		 				<div class="col-sm-4">
		 					<input type="text"
		 						   name="name" 
		 						   class="form-control" 
		 						   required='required'
		 						   placeholder="Name Of The Item" />
		 				</div>
		 			</div>
		 			<!-- End Name Field -->
		 			<!-- Start Description Field -->
		 			<div class="form-group">
		 				<label class="col-sm-2 control-label">Description</label>
		 				<div class="col-sm-4">
		 					<input type="text" 
		 						   name="description" 
		 						   class="form-control" 
		 						   placeholder="Descibe The Item" />
		 			    </div>
		 			</div>
		 			<!-- End Description Field -->
		 			<!-- Start Price Field -->
		 			<div class="form-group">
		 				<label class="col-sm-2 control-label">Price</label>
		 				<div class="col-sm-4">
		 					<input type="text" 
		 						   name="price" 
		 						  required='required'
		 						   class="form-control" 
		 						   placeholder="Add The Price Of Item" />
		 			    </div>
		 			</div>
		 			<!-- End Price Field -->
		 			<!-- Start Country Field -->
		 			<div class="form-group">
		 				<label class="col-sm-2 control-label">Country</label>
		 				<div class="col-sm-4">
		 					<input type="text" 
		 						   name="country" 	
								   required='required'	
		 						   class="form-control" 
		 						   placeholder="Country Where Made" />
		 			    </div>
		 			</div>
		 			<!-- End Country Field -->
		 			<!-- Start Status Field -->
		 			<div class="form-group">
		 				<label class="col-sm-2 control-label">Status</label>
		 				<div class="col-sm-4">
		 					<select name="status">
		 						<option value="0">...</option>
		 						<option value="1">New</option>
		 						<option value="2">Like New</option>
		 						<option value="3">Used</option>
		 						<option value="4">Very Old</option>
		 					</select>
		 			    </div>
		 			</div>
		 			<!-- End Status Field -->
					<!-- Start Members Field -->
		 			<div class="form-group">
		 				<label class="col-sm-2 control-label">Member</label>
		 				<div class="col-sm-4">
		 					<select name="member">
		 						<option value="0">...</option>
								<?php
									$allMembers = getAllFrom("*", "users", "", "", "UserID");
									foreach($allMembers as $user){
										echo "<option value='" . $user['UserID'] . "'>" . $user['Username'] . "</option>";
									}
								?>
		 					</select>
		 			    </div>
		 			</div>
		 			<!-- End Members Field -->
					<!-- Start Categories Field -->
		 			<div class="form-group">
		 				<label class="col-sm-2 control-label">Category</label>
		 				<div class="col-sm-4">
		 					<select name="category">
		 						<option value="0">...</option>
								<?php
									$allCats = getAllFrom("*", "categories", "where parent = 0", "", "ID");
									foreach($allCats as $cat){
										echo "<option value='" . $cat['ID'] . "'>" . $cat['Name'] . "</option>";
										$childCats = getAllFrom("*", "categories", "where parent = {$cat['ID']}", "", "ID");
										foreach($childCats as $child) {
											echo "<option value='" . $child['ID'] . "'>---" . $child['Name'] . "</option>";
										}

									}
								?>
		 					</select>
		 			    </div>
		 			</div>
		 			<!-- End Categories Field -->
		 				<!-- Start Tags Field -->
		 			<div class="form-group">
		 				<label class="col-sm-2 control-label">Tags</label>
		 				<div class="col-sm-4">
		 					<input type="text" 
		 						   name="tags" 	
		 						   class="form-control" 
		 						   placeholder="Separate Tags With Comma (,)" />
		 			    </div>
		 			</div>
		 			<!-- End Tags Field -->
					

		 			<!-- Start Submit Field -->
		 			<div class="form-group">
		 				<div class="col-sm-offset-2 col-sm-10">
		 					<input type="submit" value="Add Item" class="btn btn-primary" />
		 				</div>
		 			</div>
		 			<!-- End Submit Field -->
		 		</form>	
		 	</div>
	
	<?php 
	 	
	 } elseif ($do == 'Insert') {
		 
		 
		if( $_SERVER['REQUEST_METHOD'] == 'POST') {	

					echo '<h2 class="text-center">Insert Item</h2>';
					echo "<div class= 'container'>";

					//Get Variables From The Form

					$name 		 = $_POST['name'];
					$desc  	     = $_POST['description'];
					$price 	 	 = $_POST['price'];
					$country 	 = $_POST['country'];
					$status 	 = $_POST['status'];
					$member 	 = $_POST['member'];
					$cat 	 	 = $_POST['category'];
					$tags 	 	 = $_POST['tags'];


					//Validate The Form ((From Server Side))
				$formErrors = array();
				if(empty($name)){
					$formErrors[] = "Name Can't Be <strong>Empty</strong>";
				}

				if(empty($desc)){
					$formErrors[] = "Description Can't Be <strong>Empty</strong>";
				}

				if(empty($price)){
					$formErrors[] = "Price Can't Be <strong>Empty</strong>";
				}

				if(empty($country)){
					$formErrors[] = "Country Can't Be <strong>Empty</strong>";
				}

				if($status == 0){
					$formErrors[] = ">You Must Choose <strong>Status</strong>";
				}
				
				if($member == 0){
					$formErrors[] = ">You Must Choose <strong>Member</strong>";
				}
				
				if($cat == 0){
					$formErrors[] = ">You Must Choose <strong>Category</strong>";
				}

				
				//Loop Into Error Array And Echo Errors
				foreach($formErrors as $error){
					echo "<div class='alert alert-danger'>" .  $error ."</div>";
				}

					//Check If There Is No Errors Proceed The Update Operation	
		    if(empty($formErrors)){
				
				// Insert User Info Into Database
				$stmt = $con->prepare("INSERT INTO
				items(Name, Description, Price, Country_Made, Status, Add_Date, Cat_ID, Member_ID, tags)
				VALUES
			    (:zname, :zdesc, :zprice, :zcountry, :zstatus, now(), :zcat, :zmember, :ztags)");

					$stmt->execute(array(
						'zname' 	=> $name, 
						'zdesc' 	=> $desc,
						'zprice'	=> $price,
						'zcountry'  => $country,
						'zstatus'   => $status,
						'zcat'      => $cat,
						'zmember'   => $member,
						'ztags'   => $tags
						));

							//Echo Sucess Message
					$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Record Inserted</div>";
					redirectHome($theMsg, 'back', 4);
					   }
			  
					
		} else {
				echo "<div class='container'>";
				$theMsg = "<div class='alert alert-danger'>You Can't Browse This Page Directly</div>";
				redirectHome($theMsg);
				echo "</div>";
		}

		echo "</div>";

	 	
	 } elseif ($do == 'Edit') {
         

            //Check If Get Request userid exist and if it is numeric and Get The Int Value Of It
          $itemid = (isset($_GET['itemid']) &&  is_numeric($_GET['itemid'])) ?  intval($_GET['itemid']) :  0;

                $stmt = $con->prepare('SELECT * From items WHERE Item_ID = ?');

            $stmt->execute(array($itemid));
            $item = $stmt->fetch();		// get data From Database in array
            $count = $stmt->rowCount();    // how many rows found in database table


     if($count > 0) { ?>


            <h2 class="text-center">Edit Item</h2>
            <div class="container">

              <form class="form-horizontal" action="?do=Update" method="post">
                 <input type="hidden" name="itemid" value="<?php echo $itemid ?>">

                    <!-- Start Name Field -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-4">
                            <input type="text"
                                   name="name" 
                                   class="form-control" 
                                   required='required'
                                   placeholder="Name Of The Item"
                                   value="<?php echo $item['Name'] ?>"/>
                        </div>
                    </div>
                    <!-- End Name Field -->
                    <!-- Start Description Field -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-4">
                            <input type="text" 
                                   name="description" 
                                   class="form-control" 
                                   placeholder="Descibe The Item" 
                                   value="<?php echo $item['Description'] ?>"/>
                        </div>
                    </div>
                    <!-- End Description Field -->
                    <!-- Start Price Field -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Price</label>
                        <div class="col-sm-4">
                            <input type="text" 
                                   name="price" 
                                  required='required'
                                   class="form-control" 
                                   placeholder="Add The Price Of Item"
                                   value="<?php echo $item['Price'] ?>"/>
                        </div>
                    </div>
                    <!-- End Price Field -->
                    <!-- Start Country Field -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Country</label>
                        <div class="col-sm-4">
                            <input type="text" 
                                   name="country" 	
                                   required='required'	
                                   class="form-control" 
                                   placeholder="Country Where Made" 
                                   value="<?php echo $item['Country_Made'] ?>"/>
                        </div>
                    </div>
                    <!-- End Country Field -->
                    <!-- Start Status Field -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Status</label>
                    <div class="col-sm-4">
                        <select name="status">
                            <option value="1" <?php if($item['Status'] == 1){ echo 'selected';} ?>>New</option>
                            <option value="2" <?php if($item['Status'] == 2){ echo 'selected';} ?>>Like New</option>
                            <option value="3" <?php if($item['Status'] == 3){ echo 'selected';} ?>>Used</option>
                            <option value="4" <?php if($item['Status'] == 4){ echo 'selected';} ?>>Very Old</option>
                        </select>
                    </div>
                </div>
                            <!-- End Status Field -->
                    <!-- Start Members Field -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Member</label>
                        <div class="col-sm-4">
                            <select name="member">
                                <?php

                                    $stmt = $con->prepare("SELECT * FROM users");
                                    $stmt->execute();
                                    $users = $stmt->fetchAll();
                                    foreach($users as $user){
                                        echo "<option value='" . $user['UserID'] . "'";
                                        if($item['Member_ID'] == $user['UserID'] ){ echo 'selected';} 
                                        echo ">" . $user['Username'] . "</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- End Members Field -->
                    <!-- Start Categories Field -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Category</label>
                        <div class="col-sm-4">
                            <select name="category">
                               <?php

                                    $stmt2 = $con->prepare("SELECT * FROM categories");
                                    $stmt2->execute();
                                    $cats = $stmt2->fetchAll();
                                    foreach($cats as $cat){
                                        echo "<option value='" . $cat['ID'] . "'";
                                        if($item['Cat_ID'] == $cat['ID']){ echo 'selected';} 
                                        echo ">" . $cat['Name'] . "</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- End Categories Field -->
                    		<!-- Start Tags Field -->
		 			<div class="form-group">
		 				<label class="col-sm-2 control-label">Tags</label>
		 				<div class="col-sm-4">
		 					<input type="text" 
		 						   name="tags" 	
		 						   class="form-control" 
		 						   placeholder="Separate Tags With Comma (,)"
		 						   value="<?php echo $item['tags'] ?>" />

		 			    </div>
		 			</div>
		 			<!-- End Tags Field -->


                    <!-- Start Submit Field -->
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="Save Item" class="btn btn-primary" />
                        </div>
                    </div>
                    <!-- End Submit Field -->
                </form>
                
        <?php    
                     
                     
                $stmt = $con->prepare("SELECT 
                                    comments.*, users.Username 
                              FROM 
                                    comments
                              INNER JOIN
                                    users
                              ON
                                    users.UserID = comments.User_id
                              WHERE 
                                    Item_id = ?
                              
                              ");
	 	$stmt->execute(array($itemid));
	 	$rows = $stmt->fetchAll();
        
        if(!empty($rows)) {
            
	  ?>
	 
	 <h2 class="text-center">Manage [ <?php echo  $item['Name'] ?> ] Comments</h2>
	 	<div class="table-responsive">
	 		<table class="main-table text-center table table-bordered">
	 			<tr>
	 				<td>Comment</td>
                    <td>User Name</td>
                    <td>Added Date</td>
	 				<td>Control</td>
	 			</tr>

	 			<?php

	 				foreach($rows as $row){

	 					echo "<tr>";
	 						echo "<td>" . $row['Comment'] . "</td>" ;
	 						echo "<td>" . $row['Username'] . "</td>";
	 						echo "<td>" . $row['Comment_Date'] . "</td>";
	 						echo "<td>
	 								<a href='comments.php?do=Edit&comid=" . $row['C_ID'] . "' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
	 								<a href='comments.php?do=Delete&comid=" . $row['C_ID'] .
	 								 "' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete</a>";

	 								 if( $row["Status"] == 0) {

	 							echo "<a href='comments.php?do=Approve&comid=" . $row['C_ID']  .
	 								 "' class='btn btn-info activate'><i class='fa fa-check'></i> Approve</a>";	

	 								 }

	 					echo "</td>";
	 					echo "</tr>";

	 				}
    
	 			?>
	 		
	 		</table>
	 	</div>
                <?php } ?>
     </div>         


			
           

    <?php 



 } else {
                echo "<div class='container'>";
                $theMsg = "<div class='alert alert-danger'>Sorry, This ID Not Found</div>";
                redirectHome($theMsg);
                echo "</div>";	
                }

	 	
	 } elseif ($do == 'Update') {
         
         	echo '<h2 class="text-center">Update Item</h2>';
			echo "<div class= 'container'>";

			if( $_SERVER['REQUEST_METHOD'] == 'POST') {	//make sure That User Comes With POST From Form

				//Get Variables From The Form

				$id 	     = $_POST['itemid'];
				$name 	     = $_POST['name'];
				$desc 	     = $_POST['description'];
				$price 	     = $_POST['price'];
                $country 	 = $_POST['country'];
                
                $status 	 = $_POST['status'];
                $member 	 = $_POST['member'];
                $cat 	     = $_POST['category'];
                $tags 	     = $_POST['tags'];

		
					//Validate The Form ((From Server Side))
				$formErrors = array();
                
				if(empty($name)){
					$formErrors[] = "Name Can't Be <strong>Empty</strong>";
				}

				if(empty($desc)){
					$formErrors[] = "Description Can't Be <strong>Empty</strong>";
				}

				if(empty($price)){
					$formErrors[] = "Price Can't Be <strong>Empty</strong>";
				}

				if(empty($country)){
					$formErrors[] = "Country Can't Be <strong>Empty</strong>";
				}

				if($status == 0){
					$formErrors[] = ">You Must Choose <strong>Status</strong>";
				}
				
				if($member == 0){
					$formErrors[] = ">You Must Choose <strong>Member</strong>";
				}
				
				if($cat == 0){
					$formErrors[] = ">You Must Choose <strong>Category</strong>";
				}

				
				//Loop Into Error Array And Echo Errors
				foreach($formErrors as $error){
					echo "<div class='alert alert-danger'>" .  $error ."</div>";
				}
                
				//Check If There Is No Errors Proceed The Update Operation	
			    if(empty($formErrors)){

							// Update The Database With The New Info 
					$stmt = $con->prepare('UPDATE
                                                 items
                                           SET
                                                 Name = ?,
                                                 Description = ?,
                                                 Price = ?,
                                                 Country_Made = ?,
                                                 Status = ?,
                                                 Cat_ID = ?,
                                                 Member_ID = ?,
                                                 tags   = ?
                                           WHERE
                                                 Item_ID = ?'
                                         );
                    
					$stmt->execute(array($name, $desc, $price, $country, $status, $cat, $member, $tags, $id));

					//Echo Sucess Message
					$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Record Updated</div>";
 					
 					redirectHome($theMsg, 'back', 4);
			   }

				
			} else {

				$errorMsg = "<div class='alert alert-danger'>You Can't Browse This Page Directly</div>";
				redirectHome($errorMsg);
			}

			echo "</div>";

	 	
	 } elseif ($do == 'Delete') {
         
                echo '<h2 class="text-center">Delete Item</h2>';
            echo "<div class= 'container'>";


                  $itemid = (isset($_GET['itemid']) &&  is_numeric($_GET['itemid'])) ?  intval($_GET['itemid']) :  0;

                //Select All Data From db Depend On This ID  
                $check = checkItem('Item_ID', 'items', $itemid);
                if($check > 0) { 
                    $stmt = $con->prepare('DELETE FROM items WHERE Item_ID = :zid');
                    $stmt->bindParam(':zid', $itemid);
                    $stmt->execute();

                    $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Record Deleted</div>";
                    redirectHome($theMsg, 'back');
                }else{
                    $theMsg = "<div class='alert alert-danger'>not Exist</div>";
                    redirectHome($theMsg);
                }

        echo "</div>";
	 	
	 } elseif ($do == 'Approve') {
         
          echo '<h2 class="text-center">Approve Members</h2>';
            echo "<div class= 'container'>";


                  $itemid = (isset($_GET['itemid']) &&  is_numeric($_GET['itemid'])) ?  intval($_GET['itemid']) :  0;

                //Select All Data From db Depend On This ID  
                $check = checkItem('Item_ID', 'items', $itemid);
                if($check > 0) { 
                    $stmt = $con->prepare('UPDATE items SET Approve = 1 WHERE Item_ID = ?');
                    $stmt->execute(array($itemid));

                    $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Record Approved</div>";
                    redirectHome($theMsg, 'back');
                }else{
                    $theMsg = "<div class='alert alert-danger'>Sorry,This ID Is not Exist</div>";
                    redirectHome($theMsg);
                }
         
     }

	 include $tpl . 'footer.php';

  
} else {
	header('Location: index.php');
	exit;
}	 

ob_end_flush();

?>