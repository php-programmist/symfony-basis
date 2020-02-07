<?php

namespace App\EventSubscriber;

use App\Entity\Config;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class EasyAdminCacheSubscriber implements EventSubscriberInterface
{
    /**
     * @var AdapterInterface
     */
    protected $cache;
    
    public function __construct(
        AdapterInterface $cache
    ) {
        $this->cache = $cache;
    }
    
    /**
     * Returns an array of event names this subscriber wants to listen to.
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return array(
            'easy_admin.post_update' => array('postUpdate'),
        );
    }
    
    public function postUpdate(GenericEvent $event)
    {
        $entity = $event->getSubject();
        if ($entity instanceof Config) {
            $this->cache->clear();
        }
    }
    
}