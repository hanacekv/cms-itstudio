<?php

namespace App\Module\Front\Presenters;

use Nette;
use App\Model\ArticleManager;


class ArticlePresenter extends Nette\Application\UI\Presenter
{
    /** @var ArticleManager @inject */
    public $articleManager;
    
    public function renderDefault()
    {
        $user = $this->presenter->getUser();
        if($user->isInRole('guest')){
            $articles = $this->articleManager->getPublicArticles();
        } else {
            $articles = $this->articleManager->getAllArticles();
        }
        $this->template->articles = $articles;
    }
    
    public function renderDetail($id){
        $article = $this->articleManager->getArticle($id);
        if(empty($article)){
            throw new \Nette\Application\BadRequestException;
        }
        $user = $this->presenter->getUser();
        $resource = $this->name;
        
        $privilege = $article->visibility .'_'. $this->action;
        
        if(!$user->isAllowed($resource, $privilege)){
            $this->flashMessage('Pro zobrazení tohoto článku je nutné se přihlásit', 'error');
            $this->redirect('Sign:in', ['backlink' => $this->storeRequest()]);                    
        }
        $this->template->article = $article;
    }
}
