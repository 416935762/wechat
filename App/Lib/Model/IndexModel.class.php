<?php
class IndexModel{
	//回复图文信息
	public function responseNews($postObj,$arr){
			$toUser   = $postObj->FromUserName;
			$fromUser = $postObj->ToUserName;
			$template="<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType><![CDATA[%s]]></MsgType>
						<ArticleCount>".count($arr)."</ArticleCount>    
//图文个数根据".count($arr)."数组判断
						<Articles>";
			foreach ($arr as $k=>$v) {
						
						$template.="<item>
						<Title><![CDATA[".$v['title']."]]></Title> 
						<Description><![CDATA[".$v['description']."]]></Description>
						<PicUrl><![CDATA[".$v['picUrl']."]]></PicUrl>
						<Url><![CDATA[".$v['url']."]]></Url>
						</item>";
						}
			$template.="</Articles>
						</xml>";
						echo sprintf($template,$toUser,$fromUser,time(),'news');
	}
	//回复文本消息
	public function responseText($postObj,$content){
		$template=" <xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							</xml>";
				$fromUser=$postObj->ToUserName;
				$toUser=$postObj->FromUserName;
				$time=time();
				$msgType='text';
				//$content="金众 is very good";
				echo sprintf($template,$toUser,$fromUser,$time,$msgType,$content);

	}
	//关注事件推送消息
	public function responseMsg($postObj,$arr){
		//回复用户消息
				/*$toUser=$postObj->FromUserName;
				$fromUser=$postObj->ToUserName;
				$time=time();
				$msgType='text';
				$content="欢迎关注金众电子:\n".$postObj->ToUserName.",\n微信用户:\n".$postObj->FromUserName."\n回复消息格式:\n".$tmpStr;
				$template=" <xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							</xml>";
				$info=sprintf($template,$toUser,$fromUser,$time,$msgType,$content);
				echo $info;*/
				
				$this->responseNews($postObj,$arr);
	}
// 	public function reselect($postObj,$gzGh,$gzPwd){
// 	//从环境变量中取出数据库所要的参数
//     $host=getenv('SAE_MYSQL_HOST_M');//主库域名
//     $port=getenv('SAE_MYSQL_PORT');//端口
//     $user=getenv('SAE_MYSQL_USER');//用户名
//     $pwd=getenv('SAE_MYSQL_PASS');//密码
//     /***配置数据库名称，全局名MYSQLNAME或者$dbname ***/
//     //define("MYSQLNAME", "sNAwDmdhLkhSLCmATYsf");
//      //$dbname='app_lawlietfans';
//     //定义好的参数
//     //接着调用mysql_connect()连接服务器,可以用上面赋值之后的host、port、user、pwd来替代这里的参数
//     $db =  mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS);
//         if(!$db) {
//         die("Connect Server Failed: " . mysql_error());
//                 }else{
//         mysql_select_db(app_lttest, $db);
//     }
//     // /*连接成功后立即调用mysql_select_db()选中需要连接的数据库*/
//     if(!mysql_select_db($dbname,$db)) {
//       die("Select Database Failed: " . mysql_error($db));
//     }
//     /*至此连接已完全建立，就可对当前数据库进行相应的操作了*/
//     /*！！！注意，无法再通过本次连接调用mysql_select_db来切换到其它数据库了！！！*/
//     /* 需要再连接其它数据库，请再使用mysql_connect+mysql_select_db启动另一个连接*/
     
//     /**
//     * 接下来就可以使用其它标准php mysql函数操作进行数据库操作
//     */
//     $sql="select * from jz_Gzt where 'id'=".$gzGh."and 'pwd'=".$gzPwd."limit 0,30";
//     $rs = mysql_query($sql, $db);
//     if ($rs === false) {
//     	responseText($postObj,$rs);
//     } else {
//         //echo "Select Succeed<br />";
//         $row = mysql_fetch_assoc($rs) 
//             $content= "工号".$row['user_Id'].
//             "\n姓名".$row['user_Name'].
//             "\n基本工时".$row['jbgs'].
//             "\n基本工资".$row['jbgz'].
//             "\n技能津贴".$row['jnjt'].
//             "\n工龄工资".$row['glgz'].
//             "\n平时加班".$row['psjb'].
//             "\n周末加班".$row['zmjb'].
//             "\n法定加班".$row['fdjb'].
//             "\n姓名".$row['hfbz'].
//             "\n姓名".$row['jtbt	'].
//             "\n姓名".$row['fb'].
//             "\n姓名".$row['yjt'].
//             "\n姓名".$row['zb'].
//             "\n姓名".$row['sszbt'].
//             "\n姓名".$row['jl'].
//             "\n姓名".$row['qqk'].
//             "\n姓名".$row['srbt'].
//             "\n姓名".$row['jrfl'].
//             "\n姓名".$row['yfhj'].
//             "\n姓名".$row['ksd'].
//             "\n姓名".$row['cdzt'].
//             "\n姓名".$row['kf'].
//             "\n姓名".$row['kgkk'].
//             "\n姓名".$row['klyk'].
//             "\n姓名".$row['ksb'].
//             "\n姓名".$row['kgjj'].
//             "\n姓名".$row['kfz'].
//             "\n姓名".$row['kshj'].
//             "\n姓名".$row['sfhj'];
        // responseText($postObj,$rs);

// 	}
// }
}