<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentairesRepository")
 */
class Commentaires
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creat_at;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Messages", inversedBy="getComments")
     */
    private $idMessages;

    /**
     * @ORM\Column(type="integer")
     */
    private $idAbonne;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $contents;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatAt(): ?\DateTimeInterface
    {
        return $this->creat_at;
    }

    public function setCreatAt(\DateTimeInterface $creat_at): self
    {
        $this->creat_at = $creat_at;

        return $this;
    }

    public function getIdMessages(): ?Messages
    {
        return $this->idMessages;
    }

    public function setIdMessages(?Messages $idMessages): self
    {
        $this->idMessages = $idMessages;

        return $this;
    }

    public function getIdAbonne(): ?int
    {
        return $this->idAbonne;
    }

    public function setIdAbonne(int $idAbonne): self
    {
        $this->idAbonne = $idAbonne;

        return $this;
    }

    public function getContents(): ?string
    {
        return $this->contents;
    }

    public function setContents(string $contents): self
    {
        $this->contents = $contents;

        return $this;
    }
}
