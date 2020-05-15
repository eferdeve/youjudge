<?php

namespace App\Entity;

use App\Repository\CategoriesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoriesRepository::class)
 */
class Categories
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=Jeux::class, mappedBy="categorie")
     */
    private $catjeux;

    public function __construct()
    {
        $this->catjeux = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Jeux[]
     */
    public function getCatjeux(): Collection
    {
        return $this->catjeux;
    }

    public function addCatjeux(Jeux $catjeux): self
    {
        if (!$this->catjeux->contains($catjeux)) {
            $this->catjeux[] = $catjeux;
            $catjeux->setCategorie($this);
        }

        return $this;
    }

    public function removeCatjeux(Jeux $catjeux): self
    {
        if ($this->catjeux->contains($catjeux)) {
            $this->catjeux->removeElement($catjeux);
            // set the owning side to null (unless already changed)
            if ($catjeux->getCategorie() === $this) {
                $catjeux->setCategorie(null);
            }
        }

        return $this;
    }
}
