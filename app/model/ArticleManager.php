<?php

namespace App\Model;

use Nette;
use Nette\Database\Context;

class ArticleManager extends Nette\Object
{
	const
		TABLE_NAME = 'articles',
		COLUMN_ID = 'id',
                VISIBILITY = 'visibility',
                VISIBILITY_PUBLIC = 'public',
                VISIBILITY_PRIVATE = 'private';

	/** @var Nette\Database\Context */
	private $database;

	public function __construct(Context $database)
	{
		$this->database = $database;
	}
        
	public function add($values)
	{            
            return $this->database->table(self::TABLE_NAME)->insert($values);            
	}            
        
        public function edit($data){
            $id = $data->id;
            $selection = $this->database->table(self::TABLE_NAME)->wherePrimary($id);
            $selection->update($data);
            return $selection->fetch();
            
        }

        public function delete($id){
            return $this->database->table(self::TABLE_NAME)->wherePrimary($id)->delete();
        }
        
        public function updateVisibility($id, $visibility){
            return $this->database->table(self::TABLE_NAME)->wherePrimary($id)->update(['visibility' => $visibility]);       }


        public function getArticle($id){
            return $this->database->table(self::TABLE_NAME)->wherePrimary($id)->fetch();
        }

        public function getAllArticles(){
            return $this->database->table(self::TABLE_NAME)->fetchAll();
        }
        
        public function getPublicArticles(){
            return $this->database->table(self::TABLE_NAME)->where('visibility', ['public'])->fetchAll();
        }
        
        

}