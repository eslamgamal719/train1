<?php

 session_start();

 $pageTitle = 'Homepage';


 include "init.php";	

if(isset($_SESSION['user'])) {

	$getUser = $con->prepare('SELECT * FROM users WHERE Username = ?');

	$getUser->execute(array($sessionUser));

	$info = $getUser->fetch();

	$userid = $info['UserID'];

	

?>


<h1 class="text-center">My Profile</h1>
 
	<div class="information block">
		<div class="container">
			<div class="panel panel-primary">
				<div class="panel-heading">My Information</div>
				<div class="panel panel-body">
					<ul class="list-unstyled">
						<li>
							<i class="fa fa-unlock-alt fa-fw"></i>
							<span>Login Name</span> : <?php echo $info['Username'] ?>
						</li>
						<li>
							<i class="fa fa-envelope-o fa-fw"></i>
							<span>Email</span> : <?php echo $info['Email'] ?>
						</li>
						<li>
							<i class="fa fa-user fa-fw"></i>
							<span>Full Name</span> : <?php echo $info['FullName'] ?>
						</li>
						<li>
							<i class="fa fa-calender fa-fw"></i>
							<span>Register Date</span> : <?php echo $info['Date'] ?>
						</li>
						<li>
							<i class="fa fa-tags fa-fw"></i>
							<span>Favourite Category</span> :
						</li>
					</ul>
					<a href="#" class="btn btn-default">Edit Information</a>
				</div>
			</div>	
		</div>
	</div>	

	<div id="my-ad" class="m-ads block">
		<div class="container">
			<div class="panel panel-primary">
				<div class="panel-heading">My Items</div>
				<div class="panel panel-body">
				 	
				 	<?php 
				 		$myitems = getAllFrom("*", "items", "where Member_ID = $userid", "", "Item_ID");
				 		if(!empty($myitems)){
				 			echo '<div class="row">';
					 		foreach( $myitems as $item) {
						 		echo "<div class='col-sm-6 col-md-3'>";
						 			echo "<div class='thumbnail item-box'>";
						 			if($item['Approve'] == 0) {
						 				 echo "<span class='approve-status'>Not Approved</span>";
						 			  }
						 				echo "<span class='price-tag'>$" . $item['Price'] . "</span>";
						 				echo "<img class='img-responsive' src='img.png' alt=''>";
						 				echo "<div class='caption'>";
						 						echo "<h3><a href='items.php?itemid=" . $item['Item_ID'] .  "'>" . $item['Name'] . "</a></h3>";
						 						echo "<p>" . $item['Description'] . "</p>";
						 						echo "<div class='date'>" . $item['Add_Date'] . "</div>";
						 					echo "</div>";
						 				echo "</div>";
						 			echo "</div>";
						 	}

					 		echo '</div>';	
					 		

				    	} else {
				    		echo "Sorry, There Isn't Any Ads To Show, Create <a href='newad.php'>New Ad</a>";
				    	}
				 		
				 	?>
				</div>
			</div>	
		</div>
	</div>	

	<div id="my-comments" class="my-comments block">
	<div class="container">
		<div class="panel panel-primary">
			<div class="panel-heading">Latest Comments</div>
			<div class="panel panel-body">
				<?php		
					$myComments = getAllFrom("Comment", "comments", "where User_id = $userid", "", "C_ID");		
		 			if(!empty($myComments)) {
		 				foreach($myComments as $comment) {
		 					echo "<p>" . $comment['Comment'] . "</p>";
		 				}
		 			}else {
		 				echo "There Is No Comments To Show";
		 			}
				?>	 	
			</div>
		</div>	
	</div>
</div>
 
 <?php 

} else {

	header('Location: login.php');
	exit();
}
  
 include $tpl . "footer.php";

?>

 