<?php

namespace App\Entity;

use App\Repository\TimeSlotRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use DateTime;

#[ORM\Entity(repositoryClass: TimeSlotRepository::class)]
class TimeSlot
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'timeSlots')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $worker = null;

    #[ORM\ManyToOne(inversedBy: 'timeSlots')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Task $task = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\LessThan(
        propertyPath: "endDate", 
        message: "La date de début doit être antérieure à la date de fin.")]
    private ?DateTime $startDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\GreaterThan(
        propertyPath: "startDate", 
        message: "La date de fin doit être postérieure à la date de début.")]
    private ?DateTime $endDate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWorker(): ?User
    {
        return $this->worker;
    }

    public function setWorker(?User $worker): static
    {
        $this->worker = $worker;

        return $this;
    }

    public function getTask(): ?Task
    {
        return $this->task;
    }

    public function setTask(?Task $task): static
    {
        $this->task = $task;

        return $this;
    }

    public function getStartDate(): ?DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(DateTime $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?DateTime
    {
        return $this->endDate;
    }

    public function setEndDate(DateTime $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }
}
