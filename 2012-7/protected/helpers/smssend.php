<?php
/**
 *  @sms下发
 *  @zwy
 */

/*********************
 * <smsmt>
		<MsgSeqID>产生该下行消息的上行消息内部流水号</MsgSeqID>
		<CpAccount>CP帐号</ CpAccount >
		< Password >CP密码</Password>
		<SrcTermID>源号码</SrcTermID>
		<DestTermID>目的号码</DestTermID>
		<CharSet>编码类型</CharSet>
		<MsgFormat>编码类型消息格式</MsgFormat>
		< FeeUserType >计费用户类型</ FeeUserType >
		< FeeTermID >计费号码</ FeeTermID >
		< DestTermType >目的号码类型</ DestTermType >
		< FeeTermType >计费号码类型</ FeeTermType >
		<ProductID>产品代码</ProductID>
		<LinkID>点播用的LinkID</LinkID>
		<ContentType>消息类型，0表示文本，1表示wap push</ContentType>
		<Content>消息内容</Content>
		<Title> Wap Pusth的标题。</Title>
		<URL> Wap Pusth中包含的URL。</URL>
</smsmt>
 * @author Administrator
 */
class SmsSend{
	var $MsgSeqID;
	var $CpAccount;
	var $CpPassWord;
	var $SrcTermID;
	var $DestTermID;
	var $CharSet;
	var $MsgFormat;
	var $FeeUserType;
	var $FeeTermID;
	var $DestTermType;
	var $FeeTermType;
	var $ProductID;
	var $LinkID;
	var $ContentType;
	var $Content;
	var $Title;
	var $SendURL; //网关
	var $URL;
	var $XMLData;
	
	function __construct() {
		$this->CpAccount = 'sbc';
		$this->CpPassWord = '098765';
		$this->SrcTermID = '10658599';
		$this->DestTermID = '';
		$this->CharSet = 'UTF-8';
		$this->MsgFormat = '15';
		$this->FeeUserType = 0;
		$this->FeeTermID = '';
		$this->DestTermType = '0';
		$this->FeeTermType = '0';
		$this->ProductID = 'sbc-sms-free';
		$this->LinkID = '';
		$this->ContentType = '0';
		$this->Content = '';
		$this->title = '';
		$this->URL = '';
		$this->SetMsgSeqID = $this->random_number(3);
		$this->SendURL = '192.168.20.90:3334/sr/smsmt';
		$this->XMLData = '';	
	}	
	/*
	 * 网关
	 */
	function SetSendURL($sendurl){
		$this->SendURL = $sendurl;
	}
	function SetMsgSeqID($msgseqid){
		$this->SetMsgSeqID = $msgseqid;
	}
	function SetCpAccount($cpaccount){
		$this->CpAccount = $cpaccount;
	}
	function SetPassword($password){
		$this->CpPassWord = $password;
	}
	function SetSrcTermID($srctermid){
		$this->SrcTermID = $srctermid;
	}
	function SetDestTermID($desttermid){
		$this->DestTermID = $desttermid;
	}
	function SetCharSet($charset){
		$this->CharSet = $charset;
	}
	function SetMsgFormat($msgformat){
		$this->MsgFormat = $msgformat;
	}
	function SetFeeUserType($feeusertype){
		$this->FeeUserType = $feeUsertype;
	}
	function SetFeeTermID($feetermid){
		$this->FeeTermID = $feetermid;
	}
	function SetDestTermType($desttermtype){
		$this->DestTermType = $desttermtype;
	}
	function SetProductID($productid){
		$this->ProductID = $productid;
	}
	function SetSetLinkID($linkid){
		$this->LinkID = $linkid;
	}
	function SetContentType($contenttype){
		$this->ContentType = $contenttype;
	}
	function SetContent($content){
		$this->Content = $content;
	}
	function SetTitle($title){
		$this->Title = $title;
	}
	function SetURL($url){
		$this->URL = $url;
	}
	function SetXMLFile($xmlfile){
		$this->XMLFile = $xmlfile;
	}
	
	function SetXMLValue(){
		$xml = iconv('gbk','utf-8',"<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<smsmt>
		<MsgSeqID>".$this->SetMsgSeqID."</MsgSeqID>
		<CpAccount>".$this->CpAccount."</CpAccount>
		<Password>".$this->CpPassWord."</Password>
		<SrcTermID>".$this->SrcTermID."</SrcTermID>
		<DestTermID>".$this->DestTermID."</DestTermID>
		<CharSet>".$this->CharSet."</CharSet>
		<MsgFormat>".$this->MsgFormat."</MsgFormat>
		<FeeUserType>".$this->FeeUserType."</FeeUserType>
		<FeeTermID>".$this->FeeTermID."</FeeTermID>
		<DestTermType>".$this->DestTermType."</DestTermType>
		<FeeTermType>".$this->FeeTermType."</FeeTermType>
		<ProductID>".$this->ProductID."</ProductID>
		<LinkID>".$this->LinkID."</LinkID>
		<ContentType>".$this->ContentType."</ContentType>
		<Content><![CDATA[".$this->Content."]]></Content>
		<Title></Title>
		<URL></URL></smsmt>");
		$this->XMLData = $xml;
	}	
	
	/**
	 * 发送短彩
	 */
	function SendSMS(){
		$ch = curl_init ();		
		$header[] = "Content-type: text/xml";//定义content-type为xml
		curl_setopt($ch, CURLOPT_URL, $this->SendURL);	
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 3);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $this->XMLData);
		$response = curl_exec($ch);		

		if(curl_errno($ch))
		{
		    $errno = curl_errno($ch);
			return $errno;
			curl_close( $ch );
		}
		curl_close($ch);
		return $response;

	}
		/**
		 * 获取指定位数的随机数
		 * 
		 */
		function random_number($domnum=9){
		    $str = '';
		    for($i = 0; $i < $domnum; $i++)
		    {
		        $str .= mt_rand(0, 9);
		    }		
		    return time() . $str;
		}		
	
}

