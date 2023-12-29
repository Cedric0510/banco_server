<?php

namespace App\Controller;

use App\Entity\Transaction;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

class TransactionController extends AbstractController
{


    #[Route('/Transactions', name: 'all_transactions', methods: ['GET'])]
    public function getAll(SerializerInterface $serializer, EntityManagerInterface $entityManager): JsonResponse
    {
        $transactions = $entityManager->getRepository(Transaction::class)->findAll();
        $json = $serializer->serialize($transactions, 'json', ['groups' => 'transaction_read']);
        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    #[Route('/Transaction/{id}', name: 'transaction_by_id', methods: ['GET'])]
    public function getById(EntityManagerInterface $entityManager, SerializerInterface $serializer, int $id): Response
    {
        $transaction = $entityManager->getRepository(Transaction::class)->find($id);
        if (!$transaction) {
            throw $this->createNotFoundException(
                'Not found' . $id
            );
        }
        $json = $serializer->serialize($transaction, 'json', ['groups' => 'transaction_read']);
        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    #[Route('/Transaction', name: 'create_transaction', methods: ['POST'])]
    public function createTransaction(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator, SerializerInterface $serializer): Response
    {
        $data = $request->getContent();
        $transaction = $serializer->deserialize($data, Transaction::class, 'json');
        $entityManager->persist($transaction);
        $entityManager->flush();
        $json = $serializer->serialize($transaction, 'json', ['groups' => 'transaction_write']);
        return new JsonResponse($json, Response::HTTP_CREATED, [], true);
    }

    #[Route('/Transaction/edit/{id}', name: 'update_transaction', methods: ['PUT'])]
    public function updateTransaction(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer, int $id): Response
    {
        $transaction = $entityManager->getRepository(Transaction::class)->find($id);
        if (!$transaction) {
            throw $this->createNotFoundException('No transaction found for' . $id);
        }
        $serializer->deserialize($request->getContent(), Transaction::class, 'json', ['object_to_populate' => $transaction]);
        $entityManager->flush();
        return new JsonResponse(['status' => 'Transaction updated!'], Response::HTTP_OK);
    }

    #[Route('/Transaction/delete/{id}', name: 'delete_transaction', methods: ['DELETE'])]
    public function deleteTransaction(EntityManagerInterface $entityManager, int $id): Response
    {
        $transaction = $entityManager->getRepository(Transaction::class)->find($id);
        if (!$transaction) {
            throw $this->createNotFoundException(
                'Not found' . $id
            );
        }
        $entityManager->remove($transaction);
        $entityManager->flush();
        return new JsonResponse(['status' => 'Transaction deleted!'], Response::HTTP_OK);
    }
}
