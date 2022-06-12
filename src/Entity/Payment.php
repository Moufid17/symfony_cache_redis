<?php

namespace App\Entity;

use App\Repository\PaymentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaymentRepository::class)]
class Payment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'array', nullable: true)]
    private $datas = [];

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'payments')]
    #[ORM\JoinColumn(nullable: false)]
    private $userFrom;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'paymentsFrom')]
    #[ORM\JoinColumn(nullable: false)]
    private $userTo;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatas(): ?array
    {
        return $this->datas;
    }

    public function setDatas(?array $datas): self
    {
        $this->datas = $datas;

        return $this;
    }

    public function getUserFrom(): ?User
    {
        return $this->userFrom;
    }

    public function setUserFrom(?User $userFrom): self
    {
        $this->userFrom = $userFrom;

        return $this;
    }

    public function getUserTo(): ?User
    {
        return $this->userTo;
    }

    public function setUserTo(?User $userTo): self
    {
        $this->userTo = $userTo;

        return $this;
    }
}
