<?php

namespace App\Helper;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Andegna\DateTime as AD;
use Andegna\DateTimeFactory ;
class UserHelper
{

    public function __construct(private EntityManagerInterface $em, private UserPasswordHasherInterface $userPasswordHasher)
    {
    }

    function getUsername($first, $middle)
    {
        $string_name = $first . " " . $middle;
        $rand_no = 10;
        $userRepository = $this->em->getRepository(User::class);
        while (true) {
            $username_parts = array_filter(explode(" ", strtolower($string_name))); //explode and lowercase name
            $username_parts = array_slice($username_parts, 0, 2); //return only first two arry part
            $part1 = (!empty($username_parts[0])) ? substr($username_parts[0], 0, rand(4, 6)) : ""; //cut fi rs t  name to 8 letters
            $part2 = (!empty($username_parts[1])) ? substr($username_parts[1], 0, rand(3, 5)) : ""; //cut se co n d name to 5 letters
            $username = $part1 . $part2; //str _shuffle to randomly shuffle all characters 
            if (!$userRepository->findOneBy(['username' => $username]))
                break;
        }
        return $username;
    }

    static function getPassword()
    {
        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }
    static function toEth($date){
        $ethipic = new AD($date);
       return $ethipic->getYear();
    }
    static function setExecutionTime(){
        ini_set('max_execution_time', '300'); 
        // ini_set("pcre.backtrack_limit", "2000000000000");
        ini_set('memory_limit', '3G'); // 3 Gigabytes
       return 1;;
    }
    // public function getEmployeeIDNumber($joiningDate,$prefix)
    // {
        // $year=Utils::fromGCToEth($joiningDate)->format("y");
   
    //     $employeeRepository = $this->em->getRepository(Employee::class);
    //     $lastEmployee=$employeeRepository->findOneBy([],['id'=>'DESC'],1,0);
    //     if($lastEmployee)
    //     $lastId=$lastEmployee->getEmployeeId();
    //     else
    //     $lastId='MHAA-00000-12';
    //     while (true) {
    //         $Id=(int)(explode('-',$lastId)[1]);
    //         $Id++;
    //         $ID=sprintf("%05d", $Id);
            
    //         $employeeId = $prefix . $ID.'-'.$year; //str _shuffle to randomly shuffle all characters 
    //         if (!$employeeRepository->findOneBy(['employeeId' => $employeeId]))
    //             break;
    //     }
    //     return $employeeId;
    // }
    public function getHashedPassWord($user, $password)
    {
        return $this->userPasswordHasher->hashPassword($user, $password);
    }
    public function isValid($user, $password)
    {
        return $this->userPasswordHasher->isPasswordValid($user, $password);
    }
}
