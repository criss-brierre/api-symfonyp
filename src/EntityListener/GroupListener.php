<?php
namespace App\EntityListener;

use App\Entity\Groups;
use DateTimeImmutable;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class GroupListener {

    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher){
        $this->hasher= $hasher;
    }

    public function prePersist(Groups $group){
        $this->setTheCreatedAt($group);
    }

    public function preUpdate(Groups $group){
        $this->setTheUpdatedAt($group);
    }

    public function postUpdate(Groups $group){
        $this->setTheUpdatedAt($group);
    }


    public function setTheCreatedAt(Groups $group){
      
        $DateTimeImmutableNow = new DateTimeImmutable();
        $group->setCreatedAt(
            $DateTimeImmutableNow
            );
    }

    public function setTheUpdatedAt(Groups $user){
       
        $DateTimeImmutableNow = new DateTimeImmutable();
        $user->setUpdatedAt(
            $DateTimeImmutableNow
            );
    }

}
?>