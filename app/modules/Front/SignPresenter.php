<?php

namespace App\Module\Front\Presenters;

use Nette;
use App\Forms\SignFormFactory;


class SignPresenter extends Nette\Application\UI\Presenter
{
	/** @var SignFormFactory @inject */
	public $factory;
        
        /** @persistent */
        public $backlink = '';


	/**
	 * Sign-in form factory.
	 * @return Nette\Application\UI\Form
	 */
	protected function createComponentSignInForm()
	{
		$form = $this->factory->create();
		$form->onSuccess[] = function ($form) {
                        $this->restoreRequest($this->backlink);
			$form->getPresenter()->redirect('Homepage:');
		};
		return $form;
	}


	public function actionOut()
	{
		$this->getUser()->logout();
	}

}
