<?php

namespace App\Entity;

use App\Repository\PaymentMonthRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaymentMonthRepository::class)]
class PaymentMonth  extends CommonEntity
{
  
    #[ORM\OneToMany(mappedBy: 'month', targetEntity: PaymentSetting::class)]
    private Collection $paymentSettings;

    public function __construct()
    {
        $this->paymentSettings = new ArrayCollection();
    }

    
    /**
     * @return Collection<int, PaymentSetting>
     */
    public function getPaymentSettings(): Collection
    {
        return $this->paymentSettings;
    }

    public function addPaymentSetting(PaymentSetting $paymentSetting): self
    {
        if (!$this->paymentSettings->contains($paymentSetting)) {
            $this->paymentSettings->add($paymentSetting);
            $paymentSetting->setMonth($this);
        }

        return $this;
    }

    public function removePaymentSetting(PaymentSetting $paymentSetting): self
    {
        if ($this->paymentSettings->removeElement($paymentSetting)) {
            // set the owning side to null (unless already changed)
            if ($paymentSetting->getMonth() === $this) {
                $paymentSetting->setMonth(null);
            }
        }

        return $this;
    }
}
