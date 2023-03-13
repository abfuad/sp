<?php

namespace App\Entity;

use App\Repository\AssetGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AssetGroupRepository::class)]
class AssetGroup extends BaseEntity
{
    

    #[ORM\ManyToOne(inversedBy: 'assetGroups')]
    #[ORM\JoinColumn(nullable: false)]
    private ?AssetCategory $category = null;

    #[ORM\Column]
    private ?bool $isFixed = null;

    #[ORM\Column]
    private ?float $quantity = null;

    #[ORM\Column]
    private ?float $stockIn = null;

    // #[ORM\OneToMany(mappedBy: 'assetGroup', targetEntity: Asset::class)]
    // private Collection $assets;

    // #[ORM\OneToMany(mappedBy: 'asset', targetEntity: UserCard::class)]
    // private Collection $userCards;

    #[ORM\ManyToOne(inversedBy: 'assetGroups')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Measure $unit = null;

    public function __construct()
    {
        // $this->assets = new ArrayCollection();
        //$this->userCards = new ArrayCollection();
    }

    

    public function getCategory(): ?AssetCategory
    {
        return $this->category;
    }

    public function setCategory(?AssetCategory $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function isIsFixed(): ?bool
    {
        return $this->isFixed;
    }

    public function setIsFixed(bool $isFixed): self
    {
        $this->isFixed = $isFixed;

        return $this;
    }

    public function getQuantity(): ?float
    {
        return $this->quantity;
    }

    public function setQuantity(float $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getStockIn(): ?float
    {
        return $this->stockIn;
    }

    public function setStockIn(float $stockIn): self
    {
        $this->stockIn = $stockIn;

        return $this;
    }

    // /**
    //  * @return Collection<int, Asset>
    //  */
    // public function getAssets(): Collection
    // {
    //     return $this->assets;
    // }

    // public function addAsset(Asset $asset): self
    // {
    //     if (!$this->assets->contains($asset)) {
    //         $this->assets->add($asset);
    //         $asset->setAssetGroup($this);
    //     }

    //     return $this;
    // }

    // public function removeAsset(Asset $asset): self
    // {
    //     if ($this->assets->removeElement($asset)) {
    //         // set the owning side to null (unless already changed)
    //         if ($asset->getAssetGroup() === $this) {
    //             $asset->setAssetGroup(null);
    //         }
    //     }

    //     return $this;
    // }

    // /**
    //  * @return Collection<int, UserCard>
    //  */
    // public function getUserCards(): Collection
    // {
    //     return $this->userCards;
    // }

    // public function addUserCard(UserCard $userCard): self
    // {
    //     if (!$this->userCards->contains($userCard)) {
    //         $this->userCards->add($userCard);
    //         $userCard->setAsset($this);
    //     }

    //     return $this;
    // }

    // public function removeUserCard(UserCard $userCard): self
    // {
    //     if ($this->userCards->removeElement($userCard)) {
    //         // set the owning side to null (unless already changed)
    //         if ($userCard->getAsset() === $this) {
    //             $userCard->setAsset(null);
    //         }
    //     }

    //     return $this;
    // }

    public function getUnit(): ?Measure
    {
        return $this->unit;
    }

    public function setUnit(?Measure $unit): self
    {
        $this->unit = $unit;

        return $this;
    }
}
