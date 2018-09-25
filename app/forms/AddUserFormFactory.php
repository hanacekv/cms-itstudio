<?php

namespace App\Forms;

use Nette;
use Nette\Application\UI\Form;
use App\Model\UserManager;
use App\Model\DuplicateNameException;


class AddUserFormFactory
{
    use Nette\SmartObject;

    /** @var FormFactory */
	private $factory;

	/** @var UserManager */
	private $userManager;


	public function __construct(FormFactory $factory, UserManager $userManager)
	{
		$this->factory = $factory;
		$this->userManager = $userManager;
	}


	/**
	 * @return Form
	 */
	public function create()
	{
		$form = $this->factory->create();
		$form->addText('username', 'Uživatelské jméno:')
			->setRequired('Prosím vyplňte uživatelské jméno.');

		$form->addPassword('password', 'Heslo:')
			->setRequired('Prosím vyplňte heslo.');
                
                $form->addPassword('password2', 'Ověření hesla:')
			->setRequired('Prosím vyplňte heslo pro ověření.');

		$form->addSubmit('send', 'Přidat uživatele');

		$form->onSuccess[] = array($this, 'formSucceeded');
                
		return $this->factory->setBootstrapForm($form);
	}


	public function formSucceeded(Form $form, $values)
	{
		if ($values->password != $values->password2) {
			$form->addError('Hesla se neshodují. Zadejte prosím hesla znovu');
		} else {
                    try{
                        $this->userManager->add($values->username, $values->password);
                    } catch (DuplicateNameException $e){
                        $form->addError('Toto uživatelské jméno je již obsazené, zvolte prosím jiné.');
                    }
                    
                }
	}

}
