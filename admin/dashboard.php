<?php
	
	ob_start();

session_start();

if(isset($_SESSION['userName'])){

	$pageTitle = 'dashboard';

	include 'init.php';
	//Start Dashboard Page

	 $numUsers = 5;	
	 $latestUsers = getLatest('*', 'users', 'UserID', $numUsers, 'GroupID', 0); // Latest Users Array
    
     $numItems = 5;
     $latestItems = getLatest('*', 'items', 'Item_ID', $numItems); // Latest Items Array
     
    $numComments = 4;
    $latestComments = getLatest('*', 'comments', 'C_ID', $numComments);
         

	?>

	<div class="container home-stats text-center">
		<h1>Dashboard</h1>
		<div class="row">
			<div class="col-md-3">
				<div class="stat st-members">
                    <i class="fa fa-users"></i>
					<div class="info">
                         Total Memebers
                        <span>
                            <a href="members.php"><?php echo countItems('UserID', 'users') ?></a>
                        </span>
                    </div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="stat st-pending">
                    <i class="fa fa-user-plus"></i>
				    <div class="info">
                         Pending Memebers
                        <span><a href="members.php?do=Manage&page=Pending">
                            <?php echo checkItem('RegStatus', 'users', 0) ?>
                        </a></span>
                    </div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="stat st-items">
                    <div class="info">
                      <i class="fa fa-tag"></i>
                       <div class="info">
                             Total Items
                            <span>
                                <a href='items.php'><?php echo countItems('Item_ID', 'items') ?></a>
                            </span>
                       </div>
                    </div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="stat st-comments">
					<i class="fa fa-comments"></i>
                    <div class="info">
                        Total Comments
                        <span>
                            <a href='comments.php'><?php echo countItems('C_ID', 'comments') ?></a>
                        </span>
                    </div>
				</div>
			</div>
		</div>
	</div>

	<div class="container latest">
		<div class="row">
			<div class="col-sm-6">
				<div class="panel panel-default">
					<div class="panel-heading">
                            <i class="fa fa-users"></i> Latest <?php echo $numUsers ?> Registerd Users
                            <span class="toggle-info pull-right">
                                <i class="fa fa-plus fa-lg"></i>
                            </span>    
                    </div>
					<div class="panel-body">
					<ul class="list-unstyled latest-users">	
				     	<?php
                            if(!empty($latestUsers)){
							foreach($latestUsers as $user) {
						   	   echo '<li>';
						   	      echo $user['Username'];
						   	      echo '<a href="members.php?do=Edit&userid=' . $user['UserID'] . '">';
						   	           echo '<span class="btn btn-success pull-right">';
						   	               echo '<i class="fa fa-edit"></i> Edit';


						   	                if( $user["RegStatus"] == 0) {

	 							echo "<a href='members.php?do=Activate&userid=" . $user['UserID']  .
	 								 "' class='btn btn-info pull-right activate'><i class='fa fa-check'></i> Activate</a>";
	 								 }


						   	           echo '</span>';
						   	      echo '</a>';
						   	   echo '</li>';
							 }
                            } else {
                                echo "There's No Users To Show";
                            }
					    ?>
				    </ul>
					</div>
				</div>
			</div>
				<div class="col-sm-6">
				<div class="panel panel-default">
					<div class="panel-heading">
						<i class="fa fa-tag"></i> Latest <?php echo $numItems ?>  Items
                        <span class="toggle-info pull-right">
                            <i class="fa fa-plus fa-lg"></i>
                        </span>
					</div>
					<div class="panel-body">
								<ul class="list-unstyled latest-users">	
				     	<?php
                            if(!empty($latestItems)){
                                foreach($latestItems as $item) {
                                   echo '<li>';
                                      echo $item['Name'];
                                      echo '<a href="items.php?do=Edit&itemid=' . $item['Item_ID'] . '">';
                                           echo '<span class="btn btn-success pull-right">';
                                               echo '<i class="fa fa-edit"></i> Edit';


                                                if( $item["Approve"] == 0) {

                                    echo "<a href='items.php?do=Approve&itemid=" . $item['Item_ID']  .
                                         "' class='btn btn-info pull-right activate'><i class='fa fa-check'></i> Approve</a>";
                                         }


                                           echo '</span>';
                                      echo '</a>';
                                   echo '</li>';
                                 }
                            } else {
                                echo "There's No Items To Show";
                            }
					    ?>
				    </ul>
					</div>
				</div>
			</div>
            <!-- Start Latest Comments -->
            <div class="row">
				<div class="col-sm-6">
				<div class="panel panel-default">
					<div class="panel-heading">
						<i class="fa fa-comments-o"></i>
                        Latest <?php echo $numComments ?> Comments
                        <span class="toggle-info pull-right">
                            <i class="fa fa-plus fa-lg"></i>
                        </span>
					</div>
					<div class="panel-body">
                        <?php

                            $stmt = $con->prepare("SELECT 
                                                comments.*, users.Username 
                                          FROM 
                                                comments
                                          INNER JOIN
                                                users
                                          ON
                                                users.UserID = comments.User_id
                                          ORDER BY
                                                C_ID
                                          DESC
                                          LIMIT $numComments");
    
                    $stmt->execute();
                    $comments = $stmt->fetchAll();
                            if(!empty($comments)){
                                foreach($comments as $comment) {
                                    echo '<div class="comment-box">';
                                        echo '<span class="member-n"><a href="members.php?do=Edit&userid=' . $comment["User_id"] . '">' . $comment['Username'] . '</a></span>';
                                        echo '<p class="member-c">' . $comment['Comment'] . '</p>';
                                    echo '</div>';


                                    echo	"<a href='comments.php?do=Edit&comid=" . $comment['C_ID'] . "' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
                                        <a href='comments.php?do=Delete&comid=" . $comment['C_ID'] .
                                         "' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete</a>";

                                         if( $comment["Status"] == 0) {

                                    echo "<a href='comments.php?do=Approve&comid=" . $comment['C_ID']  .
                                         "' class='btn btn-info activate'><i class='fa fa-check'></i> Approve</a>";	

                                         }

                                    }
                            }else {
                                echo "There's No Comments To Show";
                            }

                        ?>

					</div>
				</div>
			</div>
            </div>    
            <!-- End Latest Comments -->
		</div>
	</div>

	<?php
	//Start Dashboard Page
	include $tpl . "footer.php";
	
}else{
	header('Location: index.php');
	exit;
}

ob_end_flush();

?>
