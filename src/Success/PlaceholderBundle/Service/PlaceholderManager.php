<?php
namespace Success\PlaceholderBundle\Service;
use Symfony\Component\HttpFoundation\Session\Session;
use Success\PlaceholderBundle\Entity\ExternalPlaceholder;
use Success\PlaceholderBundle\Entity\PlaceholderType;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PlaceHolderManager
 *
 * @author develop1
 */
class PlaceholderManager {
    
    use \Gamma\Framework\Traits\DI\SetEntityManagerTrait;
    
    public function assignPlaceholdersToSession(array $placeholders)
    {
        $session = new Session();
        $session->set('placeholders', $placeholders);
        
        foreach ($placeholders as $name=>$value){
            $this->ResolveExternalPlaceholder($name);
        }
    }
    
    /**
     * 
     * @param type $fullName string like sponsor_first_name
     * @return Success\PlaceholderBundle\Entity\ExternalPlaceholder
     */
    public function ResolveExternalPlaceholder($fullName)
    {
        $dataChanged = false;
        
        $placeholderTypeName = mb_stristr($fullName, '_', true);
        //$placeholderTypeName = substr($placeholderTypeName, 0, strlen($placeholderTypeName)-1);
        $placeholderName = mb_stristr($fullName, '_', false);
        $placeholderName = substr($placeholderName, 1, strlen($placeholderName));
        
        $placeholderRepo = $this->em->getRepository('SuccessPlaceholderBundle:ExternalPlaceholder');
        $placeholderTypeRepo = $this->em->getRepository('SuccessPlaceholderBundle:PlaceholderType');
        
        $placeholderType = $placeholderTypeRepo->findOneBy(
                array('name'=>$placeholderTypeName));        
        
        if(!$placeholderType){
            $placeholderType = new PlaceholderType();
            $placeholderType->setName($placeholderTypeName);
            $this->em->persist($placeholderType);
            $dataChanged = true;
        }
        
        $placeholder = $placeholderRepo->findOneBy(
                array('name'=>$placeholderName, 'placeholderType'=>$placeholderType->getId()));
        
        if(!$placeholder){
            $placeholder = new ExternalPlaceholder();
            
            $placeholder->setPattern($placeholderName);
            $placeholder->setName($placeholderName);
            $placeholder->setPlaceholderType($placeholderType);
            
            $this->em->persist($placeholder);
            $dataChanged = true;
        }

        if ($dataChanged){
            $this->em->flush();
        }
        return $placeholder;
        
    }
}
