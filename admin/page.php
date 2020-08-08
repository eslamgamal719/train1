

functions

############################################################################
getTitle();   ( v1.0 )
/*
** Title Function 
** Title Function That Echo The Page Title In Case The Page Has The Variable ($pageTitle)
**	And Echo Default Title For Other Pages
*/
############################################################################


############################################################################
redirectHome($errorMsg, $seconds); ( v1.0 )
/*
** Home Redirect Function [This Function Accept Parameters]
**	$errorMsg = Echo The Error Message
**  $seconds = Seconds Before Redirecting 
*/
............................................................................
 redirectHome($theMsg,$url = null, $seconds = 3); ( v2.0 )
/*
**  Home Redirect Function v2.0
**  [This Function Accept Parameters]
**	$theMsg = Echo The Error Message [ error | success | Warning ]
**  $url = The Link You Want To Redirect To
**  $seconds = Seconds Before Redirecting 
*/
############################################################################



############################################################################
checkItem($select, $from, $value); ( v1.0 )
/*
** Uses For Make Check If $select With $value Is Exist 
** Uses also to Count How Many Selected Column Contains Specified Value
** Function To Check Item In DataBase [ Function Accept Parameters]
** $select = The Item To Select [examples: User, Item, Category]
** $from = The Table To Select From [example: users, items, categories]
** $value = The Value Of Select [example: Osama, box, Electronics]   
*/
############################################################################



############################################################################
     countItems($field, $table);  ( v1.0 ) 
/*
** Uses For Find (The Number Of Rows In $table and Check By $field)
** Count Number	Items Rows
** $field =  The Name Of The Field That Uses To Count By
** $table = The Table To Choose items rows From 
*/
############################################################################



############################################################################
		getLatest($select, $table, $order, $limit = 5);
/*
** Uses For Fetch any Number Of Elements From Table and The Data 
** returned ordered by $order descending . 
** Get Latest Records Function v1.0	
** Function To Get Latest Items From Database Table [users, items, comments,....]
** $select = The Field To Select
** $table = The Table To Choose From
** $limit = No. Of Elements You Need To Choose From Table
** $order = The Field To Use To Order By like UserID
** Return =>> An Array Including ordered data From Table
*/
############################################################################