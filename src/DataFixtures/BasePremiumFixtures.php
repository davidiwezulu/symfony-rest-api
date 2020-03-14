<?php

namespace App\DataFixtures;

use App\Entity\BasePremium;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class PostcodeRatingFixtures
 * @package App\DataFixtures
 */
class BasePremiumFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        //----- Persist Fixture --------
        $basePremium = new BasePremium();
        $basePremium->setBasePremium(500.00);
        $manager->persist($basePremium);
        $manager->flush();
    }
}
