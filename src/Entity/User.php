<?php

namespace App\Entity;

use App\Enum\ContractEnum;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(max: 255)]
    #[Assert\NotBlank]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(max: 255)]
    #[Assert\NotBlank]
    private ?string $lastName = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\Length(max: 255)]
    #[Assert\NotBlank]
    #[Assert\Email]
    private ?string $email = null;

    #[ORM\Column]
    private bool $isActive = true;

    #[ORM\Column(length: 255)]
    #[Assert\Length(max: 255)]
    private ?string $role = null;

    #[ORM\Column(nullable: true)]
    private ?DateTime $hiredOn = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(max: 255)]
    #[Assert\NotBlank]
    private ?string $password = null;


    #[ORM\Column(length: 255)]
    #[Assert\Length(max: 255)]
    #[Assert\Choice(callback: 'App\\Enum\\ContractEnum::values')]
    private ?string $status = null;

    /**
     * @var Collection<int, Project>
     */
    #[ORM\ManyToMany(targetEntity: Project::class, inversedBy: 'users')]
    private Collection $projects;

    /**
     * @var Collection<int, TimeSlot>
     */
    #[ORM\OneToMany(targetEntity: TimeSlot::class, mappedBy: 'worker', orphanRemoval: true)]
    private Collection $timeSlots;

    /**
     * @var Collection<int, Task>
     */
    #[ORM\ManyToMany(targetEntity: Task::class, inversedBy: 'users')]
    private Collection $tasks;

    public function __construct()
    {
        $this->projects = new ArrayCollection();
        $this->timeSlots = new ArrayCollection();
        $this->tasks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getHiredOn(): ?DateTime
    {
        return $this->hiredOn;
    }

    public function setHiredOn(DateTime $hiredOn): static
    {
        $this->hiredOn = $hiredOn;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getStatus(): ?ContractEnum
    {
        return $this->status ? ContractEnum::tryFrom($this->status) : null;
    }

    public function setStatus(string | ContractEnum | null $status): static
    {
        if ($status instanceof ContractEnum) {
            $this->status = $status->value;
        } else {
            $this->status = $status;
        }

        return $this;
    }

    /**
     * @return Collection<int, Project>
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(Project $project): static
    {
        if (!$this->projects->contains($project)) {
            $this->projects->add($project);
        }

        return $this;
    }

    public function removeProject(Project $project): static
    {
        $this->projects->removeElement($project);

        return $this;
    }

    /**
     * @return Collection<int, TimeSlot>
     */
    public function getTimeSlots(): Collection
    {
        return $this->timeSlots;
    }

    public function addTimeSlot(TimeSlot $timeSlot): static
    {
        if (!$this->timeSlots->contains($timeSlot)) {
            $this->timeSlots->add($timeSlot);
            $timeSlot->setWorker($this);
        }

        return $this;
    }

    public function removeTimeSlot(TimeSlot $timeSlot): static
    {
        if ($this->timeSlots->removeElement($timeSlot)) {
            // set the owning side to null (unless already changed)
            if ($timeSlot->getWorker() === $this) {
                $timeSlot->setWorker(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Task>
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Task $task): static
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks->add($task);
        }

        return $this;
    }

    public function removeTask(Task $task): static
    {
        $this->tasks->removeElement($task);

        return $this;
    }

    public function __toString(): string
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    public function getInitials(): string
    {
        $first = $this->firstName ? mb_substr($this->firstName, 0, 1) : '';
        $last = $this->lastName ? mb_substr($this->lastName, 0, 1) : '';

        return mb_strtoupper($first . $last);
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = [];
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';
        $roles[] = $this->role;

        return array_unique($roles);
    }
}
