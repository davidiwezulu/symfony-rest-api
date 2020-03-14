<?php
namespace App\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Entity\Movie;
use App\Form\PremiumType;
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
     * Create Movie.
     * @Rest\Post("/premium")
     *
     * @return Response
     */
    public function postPremium(Request $request)
    {
        $movie = new Movie();

        $form = $this->createForm(PremiumType::class);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        if ($form->isSubmitted() && $form->isValid()) {

            //--------- Trigger API call for Abi Code --------------//


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