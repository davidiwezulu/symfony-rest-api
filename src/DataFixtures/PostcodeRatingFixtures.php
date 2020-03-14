<?php

namespace App\DataFixtures;

use App\Entity\PostcodeRating;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class PostcodeRatingFixtures
 * @package App\DataFixtures
 */
class PostcodeRatingFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $data[] = ['LE10', 1.35];
        $data[] = ['PE3', 1.10];
        $data[] = ['WR2', 0.90];
        foreach ($data as $key => $arr) {
            //----- Persist Fixture --------
            $postcodeRating = new PostcodeRating();
            $postcodeRating->setRatingFactor($arr[1]);
            $postcodeRating->setPostcodeArea($arr[0]);
            $manager->persist($postcodeRating);
        }

        $manager->flush();
    }

}
