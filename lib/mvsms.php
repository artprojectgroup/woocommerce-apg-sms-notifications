<?php
/**
 * Class used for sending SMS through mVayoo API
 * Created on 14-Aug-09
 * ===================================================
 * <code>
 * 	$obj  = new MvSMS();
 *  // for setting new CampaignID for the first time working
 *  //$obj->setCampaignID();
 *  $resp = $obj->sendSMS('9895466475','Any Message will displayed');
 *  if($resp){
 *		echo 'success';
 *  }
 *  else {
 * 		echo 'failed : '.$obj->errorMsg;
 * }
 * </code>
 * ===================================================
 * @category includes
 * @package includes_sms_MvSMS
 * @by Saturn http://www.saturn.in
 */

class MvSMS{

	public $campID 		= '';

	private $mVayooAuthCode;
	private $campUrl 	= 'http://api.mVaayoo.com/mvaayooapi/CreateCampaign?user=';
	private $composeUrl 	= 'http://api.mvaayoo.com/mvaayooapi/MessageCompose?user=';
	private $errorMsg	= '';
	private $logFile		= false;
	private $senderName	= 'TEST SMS';

	/**
	 * Constructor to initialize common settings
	 *
	 * @param string $authCode
	 * 		The mVayoo User field(default:false -> will take saturn's account)
	 */
	public function __construct($mvLogin,$mvPasswd,$mvCampaign='',$mvSender = '') {
		$this->mVayooAuthCode = $mvLogin.':'.$mvPasswd;
		if(!empty($mvCampaign))
		    $this->campID         = $mvCampaign;
		else{
			$this->setCampaignID();
		}    
		if(!empty($mvSender))    
            $this->senderName     = $mvSender; 
	}

	/**
	 * Function to set the campaign ID
	 *
	 */
	private function setCampaignID() {
		$url 			= $this->campUrl.$this->mVayooAuthCode;
		$response		= $this->requestCURL($url);
		$this->campID 	= $response;
		$this->writeToLog($response,$url);
	}

	/**
	 * Function to send the SMS through mVayoo API
	 *
	 * @param string $receipientno
	 * 		The mobile no of the receipient
	 * @param string $message
	 * 		The message to send
	 * @return mixed Transaction no on success else false
	 */
	public function sendSMS($receipientno, $message) {
		$url = $this->getComposeUrl(trim($receipientno),trim($message));
		if($url){
			$transID = $this->requestCURL($url);
			$this->writeToLog($transID,$url);

			if(!empty($transID)){
				return $transID;
			}
		}
		return false;
	}

	/**
	 * Function to build the request for mVayoo
	 * @param string $receipientno
	 * 		The mobile no of the receipient
	 * @param string $message
	 * 		The message to send
	 * @return mixed The compose url on success else false
	 */
	private function getComposeUrl($receipientno, $message) {

		if(empty($receipientno)
		 || is_int($receipientno)
		 /*|| substr($receipientno,0,2)!='91'*/){
			$this->errorMsg = 'Invalid Receipient Phonenumber';
			return false;
		}
		else if(empty($this->campID)){
			$this->errorMsg = 'Invalid Campaign ID ';
			return false;
		}
		else{
			$url   =  $this->composeUrl.$this->mVayooAuthCode;

			$url  .= '&senderID='.urlencode($this->senderName);
			$url  .= "&receipientno=$receipientno";
			$url  .= '&dcs=0';
			$url  .= '&msgtxt='.urlencode($message);
			$url  .= '&state=4';
			//$url  .= '&cid='.$this->campID;
			return $url;
		}
	}

	/**
	 * Function to send the curl request
	 *
	 * @param string $url The url to send
	 * @return mixed
	 * 		1.Transaction ID on success
	 * 		2.false on curl failure or sms failure
	 */
	private function requestCURL($url){
		$response 	= false;
		$ch   		= curl_init();

		if($ch){
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HEADER, 0);

			$response 		= curl_exec($ch);
			list($id,$msg) 	= sscanf($response, 'Status=%d, %s');

			if($id){
				$this->errorMsg = trim(strstr($response,','),',');
				return false;
			}
			else{
				return $msg;
			}
		}
		return $response;
	}

	/**
	 * Function used to write the log entry
	 *
	 * @param string $transID The transaction id
	 * @param string $url The url used for request
	 */
	private function writeToLog($transID,$url){
	    if(!$this->logFile){
	        return;
	    }
		$content = '['.date('Y-m-d H:i:s').'] [<'.$transID.'>'.$url.']'.PHP_EOL;
		//if(is_writable($this->logFile)){
			@file_put_contents($this->logFile,$content,FILE_APPEND);
		//}
	}
}

/*
$obj  = new MvSMS();
$resp = $obj->sendSMS('9895466475','Any Message will displayed');

if($resp){
	echo 'success';
}
else{
	echo 'failed : '.$obj->errorMsg;
}
*/
