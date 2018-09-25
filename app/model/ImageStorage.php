<?php

namespace App\Model;

use Nette;
use Nette\Utils\Image;

class ImageStorage
{
    use Nette\SmartObject;
	       
        protected $imgPath;

	public function __construct($imgPath)
	{
		$this->imgPath = $imgPath;
	}

	public function addArticleImage(Image $image, $image_name)
	{            
                $image->resize(800, NULL, Image::SHRINK_ONLY);
                $image->sharpen();
                $img = $this->imgPath . '/articles/' . $image_name;
                $image->save($img, 80, Image::PNG); // PNG, kvalita 80%            
	}
}