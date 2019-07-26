<?php

namespace App\Entity;

use App\Repository\VehiculeRepository;
use App\Repository\VoyageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Asset;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
/**
 * Abonnes
 *
 * @ORM\Table(name="abonnes")
 * @ORM\Entity(repositoryClass="App\Repository\AbonnesRepository")
 * @Vich\Uploadable()
 * @UniqueEntity("email")
 * @UniqueEntity("telephone")
 */
class Abonnes implements UserInterface, \Serializable
{
    const TYPEAB = array(
        1=>'Affreteur/Courtier',
        2=>'Transporteur'
    );
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="nom", type="string", length=70, nullable=false)
     */
    private $nom;

    /**
     * @Asset\Regex("/^[0-9]{9}$/")
     * @var string
     * @ORM\Column(name="telephone", type="string", length=25, nullable=true)
     */
    private $telephone;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=100, nullable=false)
     */
    private $email;
    /**
     * @Asset\Image(mimeTypes="image/jpeg")
     * @var File|null
     * @Vich\UploadableField( mapping="abonnes_profil",fileNameProperty="file")
     */
    private $imageProfil;
    /**
     * @var string|null
     *
     * @ORM\Column(name="file", type="string", length=100, nullable=true)
     */
    private $file;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=200, nullable=false)
     */
    private $password;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $typeAbonne;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Prenom;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Entreprise", inversedBy="abonnes")
     */
    private $id_entreprise;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Vehicule", mappedBy="idAbonne")
     */
    private $listeVehicule;

    private $pwd;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Messages", mappedBy="idAbonne")
     */
    private $messages;
    /**
     * @return mixed
     */
    public function getPwd()
    {
        return $this->pwd;
    }

    /**
     * @param mixed $pwd
     */
    public function setPwd($pwd): void
    {
        $this->pwd = $pwd;
    }
    private $NbreMessageInread;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Chauffeur", mappedBy="idAbonnes")
     */
    private $Chauffeurs;

    /**
     * @ORM\Column(type="boolean")
     */
    private $admin;

    /**
     * @return mixed
     */
    public function getNbreMessageInread()
    {
        return $this->NbreMessageInread;
    }

    /**
     * @param mixed $NbreMessageInread
     */
    public function setNbreMessageInread($NbreMessageInread): void
    {
        $this->NbreMessageInread = $NbreMessageInread;
    }

    public function __construct()
    {
        $this->listeVehicule = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->Chauffeurs = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }


    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(?string $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getTypeAbonneValue():string
    {
        return self::TYPEAB[$this->getTypeAbonne()];
    }

    /**
     * Returns the roles granted to the user.
     *
     *     public function getRoles()
     *     {
     *         return ['ROLE_USER'];
     *     }
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return array (Role|string)[] The user roles
     */
    public function getRoles()
    {
        return ['ROLE_USER'];
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        // TODO: Implement getUsername() method.
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {

    }

    /**
     * String representation of object
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize()
    {
        return serialize(
            [
                $this->id,
                $this->email,
                $this->password
            ]
        );
    }

    /**
     * Constructs the object
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->email,
            $this->password
            ) = unserialize($serialized, ['allowed_classes' => false]);
    }

    public function getTypeAbonne(): ?int
    {
        return $this->typeAbonne;
    }

    public function setTypeAbonne(int $typeAbonne): self
    {
        $this->typeAbonne = $typeAbonne;

        return $this;
    }

    /**
     * @return null|File
     */
    public function getImageProfil(): ?File
    {
        return $this->imageProfil;
    }

    /**
     * @param null|File $imageProfil
     */
    public function setImageProfil(?File $imageProfil): void
    {
        $this->imageProfil = $imageProfil;
        if ($this->imageProfil instanceof UploadedFile)
        {
            $this->created_at = new \DateTime('now');
        }
        $this->created_at = new \DateTime('now');
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->Prenom;
    }

    public function setPrenom(string $Prenom): self
    {
        $this->Prenom = $Prenom;

        return $this;
    }

    public function getIdEntreprise(): ?Entreprise
    {
        return $this->id_entreprise;
    }

    public function setIdEntreprise(?Entreprise $id_entreprise): self
    {
        $this->id_entreprise = $id_entreprise;

        return $this;
    }

    /**
     * @return Collection|Vehicule[]
     */
    public function getListeVehicule(): Collection
    {
        return $this->listeVehicule;
    }

    public function addListeVehicule(Vehicule $listeVehicule): self
    {
        if (!$this->listeVehicule->contains($listeVehicule)) {
            $this->listeVehicule[] = $listeVehicule;
            $listeVehicule->setIdAbonne($this);
        }

        return $this;
    }

    public function removeListeVehicule(Vehicule $listeVehicule): self
    {
        if ($this->listeVehicule->contains($listeVehicule)) {
            $this->listeVehicule->removeElement($listeVehicule);
            // set the owning side to null (unless already changed)
            if ($listeVehicule->getIdAbonne() === $this) {
                $listeVehicule->setIdAbonne(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Messages[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Messages $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setIdAbonne($this);
        }

        return $this;
    }

    public function removeMessage(Messages $message): self
    {
        if ($this->messages->contains($message)) {
            $this->messages->removeElement($message);
            // set the owning side to null (unless already changed)
            if ($message->getIdAbonne() === $this) {
                $message->setIdAbonne(null);
            }
        }

        return $this;
    }

    /**
     * @param VoyageRepository $repository
     * @param $type
     * @return Collection|Vehicule[]
     */
    public function getVehiculeVide(VoyageRepository $repository, $type): Collection
    {
        $listeVehiculeVide = $this->getListeVehicule();
        foreach ($listeVehiculeVide as $vh ){
            if ($repository->findVoyageActif($vh) != null){
                $listeVehiculeVide->removeElement($vh);
            }
            if ($vh->getTypeVehicule() != $type){
                $listeVehiculeVide->removeElement($vh);
            }
        }
        return $listeVehiculeVide;
    }

    /**
     * @return Collection|Chauffeur[]
     */
    public function getChauffeurs(): Collection
    {
        return $this->Chauffeurs;
    }

    public function addChauffeur(Chauffeur $chauffeur): self
    {
        if (!$this->Chauffeurs->contains($chauffeur)) {
            $this->Chauffeurs[] = $chauffeur;
            $chauffeur->setIdAbonnes($this);
        }

        return $this;
    }

    public function removeChauffeur(Chauffeur $chauffeur): self
    {
        if ($this->Chauffeurs->contains($chauffeur)) {
            $this->Chauffeurs->removeElement($chauffeur);
            // set the owning side to null (unless already changed)
            if ($chauffeur->getIdAbonnes() === $this) {
                $chauffeur->setIdAbonnes(null);
            }
        }

        return $this;
    }

    public function getAdmin(): ?bool
    {
        return $this->admin;
    }

    public function setAdmin(bool $admin): self
    {
        $this->admin = $admin;

        return $this;
    }
}
