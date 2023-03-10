<?php

namespace App\Entity;

use App\Repository\IncomeTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IncomeTypeRepository::class)]
class IncomeType extends CommonEntity
{
   
    #[ORM\OneToMany(mappedBy: 'type', targetEntity: BudgetIncomePlan::class)]
    private Collection $budgetIncomePlans;

    #[ORM\OneToMany(mappedBy: 'type', targetEntity: IncomeSetting::class)]
    private Collection $incomeSettings;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $source = null;

    public function __construct()
    {
        $this->budgetIncomePlans = new ArrayCollection();
        $this->incomeSettings = new ArrayCollection();
    }

   

    /**
     * @return Collection<int, BudgetIncomePlan>
     */
    public function getBudgetIncomePlans(): Collection
    {
        return $this->budgetIncomePlans;
    }

    public function addBudgetIncomePlan(BudgetIncomePlan $budgetIncomePlan): self
    {
        if (!$this->budgetIncomePlans->contains($budgetIncomePlan)) {
            $this->budgetIncomePlans->add($budgetIncomePlan);
            $budgetIncomePlan->setType($this);
        }

        return $this;
    }

    public function removeBudgetIncomePlan(BudgetIncomePlan $budgetIncomePlan): self
    {
        if ($this->budgetIncomePlans->removeElement($budgetIncomePlan)) {
            // set the owning side to null (unless already changed)
            if ($budgetIncomePlan->getType() === $this) {
                $budgetIncomePlan->setType(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, IncomeSetting>
     */
    public function getIncomeSettings(): Collection
    {
        return $this->incomeSettings;
    }

    public function addIncomeSetting(IncomeSetting $incomeSetting): self
    {
        if (!$this->incomeSettings->contains($incomeSetting)) {
            $this->incomeSettings->add($incomeSetting);
            $incomeSetting->setType($this);
        }

        return $this;
    }

    public function removeIncomeSetting(IncomeSetting $incomeSetting): self
    {
        if ($this->incomeSettings->removeElement($incomeSetting)) {
            // set the owning side to null (unless already changed)
            if ($incomeSetting->getType() === $this) {
                $incomeSetting->setType(null);
            }
        }

        return $this;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(?string $source): self
    {
        $this->source = $source;

        return $this;
    }
}
