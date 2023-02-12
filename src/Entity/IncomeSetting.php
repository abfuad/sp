<?php

namespace App\Entity;

use App\Repository\IncomeSettingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IncomeSettingRepository::class)]
class IncomeSetting extends CommonEntity
{
   
    #[ORM\ManyToOne(inversedBy: 'incomeSettings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?IncomeType $type = null;

    #[ORM\ManyToOne(inversedBy: 'incomeSettings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?PaymentYear $year = null;

    #[ORM\OneToMany(mappedBy: 'type', targetEntity: Income::class)]
    private Collection $incomes;

    public function __construct()
    {
        $this->incomes = new ArrayCollection();
    }

   

    public function getType(): ?IncomeType
    {
        return $this->type;
    }

    public function setType(?IncomeType $type): self
    {
        $this->type = $type;

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
            $income->setType($this);
        }

        return $this;
    }

    public function removeIncome(Income $income): self
    {
        if ($this->incomes->removeElement($income)) {
            // set the owning side to null (unless already changed)
            if ($income->getType() === $this) {
                $income->setType(null);
            }
        }

        return $this;
    }
}
