<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterController extends AbstractController
{
    #[Route('/register', name: 'app_register', methods: ['POST'])]
    public function index(Request $request, UserPasswordHasherInterface  $passwordEncoder, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $user = new User();
        $user->setEmail($data['email']);
        $user->setFirstName($data['firstName']);
        $user->setLastName($data['lastName']);
        $user->setPhoneNumber($data['phoneNumber']);
        $user->setPassword($passwordEncoder->hashPassword($user, $data['password']));
        $user->setRoles(['ROLE_USER']);
        $em->persist($user);
        $em->flush();

        return new JsonResponse(['message' => 'User created'], 201);
    }
}
