<?php

namespace App\Entity;

use App\Repository\TransactionTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TransactionTypeRepository::class)]
class TransactionType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["transactionType_group"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["transactionType_group", "transaction_read"])]
    private ?string $trt_type = null;

    #[ORM\OneToMany(mappedBy: 'fk_trt_id', targetEntity: Transaction::class)]
    private Collection $transactions;

    public function __construct()
    {
        $this->transactions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTrtType(): ?string
    {
        return $this->trt_type;
    }

    public function setTrtType(string $trt_type): static
    {
        $this->trt_type = $trt_type;

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
            $transaction->setFkTrtId($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): static
    {
        if ($this->transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getFkTrtId() === $this) {
                $transaction->setFkTrtId(null);
            }
        }

        return $this;
    }
}
