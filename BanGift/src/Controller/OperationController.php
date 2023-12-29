<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Transactions;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\BankAccount;
use Symfony\Component\Serializer\SerializerInterface;

class OperationController extends AbstractController
{
    #[Route('/operation', name: 'app_operation')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/OperationController.php',
        ]);
    }

    private $transactions;
    private $entityManager;
    private $serializer;

    public function __construct(Transactions $transactions, EntityManagerInterface $entityManager, 
    SerializerInterface $serializer)
    {
        $this->transactions = $transactions;
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
    }

    #[Route('operation/account/{bnkId}', name: 'transactions_by_account', methods: ['GET'])]
    public function getTransactionsByAccount(int $bnkId): JsonResponse
    {
        $result = $this->transactions->getTransactionsByAccount($bnkId);
        $json = $this->serializer->serialize($result, 'json');
        return new JsonResponse($json, JsonResponse::HTTP_OK, [], true);
    }

    #[Route('operation/{bnkId}/{debit}', name: 'operations_by_account', methods: ['GET'])]
    public function getTransactionsByDebitTypes(int $bnkId, int $debit): JsonResponse
    {
        $result = $this->transactions->getTransactionsByDebitType($bnkId, $debit);
        return $this->json($result);
    }

    #[Route('operation/{bnkId}', name: 'operations_balance', methods: ['GET'])]
    public function updateBalanceByTransaction(int $bnkId): JsonResponse
    {
        $debit = $this->transactions->getSumTransactionsByDebit($bnkId);
        $credit = $this->transactions->getSumTransactionsByCredit($bnkId);
        $balanceUpdate = $credit - $debit;

        $bankAccount = $this->entityManager->getRepository(BankAccount::class)->find($bnkId);

        if (!$bankAccount) {
            return $this->json(['error' => 'BankAccount not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $bankAccount->setBnkBalance(abs($balanceUpdate));
        $bankAccount->setBnkDebit($balanceUpdate < 0 ? 1 : 0);

        $this->entityManager->persist($bankAccount);
        $this->entityManager->flush();

        return $this->json(['success' => 'Bank balance updated']);
    }
}