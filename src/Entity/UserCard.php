<?php

namespace App\Entity;

use App\Repository\UserCardRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserCardRepository::class)]
class UserCard extends BaseEntity
{
   

    #[ORM\ManyToOne(inversedBy: 'userCards')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    

    #[ORM\Column]
    private ?bool $isReturned = null;

    #[ORM\Column]
    private ?float $quantity = null;

    #[ORM\ManyToOne(inversedBy: 'userCards')]
    #[ORM\JoinColumn(nullable: false)]
    private ?AssetCategory $asset = null;

   

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    

    public function isIsReturned(): ?bool
    {
        return $this->isReturned;
    }

    public function setIsReturned(bool $isReturned): self
    {
        $this->isReturned = $isReturned;

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

    public function getAsset(): ?AssetCategory
    {
        return $this->asset;
    }

    public function setAsset(?AssetCategory $asset): self
    {
        $this->asset = $asset;

        return $this;
    }
}
