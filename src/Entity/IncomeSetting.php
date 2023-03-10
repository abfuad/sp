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

    #[ORM\OneToMany(mappedBy: 'type', targetEntity: Income::class)]
    private Collection $incomes;

    #[ORM\Column(nullable: true)]
    private ?float $fee = null;

    #[ORM\OneToMany(mappedBy: 'type', targetEntity: SpecialIncome::class)]
    private Collection $specialIncomes;

    #[ORM\OneToMany(mappedBy: 'type', targetEntity: PenalityFee::class)]
    private Collection $penalityFees;

    public function __construct()
    {
        $this->incomes = new ArrayCollection();
        $this->specialIncomes = new ArrayCollection();
        $this->penalityFees = new ArrayCollection();
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

    public function getFee(): ?float
    {
        return $this->fee;
    }

    public function setFee(?float $fee): self
    {
        $this->fee = $fee;

        return $this;
    }

    /**
     * @return Collection<int, SpecialIncome>
     */
    public function getSpecialIncomes(): Collection
    {
        return $this->specialIncomes;
    }

    public function addSpecialIncome(SpecialIncome $specialIncome): self
    {
        if (!$this->specialIncomes->contains($specialIncome)) {
            $this->specialIncomes->add($specialIncome);
            $specialIncome->setType($this);
        }

        return $this;
    }

    public function removeSpecialIncome(SpecialIncome $specialIncome): self
    {
        if ($this->specialIncomes->removeElement($specialIncome)) {
            // set the owning side to null (unless already changed)
            if ($specialIncome->getType() === $this) {
                $specialIncome->setType(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PenalityFee>
     */
    public function getPenalityFees(): Collection
    {
        return $this->penalityFees;
    }

    public function addPenalityFee(PenalityFee $penalityFee): self
    {
        if (!$this->penalityFees->contains($penalityFee)) {
            $this->penalityFees->add($penalityFee);
            $penalityFee->setType($this);
        }

        return $this;
    }

    public function removePenalityFee(PenalityFee $penalityFee): self
    {
        if ($this->penalityFees->removeElement($penalityFee)) {
            // set the owning side to null (unless already changed)
            if ($penalityFee->getType() === $this) {
                $penalityFee->setType(null);
            }
        }

        return $this;
    }
}
