<?php

namespace App\Controller\Admin;
use Symfony\Component\PropertyAccess\PropertyAccess;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/admin")
 */
class AdminController extends EasyAdminController
{
    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectToReferrer()
    {
        $refererAction = $this->request->query->get('action');
        // from new|edit action, redirect to edit if possible
        if (in_array($refererAction, array('new', 'edit')) && $this->request->request->get('referer') === 'apply' && $this->isActionAllowed('edit')) {
            return $this->redirectToRoute('easyadmin', array(
                'action' => 'edit',
                'entity' => $this->entity['name'],
                'menuIndex' => $this->request->query->get('menuIndex'),
                'submenuIndex' => $this->request->query->get('submenuIndex'),
                'id' => ('new' === $refererAction)
                    ? PropertyAccess::createPropertyAccessor()->getValue($this->request->attributes->get('easyadmin')['item'], $this->entity['primary_key_field_name'])
                    : $this->request->query->get('id'),
            ));
        }
        
        return parent::redirectToReferrer();
    }
}