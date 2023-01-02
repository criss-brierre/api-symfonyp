<?php
namespace App\Controller;
use App\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentification\AuthentificationUtils;

class SecurityController extends AbstractController {
    #[Route(path: 'api/login',name :'api_login',methods:
    ['POST'])]
    public function apiLogin(){
        $user = $this->getUser();
        return $this->json([
            'email' => $user->getUserIdentifier()(),
            'roles' => $user->getRoles()
        ]);
    }
}

?>