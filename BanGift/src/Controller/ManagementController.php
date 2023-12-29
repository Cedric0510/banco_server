<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Transactions;
use Symfony\Component\Serializer\SerializerInterface;

class ManagementController extends AbstractController
{
    private $transactions;
    private $serializer;

    public function __construct(Transactions $transactions, SerializerInterface $serializer)
    {
        $this->transactions = $transactions;
        $this->serializer = $serializer;
    }

    #[Route('management/{bnkId}/{catType}', name: 'transactions_by_cat', methods: ['GET'])]
    public function getTransactionsByCat(int $bnkId, string $catType): JsonResponse
    {
        $result = $this->transactions->getTransactionsByCategory($bnkId, $catType);
        $json = $this->serializer->serialize($result, 'json');
        return new JsonResponse($json, JsonResponse::HTTP_OK, [], true);
    }
}