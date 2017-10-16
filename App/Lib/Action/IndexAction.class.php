<?php
// 本类由系统自动生成，仅供测试用途
class IndexAction extends Action {
    public function index(){
        
        //获得参数signature nonce token timestamp echostr
        $nonce     =    $_GET['nonce'];
        $token     =    'weixin';
        $timestamp =    $_GET['timestamp'];
        $echostr   =    $_GET['echostr'];
        $signature =    $_GET['signature'];
        //形成数组，然后按字典序排序
        $array=array();
        $array= array($nonce,$token,$timestamp);
        sort($array);
        //拼接成字符串，然后与signature进行校验
        $str=sha1(implode($array));
        if ($str==$signature&&$echostr) {
            echo $echostr;
            exit;
        }else{
            $this->reponseMsg();
        }
    }
        public function reponseMsg(){
        //获取到微信推送过来的post数据(xml格式)
        $postArr = $GLOBALS['HTTP_RAW_POST_DATA'];
        $tmpStr  =$postArr;
        //处理消息类型，并设置回复类型和内容
        $postObj = simplexml_load_string($postArr);
        $keyword = trim($postObj->Content);//获取用户输入信息
        //判断该数据包是否是订阅的事件推送
        if (strtolower($postObj->MsgType)=='event') {
            //如果是关注subscribe事件
            if (strtolower($postObj->Event)=='subscribe') {
                $arr =array(
                    array(
                          'title' => '金众电子欢迎您', 
                          'description' => "点我进入金众电子官网",
                          'picUrl' => 'http://pic.90sjimg.com/back_pic/u/00/01/53/12/55ecfb7a16ade.jpg',
                          'url' => 'http://www.dgjinzhong.com',
                                ),
                         );
                $indexModel=new IndexModel;
                $indexModel->responseMsg($postObj,$arr);
            }
        }

    //截取关键字
    $weather_key = mb_substr($keyword,-2,2,"UTF-8");//截取最后两个字符(天气关键字)
    $city_key = mb_substr($keyword,0,-2,"UTF-8");//截取除最后两个字符以外的字符(城市名关键字)
    $translate_key = mb_substr($keyword,0,2,"UTF-8");//截取前面两个字符(翻译关键字)
    $word_key = mb_substr($keyword,2,200,"UTF-8");//截取除前面两个字符以外的字符，最多只能识别两百个字符(需要翻译的字符)
    $gzGh = mb_substr($keyword,0,-7,"UTF-8");//截取除最后七个字符以外的字符(工号)
    $p = mb_substr($keyword,-7,1,"UTF-8");//截取除关键符号"#"
    $gzPwd1 = mb_substr($keyword,-6,6,"UTF-8");//截取密码

    if($weather_key == '天气' &&!empty($city_key) && $translate_key != '翻译' ){
            $ch = curl_init();
            $url = "http://apis.baidu.com/netpopo/weather/query?city=".$city_key."&cityid=111&citycode=101260301";
            $header = array(
                'apikey: 44a4a78df33c4b66882c61a19a51b47e',
            );
            // 添加apikey到header
            curl_setopt($ch, CURLOPT_HTTPHEADER  , $header);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            // 执行HTTP请求
            curl_setopt($ch , CURLOPT_URL , $url);
            $res = curl_exec($ch);
            
            $arr=json_decode($res,true);
            $content='城市:'.$arr['result']['city'].
                     "\n风力:".$arr['result']['winddirect'].$arr['result']['windpower'].
                     "\n天气情况:".$arr['result']['weather'].
                     "\n温度:".$arr['result']['temp'].
                     "\n最高温度:".$arr['result']['temphigh'].
                     "\n最低温度:".$arr['result']['templow'].
                     "\n更新时间:".$arr['result']['updatetime'];
            $indexModel=new IndexModel;
            $indexModel->responseText($postObj,$content);
    // $content = _weather($city_key);
        }elseif($translate_key == '翻译'  ){
        if (!empty($word_key)) {
        $ch = curl_init();
        $url = 'http://apis.baidu.com/netpopo/translate/translate?type=youdao&from=auto&to=auto&text='.$word_key;
        $header = array(
            'apikey: 44a4a78df33c4b66882c61a19a51b47e',
        );
        // 添加apikey到header
        curl_setopt($ch, CURLOPT_HTTPHEADER  , $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // 执行HTTP请求
        curl_setopt($ch , CURLOPT_URL , $url);
        $res = curl_exec($ch);

            
        $arr=json_decode($res,true);
        $content=$arr['result']['result'];
        $indexModel=new IndexModel;
        $indexModel->responseText($postObj,$content);
    }else{
            $content="翻译内容不能为空";
            $indexModel=new IndexModel;
            $indexModel->responseText($postObj,$content);
    }
    }
    elseif($p=='#'){
            $mysql = new SaeMysql();
            $sql = "SELECT * FROM gzt WHERE user_Id='$gzGh' AND gzPwd='$gzPwd1'";
            $row=$mysql->getData($sql);
            if($mysql->errno()!=0){
                die("Error:".$mysql->errmsg());
            }elseif (!empty($row)) {
          $content= "工号:".$row['0']['user_Id'].
                "\n姓名:".$row['0']['user_Name'].
                "\n基本工时:".$row['0']['jbgs'].
                "\n基本工资:".$row['0']['jbgz'].
                "\n技能津贴:".$row['0']['jnjt'].
                "\n工龄工资:".$row['0']['glgz'].
                "\n平时加班:".$row['0']['psjb'].
                "\n周末加班:".$row['0']['zmjb'].
                "\n法定加班:".$row['0']['fdjb'].
                "\n话费补助:".$row['0']['hfbz'].
                "\n交通补助:".$row['0']['jtbt'].
                "\n房补:".$row['0']['fb'].
                "\n夜津贴:".$row['0']['yjt'].
                "\n增补:".$row['0']['zb'].
                "\n宿舍长补贴:".$row['0']['sszbt'].
                "\n奖励:".$row['0']['jl'].
                "\n缺勤款:".$row['0']['qqk'].
                "\n生日补贴:".$row['0']['srbt'].
                "\n节日福利:".$row['0']['jrfl'].
                "\n应发合计:".$row['0']['yfhj'].
                "\n扣水电:".$row['0']['ksd'].
                "\n迟到早退扣款:".$row['0']['cdzt'].
                "\n扣罚:".$row['0']['kf'].
                "\n旷工扣款:".$row['0']['kgkk'].
                "\n扣旅游款:".$row['0']['klyk'].
                "\n扣社保:".$row['0']['ksb'].
                "\n扣公积金:".$row['0']['kgjj'].
                "\n扣房租:".$row['0']['kfz'].
                "\n扣税合计:".$row['0']['kshj'].
                "\n实发工资:".$row['0']['sfhj'];
                // $mysql.closeDb();
            }else{
                $content="你输入的工号或者密码有误";
            }
        $indexModel=new IndexModel;
        $indexModel->responseText($postObj,$content);
    }else{
    $content = "感谢关注东莞市金众电子股份有限公司，目前公众号有以下功能：\n
    1、工资查询：工号#密码，如JZ0001#123456;\n
    2、天气查询：XX天气，如输入“东莞天气”;\n
    3、翻译功能：翻译XX，如翻译你好;\n";
    $indexModel=new IndexModel;
    $indexModel->responseText($postObj,$content);
    }



}//reponseMsg end


public function getWxAccessToken(){
	//将access_token存储在session/cookie中
    if ($_SESSION['access_token']&&$_SESSION['expire_time']>time()) {
        //如果access_token没有过期
        return $_SESSION['access_token'];
    }else{
        //如果access_token不存在或者已经过期
        $appId='wx403c541b4f596eda';
        $appSecret='69c79d5688b034196e39e8d4b0fb0b27';
        $url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appId."&secret=".$appSecret;
        $this->http_curl($url,'get','json');
        $access_token=$res['access_token'];

        $_SESSION['access_token']=$access_token;
        $_SESSION['expire_time']=time()+7100;
        return $access_token;
    }
        // //1.请求地址
        // $appId='wx403c541b4f596eda';
        // $appSecret='69c79d5688b034196e39e8d4b0fb0b27';
        // $url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appId."&secret=".$appSecret;
        // //2.初始化
        // $ch=curl_init();
        // //3.设置参数
        // curl_setopt($ch, CURLOPT_URL, $url);
        // curl_setopt($url, CURLOPT_RETURNTRANSFER, 1);
        // // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
        // // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);  // 从证书中检查SSL加密算法是否存在
        // //3.抓取、采集
        // $res=curl_exec($ch);
        
        // //4.关闭
        // curl_close($ch);
        // if (curl_errno($ch)) {
        //     var_dump(curl_error($ch));
        // }
        // $arr=json_decode($res,true);
        // var_dump($arr);
    }

    /*
    $url   接口url
    $type  请求类型
    $res   返回参数类型
    $arr   post请求参数
    */
    function http_curl($url,$type='get',$res='json',$arr=''){
        //1.初始化
        $ch=curl_init();
        //2.设置参数
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($url, CURLOPT_RETURNTRANSFER, 1);
        if ($type=='post') {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $arr);
        }
        //3.抓取、采集
        $res=curl_exec($ch);
        
        //4.关闭
        curl_close($ch);
        if ($res=='json') {
            return json_decode($output,true);
            }

    }


    public function defineItem(){
        //自定义菜单curl
        $access_token=$this->getWxAccessToken();
        $url="https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$access_token;
        $postArr =array(
            'button'=>array(
                    array('name'=>'商业合作',
                          'type'=>'click',
                          'key'=>'WE',
                        ),
                    array(
                            'name'=>'加入我们',
                         	'sub_button'=>array(
                            //二级菜单
                                array(
                                		'name'=>'岗位需求',
                                		'type'=>'click',
                                		'key'=>'item1',
                                	),
                                array(
                                		'name'=>'培训通知',
                                		'type'=>'click',
                                		'key'=>'item2',
                                	),
                                array(
                                		'name'=>'电脑小技巧',
                                		'type'=>'click',
                                		'key'=>'item3',
                                	),
/*                                array(
                                		'name'=>'岗位需求',
                                		'type'=>'click',
                                		'key'=>'item1',
                                	),
                                array(
                                		'name'=>'岗位需求',
                                		'type'=>'click',
                                		'key'=>'item1',
                                	),*/
                            ),
                        ),
                    array(
                            'name'=>'精彩金众',
                          'sub_button'=>array(
                            //二级菜单
                                array(
                                		'name'=>'活动留影',
                                		'type'=>'click',
                                		'key'=>'team1',
                                	),
                                array(),
                                array(),
                                array(),
                                array(),
                            ),
                        )，
                ),
            );
        $postJson=json_encode($postArr);
        $this->http_curl($url,'post','json',$postJson);
    }//getWxAccessToken end

}//class end