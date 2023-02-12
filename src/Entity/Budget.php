<?php

namespace App\Entity;

use App\Repository\BudgetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BudgetRepository::class)]
class Budget extends CommonEntity
{
   

    #[ORM\OneToOne(inversedBy: 'budget', cascade: ['persist', 'remove'])]
    private ?PaymentYear $year = null;

    #[ORM\Column]
    private ?float $planValue = null;

    #[ORM\Column(nullable: true)]
    private ?float $actualValue = null;

    #[ORM\OneToMany(mappedBy: 'budget', targetEntity: BudgetIncomePlan::class)]
    private Collection $budgetIncomePlans;

    #[ORM\OneToMany(mappedBy: 'budget', targetEntity: BudgetExpensePlan::class)]
    private Collection $budgetExpensePlans;

   
    public function __construct()
    {
        $this->budgetIncomePlans = new ArrayCollection();
        $this->budgetExpensePlans = new ArrayCollection();
        // $this->incomes = new ArrayCollection();
    }


    public function getYear(): ?PaymentYear
    {
        return $this->year;
    }

    public function setYear(?PaymentYear $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getPlanValue(): ?float
    {
        return $this->planValue;
    }

    public function setPlanValue(float $planValue): self
    {
        $this->planValue = $planValue;

        return $this;
    }

    public function getActualValue(): ?float
    {
        return $this->actualValue;
    }

    public function setActualValue(?float $actualValue): self
    {
        $this->actualValue = $actualValue;

        return $this;
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
            $budgetIncomePlan->setBudget($this);
        }

        return $this;
    }

    public function removeBudgetIncomePlan(BudgetIncomePlan $budgetIncomePlan): self
    {
        if ($this->budgetIncomePlans->removeElement($budgetIncomePlan)) {
            // set the owning side to null (unless already changed)
            if ($budgetIncomePlan->getBudget() === $this) {
                $budgetIncomePlan->setBudget(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, BudgetExpensePlan>
     */
    public function getBudgetExpensePlans(): Collection
    {
        return $this->budgetExpensePlans;
    }

    public function addBudgetExpensePlan(BudgetExpensePlan $budgetExpensePlan): self
    {
        if (!$this->budgetExpensePlans->contains($budgetExpensePlan)) {
            $this->budgetExpensePlans->add($budgetExpensePlan);
            $budgetExpensePlan->setBudget($this);
        }

        return $this;
    }

    public function removeBudgetExpensePlan(BudgetExpensePlan $budgetExpensePlan): self
    {
        if ($this->budgetExpensePlans->removeElement($budgetExpensePlan)) {
            // set the owning side to null (unless already changed)
            if ($budgetExpensePlan->getBudget() === $this) {
                $budgetExpensePlan->setBudget(null);
            }
        }

        return $this;
    }

   
 
}
