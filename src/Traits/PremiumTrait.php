<?php

namespace App\Traits;

use App\Entity\AbiCodeRating;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\PostcodeRating;
use App\Entity\AgeRating;
use App\Entity\Quote;
use App\Entity\BasePremium;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * Trait PremiumTrait
 * @package App\Traits
 */
trait PremiumTrait
{
    /**
     * @param $em
     * @return mixed
     */
    public function getBasePremium(EntityManagerInterface $em)
    {
        $basePremiumRepository  = $em->getRepository(BasePremium::class);
        $basePremiumObject      = $basePremiumRepository->findFirstPremium();
        $basePremium            = $basePremiumObject->getBasePremium();

        return $basePremium;
    }

    /**
     * @param $em
     * @param $data
     * @param $premiumDataArray
     * @return Quotes
     */
    public function persistNewQuote(EntityManagerInterface $em, $data, $premiumDataArray)
    {
        $averagePremium = array_sum($premiumDataArray)/count($premiumDataArray);
        $quote = new Quote();
        $quote->setPolicyNumber(mt_rand(10000000000,99999999999)); //Production should have a better logic
        $quote->setAge($data['age']);
        $quote->setPostcode($data['postcode']);
        $quote->setRegNo($data['regNo']);
        $quote->setAbiCode($data['abiCode']);
        $quote->setPremium($averagePremium);
        $em->persist($quote);
        $em->flush();

        return $quote;
    }


    /**
     * @param $codeRepository
     * @param $needle
     * @param float $basePremium
     * @return string
     */
    public function calculateRatingFactor($codeRepository, string $needle, $basePremium = 500.00)
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
     * @param $data
     * @return int
     */
    public function abiCodeLookUp(array $data)
    {
        //----- Fall back ABI code for API mock up ----------------//
        $abiCode    = 22529902;

        //-- Proceed with the next request ------------------------//
        try {
            $rest_data  = $this->callVendorsAPI('POST', 'https://testing.test.com/oauth/token', $data);
            $response   = json_decode($rest_data, true);
            $codeData   = $response['response']['data'][0];
            if ($codeData && ! is_null($codeData) ) $abiCode = $codeData;
        } catch (\Exception $exception) {}

        return $abiCode;

    }

    /**
     * @param $method
     * @param $url
     * @param $data
     * @return mixed
     */
    public function callVendorsAPI(string $method, string $url, array $data)
    {
        $curl           = curl_init();
        //~~~~~~~~~ Should be stored in a config environment ~~~~~//
        $vendorToken    = "080042cad6356ad5dc0a720c18b53b8e53d4c274";
        $authorization  = "Authorization: Bearer $vendorToken";
        switch ($method){
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            default:
                if ($data)
                    $url = sprintf("%s?%s", $url, http_build_query($data));
        }
        //---------- OPTIONS: ------------------//
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            $authorization,
            'Content-Type: application/json',
        ]);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

        //----------- EXECUTE: ------------------//
        $result = curl_exec($curl);

        curl_close($curl);
        return $result;
    }

}
