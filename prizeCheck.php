<?php
	include '../common/mysql_init.php';
	
	$drawID = (isset($_POST['drawID'])) ? $_POST['drawID'] : 0;
	//echo $drawID;
	$openNumber = 3;
	$clickNumber = 0;
	$result = mysql_query(sprintf($getClickNumber,$drawID));
	//echo sprintf($getClickNumber,$drawID);
	if($row = mysql_fetch_array($result)){
		$clickNumber = $row[0];
		//echo 'clickNumber==='.$clickNumber;
	}
	if($clickNumber >= $openNumber){
		$result = mysql_query(sprintf($getPrizeInfo,$drawID));
		if($row = mysql_fetch_array($result)){
			$prizeName = $row[0];
			$prizeDescribe = $row[1];
			echo json_encode(array(
				'success' => true,
				'prizeName' => $prizeName,
				'prizeDescribe' => $prizeDescribe
			));
		}else{
			echo json_encode(array(
				'success' => true,
				'prizeName' => '抱歉抽奖活动还没开始',
				'prizeDescribe' => '抽奖活动还没开始'
			));
		}
	}else{
		echo json_encode(array(
			'success' => false
		));
	}
?>