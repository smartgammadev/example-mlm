<?php
namespace Success\NotificationBundle\Service;
use Buzz\Browser;

class SMSBytehand {
    
    const SERVICE_URL = "http://bytehand.com:3800/";
    const KEY = "15993";
    const ID = "B0BE02F4608FED36";
    const MSG_FROM = "4Success";
    
    //private $id;
    //private $key;
    private $browser;
    
    public function __construct()
    {
        $this->browser = new Browser();
        $this->serviceInit();
    }

    public function serviceInit()
    {                        
        //$this->id = urlencode("15993");
        //$this->key = urlencode("B0BE02F4608FED36");
    }

    
    /**
     * 
     * @param type $toNumber
     * @param type $text
     * @return boolean true if success, error description if failed
     */
    public function msgSend($toNumber,$text)
    {
        $msgText = urlencode($text);
        $msgToNumber = urlencode($toNumber);
        
        $sendUrl = $this::SERVICE_URL."send?id=$this->id&key=$this->key&to=$msgToNumber&from=".$this::MSG_FROM."&text=$msgText";
        
        echo "Sending SMS to: $toNumber\r\n";
        $response_json = $this->browser->get($sendUrl);
        $response = json_decode($response_json);
        
        if($response['status']==0){
            return true;
        } else {
            echo "Error: Status=".$response['status']." Description=".$response['description'];
            return $response['description'];
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
