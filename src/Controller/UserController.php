<?php

namespace App\Controller;

use App\Entity\Users;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

#[Route('/api')]

class UserController extends AbstractController
{
   private UserPasswordHasherInterface $passwordHasher;
   private EntityManagerInterface $em;
   private UsersRepository $usersRepository;
   private JWTTokenManagerInterface $JWTManager;

   public function __construct(UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em, UsersRepository $usersRepository,JWTTokenManagerInterface $JWTManager)
   {
    $this->passwordHasher = $passwordHasher;
    $this->em = $em;
    $this->usersRepository = $usersRepository;
    $this->JWTManager = $JWTManager;

   }

    #[Route('/register', name: 'app_register', methods: ['POST'])]
    public function register(Request $request): JsonResponse
    {
        //recup les données du front
        $data = json_decode($request->getContent(), true);
        //Creér user et hasher le mot de passe
        $user = new Users();
        $user->setEmail($data['email']);
        $user->setFirstname($data['name']);
        $user->setName($data['firstname']);
        $user->setPassword($this->passwordHasher->hashPassword( $user, $data['password']));
        $user->setRoles(['ROLE_USER']);
        $this->em->persist($user);
        $this->em->flush();

        return $this->json([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'firstname' => $user->getFirstname(),
            'name' => $user->getName(),
            'password' =>$user->getPassword(),
            'role' => $user->getRoles()
            
        ],JsonResponse::HTTP_CREATED);
    }

    #[Route('/login', name: 'app_login', methods: ['POST'])]
    public function login(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $user = $this->usersRepository->findOneBy(['email' => $data['email']]);
        if ($user && $user->isBanni()) {
            return new JsonResponse(['message' => 'Votre compte est banni'], JsonResponse::HTTP_FORBIDDEN);
        }
        if(!$user || !$this->passwordHasher->isPasswordValid($user, $data['password'])){
            return new JsonResponse(['message' => 'Email ou mot de passe incorrect'], JsonResponse::HTTP_UNAUTHORIZED);
        }
        $token = $this->JWTManager->create($user);
        $roles = $user->getRoles();
        $res = new JsonResponse([
            'message'=>'Connexion réussie',
            'roles' => $roles,
            
        ]);
        $res->headers->setCookie(new Cookie('BEARER', $token,time() + 3600, '/', null,true,true));
        return $res;

    }

    #[Route('/logout', name: 'app_logout', methods: ['POST'])]
    public function logout(Request $request): JsonResponse
    {
        $res= new JsonResponse(['message' => 'Déconnexion réussie']);
        //Supprimer le cookie
        $res->headers->clearCookie('BEARER');
        return $res;
    }

    #[Route('/users', name: 'app_users', methods: ['GET'])]
    public function getUsers(): JsonResponse
    {
        $users = $this->usersRepository->findAll();
        $data = [];
        foreach ($users as $user) {
            $data[] = [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'firstname' => $user->getFirstname(),
                'name' => $user->getName(),
                'bannir' => $user->isBanni(),
                'role' => $user->getRoles()
            ];
        }
        return $this->json($data);


    }



    #[Route('/users/{userId}', name: 'app_ban_user', methods: ['PATCH'])]
    public function banUser(int $userId): JsonResponse
    {
        
        $user = $this->usersRepository->find($userId);

        if (!$user) {
            return $this->json(['message' => 'Utilisateur non trouvé'], JsonResponse::HTTP_NOT_FOUND);
        }

        $user->setBanni(true);
        $this->em->flush();

        return $this->json(['message' => 'Utilisateur banni avec succès'], JsonResponse::HTTP_OK);
    }


}



