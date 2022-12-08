<?php

namespace App\DataFixtures;

use App\Entity\Producer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
         
class ProducerFixtures extends Fixture
{
    public const PRODUCER_REFERENCE = 'producer';
    
    public function load(ObjectManager $manager)
    {
        $jsonData = file_get_contents(__DIR__.'/data/producers.json');
        $producersData = json_decode($jsonData, true);

        for ($i = 0; $i < 10; $i++) {
            $producer = new Producer();
            $producer->setName($producersData[$i]['name']);
            $producer->setCountry($producersData[$i]['country']);
            $manager->persist($producer);
            $this->addReference(self::PRODUCER_REFERENCE.'_'.$i, $producer);
        }

        $manager->flush();
    }
}