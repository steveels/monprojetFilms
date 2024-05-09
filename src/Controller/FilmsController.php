<?php

namespace App\Controller;

use App\Entity\Films;
use App\Entity\Likes;
use App\Entity\Categories;
use App\Repository\FilmsRepository;
use App\Repository\CategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api')]

class FilmsController extends AbstractController
{ 
    
    private $filmrepo;
    private $em;
    private $categoriesRepository;

    public function __construct(FilmsRepository $filmrepo, EntityManagerInterface $em, CategoriesRepository $categoriesRepository)
    {
        $this->filmrepo = $filmrepo;
        $this->em = $em;
        $this->categoriesRepository = $categoriesRepository;
    }


    #[Route('/films', name: 'get_films', methods: ['GET'])]
    public function AllFilms(SerializerInterface $serializer): JsonResponse
    {
        $user = $this->getUser(); // Récupérer l'utilisateur connecté

        $films = $this->filmrepo->findAll();
    
        $AllfilmsData = [];
        foreach ($films as $film) {
            $liked = false;
            if ($user) {
                $like = $this->em->getRepository(Likes::class)->findOneBy([
                    'likeuser' => $user,
                    'filmslikes' => $film,
                ]);
                $liked = $like !== null;
            }
    
            $dateFormattee = null;
            $dateDeSortie = $film->getDateDeSortie();
            if ($dateDeSortie !== null) {
                $dateFormattee = $dateDeSortie->format('Y-m-d');
            }
    
            $AllfilmsData[] = [
                'id' => $film->getId(),
                'title' => $film->getTitle(),
                'content' => $film->getContent(),
                'image' => $film->getImages(),
                'date_de_sortie' => $dateFormattee,
                'liked' => $liked,
            ];
        }
    
        return $this->json($AllfilmsData);
    }
    #[Route('/films/{id}', name: 'get_detailfilm', methods: ['GET'])]
    public function FilmsId( $id, SerializerInterface $serializer): Response
    {
        $film = $this->filmrepo->find($id);
        if (!$film) {
            return $this->json(['message' => 'Film non trouvé'], 404);
        }
        $detailFilm = $serializer->serialize($film, 'json', ['groups' => 'groupe1']);
        // $detailFilm = [
        //     'title' => $film->getTitle(),
        // ];


        // return $this->json($detailFilm);
        return new Response($detailFilm , 200, ['Content-Type' => 'application/json']);
    }
    #[Route('/categories', name: 'get_categories', methods: ['GET'])]
public function getCategories(): JsonResponse
{
    $categories = $this->categoriesRepository->findAll();
    $data = [];

    foreach ($categories as $category) {
        $data[] = [
            'id' => $category->getId(),
            'name' => $category->getName()
        ];
    }

    return $this->json($data);
}

#[Route('/categories', name: 'create_categ', methods: ['POST'])]
public function createCateg(Request $request, SerializerInterface $serializer): JsonResponse
{
    $data = json_decode($request->getContent(), true);

    $category = new Categories();
    $category->setName($data['name']);

    $this->em->persist($category);
    $this->em->flush();

    return $this->json($serializer->serialize($category, 'json'));
}


#[Route('/categories/{id}', name: 'get_categorie', methods: ['GET'])]
public function getCategoriesId($id): JsonResponse
{
    $categories = $this->categoriesRepository->find($id);
    $data = [];

    foreach ($categories as $category) {
        $data[] = [
            'id' => $category->getId(),
            'name' => $category->getName()
        ];
    }

    return $this->json($data);
}
#[Route('/categories/{id}/films', name: 'get_films_by_category', methods: ['GET'])]
public function getFilmsByCategory($id): JsonResponse
{
    $category = $this->categoriesRepository->find($id);
    if (!$category) {
        return $this->json(['error' => 'Category not found'], 404);
    }

    $films = $category->getFilmscateg()->toArray();
    

    $data = [];
    foreach ($films as $film) {
        $data[] = [
            'id' => $film->getId(),
            'title' => $film->getTitle(),
            'content' => $film->getContent(),
            'date_de_sortie' => $film->getDateDeSortie()
            // Ajoutez d'autres propriétés du film si nécessaire
        ];
    }

    return $this->json($data);
}

#[Route('/categories/{id}', name: 'update_categ', methods: ['PUT'])]
public function updateCateg(Request $request,$id) :Response
{
    $data = json_decode($request->getContent(), true);
    $updateCateg = $this->categoriesRepository->find($id);
    if(!$updateCateg){
        return $this->json(['error' => 'Category not found'], 404);
    }
    $updateCateg->setName( $data['name']) ?? $updateCateg->getName();
    $this->em->persist($updateCateg);
    $this->em->flush();
    return $this->json(['message' => 'Category updated successfully']);


}

#[Route('/categories/{id}', name: 'delete_categ', methods: ['DELETE'])]
public function deleteCateg($id,Request $request): JsonResponse
{
    $category = $this->categoriesRepository->find($id);
    if (!$category) {
        return $this->json(['error' => 'Category not found'], 404);
    }
    
    
    $this->em->remove($category);
    $this->em->flush();
    return $this->json(['message' => 'Category deleted successfully']);

}



#[Route('/films', name: 'create_post', methods: ['POST'])]
public function createPost(Request $request,SerializerInterface $serializer): Response
{
    //Récuperer les données envoyées par le front sous forme de tableau associatif (true)
    $data = json_decode($request->getContent(), true);

    //Créer une nouvelle instance de  Post
    $post = new Films();
    $post->setTitle($data['title']);
    $post->setContent($data['content']);
    // Gérer le fichier d'image
    $imageFile = $request->files->get('image');
    if ($imageFile) {
        $newFilename = uniqid() . '.' . $imageFile->guessExtension();
        $imageFile->move(
            $this->getParameter('images_directory'),
            $newFilename
        );
        $post->setImages($newFilename);
    }
   // Vérifier si la catégorie existe
   $category = $this->categoriesRepository->findOneBy(['name' => $data['filmscateg']]);
   if ($category) {
       $post->setFilmscateg($category);
   } else {
       // Gérer le cas où la catégorie n'existe pas
       return $this->json(['error' => 'Catégorie non trouvée'], Response::HTTP_BAD_REQUEST);
   }
    if ($data['date_de_sortie'] !== null) {
        $dateTime = new \DateTime($data['date_de_sortie']);
        $post->setDateDeSortie($dateTime);
    } else {
        $post->setDateDeSortie(null);
    }
    $this->em->persist($post);
    $this->em->flush();
    $post = $serializer->serialize($post, 'json', ['groups' => 'groupe1']);
    return new Response($post, 200, ['Content-Type' => 'application/json']);
    

   return $this->json($post);
}

#[Route('/films/{id}', name: 'update_post', methods: ['PUT'])]
public function updatePost($id ,Request $request,SerializerInterface $serializer): Response
{
    //Récuperer les données envoyées par le front sous forme de tableau associatif (true)
    $data = json_decode($request->getContent(), true);

    //Récuperer le post
    $post = $this->filmrepo->find($id);

    //Verifie si le post existe
    if (!$post) {
        return $this->json(['message' => 'Post not found'], Response::HTTP_NOT_FOUND);
    }

    //Mofier les props du post qui ont été modifié par le front
   
    $post->setTitle($data['title'] ?? $post->getTitle());
    $post->setContent($data['content'] ?? $post->getContent());
    if ($data['date_de_sortie'] !== null) {
        $dateTime = new \DateTime($data['date_de_sortie']);
        $post->setDateDeSortie($dateTime);
    } else {
        $post->setDateDeSortie(null);
    }
  
    $this->em->persist($post);
    $this->em->flush();
    $post = $serializer->serialize($post, 'json', ['groups' => 'groupe1']);
    return new Response($post, 200, ['Content-Type' => 'application/json']);

    

  
}
#[Route('/films/{id}', name: 'delete_post', methods: ['DELETE'])]
        public function deletePost($id): JsonResponse
        {
            $post = $this->filmrepo->find($id);
            if (!$post) {
                return $this->json(['message' => 'Post not found'], Response::HTTP_NOT_FOUND);
            }

            $this->em->remove($post);
            $this->em->flush();

            return new JsonResponse(['message' => 'Post deleted'], Response::HTTP_OK);
        }

 #[Route('/films/{id}/like', name: 'like_post', methods: ['POST'])]
        public function Like($id, Request $request): JsonResponse
        {
            $data = json_decode($request->getContent(), true);
            $post = $this->filmrepo->find($id);
            if (!$post) {
                return $this->json(['message' => 'film not found'], Response::HTTP_NOT_FOUND);
            }
            $user = $this->getUser();
            $existingLike = $this->em->getRepository(Likes::class)->findOneBy([
                'likeuser' => $user,
                'filmslikes' => $post,
            ]);
        
            if ($existingLike) {
                return $this->json(['message' => 'Vous avez déjà liké ce film'], 400);
            }
            $like= new Likes();
            $like->setDatetime(new \DateTime());
            $like->setLikeuser($user);
            $like->setFilmslikes($post);



            $this->em->persist($like);
                $this->em->flush();

            return new JsonResponse(['message' => ' Film liké avec succès'], Response::HTTP_OK);
        }


        #[Route('/films/{id}/unlike', name: 'unlike_film', methods: ['POST'])]
public function unlikeFilm($id, Request $request): JsonResponse
{
    $film = $this->filmrepo->find($id);
    if (!$film) {
        return $this->json(['error' => 'Film non trouvé'], 404);
    }

    $user = $this->getUser(); // Récupérer l'utilisateur connecté

    $like = $this->em->getRepository(Likes::class)->findOneBy([
        'likeuser' => $user,
        'filmslikes' => $film,
    ]);

    if (!$like) {
        return $this->json(['error' => 'Vous n\'avez pas encore liké ce film'], 404);
    }

    $this->em->remove($like);
    $this->em->flush();

    return $this->json(['message' => 'Film unliké avec succès']);
}




    

}
