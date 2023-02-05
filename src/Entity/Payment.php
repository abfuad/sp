<?php

namespace App\Entity;

use App\Repository\PaymentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaymentRepository::class)]
class Payment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'payments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Student $student = null;

    #[ORM\Column(length: 255)]
    private ?string $receiptNumber = null;

    #[ORM\ManyToOne(inversedBy: 'payments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?PaymentSetting $priceSetting = null;

    #[ORM\Column(nullable: true)]
    private ?float $amount = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getReceiptNumber(): ?string
    {
        return $this->receiptNumber;
    }

    public function setReceiptNumber(string $receiptNumber): self
    {
        $this->receiptNumber = $receiptNumber;

        return $this;
    }

    public function getPriceSetting(): ?PaymentSetting
    {
        return $this->priceSetting;
    }

    public function setPriceSetting(?PaymentSetting $priceSetting): self
    {
        $this->priceSetting = $priceSetting;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(?float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }
}
