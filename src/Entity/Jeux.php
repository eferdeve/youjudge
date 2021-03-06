<?php

namespace App\Entity;

use App\Entity\Commentaires;
use App\Repository\JeuxRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=JeuxRepository::class)
 */
class Jeux
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="decimal", precision=4, scale=1, nullable=true)
     */
    private $notemoyenne;

    /**
     * @ORM\OneToMany(targetEntity=Commentaires::class, mappedBy="jeu", orphanRemoval=true)
     */
    private $commentaires;

    /**
     * @ORM\OneToMany(targetEntity=Notes::class, mappedBy="jeu", orphanRemoval=true)
     */
    private $notes;

    /**
     * @ORM\Column(type="string", length=500)
     */
    private $synopsis;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $streamer;

    public function __construct()
    {
        $this->commentaires = new ArrayCollection();
        $this->notes = new ArrayCollection();
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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getNotemoyenne(): ?string
    {
        return $this->notemoyenne;
    }

    public function setNotemoyenne(string $notemoyenne): self
    {
        $this->notemoyenne = $notemoyenne;

        return $this;
    }

    /**
     * @return Collection|Commentaires[]
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaires $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setJeu($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaires $commentaire): self
    {
        if ($this->commentaires->contains($commentaire)) {
            $this->commentaires->removeElement($commentaire);
            // set the owning side to null (unless already changed)
            if ($commentaire->getJeu() === $this) {
                $commentaire->setJeu(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Notes[]
     */
    public function getNotes(): Collection
    {
        return $this->notes;
    }

    public function addNote(Notes $note): self
    {
        if (!$this->notes->contains($note)) {
            $this->notes[] = $note;
            $note->setJeu($this);
        }

        return $this;
    }

    public function removeNote(Notes $note): self
    {
        if ($this->notes->contains($note)) {
            $this->notes->removeElement($note);
            // set the owning side to null (unless already changed)
            if ($note->getJeu() === $this) {
                $note->setJeu(null);
            }
        }

        return $this;
    }

    public function getSynopsis(): ?string
    {
        return $this->synopsis;
    }

    public function setSynopsis(string $synopsis): self
    {
        $this->synopsis = $synopsis;

        return $this;
    }

    public function getStreamer(): ?string
    {
        return $this->streamer;
    }

    public function setStreamer(?string $streamer): self
    {
        $this->streamer = $streamer;

        return $this;
    }
}
