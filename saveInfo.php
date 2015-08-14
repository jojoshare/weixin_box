<?php
	include '../common/mysql_init.php';
	date_default_timezone_set(PRC);
	$drawOpenid = isset($_POST['drawOpenid']) ? $_POST['drawOpenid'] : null;
	$drawNickname = isset($_POST['drawNickname']) ? $_POST['drawNickname'] : null;
	$drawLogo = isset($_POST['drawLogo']) ? $_POST['drawLogo'] : null;
	$prizeID = isset($_POST['prizeID']) ? $_POST['prizeID'] : 0;
	$drawDate = date('Y-m-d H:i:s');
	$sqlStr = "";
	$drawID = 0;
	
	if($drawOpenid != null && $drawNickname != null && $drawLogo != null && $prizeID != 0){
		$result = mysql_query(sprintf($getDrawID,$drawOpenid));
		if($result != null && mysql_num_rows($result) > 0){
			$row = mysql_fetch_array($result);
			$sqlStr = sprintf($updateDrawInfo,$drawOpenid,$drawNickname,$drawLogo,$drawDate,$row[0]);			
		}else{
			$sqlStr = sprintf($insertDrawInfo,$drawOpenid,$drawNickname,$drawLogo,$drawDate,$prizeID);			
		}
		$result = mysql_query($sqlStr);
	}
	
	if(mysql_affected_rows() != 0){
		$result = mysql_query(sprintf($getDrawID,$drawOpenid));
		if($row = mysql_fetch_array($result)){
			$drawID = $row[0];
		}
	    echo json_encode(array(
			'success' => true,
	    	'drawID' => $drawID
		));
	}else{
	    echo json_encode(array(
			'success' => false
		));
    }
?>