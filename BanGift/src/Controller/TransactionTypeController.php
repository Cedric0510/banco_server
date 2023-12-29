<?php

namespace App\Controller;

use App\Entity\TransactionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TransactionTypeController extends AbstractController
{

    //Read All
    #[Route('/TransactionTypes', name: 'app_transactiontype_all', methods: ['GET'])]

    public function getAll(SerializerInterface $serializer, EntityManagerInterface $entityManager): Response
    {

        $transactionType = $entityManager->getRepository(TransactionType::class)->findAll();

        $json = $serializer->serialize($transactionType, 'json', ["groups" => "transactionType_group"]);

        return new JsonResponse($json, Response::HTTP_OK, [], true); // HHTP OK = statut 200

    }
    // Read One:
    #[Route('/TransactionType/{id}', name: 'app_transactiontype_one', methods: ['GET'])]
    public function getById(SerializerInterface $serializer, EntityManagerInterface $entityManager, int $id): Response
    {
        $transactionType = $entityManager->getRepository(TransactionType::class)->find($id);

        if (!$transactionType) {
            throw $this->createNotFoundException(
                'No Transaction Type found for id ' . $id
            );
        }

        $json = $serializer->serialize($transactionType, 'json', ["groups" => "transactionType_group"]);

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }
    //Create:
    #[Route('/TransactionType', name: 'app_transactiontype_new', methods: ['POST'])]
    public function createProduct(Request $request, ValidatorInterface $validator, EntityManagerInterface $entityManager, SerializerInterface $serializer): Response
    {
        $data = $request->getContent();
        $transactionType = $serializer->deserialize($data, TransactionType::class, 'json');

        $entityManager->persist($transactionType);

        $entityManager->flush();

        $json = $serializer->serialize($transactionType, 'json', ["groups" => "transactionType_group"]);

        return new JsonResponse($json, Response::HTTP_CREATED, [], true);

        $errors = $validator->validate($transactionType);
        if (count($errors) > 0) {
            return new Response((string) $errors, 400);
        }
    }
    //Update:
    #[Route('/TransactionType/edit/{id}', name: 'app_transactiontype_edit', methods: ['PUT'])]
    public function update(EntityManagerInterface $entityManager, SerializerInterface $serializer, int $id, Request $request): Response
    {
        $transactionType = $entityManager->getRepository(TransactionType::class)->find($id);
        $data = $request->getContent();
        if (!$transactionType) {
            throw $this->createNotFoundException(
                'No transaction type for id ' . $id
            );
        }
        $editTransactionType = $serializer->deserialize($data, TransactionType::class, 'json');
        $transactionType->setTrtType($editTransactionType->getTrtType());
        $entityManager->flush();
        $json = $serializer->serialize($transactionType, 'json', ["groups" => "transactionType_group"]);
        return new JsonResponse($json, Response::HTTP_ACCEPTED, [], true);
    }
    //Delete:
    #[Route('/TransactionType/delete/{id}', name: 'app_transactiontype_delete', methods: ['DELETE'])]
    public function delete(EntityManagerInterface $entityManager, int $id): Response
    {
        $transactionType = $entityManager->getRepository(TransactionType::class)->find($id);

        if (!$transactionType) {
            throw $this->createNotFoundException(
                'No Transaction type for id ' . $id
            );
        }

        $entityManager->remove($transactionType);
        $entityManager->flush();

        return new JsonResponse(['status' => 'Transaction type deleted!'], Response::HTTP_OK);
    }
}
