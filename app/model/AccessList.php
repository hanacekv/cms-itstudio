<?php
namespace App\Model\Security;
use Nette\Security\Permission;

class AccessList extends Permission {
    const   GUEST = 'guest',
            REGISTERED = 'registered';

    public function __construct()
    {
        /* seznam uživatelských rolí */
        $this->addRole(self::GUEST);
        $this->addRole(self::REGISTERED);
        $this->addRole('admin', 'registered');

        /* Admin Module Main */
        $this->addResource('Admin');
        $this->addResource('Admin:Article', 'Admin');
        $this->addResource('Admin:User', 'Admin');        
        
        /* Front Module Main */
        $this->addResource('Front');
        $this->addResource('Front:Article', 'Front');
        $this->addResource('Front:Contact', 'Front');
        $this->addResource('Front:Homepage', 'Front');
        $this->addResource('Front:Sign', 'Front');        

        /* seznam pravidel oprávnění */
        $this->allow(self::GUEST, 'Front');
        $this->deny(self::GUEST, 'Front:Article', 'private_detail');
        
        $this->allow(self::REGISTERED, 'Front:Article');
        
        # admin má práva na všechno
        $this->allow('admin');       
    }
}

