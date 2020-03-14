<?php

namespace App\DataFixtures;

use App\Entity\AgeRating;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class PostcodeRatingFixtures
 * @package App\DataFixtures
 */
class AgeRatingFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $data[] = [17, 1.50];
        $data[] = [18, 1.40];
        $data[] = [19, 1.30];
        $data[] = [20, 1.20];
        $data[] = [21, 1.10];
        $data[] = [22, 1.00];
        $data[] = [23, 0.95];
        $data[] = [24, 0.90];
        $data[] = [25, 0.75];
        foreach ($data as $key => $arr) {
            //----- Persist Fixture --------
            $ageRating = new AgeRating();
            $ageRating->setRatingFactor($arr[1]);
            $ageRating->setAge($arr[0]);
            $manager->persist($ageRating);
        }

        $manager->flush();
    }
}
