<?php

namespace App\Entity;

use App\Repository\StudentRegistrationRepository;
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
}
