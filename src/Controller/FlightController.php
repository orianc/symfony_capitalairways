<?php

namespace App\Controller;

use App\Entity\Flight;
use App\Form\FlightType;
use App\Repository\FlightRepository;
use App\Services\FlightService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/Flight")
 */
class FlightController extends AbstractController
{
    private $em;

    function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    
    /**
     * @Route("/", name="flight_index", methods={"GET"})
     */
    public function index(FlightRepository $flightRepository): Response
    {
        return $this->render('flight/index.html.twig', [
            'flights' => $flightRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="flight_new", methods={"GET","POST"})
     */
    public function new(Request $request, FlightService $fs): Response
    {
        $flight = new Flight();
        $flight->setNumero($fs->getFlightNumber());

        $form = $this->createForm(FlightType::class, $flight);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($flight);
            $this->em->flush();
            $this->addFlash('success', 'Creation Flight Success.');

            return $this->redirectToRoute('flight_new');
        }

        return $this->render('flight/new.html.twig', [
            'flight' => $flight,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="flight_show", methods={"GET"})
     */
    public function show(Flight $flight): Response
    {
        return $this->render('flight/show.html.twig', [
            'flight' => $flight,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="flight_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Flight $flight): Response
    {
        $form = $this->createForm(FlightType::class, $flight);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('flight_index');
        }

        return $this->render('flight/edit.html.twig', [
            'flight' => $flight,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="flight_delete", methods={"POST"})
     */
    public function delete(Request $request, Flight $flight): Response
    {
        if ($this->isCsrfTokenValid('delete'.$flight->getId(), $request->request->get('_token'))) {
            $this->em->remove($flight);
            $this->em->flush();
        }

        return $this->redirectToRoute('flight_index');
    }
}
