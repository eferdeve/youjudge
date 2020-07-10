<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\JoinColumn;
use App\Repository\CommentairesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommentairesRepository::class)
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
     * @ORM\Column(type="string", length=1000)
     */
    private $contenu;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\ManyToOne(targetEntity=Jeux::class, inversedBy="commentaires")
     * @JoinColumn(name="jeu_id", referencedColumnName="id")
     * @ORM\JoinColumn(nullable=true)
     */
    private $jeu;

    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="commentaires")
     * @ORM\JoinColumn(nullable=true)
     */
    private $auteur;

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getJeu(): ?Jeux
    {
        return $this->jeu;
    }

    public function setJeu($jeu): self
    {
        $this->jeu = $jeu;

        return $this;
    }

    public function getAuteur(): ?Users
    {
        return $this->auteur;
    }

    public function setAuteur(?Users $auteur): self
    {
        $this->auteur = $auteur;

        return $this;
    }
}
