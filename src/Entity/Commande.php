<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateCommande = null;

    #[ORM\Column]
    private ?float $total = null;

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    private ?Client $client = null;

    /**
     * @var Collection<int, CommandeArticle>
     */
    #[ORM\OneToMany(targetEntity: CommandeArticle::class, mappedBy: 'commande')]
    private Collection $commandeArticles;

    /**
     * @var Collection<int, CommandeArticle>
     */
    #[ORM\OneToMany(targetEntity: CommandeArticle::class, mappedBy: 'commandes')]
    private Collection $lignes;

    public function __construct()
    {
        $this->commandeArticles = new ArrayCollection();
        $this->lignes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateCommande(): ?\DateTimeInterface
    {
        return $this->dateCommande;
    }

    public function setDateCommande(\DateTimeInterface $dateCommande): static
    {
        $this->dateCommande = $dateCommande;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): static
    {
        $this->total = $total;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @return Collection<int, CommandeArticle>
     */
    public function getCommandeArticles(): Collection
    {
        return $this->commandeArticles;
    }

    public function addCommandeArticle(CommandeArticle $commandeArticle): static
    {
        if (!$this->commandeArticles->contains($commandeArticle)) {
            $this->commandeArticles->add($commandeArticle);
            $commandeArticle->setCommande($this);
        }

        return $this;
    }

    public function removeCommandeArticle(CommandeArticle $commandeArticle): static
    {
        if ($this->commandeArticles->removeElement($commandeArticle)) {
            // set the owning side to null (unless already changed)
            if ($commandeArticle->getCommande() === $this) {
                $commandeArticle->setCommande(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CommandeArticle>
     */
    public function getLignes(): Collection
    {
        return $this->lignes;
    }

    public function addLigne(CommandeArticle $ligne): static
    {
        if (!$this->lignes->contains($ligne)) {
            $this->lignes->add($ligne);
            $ligne->setCommandes($this);
        }

        return $this;
    }

    public function removeLigne(CommandeArticle $ligne): static
    {
        if ($this->lignes->removeElement($ligne)) {
            // set the owning side to null (unless already changed)
            if ($ligne->getCommandes() === $this) {
                $ligne->setCommandes(null);
            }
        }

        return $this;
    }
}
