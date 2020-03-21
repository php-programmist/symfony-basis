<?php

namespace App\Controller\Admin;

use PhpProgrammist\FileSqlLoggerBundle\FileSqlLogger;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyAccess\PropertyAccess;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/admin")
 */
class AdminController extends EasyAdminController
{
    /**
     * @var AdapterInterface
     */
    protected $cache;
    /**
     * @var FileSqlLogger
     */
    protected $sql_logger;
    
    public function __construct(AdapterInterface $cache,FileSqlLogger $sql_logger)
    {
        $this->cache = $cache;
        $this->sql_logger = $sql_logger;
    }
    
    protected function initialize(Request $request)
    {
        parent::initialize($request);
        if ($this->em) {
            $connection = $this->em->getConnection();
            $connection->getConfiguration()->setSQLLogger($this->sql_logger);
        }
    }
    
    /**
     * @Route("/cache-clear", name="admin_cache_clear")
     */
    public function cacheClearAction()
    {
        if ($this->cache->clear()) {
            return $this->json(['status'=>true,'msg'=>'Кэш очищен']);
        }else{
            return $this->json(['status'=>false,'msg'=>'Произошла ошибка']);
        }
    }
    
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