<?php

namespace App\Entity;

use App\Repository\MeasureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MeasureRepository::class)]
class Measure extends CommonEntity
{
    

    #[ORM\OneToMany(mappedBy: 'unit', targetEntity: AssetGroup::class)]
    private Collection $assetGroups;

    #[ORM\OneToMany(mappedBy: 'unit', targetEntity: AssetCategory::class)]
    private Collection $assetCategories;

    public function __construct()
    {
        $this->assetGroups = new ArrayCollection();
        $this->assetCategories = new ArrayCollection();
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
            $assetGroup->setUnit($this);
        }

        return $this;
    }

    public function removeAssetGroup(AssetGroup $assetGroup): self
    {
        if ($this->assetGroups->removeElement($assetGroup)) {
            // set the owning side to null (unless already changed)
            if ($assetGroup->getUnit() === $this) {
                $assetGroup->setUnit(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, AssetCategory>
     */
    public function getAssetCategories(): Collection
    {
        return $this->assetCategories;
    }

    public function addAssetCategory(AssetCategory $assetCategory): self
    {
        if (!$this->assetCategories->contains($assetCategory)) {
            $this->assetCategories->add($assetCategory);
            $assetCategory->setUnit($this);
        }

        return $this;
    }

    public function removeAssetCategory(AssetCategory $assetCategory): self
    {
        if ($this->assetCategories->removeElement($assetCategory)) {
            // set the owning side to null (unless already changed)
            if ($assetCategory->getUnit() === $this) {
                $assetCategory->setUnit(null);
            }
        }

        return $this;
    }
}
