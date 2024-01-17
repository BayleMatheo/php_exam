<?php

namespace App\Entity;

use App\Repository\CartRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CartRepository::class)]
class Cart
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?User $user_id = null;

    #[ORM\OneToMany(mappedBy: 'cart', targetEntity: article::class)]
    private Collection $article_id;

    public function __construct()
    {
        $this->article_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * @return Collection<int, article>
     */
    public function getArticleId(): Collection
    {
        return $this->article_id;
    }

    public function addArticleId(article $articleId): static
    {
        if (!$this->article_id->contains($articleId)) {
            $this->article_id->add($articleId);
            $articleId->setCart($this);
        }

        return $this;
    }

    public function removeArticleId(article $articleId): static
    {
        if ($this->article_id->removeElement($articleId)) {
            // set the owning side to null (unless already changed)
            if ($articleId->getCart() === $this) {
                $articleId->setCart(null);
            }
        }

        return $this;
    }
}
