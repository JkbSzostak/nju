<?php

namespace App\Entity;

use App\Repository\UnitRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UnitRepository::class)]
class Unit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 244, nullable: true)]
    #[Assert\Length(max: 244)]
    #[Assert\Type('string')]
    private ?string $verificationStatus = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVerificationStatus(): ?string
    {
        return $this->verificationStatus;
    }

    public function setVerificationStatus(?string $verificationStatus): static
    {
        $this->verificationStatus = $verificationStatus;

        return $this;
    }
}