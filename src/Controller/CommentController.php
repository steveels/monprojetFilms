<?php

namespace App\Controller;

use DateTime;
use App\Entity\Films;
use App\Entity\Commentaires;
use App\Entity\LikeCommentaire;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CommentairesRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\LikeCommentaireRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/api')]
class CommentController extends AbstractController
{
    private $entityManager;
    private $serializer;
    private $commentrepo;
    private $likeCom;

    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer,CommentairesRepository $commentrepo, LikeCommentaireRepository $likeCom)
    {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
        $this->commentrepo = $commentrepo;
        $this->likeCom = $likeCom;
    }

    #[Route('/comment/{id}', name: 'create_comment', methods: ['POST'])]
    public function createPost($id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Validation des données
        if (empty($data['content'])) {
            return $this->json(['error' => 'Le contenu du commentaire est obligatoire'], 400);
        }

        $film = $this->entityManager->getRepository(Films::class)->find($id);
        if (!$film) {
            return $this->json(['error' => 'Film not found'], 404);
        }

        $user = $this->getUser();
        if (!$user) {
            return $this->json(['error' => 'Utilisateur non connecté'], 401);
        }
        $currentDateTime = new DateTime();
        $formattedDateTime = $currentDateTime->format('Y-m-d H:i:s');
        $dateTimeObject = DateTime::createFromFormat('Y-m-d H:i:s', $formattedDateTime);

        $comment = new Commentaires();
        $comment->setContent($data['content']);
        $comment->setDateCommentaire($dateTimeObject);
        $comment->setFilms($film);
        $comment->setUsers($user);

        $film->addCommentaire($comment);

        $this->entityManager->persist($comment);
        $this->entityManager->flush();
    
        $commentData = $this->serializer->normalize($comment, null, ['groups' => 'comment']);
    
        return $this->json($commentData);

       
    }

    #[Route('/comment/{id}', name: 'get_comment', methods: ['GET'])]
public function getComment($id): JsonResponse
{
    $film = $this->entityManager->getRepository(Films::class)->find($id);
    if (!$film) {
        return $this->json(['error' => 'Film non trouvé'], 404);
    }

    $comments = $film->getCommentaires();
    
    $commentData = [];
        foreach ($comments as $comment) {
    $commentData[] = [
        'id' => $comment->getId(),
        'content' => $comment->getContent(),
        'date_commentaire' => $comment->getDateCommentaire()->format('Y-m-d H:i:s'),
        'user'=> $comment->getUsers()->getFirstname(),

        // Ajoutez d'autres propriétés si nécessaire
    ];
}
    return $this->json($commentData);
}

#[Route('/comment/{id}', name: 'delete_comment', methods: ['DELETE'])]
        public function deleteCommment($id): JsonResponse
        {
            $post = $this->commentrepo->find($id);
            if (!$post) {
                return $this->json(['message' => 'Post not found'], Response::HTTP_NOT_FOUND);
            }

            $this->entityManager->remove($post);
            $this->entityManager->flush();

            return new JsonResponse(['message' => 'Post deleted'], Response::HTTP_OK);
        }



        #[Route('/comment/{id}/likeCom', name: 'like_comment', methods: ['POST'])]
        public function likeComment($id, Request $request): JsonResponse
        {
            $data = json_decode($request->getContent(), true);
            $commentaires = $this->commentrepo->find($id);
            if (!$commentaires) {
                return $this->json(['message' => 'Comment not found'], Response::HTTP_NOT_FOUND);
            }
            $user = $this->getUser();
            if (!$user) {
                return $this->json(['message' => 'User not authenticated'], Response::HTTP_UNAUTHORIZED);
            }
            $like = $this->likeCom->findOneBy(['LikecomId' => $commentaires, 'likeUser' => $user]);
            if ($like) {
                // Le commentaire est déjà liké par l'utilisateur
                return $this->json(['message' => 'Commentaire déjà liké'], Response::HTTP_BAD_REQUEST);
            }
            $likecomm = new LikeCommentaire();
            $likecomm->setLikeUser($user);
            $likecomm->setLikecomId($commentaires);
            $likecomm->setDateTime(new \DateTime());
        
            $this->entityManager->persist($likecomm);
            $this->entityManager->flush();
        
            return new JsonResponse(['message' => 'Commentaire liké avec succès'], Response::HTTP_OK);

        }

        #[Route('/comment/{id}/unlikeCom', name: 'unlike_comment', methods: ['POST'])]
        public function unlikeComment($id, Request $request): JsonResponse
        {
            $data = json_decode($request->getContent(), true);
            $commentaires = $this->commentrepo->find($id);
            if (!$commentaires) {
                return $this->json(['message' => 'Comment not found'], Response::HTTP_NOT_FOUND);
            }
            $user = $this->getUser();
            if (!$user) {
                return $this->json(['message' => 'User not authenticated'], Response::HTTP_UNAUTHORIZED);
            }
            $like = $this->likeCom->findOneBy(['LikecomId' => $commentaires, 'likeUser' => $user]);
            if (!$like) {
                // Le commentaire n'est pas déjà liké par l'utilisateur
                return $this->json(['message' => 'Commentaire non liké'], Response::HTTP_BAD_REQUEST);
            }
            $this->entityManager->remove($like);
            $this->entityManager->flush();
            return new JsonResponse(['message' => 'Commentaire déliké avec succès'], Response::HTTP_OK);

        }

}
