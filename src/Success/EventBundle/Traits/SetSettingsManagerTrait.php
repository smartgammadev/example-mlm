<?php
namespace Success\EventBundle\Traits;
use Success\SettingsBundle\Service\SettingsManager;
/**
 * Description of SetMemberManagerTrait
 *
 * @author develop1
 */
trait SetSettingsManagerTrait {
    
    /**
     *
     * @var \Success\SettingsBundle\Service\SettingsManager
     */
    private $settingsManager;
    
    
    public function setSettingsManager(SettingsManager $placeholderManager)
    {
        $this->settingsManager = $placeholderManager;
    }        
}
