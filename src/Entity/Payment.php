<?php

namespace App\Entity;

use App\Repository\PaymentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


#[UniqueEntity('receiptNumber')]
#[ORM\Entity(repositoryClass: PaymentRepository::class)]

class Payment extends BaseEntity
{
  
    #[ORM\ManyToOne(inversedBy: 'payments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Student $student = null;

    #[ORM\Column(length: 255,unique:true,nullable:true)]
    private ?string $receiptNumber = null;

    #[ORM\ManyToOne(inversedBy: 'payments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?PaymentSetting $priceSetting = null;

    #[ORM\Column(nullable: true)]
    private ?float $amount = null;

    #[ORM\ManyToOne(inversedBy: 'payments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?PaymentMonth $month = null;

    #[ORM\ManyToOne(inversedBy: 'payments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?StudentRegistration $registration = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isPaid = null;


    public  const  PAID =1;
    public  const  UNPAID =0;
   // public  const  COMPLETED ='COMPLETED';
    // public  const  OTHERSCHOOL ='OTHERSCHOOL';

  public const STATUS=['Paid'=>Payment::class,'Other School'=>Student::OTHERSCHOOL];
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

    public function getMonth(): ?PaymentMonth
    {
        return $this->month;
    }

    public function setMonth(?PaymentMonth $month): self
    {
        $this->month = $month;

        return $this;
    }

    public function getRegistration(): ?StudentRegistration
    {
        return $this->registration;
    }

    public function setRegistration(?StudentRegistration $registration): self
    {
        $this->registration = $registration;

        return $this;
    }

    public function isIsPaid(): ?bool
    {
        return $this->isPaid;
    }

    public function setIsPaid(?bool $isPaid): self
    {
        $this->isPaid = $isPaid;

        return $this;
    }
}
