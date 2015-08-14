<!DOCTYPE html>
<html>
	<head>
		<title>拆盒子</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0;">
		<!--<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<meta name="format-detection" content="telephone=no">-->
		<script>
			
		</script>
	</head>

<script>
	
	 
</script>
	
	<body style="text-align: center;">
		<h1>点击礼盒开奖</h1>
		<div>
			<span>一等奖：价值一个肾的iPhone6 plus一部</span><br>
			<span>二等奖：充满情怀的Smartisan T1一部</span><br>
			<span>三等奖：无所不能的红米NOTE一部</span>
		</div>
		<img id="box" src="image/close.jpg" width="100%" onclick="ifOpen();">
		<div>
			<p>===帮您点击的小伙伴===</p>
			<ul id="friendList">
				
			</ul>
		</div>
		<script type="text/javascript" src="js/WeixinApi.js"></script>
		<script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
		<script type="text/javascript" src="js/jquery.cookie.js"></script>
		<script type="text/javascript">
			/*用户自己的ID*/
			//alert('start!!!');			
			var drawID = $.cookie('drawID');
			//alert('drawID--------'+drawID);
			/*用户得到的奖盒ID*/
			var prizeID = $.cookie('prizeID');
			if(!prizeID){
				prizeID = getRandom(3);
				$.cookie('prizeID',prizeID);
			}
			//alert('prizeID--------'+prizeID);
			/*用户好友的ID*/
			var friendID = $.cookie('friendID');
			//alert('friendID--------'+friendID);

			/*获取get参数*/
			if(getArgs().drawID > 0){
				friendID = getArgs().drawID;
				$.cookie('friendID',friendID);
				//alert('cookie_friendID======'+friendID);
			}
			//$.cookie('draw_openid',null);
			//$.cookie('draw_nickname',null);
			//$.cookie('draw_logo',null);
			
			/*从cookie中取值*/
			var drawOpenid = $.cookie('draw_openid');
			var drawNickname = $.cookie('draw_nickname');
			var drawLogo = $.cookie('draw_logo');

			/*如果没有值，去授权*/
			if(!drawOpenid || !drawNickname || !drawLogo){
				location.href = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxd3da8189b5ec1ba7&redirect_uri=http://www.datart.cn/weixinservice/company/box/oauth2.php&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";
			}else{
				saveInfo();
				appendFriendList();
				//alert(friendID);
				//saveFriendInfo();
				if(friendID >0 && friendID != drawID){
					//alert('savefriendinfo');
					saveFriendInfo();
					updateClickNumber();
				}
			}
			
			/*保存用户信息*/
			function saveInfo(){
				//alert("用户信息保存成功");
				$.post("saveInfo.php",{drawOpenid:drawOpenid,drawNickname:drawNickname,drawLogo:drawLogo,prizeID:prizeID},function(result){ 
				   var result = eval('('+result+')');
				   if(result.success){
					   if(result.drawID){
				   			$.cookie('drawID',result.drawID);
				   			drawID = $.cookie('drawID');
					   }
		            	//alert('信息保存成功');
		            }else{ 
		                //alert('数据保存异常');
		            } 
		       }); 
			}

			/*保存好友信息*/
			function saveFriendInfo(){
				//alert("好友信息保存成功");
				//alert('save-----'+friendID);
				$.post("saveFriendInfo.php",{drawOpenid:drawOpenid,drawNickname:drawNickname,drawLogo:drawLogo,drawID:friendID},function(result){
					//alert('好友信息已保存');
					//alert(result);
				});
			}

			/*更新点击次数*/
			function updateClickNumber(){
				//alert("点击次数已更新");
				//alert('updateID===='+friendID);
				$.post("updateClickNumber.php",{friendID:friendID},function(result){
					//alert('点击次数已更新');
				});
			}
			
			/*验证点击人数是否达到开奖标准*/
			function ifOpen(){
				//alert("恭喜你");
				$.post("prizeCheck.php",{drawID:drawID},function(result){ 
				   //alert(result);
				   var result = eval('('+result+')');
				   if(result.success){
						$("#box").attr('src','image/open.jpg');
		            	alert('恭喜你获得：' + result.prizeName);
		            }else{ 
		                alert('赶紧分享，邀请朋友来帮忙');
		            } 
		       }); 
			}

			/*动态写入好友点击列表*/
			function appendFriendList(){
				$.post('getFriendInfo.php',{drawID:drawID},function(data){
					//alert(data);
					var data = eval('('+data+')');
					$(data).each(function(index){
						var nickname = data[index].friend_nickname;
						var logo = data[index].friend_logo;
						var text = '<li style="list-style-type:none;">'+'<img src='+logo+' width=30px>'+'<span>'+nickname+'</span>'+'</li><br>'
						$("#friendList").append(text);
					});
				});
			}
			
			/*获取get参数*/
			function getArgs() {
			    var args = {};
			    var query = location.search.substring(1);
			    // Get query string
			    var pairs = query.split("&");
		        // Break at ampersand
			    for(var i = 0; i < pairs.length; i++) {
		            var pos = pairs[i].indexOf('=');
		             // Look for "name=value"
		            if (pos == -1) continue;
		                    // If not found, skip
		                var argname = pairs[i].substring(0,pos);// Extract the name
		                var value = pairs[i].substring(pos+1);// Extract the value
		                //value = decodeURIComponent(value);// Decode it, if needed
		                args[argname] = value;
		                // Store as a property
		        }
			    return args;// Return the object
			}

			//随机生成一个盒子ID
			function getRandom(n){
		    	return Math.floor(Math.random()*n+1)
		    }
			
			/*微信Api*/
			WeixinApi.ready(function(Api) {
				// 微信分享的数据
				var wxData = {
					"appId": "", // 服务号可以填写appId
					"imgUrl" : 'http://www.datart.cn/weixinservice/company/box/image/close.jpg',
					"link" : 'http://www.datart.cn/weixinservice/company/box/index.php?drawID=' + drawID,
					"desc" : '拆盒有奖',
					"title" : "拆盒有奖"
				};

				// 分享的回调
				var wxCallbacks = {
					// 分享操作开始之前
					ready : function() {
						//alert("准备分享");
						//alert('drawID====' +　drawID);
					},
					// 分享被用户自动取消
					cancel : function(resp) {
						//alert("分享被取消");
					},
					// 分享失败了
					fail : function(resp) {
						//alert("分享失败");
					},
					// 分享成功
					confirm : function(resp) {
						//alert("分享成功");
					},
					// 整个分享过程结束
					all : function(resp) {
						//alert("分享结束");
					}
				};

				// 用户点开右上角popup菜单后，点击分享给好友，会执行下面这个代码
				Api.shareToFriend(wxData, wxCallbacks);

				// 点击分享到朋友圈，会执行下面这个代码
				Api.shareToTimeline(wxData, wxCallbacks);

				// 点击分享到腾讯微博，会执行下面这个代码
				Api.shareToWeibo(wxData, wxCallbacks);
			});

		</script>
	</body>
</html>
