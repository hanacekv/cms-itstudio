<?php

namespace App\Module\Admin\Presenters;

use Nette;

use App\Forms\AddUserFormFactory;
use App\Model\UserManager;


class UserPresenter extends SecuredPresenter
{
        /** @var AddUserFormFactory @inject */
	public $factory;
        
        /** @var UserManager @inject */
	public $userManager;
        
        public function renderDefault()
        {
            $this->template->users = $this->userManager->getRegisteredUsers();
        }
        
        public function actionEdit($id){
            $this->flashMessage('Uživatel byl upraven');
            $this->redirect('default');
            
        }
        
        public function actionDelete($id){
            
            if($this->getUser()->isInRole('admin')){
                try{
                    $this->userManager->delete($id);
                    $this->flashMessage('Uživatel byl odstraněn', 'success');
                } catch(\Exception $e){
                    $this->flashMessage('Nepodařilo se odstranit uživatele. Opakujte akci prosím později', 'warning');
                } 
                
            } else {
                $this->flashMessage('Na tuto operaci nemáte oprávnění', 'error');
            }            
            $this->redirect('default');
        }

        



        /**
         * 
         * @return Nette\Application\UI\Form
         */
        protected function createComponentAddUserForm()
	{
		$form = $this->factory->create();
		$form->onSuccess[] = function ($form) {
			$form->getPresenter()->redirect('User:');
		};
		return $form;
	}
}
