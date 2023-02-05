<?php
namespace App\Controller;


use App\Helper\PrintHelper;
use Doctrine\ORM\EntityManagerInterface;

trait BaseControllerTrait {
 
    public function __construct(private EntityManagerInterface $em,private PrintHelper $printHelper)
    {
        
    }

  
}