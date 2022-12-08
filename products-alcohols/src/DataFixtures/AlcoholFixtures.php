<?php

namespace App\DataFixtures;

use App\Entity\Alcohol;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class AlcoholFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $jsonData = file_get_contents(__DIR__.'/data/alcohols.json');
        $alcoholsData = json_decode($jsonData, true);
        $alcoholTypes = ['beer', 'wine', 'liqueur', 'base liquor'];

        for ($i = 0; $i < 50; $i++) {
            $alcohol = new Alcohol();
            $alcohol->setName($alcoholsData[$i]['name']);
            $alcohol->setDescription($alcoholsData[$i]['description']);
            $alcohol->setType($alcoholTypes[mt_rand(0,3)]);
            $alcohol->setAbv(mt_rand( 1, 80 ) / 10);
            $alcohol->setProducer($this->getReference(ProducerFixtures::PRODUCER_REFERENCE.'_'. mt_rand(0,9)));
            $alcohol->setImage($this->getReference(ImageFixtures::IMAGE_REFERENCE.'_'.$i));
            $manager->persist($alcohol);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ProducerFixtures::class,
            ImageFixtures::class,
        ];
    }
}
