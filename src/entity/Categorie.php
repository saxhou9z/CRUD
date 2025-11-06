<?php
namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\JoinColumn;

#[Entity]
#[Table(name: 'categorie')]
class Categorie
{
    #[Id, Column(type: 'integer'), GeneratedValue]//id : nom en bdd de la propriété, type : type de données
    private int|null $id = null;

    #[Column(type: 'string', length: 255)]
    private string $libelle;

    public function __construct(string $libelle)
    {
        $this->libelle = $libelle;
    }
    public function getId(): int|null
    {
        return $this->id;
    }
    public function getLibelle(): string
    {
        return $this->libelle;
    }
    public function setLibelle(string $libelle): void
    {
        $this->libelle = $libelle;
    }
}