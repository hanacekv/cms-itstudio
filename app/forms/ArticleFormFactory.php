<?php

namespace App\Forms;

use Nette;
use Nette\Application\UI\Form;
use App\Model\ArticleManager;
use App\Model\ImageStorage;


class ArticleFormFactory
{
    use Nette\SmartObject;

        const
                TYPE_ADD = 'add',
                TYPE_EDIT = 'edit';

        /** @var FormFactory */
	private $factory;
        
        /** @var ArticleManager */
	private $articleManager;
        
        /** @var ImageStorage */
	private $imageStorage;
	
	public function __construct(FormFactory $factory, ArticleManager $articleManager, ImageStorage $imageStorage)
	{
		$this->factory = $factory;
                $this->articleManager = $articleManager;
                $this->imageStorage = $imageStorage;
	}


	/**
	 * @return Form
	 */
	public function create($type = self::TYPE_ADD)
	{
		$form = $this->factory->create();
                
                $form->addHidden('id');
		
                $form->addText('title', 'Název článku:')
			->setRequired('Prosím zadejte název článku.');

		$form->addTextArea('text', 'Text:')
			->setRequired('Prosím napište článek.')
                        ->setAttribute('rows', 20)
                        ->setAttribute('class', 'mceEditor');
                
                $form->addUpload('image_path', 'Obrázek:')
                        ->setAttribute('accept', 'image/*');
                
                $form->addSelect('visibility', 'Viditelnost', ['public'=>'Veřejný', 'private'=>'Soukromý'])->setPrompt('Vyberte...')->setRequired('Nastavte prosím viditelnost článku.');

                if($type == self::TYPE_ADD){
                    $form->addSubmit('send', 'Přidat článek');
                    $form->onSuccess[] = array($this, 'formSucceeded');
                } elseif ($type == self::TYPE_EDIT) {
                    $form->addSubmit('send', 'Upravit článek');
                    $form->onSuccess[] = array($this, 'formSucceeded');
                }
                
                $form->getElementPrototype()->onsubmit('tinyMCE.triggerSave()');               
                
		return $this->factory->setBootstrapForm($form);
	}


	public function formSucceeded(Form $form, $values)
	{
            $file = $values->image_path;
            if($file->isOK() && !$file->isImage()){
                $form->addError('Vybraný soubor není obrázek');
            } else {                
                unset($values->image_path);
                if(empty($values->id)){
                    $article = $this->articleManager->add($values);                    
                } else {
                    $article = $this->articleManager->edit($values);
                }                             
                
                if($file->isOK()){
                    $values->id = $article->id;
                    $values->image_path = $article->id . '_' . time() . '.png';
                    $image = $file->toImage();
                    $this->imageStorage->addArticleImage($image, $values->image_path);                  
                    $this->articleManager->edit($values);
                }
            }          
	}
}
