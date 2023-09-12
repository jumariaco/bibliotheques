<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\Auteur;
use App\Entity\Emprunt;
use App\Entity\Emprunteur;
use App\Entity\Genre;
use App\Entity\Livre;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class TestFixtures extends Fixture implements FixtureGroupInterface
{
    private $faker;
    private $hasher;
    private $manager;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->faker = FakerFactory::create('fr_FR');
        $this->hasher = $hasher;
    }

    public static function getGroups():array
    {
        return ['test'];
    }

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;
        $this->loadUsers();
        $this->loadGenres();
        $this->loadAuteurs();
        $this->loadLivres();
        $this->loadEmprunts();
    }
    
    public function loadUsers(): void
    {
        //données statiques
        $datas = [
            [
                'email'=>'foo.foo@example.com',
                'password'=>'123',
                'roles'=>['ROLE_USER'],
                'actif'=>true,
                'nom'=>'foo',
                'prenom'=>'foo',
                'telephone'=>'123456789',
            ],
            [
                'email'=>'bar.bar@example.com',
                'password'=>'123',
                'roles'=>['ROLE_USER'],
                'actif'=>false,
                'nom'=>'bar',
                'prenom'=>'bar',
                'telephone'=>'123456789',
            ],
            [
                'email'=>'baz.baz@example.com',
                'password'=>'123',
                'roles'=>['ROLE_USER'],
                'actif'=>true,
                'nom'=>'baz',
                'prenom'=>'baz',
                'telephone'=>'123456789',
            ],
        ];

        foreach ($datas as $data) {
            $emprunteur=new Emprunteur();
            $emprunteur->setNom($data['nom']);
            $emprunteur->setPrenom($data['prenom']);
            $emprunteur->setTelephone($data['telephone']);

            $this->manager->persist($emprunteur);

            $user=new User();
            $user->setEmail($data['email']);
            $password=$this->hasher->hashPassword($user, '123');
            $user->setPassword($password);
            $user->setRoles($data['roles']);
            $user->setActif($data['actif']);
            $user->setEmprunteur($emprunteur);


            $this->manager->persist($user);
        }
        $this->manager->flush();

        //données dynamiques
        for ($i=0; $i < 100; $i++){
            $emprunteur=new Emprunteur();
            $emprunteur->setNom($this->faker->lastName());
            $emprunteur->setPrenom($this->faker->firstName());
            $emprunteur->setTelephone($this->faker->phoneNumber());

            $this->manager->persist($emprunteur);

            $user=new User();
            $user->setEmail($this->faker->unique()->safeEmail());
            $password=$this->hasher->hashPassword($user, '123');
            $user->setPassword($password);
            $user->setRoles(['ROLE_USER']);
            $user->setActif($this->faker->boolean(70));
            $user->setEmprunteur($emprunteur);

            $this->manager->persist($user);
        }
        $this->manager->flush();
    }
    public function loadGenres(): void 
    {
        //données statiques
        $datas=[
            ['nom'=>'poésie',
            'description'=>null,
            ],
            ['nom'=>'nouvelle',
            'description'=>null,
            ],
            ['nom'=>'roman historique',
            'description'=>null,
            ],
            ['nom'=>'roman d\'amour',
            'description'=>null,
            ],
            ['nom'=>'roman d\'aventure',
            'description'=>null,
            ],
            ['nom'=>'science-fiction',
            'description'=>null,
            ],
            ['nom'=>'fantaisie',
            'description'=>null,
            ],
            ['nom'=>'biographie',
            'description'=>null,
            ],
            ['nom'=>'conte',
            'description'=>null,
            ],
            ['nom'=>'témoignage',
            'description'=>null,
            ],
            ['nom'=>'théâtre',
            'description'=>null,
            ],
            ['nom'=>'essai',
            'description'=>null,
            ],
            ['nom'=>'journal intime',
            'description'=>null,
            ],
        ];
        foreach($datas as $data) {
            $genre=new Genre();
            $genre->setNom($data['nom']);
            $genre->setDescription($data['description']);
            
            $this->manager->persist($genre);
        }
        $this->manager->flush();
    }

    public function loadAuteurs(): void
    {
        //données statiques
        $datas=[
            ['nom'=>'auteur inconnu',
            'prenom'=>'',
            ],
            ['nom'=>'Cartier',
            'prenom'=>'Hugues',
            ],
            ['nom'=>'Lambert',
            'prenom'=>'Armand',
            ],
            ['nom'=>'Moitessier',
            'prenom'=>'Thomas',
            ],
        ];
        foreach($datas as $data){
            $auteur=new Auteur();
            $auteur->setNom($data['nom']);
            $auteur->setPrenom($data['prenom']);

            $this->manager->persist($auteur);
        }
        $this->manager->flush();

        //données dynamiques
        for ($i=0; $i < 500;$i++){
            $auteur=new Auteur();
            $auteur->setNom($this->faker->lastName);
            $auteur->setPrenom($this->faker->firstName);

            $this->manager->persist($auteur);
        }
        $this->manager->flush();

    }

    public function loadLivres():void
    {
        //relation ManytoMany : table de jointure livre-genre
        $genreRepository =$this->manager->getRepository(Genre::class);
        $genres=$genreRepository->findAll();
        $poesieGenre=$genreRepository->find(1);
        $nouvelleGenre=$genreRepository->find(2);
        $romanHistoriqueGenre=$genreRepository->find(3);
        $romandAmourGenre=$genreRepository->find(4);

        //relation ManytoOne : avec auteur
        $auteurRepository =$this->manager->getRepository(Auteur::class);
        $auteurs=$auteurRepository->findAll();
        $auteur1=$auteurRepository->find(1);
        $auteur2=$auteurRepository->find(2);
        $auteur3=$auteurRepository->find(3);
        $auteur4=$auteurRepository->find(4);
        //données statiques
        $datas=[
            ['titre'=>'Lorem ipsum dolor sit amet',
            'anneeEdition'=>2010,
            'nombrePage'=>100,
            'codeIsbn'=>'9785786930024',
            'genres'=>[$poesieGenre],
            'auteur'=>$auteur1,
            ],
            ['titre'=>'Consectetur adipiscing elit',
            'anneeEdition'=>2011,
            'nombrePage'=>150,
            'codeIsbn'=>'9783817260935',
            'genres'=>[$nouvelleGenre],
            'auteur'=>$auteur2,
            ],
            ['titre'=>'Mihi quidem Antiochum',
            'anneeEdition'=>2012,
            'nombrePage'=>200,
            'codeIsbn'=>'9782020493727',
            'genres'=>[$romanHistoriqueGenre],
            'auteur'=>$auteur3,
            ],
            ['titre'=>'Quem audis satis belle',
            'anneeEdition'=>2013,
            'nombrePage'=>250,
            'codeIsbn'=>'9794059561353',
            'genres'=>[$romandAmourGenre],
            'auteur'=>$auteur4,
            ],
        ];
        foreach ($datas as $data) {
            $livre=new Livre();
            $livre->setTitre($data['titre']);
            $livre->setAnneeEdition($data['anneeEdition']);
            $livre->setNombrePage($data['nombrePage']);
            $livre->setCodeIsbn($data['codeIsbn']);
            //ajout données de la relation avec la classe Auteur
            $livre->setAuteur($data['auteur']);
            //ajout données de la table de jointure livre-genre
            foreach ($data['genres'] as $genre) {
                $livre->addGenre ($genre);
            }
            $this->manager->persist($livre);
        }
        $this->manager->flush();

        //données dynamiques
        for ($i=0; $i<1000; $i++){
            $livre=new Livre();
            $words=random_int(2,3);
            $livre->setTitre($this->faker->sentence($words));
            $date=rand('800', '2020');
            $livre->setAnneeEdition($date);
            $numbers=rand('20','800');
            $livre->setNombrePage($numbers);
            // $isbn=random_bytes(13);
            $isbn=rand('1111111111111','9999999999999');
            $livre->setCodeIsbn($isbn);
            //ajout données de la relation avec la classe Auteur
            $auteur=$this->faker->randomElement($auteurs);
            $livre->setAuteur($auteur);

            //ajout données de la table de jointure livre-genre
            $genresCount=random_int(1,4);
            $shortList=$this->faker->randomElements($genres, $genresCount);
            foreach($shortList as $genre){
                $livre->addGenre($genre);
            }
            $this->manager->persist($livre);
        }
        $this->manager->flush();
    }
    public function loadEmprunts(): void
    {
        $emprunteurRepository=$this->manager->getRepository(Emprunteur::class);
        $emprunteurs=$emprunteurRepository->findAll();
        $emprunteur1=$emprunteurRepository->find(1);
        $emprunteur2=$emprunteurRepository->find(2);
        $emprunteur3=$emprunteurRepository->find(3);

        $livreRepository=$this->manager->getRepository(Livre::class);
        $livres=$livreRepository->findAll();
        $livre1=$livreRepository->find(1);
        $livre2=$livreRepository->find(2);
        $livre3=$livreRepository->find(3);

        //données statiques
        $datas=[
            ['dateEmprunt'=>new DateTime('2020-02-01 10:00:00'),
            'dateRetour'=>new DateTime('2020-03-01 10:00:00'),
            'emprunteur'=>$emprunteur1,
            'livre'=>$livre1,
            ],
            ['dateEmprunt'=>new DateTime('2020-03-01 10:00:00'),
            'dateRetour'=>new DateTime('2020-04-01 10:00:00'),
            'emprunteur'=>$emprunteur2,
            'livre'=>$livre2,
            ],
            ['dateEmprunt'=>new DateTime('2020-04-01 10:00:00'),
            'dateRetour'=>null,
            'emprunteur'=>$emprunteur3,
            'livre'=>$livre3,
            ],
        ];
        foreach($datas as $data){
            $emprunt=new Emprunt();
            $emprunt->setDateEmprunt($data['dateEmprunt']);
            $emprunt->setDateRetour($data['dateRetour']);
            $emprunt->setEmprunteur($data['emprunteur']);
            $emprunt->setLivre($data['livre']);
            $this->manager->persist($emprunt);
        }
        $this->manager->flush();
        //données dynamiques
        for ($i=0; $i<200; $i++){
            $emprunt=new Emprunt();
            $dateEmprunt = $this->faker->dateTimeBetween ('-3 year', '-1 year');
            $emprunt->setDateEmprunt($dateEmprunt);
            $dateRetour = $this->faker->dateTimeBetween ('-1 year', '-1 month');
            $emprunt->setDateRetour($dateRetour);
            $emprunteur=$this->faker->randomElement($emprunteurs);
            $emprunt->setEmprunteur($emprunteur);
            $livre=$this->faker->randomElement($livres);
            $emprunt->setLivre($livre);
            $this->manager->persist($emprunt);
        }
        $this->manager->flush();
    }

    
}
