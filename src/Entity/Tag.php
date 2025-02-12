<?php

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TagRepository::class)]
class Tag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 80)]
    private ?string $name = null;

    /**
     * @var Collection<int, LandingPage>
     */
    #[ORM\ManyToMany(targetEntity: LandingPage::class, inversedBy: 'tags')]
    private Collection $landing_page;

    public function __construct()
    {
        $this->landing_page = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, LandingPage>
     */
    public function getLandingPage(): Collection
    {
        return $this->landing_page;
    }

    public function addLandingPage(LandingPage $landingPage): static
    {
        if (!$this->landing_page->contains($landingPage)) {
            $this->landing_page->add($landingPage);
        }

        return $this;
    }

    public function removeLandingPage(LandingPage $landingPage): static
    {
        $this->landing_page->removeElement($landingPage);

        return $this;
    }
}