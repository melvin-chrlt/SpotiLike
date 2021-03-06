<?php

namespace App\Controller;

use App\Entity\Playlist;
use App\Form\PlaylistType;
use App\Entity\PlaylistLike;
use App\Repository\CategoryRepository;
use App\Repository\PlaylistRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PlaylistLikeRepository;
use Knp\Component\Pager\PaginatorInterface;
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
        return $this->render('playlist/index.html.twig');
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
        
        if($form->isSubmitted()){
            if($form->isValid()){
                $url = $form["code"]->getData();// récupère ce qu'il y a dans l'input "code"
                $url = parse_url($url, PHP_URL_PATH); // prend une partie du lien (ici c'est le chemin)
                $playlist
                    ->setAuthor($this->getUser())
                    ->setCode($url);   
                // set dans mon instance de playlist le user de la session actuelle et le code contenu dans $url
                $playlistManager->add($playlist);
                $this->addFlash('success', 'La playlist a bien été ajoutée');
                return $this->redirectToRoute('app_profil');
            } else {
                $this->addFlash('error', 'Veuillez réessayez avec une autre playlist');
                return $this->redirectToRoute('app_profil');
            }
        } 

        return $this->render('playlist/add.html.twig', [
            'form' => $form->createView(),
            'categories' => $categories,
        ]);
    }

    // REMOVE PLAYLIST
    #[Route('/delPlaylist/{id}', name: 'app_delete_playlist')]
    #[IsGranted('ROLE_USER')]
    public function delete(Playlist $playlist, EntityManagerInterface $em, Request $request): Response
    {
        // //On récupère le token envoyer par le formulaire
        // $csrf_token = $request->get('token');
        
        // if($this->getUser() === $playlist->getAuthor()){
        //     //On vérifie si il correspond à celui de la session courante
        //     if($this->isCsrfTokenValid('delete-playlist'.$playlist->getId(), $csrf_token)){
        //         $em->remove($playlist);
        //         $em->flush();
        //     }
        // }
        
        // return $this->redirectToRoute('app_profil');
        if($this->getUser() === $playlist->getAuthor()){
            
            if ($this->isCsrfTokenValid('delete-playlist'.$playlist->getId(), $request->get('csrf_token'))) {
                $em->remove($playlist);
                $em->flush();
                $this->addFlash('success', 'La playlist a bien été supprimée');
            }
        }
        return $this->redirectToRoute('app_profil');
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
    #[Route('/RockPlaylist', name: 'app_rock_playlist')]
    public function rock(PlaylistRepository $playlistManager): Response
    {
        $entities = $playlistManager->findBy(['category' => 3]);
        
        return $this->render('playlist/rock.html.twig', ['entities' => $entities]);
    }

    // SEE ELECTRO PLAYLISTS
    #[Route('/ElectroPlaylist', name: 'app_electro_playlist')]
    public function electro(PlaylistRepository $playlistManager): Response
    {
        $entities = $playlistManager->findBy(['category' => 4]);
        
        return $this->render('playlist/electro.html.twig', ['entities' => $entities]);
    }

    // SEE REGGAE PLAYLISTS
    #[Route('/ReggaePlaylist', name: 'app_reggae_playlist')]
    public function reggae(PlaylistRepository $playlistManager): Response
    {
        $entities = $playlistManager->findBy(['category' => 5]);
        
        return $this->render('playlist/reggae.html.twig', ['entities' => $entities]);
    }

    // SEE CHILL PLAYLISTS
    #[Route('/ChillPlaylist', name: 'app_chill_playlist')]
    public function chill(PlaylistRepository $playlistManager): Response
    {
        $entities = $playlistManager->findBy(['category' => 6]);
        
        return $this->render('playlist/chill.html.twig', ['entities' => $entities]);
    }

    // SEE CLASSIQUE PLAYLISTS
    #[Route('/ClassiquePlaylist', name: 'app_classique_playlist')]
    public function classique(PlaylistRepository $playlistManager): Response
    {
        $entities = $playlistManager->findBy(['category' => 7]);
        
        return $this->render('playlist/classique.html.twig', ['entities' => $entities]);
    }

    // SEE AUTRE PLAYLISTS
    #[Route('/AutrePlaylist', name: 'app_autre_playlist')]
    public function autre(PlaylistRepository $playlistManager): Response
    {
        $entities = $playlistManager->findBy(['category' => 8]);
        
        return $this->render('playlist/autre.html.twig', ['entities' => $entities]);
    }

    // DAY OF THE PLAYLIST
    #[Route('/DayPlaylist', name: 'app_day_playlist')]
    public function day(PlaylistRepository $playlistManager): Response
    {
        $entities = $playlistManager->findBy(['id' => rand(33, 48)]);
        
        return $this->render('playlist/day.html.twig', ['entities' => $entities]);
    }


    // LIKE PLAYLIST
    #[Route('/playlist/{id}/like', name: 'app_like_playlist')]
    #[IsGranted('ROLE_USER')]
    public function like(int $id, Request $request, PlaylistRepository $playlistManager, PlaylistLikeRepository $likeManager, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $playlist = $playlistManager->findOneBy(['id' => $id]);

        // Si le user a déjà liké
        if($playlist->isLikedByUser($user)) {
            $like = $likeManager->findOneBy([
                'playlist' => $playlist,
                'user' => $user
            ]);
            $em->remove($like);
            $em->flush();
            
            return $this->redirect($request->headers->get('referer'));
        }

        // // Si le user n'a pas liké
        $like = new PlaylistLike();
        $like->setPlaylist($playlist)
             ->setUser($user);
        
        $em->persist($like);
        $em->flush();
        
        return $this->redirect($request->headers->get('referer'));
    }

    // CLASSEMENT PLAYLISTS LIKE
    #[Route('/ClassementLikePlaylist', name: 'app_classement_like_playlist')]
    public function classementLike(PlaylistRepository $playlistManager): Response
    {
        $entities = $playlistManager->findAll();
        //array like  +tri
        //renvoi array a la vue
        // foreach ($entities as $entity){
        //     $likes[] = $entity->getLikes();
        // }
        return $this->render('classement/like.html.twig', ['entities' => $entities]);
    }

    // PAGE MON COMPTE
    #[Route('/profile', name: 'app_profil')]
    public function profil(Request $request,PlaylistRepository $playlistManager, PlaylistLikeRepository $playlistLikeManager, PaginatorInterface $paginator): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $playlist = $playlistManager->findBy(['author' => $user->getId()]);
        $playlistLike = $playlistLikeManager->findBy(['user' => $user->getId()]);

        $entities = $paginator->paginate(
            $playlist,
            $request->query->getInt('page', 1),
            5
        );

        $likes = $paginator->paginate(
            $playlistLike,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('profil/index.html.twig', [
            'entities' => $entities,
            'likes' => $likes
        ]);
    }

    // PAGE TUTO
    #[Route('/tuto', name: 'app_tuto')]
    public function tuto(): Response
    {
        return $this->render('playlist/tuto.html.twig');
    }
}