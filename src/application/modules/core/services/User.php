<?php

class Core_Service_User
{

    private $userMapper;

    /**
     * Lazy Loading du mapper User
     */
    private function getUserMapper()
    {
        if (null === $this->userMapper) {
            $this->userMapper = new Core_Model_Mapper_User();
        }
        return $this->userMapper;
    }

    public function save(Model_User $user)
    {
        if (null === $user->getId()) {
            $this->getUserMapper()->insert($user);
        } else {
            $this->getUserMapper()->update($user);
        }
    }

    public function find($id)
    {
        $cache = Zend_Controller_Front::getInstance()->getParam('bootstrap')
            ->getResource('cachemanager')
            ->getCache('data');
        
        if (! $user = $cache->load('user' . $id)) {
            $user = $this->getUserMapper()->find($id);
            $cache->save($user, 'user' . $id);
        }
        return $user;
    }

    public function getUserList($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->getUserMapper()->fetchAll($where, $order, $count, $offset);
    }

    public function getUser($userId)
    {
        return $this->find($userId);
    }
}