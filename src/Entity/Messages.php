<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MessagesRepository")
 */
class Messages
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $contenu;

    /**
     * @ORM\Column(type="datetime")
     */
    private $create_at;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $read_at;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ReadingMessages", mappedBy="idMessage")
     */
    private $reading;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Abonnes", inversedBy="messages")
     */
    private $idAbonne;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Commentaires", mappedBy="idMessages")
     */
    private $getComments;

    public function __construct()
    {
        $this->reading = new ArrayCollection();
        $this->getComments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->create_at;
    }

    public function setCreateAt(\DateTimeInterface $create_at): self
    {
        $this->create_at = $create_at;

        return $this;
    }

    public function getReadAt(): ?\DateTimeInterface
    {
        return $this->read_at;
    }

    public function setReadAt(\DateTimeInterface $read_at): self
    {
        $this->read_at = $read_at;

        return $this;
    }

    public function getExtrait(){
        return substr($this->getContenu(),0,100);
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
    /**
     * @return Collection|ReadingMessages[]
     */
    public function getReading(): Collection
    {
        return $this->reading;
    }

    public function addReading(ReadingMessages $reading): self
    {
        if (!$this->reading->contains($reading)) {
            $this->reading[] = $reading;
            $reading->setIdMessage($this);
        }

        return $this;
    }

    public function removeReading(ReadingMessages $reading): self
    {
        if ($this->reading->contains($reading)) {
            $this->reading->removeElement($reading);
            // set the owning side to null (unless already changed)
            if ($reading->getIdMessage() === $this) {
                $reading->setIdMessage(null);
            }
        }
        return $this;
    }

    public function getIdAbonne(): ?Abonnes
    {
        return $this->idAbonne;
    }

    public function setIdAbonne(?Abonnes $idAbonne): self
    {
        $this->idAbonne = $idAbonne;

        return $this;
    }

    /**
     * @return Collection|Commentaires[]
     */
    public function getGetComments(): Collection
    {
        return $this->getComments;
    }

    public function addGetComment(Commentaires $getComment): self
    {
        if (!$this->getComments->contains($getComment)) {
            $this->getComments[] = $getComment;
            $getComment->setIdMessages($this);
        }

        return $this;
    }

    public function removeGetComment(Commentaires $getComment): self
    {
        if ($this->getComments->contains($getComment)) {
            $this->getComments->removeElement($getComment);
            // set the owning side to null (unless already changed)
            if ($getComment->getIdMessages() === $this) {
                $getComment->setIdMessages(null);
            }
        }

        return $this;
    }
}
