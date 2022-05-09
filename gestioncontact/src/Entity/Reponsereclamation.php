<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups ;
/**
 * Reponsereclamation
 *
 * @ORM\Table(name="reponsereclamation", indexes={@ORM\Index(name="numero", columns={"numero"})})
 * @ORM\Entity(repositoryClass="App\Repository\ReponsereclamationRepository")
 */
class Reponsereclamation
{
    /**
     * @var int


     * @ORM\Column(name="IdRep", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups("post:read")

     */
    private $idrep;

    /**
     * @var string
     * @ORM\Column(name="Reponse", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message=" Reponse doit etre non vide")

     * @Groups("post:read")
     */
    private $reponse;

    /**
     * @var \Reclamation
     *

     * @ORM\ManyToOne(targetEntity="Reclamation")
     * @Groups("post:read")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="numero", referencedColumnName="numero")
     * })
     * @Groups("post:read")

     */
    private $numero;

    public function getIdrep(): ?int
    {
        return $this->idrep;
    }

    public function getReponse(): ?string
    {
        return $this->reponse;
    }

    public function setReponse(string $reponse): self
    {
        $this->reponse = $reponse;

        return $this;
    }

    public function getNumero(): ?Reclamation
    {
        return $this->numero;
    }

    public function setNumero(?Reclamation $numero): self
    {
        $this->numero = $numero;

        return $this;
    }


}
