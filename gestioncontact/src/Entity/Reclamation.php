<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\ReclamationRepository;

use Symfony\Component\Serializer\Annotation\Groups ;
/**
 * Reclamation
 *
 * @ORM\Table(name="reclamation", indexes={@ORM\Index(name="id", columns={"id"})})
 * @ORM\Entity(repositoryClass="App\Repository\ReclamationRepository")
 */
class Reclamation
{
    /**
     * @var int
     * @Groups({"reclamation"})
     * @Groups("posts:read")
     * @Groups("post:read")

     * @ORM\Column(name="numero", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $numero;

    /**
     * @var string
     * * @Groups({"reclamation"})
     * @Groups("post:read")

     * @Groups("posts:read")
     * @Assert\NotBlank(message=" nom doit etre non vide")
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     * @Groups({"reclamation"})
     * @Groups("posts:read")
     * @Groups("post:read")

     * @Assert\NotBlank(message=" prenom doit etre non vide")
     * @ORM\Column(name="prenom", type="string", length=255)
     */
    private $prenom;

    /**
     * @var string
     * @Groups({"reclamation"})
     * @Groups("posts:read")
     * @Groups("post:read")

     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="Email is required")
     * @Assert\Email(message = "The email '{{  }}' is not a valid
    email.")
     */
    private $email;

    /**
     * @var string
     * @Assert\NotBlank(message="le champ commentaire doit etre non vide")
     * @Groups({"reclamation"})
     * @Groups("posts:read")
     * @Groups("post:read")
     *  @Assert\Length(min=3,minMessage="content length must be greater than 3")
     * @ORM\Column(name="commentaire", type="string", length=255)
     */
    private $commentaire;

    /**
     * @var \DateTime

     * Checks whether a time is valid.
     *
     * @internal
     * @Groups({"reclamation"})
     *  @Groups("post:read")
     * @Groups("posts:read")
     * @ORM\Column(name="dateReclamation", type="date", nullable=false)
     */
    private $datereclamation;

    /**
     * @var string
     * @Groups({"reclamation"})
     * @Groups("posts:read")
     * @ORM\Column(name="typeReclamation", type="string", length=255, nullable=false)
      * @Groups("post:read")
     */
    private $typereclamation;

    /**
     * @var \Users
     * @Groups("posts:read")
     * @Groups("post:read")
     * @ORM\ManyToOne(targetEntity="Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id", referencedColumnName="id")
     * })
     */
    private $id;

    public function getNumero(): ?int
    {
        return $this->numero;
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }


    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(string $commentaire): self
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getDatereclamation(): ?\DateTimeInterface
    {
        return $this->datereclamation;
    }

    public function setDatereclamation(\DateTimeInterface $datereclamation): self
    {
        $this->datereclamation = $datereclamation;

        return $this;
    }

    public function getTypereclamation(): ?string
    {
        return $this->typereclamation;
    }

    public function setTypereclamation(string $typereclamation): self
    {
        $this->typereclamation = $typereclamation;

        return $this;
    }

    public function getId(): ?Users
    {
        return $this->id;
    }

    public function setId(?Users $id): self
    {
        $this->id = $id;

        return $this;
    }


}
