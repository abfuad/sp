<?php

namespace App\Entity;

use App\Repository\PaymentSettingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaymentSettingRepository::class)]
class PaymentSetting extends CommonEntity
{
  

    #[ORM\ManyToOne(inversedBy: 'paymentSettings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?PaymentMonth $month = null;

    #[ORM\ManyToOne(inversedBy: 'paymentSettings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?PaymentYear $year = null;

    #[ORM\Column]
    private ?float $amount = null;

    #[ORM\OneToMany(mappedBy: 'priceSetting', targetEntity: Payment::class)]
    private Collection $payments;

    public function __construct()
    {
        $this->payments = new ArrayCollection();
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

    public function getYear(): ?PaymentYear
    {
        return $this->year;
    }

    public function setYear(?PaymentYear $year): self
    {
        $this->year = $year;

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

    /**
     * @return Collection<int, Payment>
     */
    public function getPayments(): Collection
    {
        return $this->payments;
    }

    public function addPayment(Payment $payment): self
    {
        if (!$this->payments->contains($payment)) {
            $this->payments->add($payment);
            $payment->setPriceSetting($this);
        }

        return $this;
    }

    public function removePayment(Payment $payment): self
    {
        if ($this->payments->removeElement($payment)) {
            // set the owning side to null (unless already changed)
            if ($payment->getPriceSetting() === $this) {
                $payment->setPriceSetting(null);
            }
        }

        return $this;
    }
}
