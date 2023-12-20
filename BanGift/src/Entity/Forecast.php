<?php

namespace App\Entity;

use App\Repository\ForecastRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ForecastRepository::class)]
class Forecast
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["forecast_group"])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(["forecast_group"])]
    private array $frc_amounts = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFrcAmounts(): array
    {
        return $this->frc_amounts;
    }

    public function setFrcAmounts(array $frc_amounts): static
    {
        $this->frc_amounts = $frc_amounts;

        return $this;
    }
}
