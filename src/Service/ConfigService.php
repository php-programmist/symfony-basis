<?php

namespace App\Service;

use App\Repository\ConfigRepository;
use Symfony\Component\Cache\Adapter\AdapterInterface;

class ConfigService
{
    /**
     * @var ConfigRepository
     */
    protected $config_repository;
    /**
     * @var AdapterInterface
     */
    protected $cache;
    
    protected $params = [];
    protected $groups = [];
    
    public function __construct(ConfigRepository $config_repository,AdapterInterface $cache)
    {
        $this->config_repository = $config_repository;
        $this->cache = $cache;
    }
    
    public function get($param_name, $default = null)
    {
        if (!isset($this->params[$param_name])) {
            $config_entity = $this->config_repository->findOneBy(['name'=>$param_name]);
            $this->params[$param_name] = $config_entity?$config_entity->getValue():$default;
        }
        return $this->params[$param_name];
    }
    
    public function getCached($param_name, $default = null)
    {
        if (!isset($this->params[$param_name])) {
            $item = $this->cache->getItem('config.' . $param_name);
            if ( ! $item->isHit()) {
                $value = $this->get($param_name, $default);
                $item->set($value);
                $this->cache->save($item);
            }
            $this->params[$param_name] = $item->get();
        }
        return $this->params[$param_name];
    }
    
    public function getGroup($group_name, $default = null):?array
    {
        if (!isset($this->groups[$group_name])) {
            $config_entities = $this->config_repository->findGroup($group_name);
            if ( ! count($config_entities)) {
                return [];
            }
            $params = [];
            foreach ($config_entities as $config_entity) {
                $param_name = str_replace($group_name.'.','',$config_entity->getName());
                $params[$param_name] = $config_entity?$config_entity->getValue():$default;
            }
            $this->groups[$group_name] = $params;
        }
        return $this->groups[$group_name];
    }
    
    public function getCachedGroup($group_name, $default = null)
    {
        if (!isset($this->groups[$group_name])) {
            $item = $this->cache->getItem('config.group.' . $group_name);
            if ( ! $item->isHit()) {
                $value = $this->getGroup($group_name, $default);
                $item->set($value);
                $this->cache->save($item);
            }
            $this->groups[$group_name] = $item->get();
        }
        return $this->groups[$group_name];
    }
}