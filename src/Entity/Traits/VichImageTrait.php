<?php

namespace App\Entity\Traits;

use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

trait VichImageTrait
{
    use ModifyDateTrait;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;
    
    /**
     * @Vich\UploadableField(mapping="web_root", fileNameProperty="image")
     * @var File
     */
    private $imageFile;
    
    public function getImage(): ?string
    {
        return $this->image;
    }
    
    public function setImage(?string $image): self
    {
        $this->image = $image;
        
        return $this;
    }
    
    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;
        
        if ($image) {
            $this->modifyDate = new \DateTime('now');
        }
    }
    
    public function getImageFile()
    {
        return $this->imageFile;
    }
    
    /**
     * Example:
     * return 'img/product/';
     * @return string
     */
    abstract public function getImgFolder():string;
    
    public function getImageUrl()
    {
        if ( ! $this->getImage()) {
            return '';
        }
        return '/'.$this->getImgFolder(). $this->getImage();
    }
}