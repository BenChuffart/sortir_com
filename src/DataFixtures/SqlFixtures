<?php

namespace App\DataFixtures;

use App\Entity\City;
use App\Entity\Status;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SqlFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        //City
        $product = new City();
        $product->setName('Nantes');
        $product->setPostalCode(44000);
        $manager->persist($product);

        $product1 = new City();
        $product1->setName('Rennes');
        $product1->setPostalCode(35000);
        $manager->persist($product1);

        $product2= new City();
        $product2->setName('Niort');
        $product2->setPostalCode(79000);
        $manager->persist($product2);

        //Status
        $create = new Status();
        $create->setLabel('Créée');
        $manager->persist($create);

        $open = new Status();
        $open->setLabel('Ouverte');
        $manager->persist($open);

        $close = new Status();
        $close->setLabel('Clôturée');
        $manager->persist($close);

        $progress = new Status();
        $progress->setLabel('Activité en cours');
        $manager->persist($progress);

        $finish = new Status();
        $finish->setLabel('Activité passée');
        $manager->persist($finish);

        $cancel= new Status();
        $cancel->setLabel('Annulée');
        $manager->persist($cancel);
        


         $manager->flush();
    }
}
