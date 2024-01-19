<?php

namespace App\Entity\Trait;

trait SlugTrait {
    #[ORM\Column(type: 'string', length: 255)]
    public $slug;

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
