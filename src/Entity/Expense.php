<?php

namespace App\Entity;

use App\Repository\ExpenseRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExpenseRepository::class)]
class Expense extends CommonEntity
{
    
    #[ORM\ManyToOne(inversedBy: 'expenses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?BudgetExpensePlan $expensePlan = null;

    #[ORM\Column]
    private ?float $amount = null;

    

    public function getExpensePlan(): ?BudgetExpensePlan
    {
        return $this->expensePlan;
    }

    public function setExpensePlan(?BudgetExpensePlan $expensePlan): self
    {
        $this->expensePlan = $expensePlan;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }
}
