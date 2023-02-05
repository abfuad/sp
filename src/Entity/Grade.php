<?php

namespace App\Entity;

use App\Repository\GradeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GradeRepository::class)]
class Grade extends CommonEntity
{
  

    #[ORM\OneToMany(mappedBy: 'grade', targetEntity: Student::class)]
    private Collection $students;

    #[ORM\OneToMany(mappedBy: 'grade', targetEntity: StudentRegistration::class)]
    private Collection $studentRegistrations;

    public function __construct()
    {
        $this->students = new ArrayCollection();
        $this->studentRegistrations = new ArrayCollection();
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
            $student->setGrade($this);
        }

        return $this;
    }

    public function removeStudent(Student $student): self
    {
        if ($this->students->removeElement($student)) {
            // set the owning side to null (unless already changed)
            if ($student->getGrade() === $this) {
                $student->setGrade(null);
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
            $studentRegistration->setGrade($this);
        }

        return $this;
    }

    public function removeStudentRegistration(StudentRegistration $studentRegistration): self
    {
        if ($this->studentRegistrations->removeElement($studentRegistration)) {
            // set the owning side to null (unless already changed)
            if ($studentRegistration->getGrade() === $this) {
                $studentRegistration->setGrade(null);
            }
        }

        return $this;
    }
}
