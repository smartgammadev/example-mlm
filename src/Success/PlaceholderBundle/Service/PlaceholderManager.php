<?php

namespace Success\PlaceholderBundle\Service;

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

    use \Gamma\Framework\Traits\DI\SetRequestTrait;

    public function assignPlaceholdersToSession(array $placeholders)
    {
        //print_r($this->request);
        $session = $this->request->getSession();
        $session->set('placeholders', $placeholders);
    }

    public function getPlaceholdersFromSession()
    {
        $session = $this->request->getSession();
        return $session->get('placeholders');
    }

    /**
     * @return array[][placeholder Entity][value sting]
     */
    public function getPlaceholdersValuesFormSession()
    {
        /* @var $session \Symfony\Component\HttpFoundation\Session\Session */
        $session = $this->request->getSession();
        $placeholders = $session->get('placeholders');

        $result = [];
        if ($placeholders) {
            foreach ($placeholders as $pattern => $value) {
                $result[] = array('placeholder' => $this->resolveExternalPlaceholder($pattern), 'value' => $value);
            }
        }

        return $result;
    }

    public function getPlaceholdersValuesForExternalLink()
    {
        $session = $this->request->getSession();
        $placeholders = $session->get('placeholders');
        foreach ($placeholders as $pattern => $value) {
            $ph = $this->resolveExternalPlaceholder($pattern);
            if ($ph->getPassToExternalLink()) {
                $result[] = array('placeholder' => $ph, 'value' => $value);
            }
        }
        return $result;
    }

    public function getPlaceholdersValuesByTypePattern($typePattern)
    {
        $session = $this->request->getSession();
        $placeholders = $session->get('placeholders');

        foreach ($placeholders as $pattern => $value) {
            $ph = $this->resolveExternalPlaceholder($pattern);
            if ($ph->getPlaceholderType()->getPattern() == $typePattern) {
                $result[] = array('placeholder' => $ph, 'value' => $value);
            }
        }
        return $result;
    }

    /**
     * 
     * @param string $fullName string like sponsor_first_name
     * @return Success\PlaceholderBundle\Entity\ExternalPlaceholder
     */
    public function resolveExternalPlaceholder($fullName)
    {
        $persisted = false;
        $placeholderTypePattern = $this->getTypePattern($fullName);
        $placeholderPattern = $this->getPattern($fullName);

        $placeholderRepo = $this->em->getRepository('SuccessPlaceholderBundle:ExternalPlaceholder');
        $placeholderTypeRepo = $this->em->getRepository('SuccessPlaceholderBundle:PlaceholderType');

        $placeholderType = $placeholderTypeRepo->findOneBy(
                array('pattern' => $placeholderTypePattern));

        if (!$placeholderType) {
            $placeholderType = new PlaceholderType();
            $placeholderType->setName($placeholderTypePattern);
            $placeholderType->setPattern($placeholderTypePattern);
            $this->em->persist($placeholderType);
            $persisted = true;
        }
        $placeholder = $placeholderRepo->findOneBy(
                array('pattern' => $placeholderPattern, 'placeholderType' => $placeholderType->getId()));
        if (!$placeholder) {
            $placeholder = new ExternalPlaceholder();
            $placeholder->setPattern($placeholderPattern);
            $placeholder->setName($placeholderPattern);
            $placeholder->setAllowUserToEdit(false);
            $placeholder->setPassToExternalLink(false);
            $placeholder->setPlaceholderType($placeholderType);
            $this->em->persist($placeholder);
            $persisted = true;
        }

        if ($persisted) {
            $this->em->flush();
        }
        return $placeholder;
    }

    /**
     * @return Array
     */
    public function getPlaceholderTypes()
    {
        $repo = $this->em->getRepository('SuccessPlaceholderBundle:PlaceholderType');
        return $repo->findAll();
    }

    /**
     * 
     * @param string $fullname - example sponsor_mail
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
