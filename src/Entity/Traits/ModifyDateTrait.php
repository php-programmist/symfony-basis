<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;


trait ModifyDateTrait
{
    /**
     * @var \DateTimeImmutable
     *
     * @ORM\Column(name="modify_date", type="datetime")
     */
    private $modifyDate;
    
    public function getModifyDate(): ?\DateTimeInterface
    {
        return $this->modifyDate;
    }
    
    public function setModifyDate(?\DateTimeInterface $modifyDate): self
    {
        $this->modifyDate = $modifyDate;
        
        return $this;
    }
    
    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function changeModifyDate()
    {
        $this->modifyDate = new \DateTimeImmutable();
    }
}