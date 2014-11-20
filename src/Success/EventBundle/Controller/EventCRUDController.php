<?php

namespace Success\EventBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController as Controller;

class EventCRUDController extends Controller {
    
    public function cancelRepeatsAction()
    {
        $id = $this->get('request')->get($this->admin->getIdParameter());

        $object = $this->admin->getObject($id);

        var_dump($object);

        $this->addFlash('sonata_flash_success', 'Repeats for events was canceled');

        return new RedirectResponse($this->admin->generateUrl('edit'));
    }    
}
