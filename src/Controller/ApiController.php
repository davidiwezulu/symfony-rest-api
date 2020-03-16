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

            //------ Check to see if we already have a corresponding quote ----//
            $quoteRepository    = $em->getRepository(Quote::class);
            $quotes             = $quoteRepository->findBy($data);

            if ( ! empty($quotes) && count($quotes) > 0 ) {
                //---------- Send retrieved quote to APIs DTO ----------//
                $quoteData  = $quotes[0];
            } else {
                //~~~~~~~~~~~~~~~~ #\App\Traits\PremiumTrait ~~~~~~~~~~~~//
                //--------- Trigger API call to fetch ABI Code --------------//
                $data['abiCode']    = $this->abiCodeLookUp($data);
                $basePremium        = $this->getBasePremium($em);
                $premiumArray       = $this->fetchPremiumData($em, $data, $basePremium );
                $quoteData          = $this->persistNewQuote($em, $data, $premiumArray);
                //~~~~~~~~~~~~~~~~ End of Trait calls ~~~~~~~~~~~~~~~~~~~//
            }

            //--------- Building DTOs --------//
            $dataDTO['quotePolicyNumber']   = $quoteData->getPolicyNumber();
            $dataDTO['abiCode']             = $quoteData->getAbiCode();
            $dataDTO['regNo']               = $quoteData->getRegNo();;
            $dataDTO['postcode']            = $quoteData->getPostcode();
            $dataDTO['averagePremium']      = number_format($quoteData->getPremium(), 2);

            return $this->handleView($this->view(
                [
                    'success' => 'ok',
                    'data' => $dataDTO,
                    'message' => 'Premium Successfully Retrieved'
                ],
                Response::HTTP_CREATED)
            );
        }

        //------- Return failed process with validation errors --------//
        return $this->handleView($this->view($form->getErrors()));
    }
}
