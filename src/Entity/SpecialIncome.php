<?php

namespace App\Entity;

use App\Repository\SpecialIncomeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


#[UniqueEntity('receiptNumber')]
#[ORM\Entity(repositoryClass: SpecialIncomeRepository::class)]
class SpecialIncome extends CommonEntity
{
    

    #[ORM\Column]
    private ?float $amount = null;

    #[ORM\ManyToOne(inversedBy: 'specialIncomes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?IncomeSetting $type = null;

    #[ORM\ManyToOne(inversedBy: 'specialIncomes')]
    private ?Budget $budget = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $receiptNumber = null;

    

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getType(): ?IncomeSetting
    {
        return $this->type;
    }

    public function setType(?IncomeSetting $type): self
    {
        $this->type = $type;

        return $this;
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

    public function getReceiptNumber(): ?string
    {
        return $this->receiptNumber;
    }

    public function setReceiptNumber(?string $receiptNumber): self
    {
        $this->receiptNumber = $receiptNumber;

        return $this;
    }
}
