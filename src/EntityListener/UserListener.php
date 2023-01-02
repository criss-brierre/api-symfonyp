<?php
namespace App\EntityListener;

use App\Entity\Users;
use DateTimeImmutable;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserListener {

    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher){
        $this->hasher= $hasher;
    }

    public function prePersist(Users $user){
        $this->encodePassword($user);
        $this->setTheCreatedAt($user);
    }

    public function preUpdate(Users $user){
        $this->encodePassword($user);
        $this->setTheUpdatedAt($user);
    }

    public function postUpdate(Users $user){
        $this->setTheUpdatedAt($user);
    }

    public function encodePassword(Users $user){
        if ($user->getPlainPassword() === null) {
            return;
        }
        $user->setPassword(
            $this->hasher->hashPassword(
            $user,
            $user->getPlainPassword()
            )
            );

        $user->setPlainPassword("");
    }

    public function setTheCreatedAt(Users $user){
      
        $DateTimeImmutableNow = new DateTimeImmutable();
        $user->setCreatedAt(
            $DateTimeImmutableNow
            );
    }

    public function setTheUpdatedAt(Users $user){
       
        $DateTimeImmutableNow = new DateTimeImmutable();
        $user->setUpdatedAt(
            $DateTimeImmutableNow
            );
    }

}
?>