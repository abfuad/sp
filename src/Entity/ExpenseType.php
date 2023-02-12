<?php

namespace App\Entity;

use App\Repository\ExpenseTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExpenseTypeRepository::class)]
class ExpenseType extends CommonEntity
{
   

    #[ORM\OneToMany(mappedBy: 'type', targetEntity: BudgetExpensePlan::class)]
    private Collection $budgetExpensePlans;

    public function __construct()
    {
        $this->budgetExpensePlans = new ArrayCollection();
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
            $budgetExpensePlan->setType($this);
        }

        return $this;
    }

    public function removeBudgetExpensePlan(BudgetExpensePlan $budgetExpensePlan): self
    {
        if ($this->budgetExpensePlans->removeElement($budgetExpensePlan)) {
            // set the owning side to null (unless already changed)
            if ($budgetExpensePlan->getType() === $this) {
                $budgetExpensePlan->setType(null);
            }
        }

        return $this;
    }
}
