<?php

namespace App\Traits;

use App\Entity\AbiCodeRating;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

trait PremiumTrait
{

    /**
     * @param $codeRepository
     * @param $needle
     * @param float $basePremium
     * @return string
     */
    public function calculateRatingFactor($codeRepository, $needle, $basePremium = 500.00)
    {
        //---------- Use Entity's repository instance to fetch data -----------//
        $abiCodeRatePremium    = $basePremium;
        try {
            $abiCodeRate = $codeRepository->findOneByRatingFactor($needle);
        } catch (\Exception $exception) {}

        if ( ! is_null($abiCodeRate)) {
            $abiCodeRatePremium = $abiCodeRate->getRatingFactor() * $basePremium;
        }
        return $abiCodeRatePremium;
    }
}