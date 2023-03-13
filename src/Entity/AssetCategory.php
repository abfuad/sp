<?php

namespace App\Entity;

use App\Repository\AssetCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AssetCategoryRepository::class)]
class AssetCategory extends CommonEntity
{
   

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: AssetGroup::class)]
    private Collection $assetGroups;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Asset::class)]
    private Collection $assets;

    #[ORM\Column(nullable: true)]
    private ?bool $isFixed = null;

    #[ORM\ManyToOne(inversedBy: 'assetCategories')]
    private ?Measure $unit = null;

    #[ORM\OneToMany(mappedBy: 'asset', targetEntity: UserCard::class)]
    private Collection $userCards;

    public function __construct()
    {
        $this->assetGroups = new ArrayCollection();
        $this->assets = new ArrayCollection();
        $this->userCards = new ArrayCollection();
    }

  

    /**
     * @return Collection<int, AssetGroup>
     */
    public function getAssetGroups(): Collection
    {
        return $this->assetGroups;
    }

    public function addAssetGroup(AssetGroup $assetGroup): self
    {
        if (!$this->assetGroups->contains($assetGroup)) {
            $this->assetGroups->add($assetGroup);
            $assetGroup->setCategory($this);
        }

        return $this;
    }

    public function removeAssetGroup(AssetGroup $assetGroup): self
    {
        if ($this->assetGroups->removeElement($assetGroup)) {
            // set the owning side to null (unless already changed)
            if ($assetGroup->getCategory() === $this) {
                $assetGroup->setCategory(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Asset>
     */
    public function getAssets(): Collection
    {
        return $this->assets;
    }

    public function addAsset(Asset $asset): self
    {
        if (!$this->assets->contains($asset)) {
            $this->assets->add($asset);
            $asset->setCategory($this);
        }

        return $this;
    }

    public function removeAsset(Asset $asset): self
    {
        if ($this->assets->removeElement($asset)) {
            // set the owning side to null (unless already changed)
            if ($asset->getCategory() === $this) {
                $asset->setCategory(null);
            }
        }

        return $this;
    }

    public function isIsFixed(): ?bool
    {
        return $this->isFixed;
    }

    public function setIsFixed(?bool $isFixed): self
    {
        $this->isFixed = $isFixed;

        return $this;
    }

    public function getUnit(): ?Measure
    {
        return $this->unit;
    }

    public function setUnit(?Measure $unit): self
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * @return Collection<int, UserCard>
     */
    public function getUserCards(): Collection
    {
        return $this->userCards;
    }

    public function addUserCard(UserCard $userCard): self
    {
        if (!$this->userCards->contains($userCard)) {
            $this->userCards->add($userCard);
            $userCard->setAsset($this);
        }

        return $this;
    }

    public function removeUserCard(UserCard $userCard): self
    {
        if ($this->userCards->removeElement($userCard)) {
            // set the owning side to null (unless already changed)
            if ($userCard->getAsset() === $this) {
                $userCard->setAsset(null);
            }
        }

        return $this;
    }
}
