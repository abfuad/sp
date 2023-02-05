<?php

namespace App\Entity;

use App\Repository\PaymentYearRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    public function __construct()
    {
        $this->paymentSettings = new ArrayCollection();
        $this->students = new ArrayCollection();
        $this->studentRegistrations = new ArrayCollection();
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
}
