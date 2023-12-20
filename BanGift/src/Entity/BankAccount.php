<?php

namespace App\Entity;

use App\Repository\BankAccountRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BankAccountRepository::class)]
class BankAccount
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $bnk_balance = null;

    #[ORM\Column]
    private ?bool $bnk_debit = null;

    #[ORM\ManyToOne(inversedBy: 'bankAccounts')]
    private ?User $fk_usr_id = null;

    #[ORM\ManyToOne(inversedBy: 'bankAccounts')]
    private ?AccountType $fk_act_id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Forecast $fk_frc_id = null;

    #[ORM\OneToMany(mappedBy: 'fk_bnk_id', targetEntity: Transaction::class)]
    private Collection $transactions;

    public function __construct()
    {
        $this->transactions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBnkBalance(): ?float
    {
        return $this->bnk_balance;
    }

    public function setBnkBalance(float $bnk_balance): static
    {
        $this->bnk_balance = $bnk_balance;

        return $this;
    }

    public function isBnkDebit(): ?bool
    {
        return $this->bnk_debit;
    }

    public function setBnkDebit(bool $bnk_debit): static
    {
        $this->bnk_debit = $bnk_debit;

        return $this;
    }

    public function getFkUsrId(): ?User
    {
        return $this->fk_usr_id;
    }

    public function setFkUsrId(?User $fk_usr_id): static
    {
        $this->fk_usr_id = $fk_usr_id;

        return $this;
    }

    public function getFkActId(): ?AccountType
    {
        return $this->fk_act_id;
    }

    public function setFkActId(?AccountType $fk_act_id): static
    {
        $this->fk_act_id = $fk_act_id;

        return $this;
    }
    public function getFkFrcId(): ?Forecast
    {
        return $this->fk_frc_id;
    }

    public function setFkFrcId(?Forecast $fk_frc_id): static
    {
        $this->fk_frc_id = $fk_frc_id;

        return $this;
    }

    /**
     * @return Collection<int, Transaction>
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(Transaction $transaction): static
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions->add($transaction);
            $transaction->setFkBnkId($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): static
    {
        if ($this->transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getFkBnkId() === $this) {
                $transaction->setFkBnkId(null);
            }
        }

        return $this;
    }
}
