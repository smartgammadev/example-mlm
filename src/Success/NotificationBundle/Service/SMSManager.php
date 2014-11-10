<?php
namespace Success\NotificationBundle\Service;

class SMSManager {
    private $gateService;
    
    public function __construct($gateService) 
    {
        $gateService = "Success\NotificationBundle\Service\\".$gateService;
        $this->gateService = new $gateService();
        $this->gateService->serviceInit();
    }

    /**
     * @param string $toNumber
     * @param string $text
     */
    public function msgSend($toNumber, $text)
    {
        return $this->gateService->msgSend($toNumber, $text);
    }
    
    public function checkMsgStatus($msgId)
    {
        return $this->gateService->checkMsgStatus($msgId);
    }
}
