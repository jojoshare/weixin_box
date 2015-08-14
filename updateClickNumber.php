<?php
	include '../common/mysql_init.php';
	$friendID = $_POST['friendID'];
	//echo 'updateDrawID====='.$friendID;
	if($friendID != 0){
		//echo $drawID.'+++++++';
		$result = mysql_query(sprintf($getClickNumber,$friendID));
		//echo sprintf($getClickNumber,$drawID).'-------';
		if($result != null && mysql_num_rows($result) > 0){
			$row = mysql_fetch_array($result);
			$clickNumber = $row[0] + 1;
			//echo 'clickNumber'.$clickNumber;
			$result = mysql_query(sprintf($updateClickNumber,$clickNumber,$friendID));
		}
	}
?>