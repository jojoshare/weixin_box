<?php
	include '../common/mysql_init.php';
	
	$drawID = $_POST['drawID'];
	$items = array();
	if($drawID){
		$result = mysql_query(sprintf($getFriendInfo,$drawID));
		while($row = mysql_fetch_assoc($result)){
			array_push($items, $row);
		}
		echo json_encode($items);
	}
?>