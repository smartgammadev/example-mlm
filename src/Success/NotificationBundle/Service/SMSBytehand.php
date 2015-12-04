<?php

namespace Success\NotificationBundle\Service;

use Buzz\Browser;

class SMSBytehand
{

    const SERVICE_URL = "http://bytehand.com:3800/";
    const ID = "15993";
    const KEY = "B0BE02F4608FED36";
    const MSG_FROM = "4Success";

    private $browser;

    public function __construct()
    {
        $this->browser = new Browser();
        $this->serviceInit();
    }

    public function serviceInit()
    {
        
    }

    /**
     * 
     * @param type $toNumber
     * @param type $text
     * @return string msgId or 0 if not success
     */
    public function msgSend($toNumber, $text)
    {
        $msgText = urlencode($text);
        $msgToNumber = urlencode($toNumber);

        $sendUrl = $this::SERVICE_URL . "send?id=" . $this::ID . "&key=" . $this::KEY . "&to=$msgToNumber&from=" . $this::MSG_FROM . "&text=$msgText";
        $response_json = $this->browser->get($sendUrl);
        $response = json_decode($response_json);

        if ($response['status'] == 0) {
            return $response['description'];
        } else {
            return 0;
        }
    }

    public function checkMsgStatus($id)
    {
        $msgId = urlencode($id);
        $sendUrl = $this::SERVICE_URL . "status?id=" . $this::ID . "&key=" . $this::KEY . "&message=$msgId";

        $response_json = $this->browser->get($sendUrl);
        $response = json_decode($response_json);

        if ($response['status'] == 0) {
            return $response['description'];
        } else {
            return false;
        }
    }
}
