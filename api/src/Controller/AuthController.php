<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/api', name: 'app_auth')]
class AuthController extends AbstractController
{

    #[Route('/register', name: 'app_auth', methods: "POST")]
    public function index(ManagerRegistry $doctrine, Request $request, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        // prepare entity manager
        $em = $doctrine->getManager();
        //extract request body
        $body = json_decode($request->getContent());

        // map the properties to variables
        $email = $body->email;
        $plaintextPassword = $body->password;
        $firstName = $body->firstName;
        $lastName = $body->lastName;

        // create user entity and hash ( this would normally create a salt idk )
        $user = new User();
        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $plaintextPassword
        );
        // fill the user entity
        $user->setPassword($hashedPassword);
        $user->setEmail($email);
        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        // save to db
        $em->persist($user);
        $em->flush();

        // inform the user
        return $this->json(['message' => 'Registered Successfully']);
    }
}
