<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Services\UserManagerService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AuthController extends Controller
{
    /**
    * @Route("/register", name="user_registration")
    */
    public function register(Request $request,
                             UserPasswordEncoderInterface $passwordEncoder,
                             UserManagerService $userManager)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            //  make a listener
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            $userManager->addNewUser($user);


            return $this->redirectToRoute('homepage');
        }

        return $this->render(
            'default/register.html.twig',
            ['form' => $form->createView()]
        );
    }


    /**
     * @Route("/login", name="login")
     */
    public function login() {}
}
