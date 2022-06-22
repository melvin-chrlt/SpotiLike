<?php

namespace App\Controller;

use App\Entity\Playlist;
use App\Form\PlaylistType;
use App\Repository\CategoryRepository;
use App\Repository\PlaylistRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PlaylistController extends AbstractController
{
    #[Route('/playlist', name: 'app_playlist')]
    public function index(): Response
    {
        return $this->render('playlist/index.html.twig', [
            'controller_name' => 'PlaylistController',
        ]);
    }

    // ADD PLAYLIST
    #[Route('/addPlaylist', name: 'app_add_playlist')]
    #[IsGranted('ROLE_USER')]
    public function add(Request $request, PlaylistRepository $playlistManager, CategoryRepository $categoryManager): Response
    {
        $categories = $categoryManager->findAll();
        $playlist = new Playlist(); // déclaration d'une nouvelle instance de mon entité (ajout d'une ligne dans la bdd)
        $form = $this->createForm(PlaylistType::class, $playlist); // $this = AbsratController (boîte à outil)
        $form->handleRequest($request); // vérifie si chaque champ est bon
        
        if($form->isSubmitted() && $form->isValid()){
            $url = $form["code"]->getData();// récupère ce qu'il y a dans l'input "code"
            $url = parse_url($url, PHP_URL_PATH); // prend une partie du lien (ici c'est le chemin)
            // dd($playlist);
            $playlist
                ->setAuthor($this->getUser())
                ->setCode($url);   
            // set dans mon instance de playlist le user de la session actuelle et le code contenu dans $url
            $playlistManager->add($playlist);
            // $this->addFlash('success', 'La playlist a bien été ajoutée');
            return $this->redirectToRoute('app_playlist');
        }
        
        return $this->render('playlist/add.html.twig', [
            'form' => $form->createView(),
            'categories' => $categories,
        ]);
    }

    // SEE ALL PLAYLISTS
    #[Route('/Allplaylist', name: 'app_all_playlist')]
    public function seeAll(PlaylistRepository $playlistManager): Response
    {
        $entities = $playlistManager->findAll();
        
        return $this->render('playlist/all.html.twig', ['entities' => $entities]);
    }

    // SEE POP PLAYLISTS
    #[Route('/PopPlaylist', name: 'app_pop_playlist')]
    public function pop(PlaylistRepository $playlistManager): Response
    {
        $entities = $playlistManager->findBy(['category' => 1]);
        
        return $this->render('playlist/Pop.html.twig', ['entities' => $entities]);
    }

    // SEE HIP-HOP PLAYLISTS
    #[Route('/HipHopPlaylist', name: 'app_hip_hop_playlist')]
    public function hipHop(PlaylistRepository $playlistManager): Response
    {
        $entities = $playlistManager->findBy(['category' => 2]);
        
        return $this->render('playlist/hipHop.html.twig', ['entities' => $entities]);
    }

    // SEE ROCK PLAYLISTS
    #[Route('/rockPlaylist', name: 'app_rock_playlist')]
    public function rock(PlaylistRepository $playlistManager): Response
    {
        $entities = $playlistManager->findBy(['category' => 3]);
        
        return $this->render('playlist/rock.html.twig', ['entities' => $entities]);
    }

    // SEE ELECTRO PLAYLISTS
    #[Route('/electroPlaylist', name: 'app_electro_playlist')]
    public function electro(PlaylistRepository $playlistManager): Response
    {
        $entities = $playlistManager->findBy(['category' => 4]);
        
        return $this->render('playlist/electro.html.twig', ['entities' => $entities]);
    }

    // SEE REGGAE PLAYLISTS
    #[Route('/reggaePlaylist', name: 'app_reggae_playlist')]
    public function reggae(PlaylistRepository $playlistManager): Response
    {
        $entities = $playlistManager->findBy(['category' => 5]);
        
        return $this->render('playlist/reggae.html.twig', ['entities' => $entities]);
    }

    // SEE CHILL PLAYLISTS
    #[Route('/chillPlaylist', name: 'app_chill_playlist')]
    public function chill(PlaylistRepository $playlistManager): Response
    {
        $entities = $playlistManager->findBy(['category' => 6]);
        
        return $this->render('playlist/chill.html.twig', ['entities' => $entities]);
    }

    // SEE CLASSIQUE PLAYLISTS
    #[Route('/classiquePlaylist', name: 'app_classique_playlist')]
    public function classique(PlaylistRepository $playlistManager): Response
    {
        $entities = $playlistManager->findBy(['category' => 7]);
        
        return $this->render('playlist/classique.html.twig', ['entities' => $entities]);
    }

    // SEE AUTRE PLAYLISTS
    #[Route('/autrePlaylist', name: 'app_autre_playlist')]
    public function autre(PlaylistRepository $playlistManager): Response
    {
        $entities = $playlistManager->findBy(['category' => 8]);
        
        return $this->render('playlist/autre.html.twig', ['entities' => $entities]);
    }


    // LIKE PLAYLIST
    // #[Route('/playlist/{id}/like', name: 'app_like_playlist')]
    // public function like(Playlist $playlist, EntityManagerInterface $em, PlaylistLikeRepository $likeManager): Response
    // {
    //     $user = $this->getUser();

    //     if(!$user) return $this->json([
    //         'code' => 403,
    //         'message' => "Unauthorized"
    //     ], 403);
        
    //     if($playlist->isLikedByUser($user)) {
    //         $like = $likeManager->findOneBy([
    //             'playlist' => $playlist,
    //             'user' => $user
    //         ]);

    //         $em->remove($like);
    //         $em->flush();

    //         return $this->json([
    //             'code' => 200,
    //             'message' => 'Like bien supprimé',
    //             'likes' => $likeManager->count(['playlist' => $playlist])
    //         ], 200);
    //     }

    //     $like = new PlaylistLike();
    //     $like->setPlaylist($playlist)
    //          ->setUser($user);

    //     $em->persist($like);
    //     $em->flush();

    //     return $this->json([
    //         'code' => 200, 
    //         'message' => 'Like bien ajouté',
    //         'likes' => $likeManager->count(['playlist' => $playlist])
    //     ], 200);
    // }
}
