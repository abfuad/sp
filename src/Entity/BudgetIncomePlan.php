<?php

namespace App\Entity;

use App\Repository\BudgetIncomePlanRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BudgetIncomePlanRepository::class)]
class BudgetIncomePlan extends CommonEntity
{
    
    #[ORM\ManyToOne(inversedBy: 'budgetIncomePlans')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Budget $budget = null;

    #[ORM\ManyToOne(inversedBy: 'budgetIncomePlans')]
    #[ORM\JoinColumn(nullable: false)]
    private ?IncomeType $type = null;

    #[ORM\Column]
    private ?float $planValue = null;

    #[ORM\Column(nullable: true)]
    private ?float $actualValue = 0;

    #[ORM\OneToMany(mappedBy: 'incomePlan', targetEntity: Income::class)]
    private Collection $incomes;

    public function __construct()
    {
        $this->incomes = new ArrayCollection();
    }


    public function getBudget(): ?Budget
    {
        return $this->budget;
    }

    public function setBudget(?Budget $budget): self
    {
        $this->budget = $budget;

        return $this;
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

    public function getPlanValue(): ?float
    {
        return $this->planValue;
    }

    public function setPlanValue(float $planValue): self
    {
        $this->planValue = $planValue;

        return $this;
    }

    public function getActualValue(): ?float
    {
        return $this->actualValue;
    }

    public function setActualValue(?float $actualValue): self
    {
        $this->actualValue = $actualValue;

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
            $income->setIncomePlan($this);
        }

        return $this;
    }

    public function removeIncome(Income $income): self
    {
        if ($this->incomes->removeElement($income)) {
            // set the owning side to null (unless already changed)
            if ($income->getIncomePlan() === $this) {
                $income->setIncomePlan(null);
            }
        }

        return $this;
    }
}
