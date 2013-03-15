<?php
/**
 * boss接口统一平台
 * Enter description here...
 * @author king
 */
class getBossNowHelper{
    private $msisdn;
    private $datatrans;
    private $cmdids;
    public $bossData=array();
    public $bossString;

    public function __construct($mobile,$cmdids){
        
        if(!is_numeric($mobile) || !preg_match("/^[0-9]{11}$/",$mobile)) return false;
        $this->msisdn = $mobile;
        $this->datatrans = $mobile.'~6000~1';
        $this->cmdids = $cmdids;
        /*$cmdids = array(
            "用户品牌"=>50032,
            "月结日"=>40018,
            //"当前余额"=>20028,
            "短信套餐定制情况"=>20096,
            "用户业务查询"=>20054,
            "手机归属地判断"=>50002,
            "动感地带定制信息查询"=>40021,
            "M值" =>52207,
            "GPRS套餐" =>54019,
            "套餐查询" =>60119,
        );*/
        $this->getBossData($cmdids);
        $this->checkBossData();
    }

    /**
     * 获得boss数据
     * Enter description here...
     *
     */
    public function getBossData($cmdids){
        foreach ($cmdids as $value){
            $returnData = $this->boss_interface($this->msisdn,$value,$this->datatrans);
            $this->bossString = $returnData->Datatrans;
            $returnData->Datatrans = iconv('UTF-8','GBK',$returnData->Datatrans);
            $datatrans = explode('|',$returnData->Datatrans);
            if($datatrans) {
                foreach ($datatrans as $k => $v) {
                    $datatrans[$k] = explode('~',$v);
                }
            }
            $this->bossData[$value] = $datatrans;
        }
    }
    
    public function getBossString(){
    	return $this->bossString;
    }
   
    /**
     * 处理boss数据
     * Enter description here...
     *
     */
    public function checkBossData(){
        if($this->bossData[20054][1]){
            foreach ($this->bossData[20054] as $k=>$v) {//用户业务查询
                if(!in_array($k,array(0,1,2))){
                    $data[] =  $v[1];
                }
            }
            $this->bossData[20054]['data']=$data;
        }
    }
    
    private function boss_interface($msisdn,$cmdid,$datatrans){
//        $msisdn = '13560471642';
//        $cmdid = '50002';
//        $datatrans = '13560471642~6000~1';
        $XPost = "xmldata=<?xml version=\"1.0\" encoding=\"utf-8\" ?>\n<InterBOSS>\n";
        $XPost .= "<OrigDomain>DGGW</OrigDomain>\n";//String    F4    发起方应用域代码    例如：CJYX:超级邮箱发起
        $XPost .= "<HomeiDomain>BOSS</HomeiDomain>\n";//String    F4    归属方应用域代码    BOSS
        $XPost .= "<BIPCode>GDCS0001</BIPCode>\n";//String    F8    业务编码    参见具体业务
        $XPost .= "<BIPVer>0100</BIPVer>\n";//String    F4    业务流程版本号    0100;对于同一交易应答与请求版本号始终一致
        $XPost .= "<ActivityCode>T0000001</ActivityCode>\n";//String    F8    交易编码    参见具体业务
        $XPost .= "<ActionCode>0</ActionCode>\n";//String    F1    交易动作代码    0：请求，1：应答
        $XPost .= "<Routing>\n";//路由信息
        $XPost .= "<RouteType>01</RouteType>\n";//String    F2    路由类型    01
        $XPost .= "<RouteValue>13560471642</RouteValue>\n";//String    V20    路由关键值    手机号码
        $XPost .= "</Routing>\n";//
        $XPost .= "<ProcID>1</ProcID>\n";//String    V30    业务流水号    发起方填写的包含此交易业务的流水号，重发交易流水号不变
        $XPost .= "<TransIDO>1</TransIDO>\n";//String    V30    发起方交易流水号    在发起方唯一标识一个交易的流水号，系统内唯一
        $XPost .= "<TransIDH></TransIDH>\n";//String    V30    归属方交易流水号    请求中不填，由落地方在应答中填，系统内唯一
        $XPost .= "<ProcessTime>".date('Ymdhis',time())."</ProcessTime>\n";//String    F14    处理时间    发起方发起请求的时间/应答方处理请求的时间 YYYYMMDDHHMMSS
        $XPost .= "<Response>\n";//应答/错误信息    请求中不填，应答中填
        $XPost .= "<RspType></RspType>\n";//String    F1    应答/错误类型    0
        $XPost .= "<RspCode></RspCode>\n";//String    F4    应答/错误代码    成功应答：0000 操作结果通过该节点值体现。
        $XPost .= "<RspDesc></RspDesc>\n";//String    V128    应答/错误描述    应答或错误描述
        $XPost .= "</Response>\n";//
        $XPost .= "<SPReserve>\n";//BOSS保留信息    BOSS填写
        $XPost .= "<TransIDC></TransIDC>\n";//String    V60    BOSS交易流水号    由BOSS填写
        $XPost .= "<CutOffDay>".date('Ymmdd',time())."</CutOffDay>\n";//String    F8    日切点    格式：yyyymmdd，清分对帐用
        $XPost .= "<OSNDUNS></OSNDUNS>\n";//String    F4    发起方节点代码    BOSS填写 BOSS:2001
        $XPost .= "<HSNDUNS></HSNDUNS>\n";//String    F4    归属方节点代码    BOSS填写 例如，超级邮箱管理平台：CJYX
        $XPost .= "<ConvID></ConvID>\n";//String    V60    BOSS处理标识    只有BOSS使用
        $XPost .= "</SPReserve>\n";//
        $XPost .= "<TestFlag>1</TestFlag>\n";//String    F1    测试标记    发起方填写，0：非测试交易，1：测试交易；需要注意的是测试必须是业务级别，即在同一个业务流水中所有交易必须具有相同的测试标记
        $XPost .= "<MsgSender>DGGW</MsgSender>\n";//String    F4    消息发送方代码    例如，BOSS:2001 超级邮箱管理平台：CJYX
        $XPost .= "<MsgReceiver>2001</MsgReceiver>\n";//String    F4    消息直接接收方代码    该消息送往的下一方代码：例如，BOSS:2001超级邮箱管理平台：CJYX
        $XPost .= "<SvcContVer>0100</SvcContVer>\n";//String    F4    业务内容报文的版本号    0100，对于同一交易应答与请求版本号始终一致
        $XPost .= "<SvcCont><![CDATA[\n";//String        请求/应答内容    XML格式的字符串，以CDATA区表达
        $XPost .= "<CSReq>\n";
        $XPost .= "<MSISDN>$msisdn</MSISDN>\n";//String    F11    手机号码
        $XPost .= "<CMDID>$cmdid</CMDID>\n";//String    F5    客服接口命令字：例如：20052(客户号码信息)10007 (服务功能修改)
        $XPost .= "<Datatrans>$datatrans</Datatrans>\n";//String    V256    发送内容每个命令字不尽相同，请参见附录客服协议datatrans。
        $XPost .= "</CSReq>\n";
        $XPost .= "]]></SvcCont>\n";//String        请求/应答内容    XML格式的字符串，以CDATA区表达
        $XPost .= "</InterBOSS>\n";
        $contentLength = strlen($XPost);
        $fp = @fsockopen("10.243.178.148", 9080,&$errno,&$errstr,5);
        if(!$fp){
            //10.243.178.148：9080
            return  "不能连接到 server服务器";
        }
        fputs($fp, "POST /gdboss/receiver HTTP/1.0\r\n");
        fputs($fp, "Host: 10.243.178.148\r\n");
        fputs($fp, "Content-Type: application/x-www-form-urlencoded\r\n");
        fputs($fp, "Content-Length: $contentLength\r\n");
        fputs($fp, "Connection: close\r\n");
        fputs($fp, "\r\n"); 
        fputs($fp, $XPost);
        $result = '';
        while (!feof($fp)) {
            $result .= fgets($fp, 128);
        }
        preg_match("/<\?xml version=\"1.0\" encoding=\"UTF-8\"\?>(.+?)<\/InterBOSS>/is",$result,$result1);
        $xml_heard = $result1[0];
        $domHeard = new domDocument;
        @$domHeard->loadXML($xml_heard);
        $domHeard = simplexml_import_dom($domHeard);
        /**
         * @todo 写头信息的相关记录
         */
        preg_match("/<!\[CDATA\[(.+?)\]\]>/is",$result1[0],$xml_body);
        $xml_body = $xml_body[1];
        $domBody = new domDocument;
        @$domBody->loadXML($xml_body);
        $domBody = simplexml_import_dom($domBody);
        return $domBody;
    }
}
