<?php

namespace Success\EventBundle\Controller;

use Aisel\ConfigBundle\Controller\SettingsController;


class ConfigSuccessController extends SettingsController
{

    public $form = "\Success\EventBundle\Form\ConfigHomepageType";

    /**
     * {@inheritdoc }
     */
    protected function getTemplateVariables()
    {
        $this->templateVariables['base_template'] = 'SonataAdminBundle::standard_layout.html.twig';
        $this->templateVariables['admin_pool'] = $this->container->get('sonata.admin.pool');
        $this->templateVariables['blocks'] = $this->container->getParameter('sonata.admin.configuration.dashboard_blocks');
        return $this->templateVariables;        
    }

}
