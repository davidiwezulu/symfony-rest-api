<?php
namespace App\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Entity\PostcodeRating;
use App\Entity\AbiCodeRating;
use App\Traits\PremiumTrait;
use App\Form\PremiumType;
use Doctrine\ORM\EntityManagerInterface;
/**
 * Movie controller.
 * @Route("/api", name="api_")
 */
class ApiController extends FOSRestController
{
    //----- Premium rate calculator trait ------//
    use PremiumTrait;

    /**
     * Lists all Premiums.
     * @Rest\Get("/Premiums")
     *
     * @return Response
     */
    public function getMovieAction()
    {
        $repository = $this->getDoctrine()->getRepository(Movie::class);
        $movies = $repository->findall();
        return $this->handleView($this->view($movies));
    }

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

            //--------- Trigger API call for Abi Code --------------//
            $AbiCode    = 22529902;
            $basePremium = 500.00;

            //----------- AbiCode Rate Premium ---------------//
            $abiCodeRatingRepository = $em->getRepository(AbiCodeRating::class);
            $abiCodeRatePremium = $this->calculateRatingFactor(
                $abiCodeRatingRepository,
                $AbiCode,
                $basePremium
            );

            //------------ Postcode Premium -------------------//
            $PostcodeRatingRepository = $em->getRepository(PostcodeRating::class);
            $postcodeRatePremium = $this->calculateRatingFactor(
                $PostcodeRatingRepository,
                $data['postcode'],
                $basePremium
            );



            //--------- Building DTOs --------//
            $dataDTO['abiCodeRatePremium']  = $abiCodeRatePremium;
            $dataDTO['postcodeRatePremium'] = $postcodeRatePremium;








            //




            //$em = $this->getDoctrine()->getManager();
            //$em->persist($movie);
            //$em->flush();
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