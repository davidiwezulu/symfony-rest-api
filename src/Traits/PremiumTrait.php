<?php

namespace App\Traits;

use App\Entity\AbiCodeRating;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\PostcodeRating;
use App\Entity\AgeRating;
use Symfony\Component\HttpClient\NativeHttpClient;

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


    /**
     * @param EntityManagerInterface $em
     * @param $data
     * @param int $basePremium
     * @return array
     */
    public function fetchPremiumData(
        EntityManagerInterface $em,
        $data,
        $basePremium = 500 )
    {
        $returnData = [];

        //----------- AbiCode Rate Premium ---------------//
        $abiCodeRatingRepository = $em->getRepository(AbiCodeRating::class);
        $returnData[] = $this->calculateRatingFactor(
            $abiCodeRatingRepository,
            $data['abiCode'],
            $basePremium
        );

        //------------ Postcode Premium -------------------//
        $PostcodeRatingRepository = $em->getRepository(PostcodeRating::class);
        $returnData[] = $this->calculateRatingFactor(
            $PostcodeRatingRepository,
            $data['postcode'],
            $basePremium
        );

        //------------ Postcode Premium -------------------//
        $ageRatingRepository = $em->getRepository(AgeRating::class);
        $returnData[] = $this->calculateRatingFactor(
            $ageRatingRepository,
            $data['age'],
            $basePremium
        );

        return $returnData;

    }


    /**
     * Fetch ABI data from Post body
     * @param $client
     * @param $data
     * @return mixed
     */
    public static function abiCodeLookUp( $client, $data) {
        $abiCode = 22529902;

        //-- Proceed with the next request ------------------------//
        try {
            $apiRequest = $client->request('POST', 'https://testing.test.com/oauth/token', [
                'form_params' => [
                    'age' => $data['age'],
                    'postcode' => $data['postcode'],
                    'regno' => $data['regno'],
                ]
            ]);

            $apiResponse    = json_decode($apiRequest->getBody());
            $abiCode        = $apiResponse->abiCode;
        } catch (\Exception $exception) {}


        return $abiCode;

    }

}