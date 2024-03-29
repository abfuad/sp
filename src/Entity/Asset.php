<?php

namespace App\Entity;

use App\Repository\AssetRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AssetRepository::class)]
class Asset extends BaseEntity
{
   

    // #[ORM\ManyToOne(inversedBy: 'assets')]
    // #[ORM\JoinColumn(nullable: false)]
    // private ?AssetGroup $assetGroup = null;

    #[ORM\Column]
    private ?float $quantity = null;

    #[ORM\ManyToOne(inversedBy: 'assets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?AssetCategory $category = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    

    // public function getAssetGroup(): ?AssetGroup
    // {
    //     return $this->assetGroup;
    // }

    // public function setAssetGroup(?AssetGroup $assetGroup): self
    // {
    //     $this->assetGroup = $assetGroup;

    //     return $this;
    // }

    public function getQuantity(): ?float
    {
        return $this->quantity;
    }

    public function setQuantity(float $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
