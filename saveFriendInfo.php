<?php
	include '../common/mysql_init.php';
	date_default_timezone_set(PRC);
	$drawOpenid = isset($_POST['drawOpenid']) ? $_POST['drawOpenid'] : null;
	$drawNickname = isset($_POST['drawNickname']) ? $_POST['drawNickname'] : null;
	$drawLogo = isset($_POST['drawLogo']) ? $_POST['drawLogo'] : null;
	$drawID = $_POST['drawID'];
	$drawDate = date('Y-m-d H:i:s');
	//echo $drawID;
	$sqlStr = "";
	if($drawOpenid != null && $drawNickname != null && $drawLogo != null && $drawID != 0){
		$result = mysql_query(sprintf($getFriendID,$drawOpenid,$drawID));
		//echo sprintf($getFriendID,$drawOpenid,$drawID);
		if($result != null && mysql_num_rows($result) > 0){
			$row = mysql_fetch_array($result);
			$sqlStr = sprintf($updateFriendInfo,$drawOpenid,$drawNickname,$drawLogo,$drawDate,$drawID,$row[0]);
		}else{
			$sqlStr = sprintf($insertFriendInfo,$drawID,$drawOpenid,$drawNickname,$drawLogo,$drawDate);
		}
		//echo $sqlStr;
		$result = mysql_query($sqlStr);
	}
?>