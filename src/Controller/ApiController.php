<?php
namespace App\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Entity\AbiCodeRating;
use App\Form\PremiumType;
use Doctrine\ORM\EntityManagerInterface;
/**
 * Movie controller.
 * @Route("/api", name="api_")
 */
class ApiController extends FOSRestController
{
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

            $abiCodeRatingRepository = $em->getRepository(AbiCodeRating::class);
            $abiCodeRate = $abiCodeRatingRepository->findOneByAbiCode($AbiCode);





            //




            //$em = $this->getDoctrine()->getManager();
            //$em->persist($movie);
            //$em->flush();
            return $this->handleView($this->view(
                ['status' => 'ok'],
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