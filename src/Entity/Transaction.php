<?php

namespace App\Entity;

use App\Repository\TransactionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TransactionRepository::class)]
class Transaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["transaction_read"])]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(["transaction_read", "transaction_write", "banqueAccount_group"])]
    private ?\DateTimeInterface $trs_date = null;

    #[ORM\Column]
    #[Groups(["transaction_read", "transaction_write", "banqueAccount_group"])]
    private ?float $trs_amount = null;

    #[ORM\Column]
    #[Groups(["transaction_read", "transaction_write", "banqueAccount_group"])]
    private ?bool $trs_debit = null;

    #[ORM\ManyToOne(fetch: 'EAGER', inversedBy: 'transactions', cascade: ['persist'])]
    #[Groups(["transaction_write", "transaction_read"])]
    private ?TransactionType $fk_trt_id = null;

    #[ORM\ManyToOne(fetch: 'EAGER', inversedBy: 'transactions', cascade: ['persist'])]
    #[Groups(["transaction_read", "transaction_write", "banqueAccount_group"])]
    private ?Category $fk_cat_id = null;

    #[ORM\ManyToOne(fetch: 'EAGER', inversedBy: 'transactions', cascade: ['persist'])]
    #[Groups(["transaction_write"])]
    private ?BankAccount $fk_bnk_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTrsDate(): ?\DateTimeInterface
    {
        return $this->trs_date;
    }

    public function setTrsDate(\DateTimeInterface $trs_date): static
    {
        $this->trs_date = $trs_date;

        return $this;
    }

    public function getTrsAmount(): ?float
    {
        return $this->trs_amount;
    }

    public function setTrsAmount(float $trs_amount): static
    {
        $this->trs_amount = $trs_amount;

        return $this;
    }

    public function isTrsDebit(): ?bool
    {
        return $this->trs_debit;
    }

    public function setTrsDebit(bool $trs_debit): static
    {
        $this->trs_debit = $trs_debit;

        return $this;
    }

    public function getFkTrtId(): ?TransactionType
    {
        return $this->fk_trt_id;
    }

    public function setFkTrtId(?TransactionType $fk_trt_id): static
    {
        $this->fk_trt_id = $fk_trt_id;

        return $this;
    }

    public function getFkCatId(): ?Category
    {
        return $this->fk_cat_id;
    }

    public function setFkCatId(?Category $fk_cat_id): static
    {
        $this->fk_cat_id = $fk_cat_id;

        return $this;
    }

    public function getFkBnkId(): ?BankAccount
    {
        return $this->fk_bnk_id;
    }

    public function setFkBnkId(?BankAccount $fk_bnk_id): static
    {
        $this->fk_bnk_id = $fk_bnk_id;

        return $this;
    }
}
