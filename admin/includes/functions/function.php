<?php


/*
** Get  All  Function v2.0	
** Function To Get all From Table From Database Table
*/

 function getAllFrom($field, $table, $where = NULL, $and = NULL, $orderfield, $ordering = "DESC") {
 	global $con;
 	
 	$getAll = $con->prepare("SELECT $field FROM $table $where $and ORDER BY $orderfield $ordering");
 	$getAll->execute();
 	$all = $getAll->fetchAll();
 	return $all;	
 } 














/*
** Title Function v1.0
** Title Function That Echo The Page Title In Case The Page Has The Variable $pageTitle
**	And Echo Default Title For Other Pages
*/

function getTitle() {
	global $pageTitle;   // to be accessable from anywhere
	if(isset($pageTitle)){
		echo $pageTitle;
	}else{
		echo "Default";
	}
}


/*
**  Home Redirect Function v2.0
**  [This Function Accept Parameters]
**	$theMsg = Echo The Error Message [ error | success | Warning ]
**  $url = The Link You Want To Redirect To
**  $seconds = Seconds Before Redirecting 
*/

function redirectHome($theMsg,$url = null, $seconds = 3){

	if($url === null){
		    $url = 'index.php';
		    $link = 'Homepage';
	}else {
			if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== ''){
				$url = $_SERVER['HTTP_REFERER'];
				$link = 'Previous Page';
			} else {
				$url = 'index.php';
				$link = 'Homepage';
			}
	}
	echo $theMsg;
	echo "<div class='alert alert-info'>You Will Be Redirected To $link After $seconds Seconds</div>";
	header("refresh:$seconds;url=$url");
	exit;
}



/*
** Check Items Function v1.0
** Function To Check Item In DataBase [ Function Accept Parameters]
**  Or Uses For Count Number Items Rows
** $select = The Item To Select (The Field) [examples: User, Item, Category]
** $from = The Table To Select From [example: users, items, categories]
** $value = The Value Of Select [example: Osama, box, Electronics]
** If $select Is Exist Return 1 else return 0   
*/

function checkItem($select, $from, $value){

	global $con;

	$statement = $con->prepare("SELECT $select FROM $from WHERE $select = ?");
	$statement->execute(array($value));
	$count = $statement->rowCount();

	return $count;

}




/*
** Uses For Find (The Number Of Rows In $table and Check By $field)
** Count Number	Items Rows
** $field =  The Name Of The Field That Uses To Count By
** $table = The Table To Choose items rows From 
*/

function countItems($field, $table) {

	global $con;

	$stmt2 = $con->prepare("SELECT COUNT($field) FROM $table");
	$stmt2->execute();
	return $stmt2->fetchColumn();
}


/*
** Uses For Fetch any Number Of Elements From Table and The Data 
** returned ordered by $order descending . 
** Get Latest Records Function v1.0	
** Function To Get Latest Items From Database Table [users, items, comments, ....]
** $select = The Field To Select
** $table = The Table To Choose From
** $limit = No. Of Elements You Need To Choose From Table
** $order = The Field To Use To Order By like UserID
** Return =>> An Array Including ordered data From Table
*/

 function getLatest($select, $table, $order, $limit = 5) {
 	global $con;

 	$getStmt = $con->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");
 	$getStmt->execute();
 	$rows = $getStmt->fetchAll();
 	return $rows;	
 }  

