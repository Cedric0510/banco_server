<?php

namespace App\Entity;

use App\Repository\TransactionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TransactionRepository::class)]
class Transaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $trs_date = null;

    #[ORM\Column]
    private ?float $trs_amount = null;

    #[ORM\Column]
    private ?bool $trs_debit = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'transactions')]
    private ?self $fk_trt_id = null;

    #[ORM\OneToMany(mappedBy: 'fk_trt_id', targetEntity: self::class)]
    private Collection $transactions;

    #[ORM\ManyToOne(inversedBy: 'transactions')]
    private ?Category $fk_cat_id = null;

    #[ORM\ManyToOne(inversedBy: 'transactions')]
    private ?BankAccount $fk_bnk_id = null;

    public function __construct()
    {
        $this->transactions = new ArrayCollection();
    }

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

    public function getFkTrtId(): ?self
    {
        return $this->fk_trt_id;
    }

    public function setFkTrtId(?self $fk_trt_id): static
    {
        $this->fk_trt_id = $fk_trt_id;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(self $transaction): static
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions->add($transaction);
            $transaction->setFkTrtId($this);
        }

        return $this;
    }

    public function removeTransaction(self $transaction): static
    {
        if ($this->transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getFkTrtId() === $this) {
                $transaction->setFkTrtId(null);
            }
        }

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
