<?php

namespace App\Module\Admin\Presenters;

use Nette;

use App\Forms\AddUserFormFactory;
use App\Model\UserManager;


class SecuredPresenter extends Nette\Application\UI\Presenter
{
       
    public function startup() {
        parent::startup();
        
        $resource = $this->name;
        $action = $this->action;
        
        if (!$this->user->isAllowed($resource, $action)) {
            $this->flashMessage('Pro tuto operaci nemáte dostatečné oprávnění přihlaste se prosím!', 'error');
            $this->redirect(':Front:Sign:in', ['backlink' => $this->storeRequest()]);
        }
    }
}