<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Transaction;

class Transactions
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getTransactionsByDebitType(int $bnkId, int $debit)
    {
        $req = 'SELECT t.trs_amount, t.trs_debit, t.trs_date, c.cat_type
                FROM App\Entity\Transaction t
                INNER JOIN t.fk_cat_id c
                INNER JOIN t.fk_bnk_id b
                WHERE t.trs_debit = :debit AND b.id = :bnkId';

        $query = $this->em->createQuery($req)
                          ->setParameter('bnkId', $bnkId)
                          ->setParameter('debit', $debit);

        return $query->getResult();
    }

    public function getSumTransactionsByDebit(int $bnkId)
    {
        $sumReq = 'SELECT SUM(t.trs_amount) 
                   FROM App\Entity\Transaction t
                   INNER JOIN t.fk_bnk_id b
                   WHERE t.trs_debit = 1 AND b.id = :bnkId';

        $sumQuery = $this->em->createQuery($sumReq)
                             ->setParameter('bnkId', $bnkId);

        return $sumQuery->getSingleScalarResult();
    }

    public function getSumTransactionsByCredit(int $bnkId)
    {
        $sumReq = 'SELECT SUM(t.trs_amount) 
                   FROM App\Entity\Transaction t
                   INNER JOIN t.fk_bnk_id b
                   WHERE t.trs_debit = 0 AND b.id = :bnkId';

        $sumQuery = $this->em->createQuery($sumReq)
                             ->setParameter('bnkId', $bnkId);

        return $sumQuery->getSingleScalarResult();
    }

    public function getTransactionsByAccount(int $bnkId)
    {
        $req = 'SELECT t.trs_amount, t.trs_debit, t.trs_date, c.cat_type
                FROM App\Entity\Transaction t
                INNER JOIN t.fk_cat_id c
                INNER JOIN t.fk_bnk_id b
                WHERE b.id = :bnkId';

        $query = $this->em->createQuery($req)->setParameter('bnkId', $bnkId);
        return $query->getResult();
    }

    public function getTransactionsByCategory(int $bnkId, string $catType)
    {
        $req = 'SELECT t.trs_amount, t.trs_debit, t.trs_date, c.cat_type
                FROM App\Entity\Transaction t
                INNER JOIN t.fk_cat_id c
                INNER JOIN t.fk_bnk_id b
                WHERE c.cat_type = :catType AND b.id = :bnkId';

        $query = $this->em->createQuery($req)
                          ->setParameter('bnkId', $bnkId)
                          ->setParameter('catType', $catType);
        return $query->getResult();
    }
}