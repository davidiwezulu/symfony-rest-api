<?php
namespace App\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Entity\PostcodeRating;
use App\Entity\AbiCodeRating;
use App\Entity\BasePremium;
use App\Entity\AgeRating;
use App\Entity\Quote;
use App\Traits\PremiumTrait;
use App\Form\PremiumType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\HttpClientInterface;
use \GuzzleHttp\Client;
use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Component\HttpClient\NativeHttpClient;
/**
 * API controller.
 * @Route("/api", name="api_")
 */
class ApiController extends FOSRestController
{
    //----- Premium rate calculator trait ------//
    use PremiumTrait;

    /**
     * Create Premium.
     * @Rest\Post("/premium")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return mixed
     */
    public function postPremium(Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(PremiumType::class);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        if ($form->isSubmitted() && $form->isValid()) {

            $quoteRepository        = $em->getRepository(Quote::class);
            $quotes                 = $quoteRepository->findBy($data);

            //--------- Trigger API call for Abi Code --------------//
            $basePremium        = $this->getBasePremium($em);

            //~~~~~~~~~~~~~~~~ \App\Traits\PremiumTrait ~~~~~~~~~~~~//
            $data['abiCode']    = $this->abiCodeLookUp($data);
            $premiumArray       = $this->fetchPremiumData($em, $data, $basePremium );
            $quoteData          = $this->persistNewQuote($em, $data, $premiumArray);
            //~~~~~~~~~~~~~~~~ End of Trait call ~~~~~~~~~~~~~~~~~~~//

            //--------- Building DTOs --------//
            $dataDTO['quotePolicyNumber']   = $quoteData->getPolicyNumber();
            $dataDTO['abiCode']             = $quoteData->getAbiCode();
            $dataDTO['regNo']               = $quoteData->getRegNo();;
            $dataDTO['postcode']            = $quoteData->getPostcode();
            $dataDTO['averagePremium']      = number_format($quoteData->getPremium(), 2);

            return $this->handleView($this->view(
                [
                    'status' => 'ok',
                    'data' => $dataDTO,
                    'message' => 'Premium Successfully retrieved'
                ],
                Response::HTTP_CREATED));
        }

        return $this->handleView($this->view($form->getErrors()));
    }
}

/**
 * curl --header "Content-Type: application/json" \
--request POST \
--data '{"name":"movie1","description":"movie number 1"}' \
http://127.0.0.1:8000/api/movie
 */