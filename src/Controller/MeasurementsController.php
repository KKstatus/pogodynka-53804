<?php

namespace App\Controller;

use App\Entity\Measurements;
use App\Form\MeasurementsType;
use App\Repository\MeasurementsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/measurements')]
final class MeasurementsController extends AbstractController
{
    #[Route(name: 'app_measurements_index', methods: ['GET'])]
    public function index(MeasurementsRepository $measurementsRepository): Response
    {
        return $this->render('measurements/index.html.twig', [
            'measurements' => $measurementsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_measurements_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $measurement = new Measurements();
        $form = $this->createForm(MeasurementsType::class, $measurement, [
        'validation_groups' => 'create',
]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($measurement);
            $entityManager->flush();

            return $this->redirectToRoute('app_measurements_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('measurements/new.html.twig', [
            'measurement' => $measurement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_measurements_show', methods: ['GET'])]
    public function show(Measurements $measurement): Response
    {
        return $this->render('measurements/show.html.twig', [
            'measurement' => $measurement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_measurements_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Measurements $measurement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MeasurementsType::class, $measurement, [
        'validation_groups' => 'edit',
]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_measurements_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('measurements/edit.html.twig', [
            'measurement' => $measurement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_measurements_delete', methods: ['POST'])]
    public function delete(Request $request, Measurements $measurement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$measurement->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($measurement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_measurements_index', [], Response::HTTP_SEE_OTHER);
    }
}
