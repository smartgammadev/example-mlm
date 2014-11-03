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
class PlaceholderManager 
{
    
    use \Gamma\Framework\Traits\DI\SetEntityManagerTrait;
    
    public function assignPlaceholdersToSession(array $placeholders)
    {
        $session = new Session();
        $session->set('placeholders', $placeholders);
        
    }
    
    public function getPlaceholdersFromSession()
    {
        $session = new Session();
        return $session->get('placeholders');
    }
    /**
     * 
     * @return array[][placeholder Entity][value sting]
     */
    
    public function getPlaceholdersValuesFormSession()
    {   $session = new Session();    
        $placeholders = $session->get('placeholders');
        
        foreach ($placeholders as $pattern=>$value){
            $result[] = array('placeholder'=>$this->ResolveExternalPlaceholder($pattern),'value'=>$value);
        }
        return $result;
    }

    
    public function getPlaceholdersValuesByTypePattern($typePattern)
    {   $session = new Session();    
        $placeholders = $session->get('placeholders');
        
        foreach ($placeholders as $pattern=>$value){
            $ph = $this->ResolveExternalPlaceholder($pattern);
            if($ph->getPlaceholderType()->getPattern()==$typePattern){
                $result[] = array('placeholder'=>$ph,'value'=>$value);
            }
        }
        return $result;
    }    
    
    /**
     * 
     * @param type $fullName string like sponsor_first_name
     * @return Success\PlaceholderBundle\Entity\ExternalPlaceholder
     */
    public function ResolveExternalPlaceholder($fullName)
    {
        $persisted = false;        
        $placeholderTypePattern = $this->getTypePattern($fullName);
        $placeholderPattern = $this->getPattern($fullName);

        $placeholderRepo = $this->em->getRepository('SuccessPlaceholderBundle:ExternalPlaceholder');
        $placeholderTypeRepo = $this->em->getRepository('SuccessPlaceholderBundle:PlaceholderType');
        
        $placeholderType = $placeholderTypeRepo->findOneBy(
                array('pattern'=>$placeholderTypePattern));        
        
        if(!$placeholderType){
            $placeholderType = new PlaceholderType();
            $placeholderType->setName($placeholderTypePattern);
            $placeholderType->setPattern($placeholderTypePattern);
            $this->em->persist($placeholderType);
            $persisted = true;
        }        
        $placeholder = $placeholderRepo->findOneBy(
                array('pattern'=>$placeholderPattern, 'placeholderType'=>$placeholderType->getId()));        
        if(!$placeholder){
            $placeholder = new ExternalPlaceholder();            
            $placeholder->setPattern($placeholderPattern);
            $placeholder->setName($placeholderPattern);
            $placeholder->setPlaceholderType($placeholderType);            
            $this->em->persist($placeholder);
            $persisted = true;
        }

        if ($persisted){
            $this->em->flush();
        }
        return $placeholder;
    }

    
    /**
     * @return Array
     */
    public function GetPlaceholderTypes() 
    {
        $repo = $this->em->getRepository('SuccessPlaceholderBundle:PlaceholderType');
        return $repo->findAll();
    }

    /**
     * 
     * @param type $fullname string - example sponsor_mail
     * @return string - example mail
     */
    public function getPattern($fullname)
    {        
        $placeholderName = mb_stristr($fullname, '_', false);
        return substr($placeholderName, 1, strlen($placeholderName));
    }
    
    /**
     * 
     * @param type $fullname string - example sponsor_mail
     * @return string - example sponsor
     */
    public function getTypePattern($fullname)
    {
        return mb_stristr($fullname, '_', true);
    }

}