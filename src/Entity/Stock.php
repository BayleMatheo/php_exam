<?php

namespace App\Entity;

use App\Repository\StockRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StockRepository::class)]
class Stock
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'stocks')]
    #[ORM\JoinColumn(name: "article_id", referencedColumnName: "id", nullable: false)]
    private ?Article $article = null;

    #[ORM\Column]
    private ?int $nb_article = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): static
    {
        $this->article = $article;

        return $this;
    }

    public function getNbArticle(): ?int
    {
        return $this->nb_article;
    }

    public function setNbArticle(int $nb_article): static
    {
        $this->nb_article = $nb_article;

        return $this;
    }
}