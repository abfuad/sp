<?php

namespace App\Entity;

use App\Repository\StudentRegistrationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StudentRegistrationRepository::class)]
class StudentRegistration extends BaseEntity
{
  
    #[ORM\ManyToOne(inversedBy: 'studentRegistrations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?PaymentYear $year = null;

    #[ORM\ManyToOne(inversedBy: 'studentRegistrations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Student $student = null;

    #[ORM\ManyToOne(inversedBy: 'studentRegistrations')]
    private ?Grade $grade = null;

    #[ORM\Column]
    private ?bool $isFree = null;

    #[ORM\OneToMany(mappedBy: 'registration', targetEntity: Payment::class)]
    private Collection $payments;

    #[ORM\Column(nullable: true)]
    private ?bool $isCompleted = null;

    public function __construct()
    {
        $this->payments = new ArrayCollection();
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

    public function getStudent(): ?Student
    {
        return $this->student;
    }

    public function setStudent(?Student $student): self
    {
        $this->student = $student;

        return $this;
    }

    public function getGrade(): ?Grade
    {
        return $this->grade;
    }

    public function setGrade(?Grade $grade): self
    {
        $this->grade = $grade;

        return $this;
    }

    public function isIsFree(): ?bool
    {
        return $this->isFree;
    }

    public function setIsFree(bool $isFree): self
    {
        $this->isFree = $isFree;

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
            $payment->setRegistration($this);
        }

        return $this;
    }

    public function removePayment(Payment $payment): self
    {
        if ($this->payments->removeElement($payment)) {
            // set the owning side to null (unless already changed)
            if ($payment->getRegistration() === $this) {
                $payment->setRegistration(null);
            }
        }

        return $this;
    }

    public function isIsCompleted(): ?bool
    {
        return $this->isCompleted;
    }

    public function setIsCompleted(?bool $isCompleted): self
    {
        $this->isCompleted = $isCompleted;

        return $this;
    }
}
