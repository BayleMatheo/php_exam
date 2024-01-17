<?php

namespace App\Entity\Trait;

use Symfony\Component\Validator\Constraints as Assert;

trait SlugTrait{
    #[ORM\Column(type: 'string', lenght: 255)]
    private $slug;

    public function getSlug(): ?string
    {
        return $this->slug;
    }
    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }
}