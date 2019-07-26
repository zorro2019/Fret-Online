<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReadingMessagesRepository")
 */
class ReadingMessages
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $idAbonne;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Messages", inversedBy="reading")
     */
    private $idMessage;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $readed;

    public function __construct()
    {

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdAbonne(): ?int
    {
        return $this->idAbonne;
    }

    public function setIdAbonne(?int $idAbonne): self
    {
        $this->idAbonne = $idAbonne;

        return $this;
    }

    public function getIdMessage(): ?Messages
    {
        return $this->idMessage;
    }

    public function setIdMessage(?Messages $idMessage): self
    {
        $this->idMessage = $idMessage;

        return $this;
    }

    public function getReaded(): ?bool
    {
        return $this->readed;
    }

    public function setReaded(?bool $readed): self
    {
        $this->readed = $readed;

        return $this;
    }
}
