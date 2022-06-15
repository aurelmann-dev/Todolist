<?php

namespace App\Entity;

use App\Entity\Listing;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\TaskRepository;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $title;

    #[ORM\Column(type: 'boolean')]
    private $state;

    #[ORM\ManyToOne(targetEntity: Listing::class, inversedBy: 'tasks')]
    #[ORM\JoinColumn(nullable: false)]
    private $List;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function isState(): ?bool
    {
        return $this->state;
    }

    public function setState(bool $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getList(): ?Listing
    {
        return $this->List;
    }

    public function setList(?Listing $List): self
    {
        $this->List = $List;

        return $this;
    }
   
}
