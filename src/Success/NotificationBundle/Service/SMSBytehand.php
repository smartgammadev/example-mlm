<?php
namespace Success\NotificationBundle\Service;
use Buzz\Browser;

class SMSBytehand {
    
    const SERVICE_URL = "http://bytehand.com:3800/";
    const MSG_FROM = "SMS-INFO";
    
    private $id;
    private $key;
    private $browser;
    
    public function __construct()
    {
        $this->browser = new Browser();
        $this->serviceInit();
    }

    public function serviceInit()
    {                        
        $this->id = urlencode("15993");
        $this->key = urlencode("B0BE02F4608FED36");
    }        

    public function msgSend($toNumber,$text)
    {
        $msgText = urlencode($text);
        $msgToNumber = urlencode($toNumber);
        
        $sendUrl = $this::SERVICE_URL."send?id=$this->id&key=$this->key&to=$msgToNumber&from=".$this::MSG_FROM."&text=$msgText";
        
        echo "Sending SMS to: $toNumber\r\n";
        $response_json = $this->browser->get($sendUrl);
        $response = json_decode($response_json);
        
        if($response['status']==0){
            return $response['description'];
        } else {
            echo "Error: Status=".$response['status']." Description=".$response['description']."\r\n";
            return false;
        }
    }        
    
    public function checkMsgStatus($id)
    {        
        $msgId = urlencode($id);
        $sendUrl = $this::SERVICE_URL."status?id=$this->id&key=$this->key&message=$msgId";
        
        $response_json = $this->browser->get($sendUrl);
        $response = json_decode($response_json);
        
        if($response['status']==0){
            return $response['description'];
        } else {
            return false;
        }
    }
}
