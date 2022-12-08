<?php

namespace App\DataFixtures;

use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
         
class ImageFixtures extends Fixture
{
    public const IMAGE_REFERENCE = 'image';

    public function load(ObjectManager $manager)
    {

        $jsonData = file_get_contents(__DIR__.'/data/images.json');
        $imagesData = json_decode($jsonData, true);
        
        for ($i = 0; $i < 50; $i++) {
            $image = new Image();
            $image->setName($imagesData[$i]['name']);
            $image->setUrl($imagesData[$i]['url']);
            $manager->persist($image);
            $this->addReference(self::IMAGE_REFERENCE.'_'.$i, $image);
        }

        $manager->flush();
    }
}