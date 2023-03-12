<?php

namespace App\Twig\Extension;

use App\Entity\Credit;
use App\Entity\Expense;
use App\Entity\Income;
use App\Entity\PenalityFee;
use App\Twig\Runtime\AppExtensionRuntime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    private $entityManager;
    private $utils;
    private $security;

    public function __construct(EntityManagerInterface $entityManager,private \Twig\Environment $templating,Security $security)
    {
        $this->entityManager = $entityManager;
        
        $this->security=$security;
      
        
    }

    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('filter_name', [AppExtensionRuntime::class, 'doSomething']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            // new TwigFunction('function_name', [AppExtensionRuntime::class, 'doSomething']),
            new TwigFunction('getBudgetExpense', [$this, 'getBudgetExpense']),
            new TwigFunction('getIncomeBudget', [$this, 'getIncomeBudget']),
            new TwigFunction('getTotalExpense', [$this, 'getTotalExpense']),
            new TwigFunction('getTotalIncome', [$this, 'getTotalIncome']),
            new TwigFunction('getUserCredit', [$this, 'getUserCredit']),
            new TwigFunction('getUserPenalityFee', [$this, 'getUserPenalityFee']),




        ];
    }
    public function getBudgetExpense($expensePlan){
        $expense=$this->entityManager->getRepository(Expense::class)->expenseReport(['expensePlan'=>$expensePlan]);
        return $expense;
    }
    public function getIncomeBudget($incomePlan){
        $total=$this->entityManager->getRepository(Income::class)->getIncomeReport(['incomePlan'=>$incomePlan]);
        return $total;
    }
    public function getTotalExpense($year){
        $expense=$this->entityManager->getRepository(Expense::class)->expenseReport(['year'=>$year]);
        return $expense;
    }
    public function getTotalIncome($year){
        $total=$this->entityManager->getRepository(Income::class)->getIncomeReport(['year'=>$year]);
        return $total;
    }
    public function getUserCredit($user){
        $expense=$this->entityManager->getRepository(Credit::class)->getUserCredit($user);
        return $expense;
    }
    public function getUserPenalityFee($user){
        $total=$this->entityManager->getRepository(PenalityFee::class)->getUserPenalityFee($user);
        return $total;
    }
}
