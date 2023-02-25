<?php

namespace App\Entity;

use App\Repository\IncomeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: IncomeRepository::class)]
#[UniqueEntity(
    fields: ['student', 'type'],
    errorPath: 'student',
    message: 'This Student is already payed this fee.',
)]
class Income extends BaseEntity
{
    

    #[ORM\ManyToOne(inversedBy: 'incomes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?IncomeSetting $type = null;

    #[ORM\Column]
    private ?float $amount = null;

    #[ORM\ManyToOne(inversedBy: 'incomes')]
    private ?Student $student = null;

    
    #[ORM\ManyToOne(inversedBy: 'incomes')]
    private ?BudgetIncomePlan $incomePlan = null;

    

    public function getType(): ?IncomeSetting
    {
        return $this->type;
    }

    public function setType(?IncomeSetting $type): self
    {
        $this->type = $type;

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

    public function getStudent(): ?Student
    {
        return $this->student;
    }

    public function setStudent(?Student $student): self
    {
        $this->student = $student;

        return $this;
    }

    

    public function getIncomePlan(): ?BudgetIncomePlan
    {
        return $this->incomePlan;
    }

    public function setIncomePlan(?BudgetIncomePlan $incomePlan): self
    {
        $this->incomePlan = $incomePlan;

        return $this;
    }
}
