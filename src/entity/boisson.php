<?php
namespace App\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\ManyToOne;

#[Entity]
#[Table(name: 'boisson')]
class Boisson
{
    #[Id, Column(type: 'integer'), GeneratedValue]//id : nom en bdd de la propriété, type : type de données
    private int|null $id = null;

    #[Column(type: 'string', length: 255)]
    private string $nom;

    #[Column(type: 'integer', length: 255)]
    private int $volumeCL;

    #[Column(type: 'float', length: 255)]
    private float $prix;

    #[ManyToOne(targetEntity: Categorie::class, inversedBy: 'boissons')]
    private Categorie $categorie;

    public function __construct(string $nom, int $volumeCL, float $prix, Categorie $categorie)
    {
        $this->nom = $nom;
        $this->volumeCL = $volumeCL;
        $this->prix = $prix;
        $this->categorie = $categorie;
    }

    public function getId(): int|null
    {
        return $this->id;
    }
    public function getNom(): string
    {
        return $this->nom;
    }
    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }
    public function getVolumeCL(): int
    {
        return $this->volumeCL;
    }
    public function setVolumeCL(int $volumeCL): void
    {
        $this->volumeCL = $volumeCL;
    }
    public function getPrix(): float
    {
        return $this->prix;
    }
    public function setPrix(float $prix): void
    {
        $this->prix = $prix;
    }
    public function getCategorie(): Categorie
    {
        return $this->categorie;
    }
    public function setCategorie(Categorie $categorie): void
    {
        $this->categorie = $categorie;
    }
}