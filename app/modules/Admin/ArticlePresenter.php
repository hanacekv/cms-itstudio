<?php

namespace App\Module\Admin\Presenters;

use Nette;
use App\Forms\ArticleFormFactory;
use App\Model\ArticleManager;
use App\Model\ImageStorage;


class ArticlePresenter extends SecuredPresenter
{
        /** @var ArticleFormFactory @inject */
	public $factory;
        
        /** @var ArticleManager @inject */
	public $articleManager;
             
        public function renderDefault()
        {
            $this->template->articles = $this->articleManager->getAllArticles();
        }
        
        public function renderEdit($id) {
            $article = $this->articleManager->getArticle($id);
            $this['editArticleForm']->setDefaults($article);
        }
        
        public function handleDelete($id){
            
            if($this->getUser()->isAllowed($this->name, 'delete_article')){
                try{
                    $this->articleManager->delete($id);
                    $this->flashMessage('Článek byl úspěšně vymazán', 'success');
                } catch(\Exception $e){
                    $this->flashMessage('Článek se nepodařilo vymazat. Opakujte akci prosím později', 'warning');
                } 
                
            } else {
                $this->flashMessage('Na tuto operaci nemáte oprávnění', 'error');
            }            
            $this->redirect('this');
        }
        
        public function handleSetPublic($id){
            $result = $this->articleManager->updateVisibility($id, ArticleManager::VISIBILITY_PUBLIC);
            if($result){
                $this->flashMessage('Článek je nyní přístupný všem návštevníkům webu.', 'success');
            } else{
                $this->flashMessage('Nepodařilo se změnit viditelnost článku', 'error');
            }
            $this->redirect('this');
            
        }
        
        public function handleSetPrivate($id){
            $result = $this->articleManager->updateVisibility($id, ArticleManager::VISIBILITY_PRIVATE);
            if($result){
                $this->flashMessage('Článek nyní můžou videt jen přihlášení uživatelé.', 'success');
            } else{
                $this->flashMessage('Nepodařilo se změnit viditelnost článku', 'error');
            }
            $this->redirect('this');
        }

        /**
         * 
         * @return Nette\Application\UI\Form
         */
        protected function createComponentAddArticleForm()
	{
		$form = $this->factory->create();                
		$form->onSuccess[] = function ($form) {
			$form->getPresenter()->redirect('Article:');
		};
		return $form;
	}
        
        protected function createComponentEditArticleForm()
	{
		$form = $this->factory->create(ArticleFormFactory::TYPE_EDIT);
                $form->onSuccess[] = function ($form) {
			$form->getPresenter()->redirect('Article:');
		};
		return $form;
	}
}
