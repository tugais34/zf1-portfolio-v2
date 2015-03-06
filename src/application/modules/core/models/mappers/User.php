<?php

class Core_Model_Mapper_User
{

    private $userTable;

    /**
     * Récupération de tout les éléments répondant à where
     * 
     * @param string $where            
     * @param string $order            
     * @param string $count            
     * @param string $offset            
     * @return boolean|array
     */
    public function fetchAll($where = null, $order = null, $count = null, $offset = null)
    {
        $result = $this->getUserTable()->fetchAll($where, $order, $count, $offset);
        if (0 === $result->count()) {
            return false;
        }
        $users = array();
        foreach ($result as $row) {
            $users[] = $this->rowToObject($row);
        }
        return $users;
    }

    /**
     * Récupération d'un élement avec ID
     * @param integer $userId
     * @return boolean
     */
    public function find($userId)
    {
        $result = $this->getUserTable()->find($userId);
        if (0 === $result->count()) {
            return false;
        }
        return $this->rowToObject($result[0]);
    }
    
    /**
     * Insertion d'un Core_Model_User en BDD
     * @param Core_Model_User $user
     * @return mixed
     */
    public function insert(Core_Model_User $user)
    {
    	  $user->setCreated();
    	  
        $data = $this->objectToRow($user);
        return $this->getUserTable()->insert($data);
    }

    /**
     * Mise à jour d'un Core_Model_User en BDD
     * @param Core_Model_User $user
     */
    public function update(Core_Model_User $user)
    {
        $data = $this->objectToRow($user);
        $where = array(
            Core_Model_DbTable_User::COL_ID . ' = ?' => $user->getId()
        );
        return $this->getUserTable()->update($data, $where);
    }

    /**
     * Retourne l'objet DbTable de User
     * @return Core_Model_DbTable_User
     */
    private function getUserTable()
    {
        if (null === $this->userTable) {
            $this->userTable = new Core_Model_DbTable_User();
        }
        return $this->userTable;
    }
    
    /**
     * Converti un tableau de donnée en Core_Model_User
     * @param array $data
     * @return Core_Model_User
     */
    private function rowToObject($data)
    {
        $user = new Core_Model_User();
        $user->setId($data[Core_Model_DbTable_User::COL_ID])
		         ->setLinkedinId($data[Core_Model_DbTable_User::COL_LINKEDIN_ID])
		         ->setViadeoId($data[Core_Model_DbTable_User::COL_VIADEO_ID])
             ->setLogin($data[Core_Model_DbTable_User::COL_LOGIN])
             ->setEmail($data[Core_Model_DbTable_User::COL_EMAIL])
             ->setPassword($data[Core_Model_DbTable_User::COL_PASSWORD])
        		 ->setCreated($data[Core_Model_DbTable_User::COL_CREATED]);
        return $user;
    }

    /**
     * Converti Core_Model_User en un tableau de donnée
     * @param Core_Model_User $user
     * @return array
     */
    private function objectToRow(Core_Model_User $user)
    {
        return array(
            Core_Model_DbTable_User::COL_ID => $user->getId(),
        		Core_Model_DbTable_User::COL_LINKEDIN_ID => $user->getLinkedinId(),
        		Core_Model_DbTable_User::COL_VIADEO_ID => $user->getViadeoId(),
            Core_Model_DbTable_User::COL_LOGIN => $user->getLogin(),
        		Core_Model_DbTable_User::COL_EMAIL => $user->getEmail(),
            Core_Model_DbTable_User::COL_PASSWORD => $user->getPassword(),
        		Core_Model_DbTable_User::COL_CREATED => $user->getCreated()->toString(Zend_Date::DATETIME)
        );
    }
}