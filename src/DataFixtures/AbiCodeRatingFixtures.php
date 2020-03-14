<?php

namespace App\DataFixtures;

use App\Entity\AbiCodeRating;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class AbiCodeRatingFixtures
 * @package App\DataFixtures
 */
class AbiCodeRatingFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $data[] = [22529902, 1.20];
        $data[] = [46545255, 1.15];
        $data[] = [52123803, 1.25];
        foreach ($data as $key => $arr) {
            //----- Persist Fixture --------
            $abiCodeRating = new AbiCodeRating();
            $abiCodeRating->setRatingFactor($arr[1]);
            $abiCodeRating->setAbiCode($arr[0]);
            $manager->persist($abiCodeRating);
        }

        $manager->flush();
    }
}
