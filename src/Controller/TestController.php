<?php

namespace App\Controller;

use DateTime;
use Exception;
use App\Entity\Auteur;
use App\Entity\Emprunt;
use App\Entity\Emprunteur;
use App\Entity\Genre;
use App\Entity\Livre;
use App\Entity\User;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/test')]

class TestController extends AbstractController
{
    #[Route('/user', name: 'app_test_user')]
    public function user(ManagerRegistry $doctrine): Response
    {
        $em=$doctrine->getManager();
        $userRepository = $em->getRepository(User::class);

        //requêtes de lecture
        $users=$userRepository->findAll();

        $user1=$userRepository->find(1);

        $foo = $userRepository ->findOneBy([
            'email'=>'foo.foo@example.com',
        ]);

        $rolesUsers=$userRepository->findKeyword('ROLE_USER');

        $disabledUsers=$userRepository->findDisabled('0');

        $title='Test des Users';

        return $this->render('test/user.html.twig', [
            'title' => $title,
            'users'=>$users,
            'user1' => $user1,
            'foo' => $foo,
            'rolesUsers' => $rolesUsers,
            'disabledUsers' => $disabledUsers
        ]);
    }

    #[Route('/livre', name: 'app_test_livre')]
    public function livre(ManagerRegistry $doctrine): Response
    {
        $em=$doctrine->getManager();
        $livreRepository = $em->getRepository(Livre::class);
        //requête de lecture

        $livres= $livreRepository->findAll();
        
        $livre1= $livreRepository ->find (1);

        $lorem = $livreRepository ->findKeyword('lorem');

        $auteurRepository = $em->getRepository(Auteur::class);
        $auteur2 = $auteurRepository -> find(2);
        $auteur2livre = $livreRepository ->findByAuteur ($auteur2);

        //A FINIR GENRE
        $genreRepository = $em->getRepository(Genre::class);
        // $genre2 = $genreRepository -> find(2);
        // $genre2livre = $livreRepository ->findByGenre ($genre2);

        //requête de création
        // $totum = new Livre();
        // $totum->setTitre ('Totum autem id externum');
        // $totum->setAnneeEdition(2020);
        // $totum->setNombrePage(300);
        // $totum->setCodeIsbn('9790412882714');
        // $auteur2=$auteurRepository->find(2);
        // $totum->setAuteur($auteur2);
        // $genre6=$genreRepository->find(6);
        // $totum->addGenre($genre6);
        // $em->persist($totum);
        // try{
        //     $em->flush();
        // }catch(Exception $e){
        //     dump($e->getMessage());
        // }

        //requête de mise à jour
        $livre2=$livreRepository->find(2);
        $livre2->setTitre('Aperiendum est igitur');
        $genreRepository = $em->getRepository(Genre::class);
        $genre5=$genreRepository->find(5);
        $livre2->addGenre($genre5);
        $em->flush();

        //requête de suppression
        $livre123=$livreRepository->find(123);
        if($livre123){
            $em->remove($livre123);
            $em->flush();
        }

        $title='Test des Livres';

        return $this->render('test/livre.html.twig', [
            'title' => $title,
            'livres' => $livres,
            'livre1' => $livre1,
            'lorem' => $lorem,
            'auteur2livre' =>$auteur2livre,
            // 'genre2livre' => $genre2livre,
            // 'totum' => $totum,
            'livre2' => $livre2,
        ]);
    }

    #[Route('/emprunteur', name: 'app_test_emprunteur')]
    public function emprunteur(ManagerRegistry $doctrine): Response
    {
        $em=$doctrine->getManager();
        $emprunteurRepository = $em->getRepository(Emprunteur::class);


        $title='Test des Emprunteurs';

        return $this->render('test/emprunteur.html.twig', [
            'title' => $title,
            
        ]);
    }

    #[Route('/emprunt', name: 'app_test_emprunt')]
    public function emprunt(ManagerRegistry $doctrine): Response
    {
        $em=$doctrine->getManager();
        $empruntRepository = $em->getRepository(Emprunt::class);


        $title='Test des Emprunts';

        return $this->render('test/emprunt.html.twig', [
            'title' => $title,
        ]);
    }
}