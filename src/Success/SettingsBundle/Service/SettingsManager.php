<?php
namespace Success\SettingsBundle\Service;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SettingsManager
 *
 * @author develop1
 */
class SettingsManager 
{    
    use \Gamma\Framework\Traits\DI\SetEntityManagerTrait;

    /**
     * 
     * @param type $name 
     * @return string
     */
    public function getSettingValue($name){
        $repo = $this->em->getRepository('SuccessSettingsBundle:Setting');
        $setting = $repo->findOneBy(array('name' => $name));

        if (!$setting){
            throw new \Exception('Seting with name="'.$name.'" was not found');
        }
        
        $value = $setting->getSettingValue();        
        return $value;
    }
}
