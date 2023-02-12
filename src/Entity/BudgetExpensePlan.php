<?php

namespace App\Entity;

use App\Repository\BudgetExpensePlanRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BudgetExpensePlanRepository::class)]
class BudgetExpensePlan extends CommonEntity
{
  
    #[ORM\ManyToOne(inversedBy: 'budgetExpensePlans')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Budget $budget = null;

    #[ORM\Column]
    private ?float $planValue = null;

    #[ORM\ManyToOne(inversedBy: 'budgetExpensePlans')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ExpenseType $type = null;

    #[ORM\OneToMany(mappedBy: 'expensePlan', targetEntity: Expense::class)]
    private Collection $expenses;

    public function __construct()
    {
        $this->expenses = new ArrayCollection();
    }

    
    public function getBudget(): ?Budget
    {
        return $this->budget;
    }

    public function setBudget(?Budget $budget): self
    {
        $this->budget = $budget;

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

    public function getType(): ?ExpenseType
    {
        return $this->type;
    }

    public function setType(?ExpenseType $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, Expense>
     */
    public function getExpenses(): Collection
    {
        return $this->expenses;
    }

    public function addExpense(Expense $expense): self
    {
        if (!$this->expenses->contains($expense)) {
            $this->expenses->add($expense);
            $expense->setExpensePlan($this);
        }

        return $this;
    }

    public function removeExpense(Expense $expense): self
    {
        if ($this->expenses->removeElement($expense)) {
            // set the owning side to null (unless already changed)
            if ($expense->getExpensePlan() === $this) {
                $expense->setExpensePlan(null);
            }
        }

        return $this;
    }
}
