<?php

namespace Success\PricingBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController as BaseController;

class PricingAdminController extends BaseController
{
    public function listAction()
    {
        return parent::listAction();
    }

    public function editAction($id = null)
    {
        return parent::editAction($id);
    }
}
