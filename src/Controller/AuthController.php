<?php

/**
 * Created by PhpStorm.
 * User: hicham benkachoud
 * Date: 06/01/2020
 * Time: 20:39
 */

namespace App\Controller;


use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class AuthController extends ApiController
{

    public function register(Request $request, ManagerRegistry $doctrine, UserPasswordHasherInterface  $passwordHasher)
    {        
        $entityManager = $doctrine->getManager();
        $request = $this->transformJsonBody($request);

        if (
            !$request ||
            !filter_var($request->get('email'), FILTER_VALIDATE_EMAIL) ||
            !filter_var($request->get('phone'), FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[+]{1}[0-9]{7,12}$/"))) ||
            !filter_var($request->get('firstName'), FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[a-zA-Zа-яА-Я]{2,}$/"))) ||
            !filter_var($request->get('lastName'), FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[a-zA-Zа-яА-Я]{2,}$/"))) ||
            !filter_var($request->get('password'), FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/"))) ||
            !$request->get('roles')
        ) {
            return $this->respondValidationError("Invalid data");
        }
        $user = new User();
        $user->setEmail($request->get('email'));
        $user->setPhone($request->get('phone'));
        $user->setFirstName($request->get('firstName'));
        $user->setLastName($request->get('lastName'));
        $user->setRoles($request->get('roles'));
        $user->setPassword($passwordHasher->hashPassword($user, $request->get('password')));
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->respondWithSuccess(sprintf('User %s successfully created', $user->getEmail()));
    }

    /**
     * @param UserInterface $user
     * @param JWTTokenManagerInterface $JWTManager
     * @return JsonResponse
     */
    public function getTokenUser(UserInterface $user, JWTTokenManagerInterface $JWTManager)
    {
        return new JsonResponse(['token' => $JWTManager->create($user)
    
    ]);
    }
}
