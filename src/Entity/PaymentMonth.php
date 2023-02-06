<?php

namespace App\Entity;

use App\Repository\PaymentMonthRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaymentMonthRepository::class)]
class PaymentMonth  extends CommonEntity
{
  
    #[ORM\OneToMany(mappedBy: 'month', targetEntity: Payment::class)]
    private Collection $payments;

    // #[ORM\OneToMany(mappedBy: 'month', targetEntity: PaymentSetting::class)]
    // private Collection $paymentSettings;

    public function __construct()
    {
        // $this->paymentSettings = new ArrayCollection();
        $this->payments = new ArrayCollection();
    }

    
    // /**
    //  * @return Collection<int, PaymentSetting>
    //  */
    // public function getPaymentSettings(): Collection
    // {
    //     return $this->paymentSettings;
    // }

    // public function addPaymentSetting(PaymentSetting $paymentSetting): self
    // {
    //     if (!$this->paymentSettings->contains($paymentSetting)) {
    //         $this->paymentSettings->add($paymentSetting);
    //         $paymentSetting->setMonth($this);
    //     }

    //     return $this;
    // }

    // public function removePaymentSetting(PaymentSetting $paymentSetting): self
    // {
    //     if ($this->paymentSettings->removeElement($paymentSetting)) {
    //         // set the owning side to null (unless already changed)
    //         if ($paymentSetting->getMonth() === $this) {
    //             $paymentSetting->setMonth(null);
    //         }
    //     }

    //     return $this;
    // }

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
            $payment->setMonth($this);
        }

        return $this;
    }

    public function removePayment(Payment $payment): self
    {
        if ($this->payments->removeElement($payment)) {
            // set the owning side to null (unless already changed)
            if ($payment->getMonth() === $this) {
                $payment->setMonth(null);
            }
        }

        return $this;
    }
}
