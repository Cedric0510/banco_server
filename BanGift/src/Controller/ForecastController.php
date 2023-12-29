<?php

namespace App\Controller;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Forecast;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;



class ForecastController extends AbstractController
{

    #[Route('/Forecasts', name: 'app_forecasts', methods: ['GET'])]
    public function getAll(SerializerInterface $serializer, EntityManagerInterface $em): Response
    {
        $forecast = $em->getRepository(Forecast::class)->findAll();
        $json = $serializer->serialize($forecast, 'json', ['groups' => 'forecast_group']);

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    #[Route('/Forecast/{id}', name: 'forecast_show', methods: ['GET'])]
    public function show(EntityManagerInterface $entityManager, SerializerInterface $serializer, int $id): Response
    {
        $forecast = $entityManager->getRepository(Forecast::class)->find($id);

        if (!$forecast) {
            throw $this->createNotFoundException(
                'No forecast found for id ' . $id
            );
        }

        $json = $serializer->serialize($forecast, 'json', ["groups" => "forecast_group"]);
        // return new Response('Check out this forecast: ' . $forecast->getFrcAmounts() . ',' . $forecast->getId() . ' .');
        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    #[Route('/Forecast', name: 'create_forecast', methods: ['POST'])]
    public function createForecast(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator, SerializerInterface $serializer,): Response
    {
        $data = $request->getContent();
        $forecast = $serializer->deserialize($data, Forecast::class, 'json');
        $entityManager->persist($forecast);
        $entityManager->flush();
        $json = $serializer->serialize($forecast, 'json', ["groups" => "forecast_group"]);
        return new JsonResponse($json, Response::HTTP_CREATED, [], true);
    }


    #[Route('/Forecast/edit/{id}', name: 'update_forecase', methods: ['PUT'])]
    public function updateForecast(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer, int $id): Response
    {
        $forecast = $entityManager->getRepository(Forecast::class)->find($id);
        if (!$forecast) {
            throw $this->createNotFoundException('No forecast found for' . $id);
        }
        $serializer->deserialize($request->getContent(), Forecast::class, 'json', ['object_to_populate' => $forecast]);
        $entityManager->flush();
        return new JsonResponse(['status' => 'Forecast updated!'], Response::HTTP_OK);
    }

    #[Route('/Forecast/delete/{id}', name: 'forecast_edit')]
    public function delete(EntityManagerInterface $entityManager, int $id): Response
    {
        $forecast = $entityManager->getRepository(Forecast::class)->find($id);

        if (!$forecast) {
            throw $this->createNotFoundException(
                'No forecast found for id ' . $id
            );
        }

        $entityManager->remove($forecast);
        $entityManager->flush();

        return new JsonResponse(['status' => 'Forecase deleted!'], Response::HTTP_OK);
    }
}
