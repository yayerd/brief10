<?php

namespace App\DataFixtures;

use App\Entity\Candidature;
use App\Entity\Formationn;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class AppFixtures extends Fixture
{
    private $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Création d'un user AdminSimplon
        $userAdminSimplon = new User();
        $userAdminSimplon->setEmail("adminsimplon@brief10.com");
        $userAdminSimplon->setRoles(["ROLE_AdminSimplon"]);
        $userAdminSimplon->setPassword($this->userPasswordHasher->hashPassword($userAdminSimplon, "password"));
        $manager->persist($userAdminSimplon);

        // Création d'un user "Candidat"
        $listUserCandidat = [];
        for ($i = 0; $i < 10; $i++) {
            $userCandidat = new User();
            $userCandidat->setEmail("candidat.$i@brief10.com");
            $userCandidat->setRoles(["ROLE_Candidat"]);
            $userCandidat->setPassword($this->userPasswordHasher->hashPassword($userCandidat, "password"));
            $manager->persist($userCandidat);
            $listUserCandidat[] = $userCandidat;
        }

        $listFormation=[];
        for ($i = 0; $i < 15; $i++) {
            $formationn = new Formationn();

            $titres = ['Développement Web', 'Sécurité Informatique', 'Intelligence Artificielle', 'Big Data', 'Réseaux Informatiques'];
            $criteres = ['Formation avancée', 'Certification reconnue', 'Pratique intensive', 'Projets réels', 'Expertise industrielle'];

            $formationn->setStatut(true);
            $formationn->setTitre($titres[array_rand($titres)]);
            $formationn->setCriteres($criteres[array_rand($criteres)]);
            $formationn->setDuree(mt_rand(3, 9));

            $manager->persist($formationn); // pour sauvegarder 

            $listFormation[]=$formationn;
        }

        for ($i = 0; $i < 10; $i++) {
            $candidature = new Candidature();

            $candidature->setStatut(true);
            $candidature->setFormation($listFormation[array_rand($listFormation)]);
            $candidature->setUser($listUserCandidat[array_rand($listUserCandidat)]);
            
            $manager->persist($candidature);
        }

        $manager->flush();
    }

}
