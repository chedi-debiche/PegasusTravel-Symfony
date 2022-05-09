<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Publication
 *
 * @ORM\Table(name="publication")
 * @ORM\Entity
 */
class Publication
{
    /**
     * @var int
     *
     * @ORM\Column(name="idPub", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idpub;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datePub", type="date", nullable=false)
     */
    private $datepub;

    /**
     * @var string
     * @Assert\NotBlank(message=" path doit etre non vide")
     * @ORM\Column(name="Path", type="string", length=255, nullable=false)
     */
    private $path;

    /**
     * @var string
     * @Assert\NotBlank(message="le champ commentaire doit etre non vide")
     * @Assert\Length(
     *      min = 0,
     *      minMessage = "doit etre >=0 ",)

     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     */
    private $description;

    public function getIdpub(): ?int
    {
        return $this->idpub;
    }

    public function getDatepub(): ?\DateTimeInterface
    {
        return $this->datepub;
    }

    public function setDatepub(\DateTimeInterface $datepub): self
    {
        $this->datepub = $datepub;

        return $this;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function setPath( $path)
    {
        $this->path = $path;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }


}
