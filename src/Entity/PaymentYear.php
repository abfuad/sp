<?php

namespace App\Entity;

use App\Repository\PaymentYearRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaymentYearRepository::class)]
class PaymentYear  extends CommonEntity
{
   
    #[ORM\OneToMany(mappedBy: 'year', targetEntity: PaymentSetting::class)]
    private Collection $paymentSettings;

    #[ORM\OneToMany(mappedBy: 'entranceYear', targetEntity: Student::class)]
    private Collection $students;

    #[ORM\OneToMany(mappedBy: 'year', targetEntity: StudentRegistration::class)]
    private Collection $studentRegistrations;

    #[ORM\OneToOne(mappedBy: 'year', cascade: ['persist', 'remove'])]
    private ?Budget $budget = null;

    // #[ORM\OneToMany(mappedBy: 'year', targetEntity: IncomeSetting::class)]
    // private Collection $incomeSettings;

    #[ORM\OneToMany(mappedBy: 'year', targetEntity: Income::class)]
    private Collection $incomes;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $startAt = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $endAt = null;

    public function __construct()
    {
        $this->paymentSettings = new ArrayCollection();
        $this->students = new ArrayCollection();
        $this->studentRegistrations = new ArrayCollection();
        // $this->incomeSettings = new ArrayCollection();
        $this->incomes = new ArrayCollection();
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
            $paymentSetting->setYear($this);
        }

        return $this;
    }

    public function removePaymentSetting(PaymentSetting $paymentSetting): self
    {
        if ($this->paymentSettings->removeElement($paymentSetting)) {
            // set the owning side to null (unless already changed)
            if ($paymentSetting->getYear() === $this) {
                $paymentSetting->setYear(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Student>
     */
    public function getStudents(): Collection
    {
        return $this->students;
    }

    public function addStudent(Student $student): self
    {
        if (!$this->students->contains($student)) {
            $this->students->add($student);
            $student->setEntranceYear($this);
        }

        return $this;
    }

    public function removeStudent(Student $student): self
    {
        if ($this->students->removeElement($student)) {
            // set the owning side to null (unless already changed)
            if ($student->getEntranceYear() === $this) {
                $student->setEntranceYear(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, StudentRegistration>
     */
    public function getStudentRegistrations(): Collection
    {
        return $this->studentRegistrations;
    }

    public function addStudentRegistration(StudentRegistration $studentRegistration): self
    {
        if (!$this->studentRegistrations->contains($studentRegistration)) {
            $this->studentRegistrations->add($studentRegistration);
            $studentRegistration->setYear($this);
        }

        return $this;
    }

    public function removeStudentRegistration(StudentRegistration $studentRegistration): self
    {
        if ($this->studentRegistrations->removeElement($studentRegistration)) {
            // set the owning side to null (unless already changed)
            if ($studentRegistration->getYear() === $this) {
                $studentRegistration->setYear(null);
            }
        }

        return $this;
    }

    public function getBudget(): ?Budget
    {
        return $this->budget;
    }

    public function setBudget(?Budget $budget): self
    {
        // unset the owning side of the relation if necessary
        if ($budget === null && $this->budget !== null) {
            $this->budget->setYear(null);
        }

        // set the owning side of the relation if necessary
        if ($budget !== null && $budget->getYear() !== $this) {
            $budget->setYear($this);
        }

        $this->budget = $budget;

        return $this;
    }

    
    // public function removeIncomeSetting(IncomeSetting $incomeSetting): self
    // {
    //     if ($this->incomeSettings->removeElement($incomeSetting)) {
    //         // set the owning side to null (unless already changed)
    //         if ($incomeSetting->getYear() === $this) {
    //             $incomeSetting->setYear(null);
    //         }
    //     }

    //     return $this;
    // }

    /**
     * @return Collection<int, Income>
     */
    public function getIncomes(): Collection
    {
        return $this->incomes;
    }

    public function addIncome(Income $income): self
    {
        if (!$this->incomes->contains($income)) {
            $this->incomes->add($income);
            $income->setYear($this);
        }

        return $this;
    }

    public function removeIncome(Income $income): self
    {
        if ($this->incomes->removeElement($income)) {
            // set the owning side to null (unless already changed)
            if ($income->getYear() === $this) {
                $income->setYear(null);
            }
        }

        return $this;
    }

    public function getStartAt(): ?\DateTimeInterface
    {
        return $this->startAt;
    }

    public function setStartAt(?\DateTimeInterface $startAt): self
    {
        $this->startAt = $startAt;

        return $this;
    }

    public function getEndAt(): ?\DateTimeInterface
    {
        return $this->endAt;
    }

    public function setEndAt(?\DateTimeInterface $endAt): self
    {
        $this->endAt = $endAt;

        return $this;
    }
}
