<?php

namespace App\Entity;

use App\Repository\StudentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


#[UniqueEntity('idNumber')]
#[ORM\Entity(repositoryClass: StudentRepository::class)]
class Student extends UserEntity
{

   

    #[ORM\Column(length: 255)]
    private ?string $idNumber = null;

    #[ORM\ManyToOne(inversedBy: 'students')]
    private ?PaymentYear $entranceYear = null;

    #[ORM\ManyToOne(inversedBy: 'students')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Grade $grade = null;

    #[ORM\Column]
    private ?bool $isOrphan = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $profile = null;

    #[ORM\Column]
    private ?int $status = null;

    #[ORM\OneToMany(mappedBy: 'student', targetEntity: StudentRegistration::class)]
    private Collection $studentRegistrations;

    #[ORM\OneToMany(mappedBy: 'student', targetEntity: Payment::class)]
    private Collection $payments;

    public function __construct()
    {
        $this->studentRegistrations = new ArrayCollection();
        $this->payments = new ArrayCollection();
    }

   

    public function getIdNumber(): ?string
    {
        return $this->idNumber;
    }

    public function setIdNumber(string $idNumber): self
    {
        $this->idNumber = $idNumber;

        return $this;
    }

    public function getEntranceYear(): ?PaymentYear
    {
        return $this->entranceYear;
    }

    public function setEntranceYear(?PaymentYear $entranceYear): self
    {
        $this->entranceYear = $entranceYear;

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

    public function isIsOrphan(): ?bool
    {
        return $this->isOrphan;
    }

    public function setIsOrphan(bool $isOrphan): self
    {
        $this->isOrphan = $isOrphan;

        return $this;
    }

    public function getProfile(): ?string
    {
        return $this->profile;
    }

    public function setProfile(?string $profile): self
    {
        $this->profile = $profile;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

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
            $studentRegistration->setStudent($this);
        }

        return $this;
    }

    public function removeStudentRegistration(StudentRegistration $studentRegistration): self
    {
        if ($this->studentRegistrations->removeElement($studentRegistration)) {
            // set the owning side to null (unless already changed)
            if ($studentRegistration->getStudent() === $this) {
                $studentRegistration->setStudent(null);
            }
        }

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
            $payment->setStudent($this);
        }

        return $this;
    }

    public function removePayment(Payment $payment): self
    {
        if ($this->payments->removeElement($payment)) {
            // set the owning side to null (unless already changed)
            if ($payment->getStudent() === $this) {
                $payment->setStudent(null);
            }
        }

        return $this;
    }
}
