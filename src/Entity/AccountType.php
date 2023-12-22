<?php

namespace App\Entity;

use App\Repository\AccountTypeRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: AccountTypeRepository::class)]
class AccountType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["account_type_groups",])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["account_type_groups", "BankAccount_group"])]

    private ?string $act_type = null;

    #[ORM\OneToMany(mappedBy: 'fk_act_id', targetEntity: BankAccount::class)]


    private Collection $bankAccounts;

    public function __construct()
    {
        $this->bankAccounts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getActType(): ?string
    {
        return $this->act_type;
    }

    public function setActType(string $act_type): static
    {
        $this->act_type = $act_type;

        return $this;
    }
    /**
     * @return Collection<int, BankAccount>
     */
    public function getBankAccounts(): Collection
    {
        return $this->bankAccounts;
    }

    public function addBankAccount(BankAccount $bankAccount): static
    {
        if (!$this->bankAccounts->contains($bankAccount)) {
            $this->bankAccounts->add($bankAccount);
            $bankAccount->setFkActId($this);
        }

        return $this;
    }

    public function removeBankAccount(BankAccount $bankAccount): static
    {
        if ($this->bankAccounts->removeElement($bankAccount)) {
            // set the owning side to null (unless already changed)
            if ($bankAccount->getFkActId() === $this) {
                $bankAccount->setFkActId(null);
            }
        }

        return $this;
    }
}
