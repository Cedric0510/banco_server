<?php

namespace App\Entity;

use App\Repository\TransactionTypeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TransactionTypeRepository::class)]
class TransactionType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $trt_type = null;

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
}
