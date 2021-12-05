<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class TrackPoint
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", name="track_point_id", options={"unsigned"=true})
     */
    private $id;

    /**
     * @var Habit
     * @ORM\ManyToOne(targetEntity=Habit::class, inversedBy="trackPoints")
     * @ORM\JoinColumn(nullable=false, name="habit_id", referencedColumnName="habit_id")
     */
    private $habit;

    /**
     * @var DateTimeInterface
     * @ORM\Column(type="date_immutable")
     */
    private $occurredAt;

    public function __construct(Habit $habit, DateTimeInterface $occurredAt)
    {
        $this->habit = $habit;
        $this->occurredAt = $occurredAt;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getOccurredAt(): DateTimeInterface
    {
        return $this->occurredAt;
    }
}
