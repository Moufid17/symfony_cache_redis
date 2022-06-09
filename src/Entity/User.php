<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    private $Lastname;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $email;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $age;

    #[ORM\OneToMany(mappedBy: 'userFrom', targetEntity: Payment::class)]
    private $payments;

    #[ORM\OneToMany(mappedBy: 'userTo', targetEntity: Payment::class)]
    private $paymentsFrom;

    public function __construct()
    {
        $this->payments = new ArrayCollection();
        $this->paymentsFrom = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->Lastname;
    }

    public function setLastname(string $Lastname): self
    {
        $this->Lastname = $Lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(?int $age): self
    {
        $this->age = $age;

        return $this;
    }

    /**
     * @return Collection<int, Payment>
     */
    public function getPayments(): Collection
    {
        return $this->payments;
    }

    public function addPayment(Payment $payment): self
    {
        if (!$this->payments->contains($payment)) {
            $this->payments[] = $payment;
            $payment->setUserFrom($this);
        }

        return $this;
    }

    public function removePayment(Payment $payment): self
    {
        if ($this->payments->removeElement($payment)) {
            // set the owning side to null (unless already changed)
            if ($payment->getUserFrom() === $this) {
                $payment->setUserFrom(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Payment>
     */
    public function getPaymentsFrom(): Collection
    {
        return $this->paymentsFrom;
    }

    public function addPaymentsFrom(Payment $paymentsFrom): self
    {
        if (!$this->paymentsFrom->contains($paymentsFrom)) {
            $this->paymentsFrom[] = $paymentsFrom;
            $paymentsFrom->setUserTo($this);
        }

        return $this;
    }

    public function removePaymentsFrom(Payment $paymentsFrom): self
    {
        if ($this->paymentsFrom->removeElement($paymentsFrom)) {
            // set the owning side to null (unless already changed)
            if ($paymentsFrom->getUserTo() === $this) {
                $paymentsFrom->setUserTo(null);
            }
        }

        return $this;
    }

}
