<?php

namespace App\DataFixtures;

use App\Entity\Note;
use App\Entity\Plat;
use App\Entity\Regime;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{

    
    public function load(ObjectManager $manager): void
    {



        // data for plat
        function generateIngredients() {
            $length = mt_rand(2,10);
            $randomString = 'ingredient #';
            $randomString2 = '';
            for ($i = 1; $i <= $length; $i++) {
                $randomString2 .= $randomString .$i.' / ';
            }
            return $randomString2;
        }







        $users = [];
        for ($i=0; $i < 10; $i++) { 
            $user = new User();
            $user->setNomPrenom('NomPrenom#'.$i)
            ->setPseudo('Pseudo#'.$i)
            ->setEmail('user'.$i.'@email.com')
            ->setRoles(['ROLE_USER'])
            ->setPassword('password'.$i);
            $users[]=$user;
            $manager->persist($user);
        }

        $plats = [];
        for($i = 1; $i <=30; $i++){
            $plat = new Plat();
            $plat->setNomPlat('plat #'.$i)
            ->setCout(mt_rand(1,200))
            ->setNbrCalories(mt_rand(1,250))
            ->setIngredients(generateIngredients())
            ->setUser($users[mt_rand(0,count($users)-1)]);
            $plats[] = $plat;
            $manager->persist($plat);
        }

        // data for regime
        $types= [
        'régime protéiné',
        'régime hyperprotéiné',
        'régime hypocalorique',
        'régime dissocié',
        'régime végétarien',
        'régime anticellulite',
        'régime sans sel',
        'régime hypoglucidique'
        ];
        $regimes=[];
        for ($i=1; $i <= 20 ; $i++) { 
            $regime = new Regime();
            $type = $types[mt_rand(0,count($types)-1)];
            $regime->setNomRegime('Regime #'.$i)
            ->setDuree(mt_rand(1,50))
            ->setType($type)
            ->setIsFavorite(mt_rand(0,1) == 1 ?true : false)
            ->setIsPublic(mt_rand(0,1) == 1 ?true : false)
            ->setUser($users[mt_rand(0,count($users)-1)]);
            
            for ($j=0; $j <mt_rand(3,10) ; $j++) { 
                $regime->addMenu($plats[mt_rand(0,count($plats)-1)]);
            }
            $regimes[]=$regime;
            $manager->persist($regime);

        }


        foreach ($regimes as $regime) {
            for ($i=0; $i < mt_rand(0,5) ; $i++) { 
                $note = new Note();
                $note->setNote(mt_rand(1,10))
                    ->setUser($users[mt_rand(0,count($users)-1)])
                    ->setRegime($regime);

                $manager->persist($note);
            }
        }


        $manager->flush();
    }
}
