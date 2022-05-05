<?php

namespace App\Entity;

use App\Repository\EmployeeRequestsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EmployeeRequestsRepository::class)
 */
class EmployeeRequests
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $employee_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $manager_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="date")
     */
    private $vacation_start_date;

    /**
     * @ORM\Column(type="date")
     */
    private $vacation_end_date;

    /**
     * @ORM\Column(type="integer")
     */
    private $vacation_days;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmployeeId(): ?int
    {
        return $this->employee_id;
    }

    public function setEmployeeId(int $employee_id): self
    {
        $this->employee_id = $employee_id;

        return $this;
    }

    public function getManagerId(): ?int
    {
        return $this->manager_id;
    }

    public function setManagerId(int $manager_id): self
    {
        $this->manager_id = $manager_id;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getVacationStartDate(): ?\DateTimeInterface
    {
        return $this->vacation_start_date;
    }

    public function setVacationStartDate(\DateTimeInterface $vacation_start_date): self
    {
        $this->vacation_start_date = $vacation_start_date;

        return $this;
    }

    public function getVacationEndDate(): ?\DateTimeInterface
    {
        return $this->vacation_end_date;
    }

    public function setVacationEndDate(\DateTimeInterface $vacation_end_date): self
    {
        $this->vacation_end_date = $vacation_end_date;

        return $this;
    }

    public function getVacationDays(): int
    {
        return $this->vacation_days;
    }

    public function setVacationDays(int $vacation_days): self
    {
        $this->vacation_days = $vacation_days;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }
}
