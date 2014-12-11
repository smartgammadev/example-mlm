<?php
namespace Success\MemberBundle\Traits;
use Success\PlaceholderBundle\Service\PlaceholderManager;
/**
 * Description of SetMemberManagerTrait
 *
 * @author develop1
 */
trait SetPlaceholderManagerTrait {
    
    /**
     *
     * @var \Success\PlaceholderBundle\Service\PlaceholderManager
     */
    private $placeholderManager;
    
    
    public function setPlaceholderManager(PlaceholderManager $placeholderManager)
    {
        $this->placeholderManager = $placeholderManager;
    }        
}
