<?php

namespace App\Entity;

use App\Repository\UserEntityRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\MappedSuperclass;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
#[MappedSuperclass]
// #[UniqueEntity('phone')]

class UserEntity  extends BaseEntity
{
    #[Assert\Regex(
        pattern: '/\d/',
        match: false,
        message: 'Your name cannot contain a number',
    )]
    #[Assert\Length(
        min: 3,
        max: 50,
        minMessage: 'Your first name must be at least {{ limit }} characters long',
        maxMessage: 'Your first name cannot be longer than {{ limit }} characters',
    )]

    #[ORM\Column(length: 255)]
    private ?string $firstName = null;
    #[Assert\Regex(
        pattern: '/\d/',
        match: false,
        message: 'Your middle name cannot contain a number',
    )]
    #[Assert\Length(
        min: 3,
        max: 50,
        minMessage: 'Your middle name must be at least {{ limit }} characters long',
        maxMessage: 'Your middle name cannot be longer than {{ limit }} characters',
    )]

    #[ORM\Column(length: 255)]
    private ?string $middleName = null;
    #[Assert\Regex(
        pattern: '/\d/',
        match: false,
        message: 'Your last name cannot contain a number',
    )]
    #[Assert\Length(
        min: 3,
        max: 50,
        minMessage: 'Your last name must be at least {{ limit }} characters long',
        maxMessage: 'Your last name cannot be longer than {{ limit }} characters',
    )]

    #[ORM\Column(length: 255)]
    private ?string $lastName = null;

    #[ORM\Column(length: 255,name:'phone',type:'string',unique:false,nullable:true)]
    private ?string $phone = null;

    

    
   
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $kebele = null;

    #[ORM\Column(length: 255)]
    private ?string $sex = null;
    #[Assert\Positive]
    #[ORM\Column(nullable: true)]
    private ?int $age = null;

    public function getFullName(): ?string
    {
        return $this->firstName." ".$this->middleName." ".$this->lastName;
    }
  public function __toString()
  {
    return $this->getFullName();
  }
    
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getMiddleName(): ?string
    {
        return $this->middleName;
    }

    public function setMiddleName(string $middleName): self
    {
        $this->middleName = $middleName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }



    public function getKebele(): ?string
    {
        return $this->kebele;
    }

    public function setKebele(?string $kebele): self
    {
        $this->kebele = $kebele;

        return $this;
    }

    public function getSex(): ?string
    {
        return $this->sex;
    }

    public function setSex(string $sex): self
    {
        $this->sex = $sex;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): self
    {
        $this->age = $age;

        return $this;
    }
}
