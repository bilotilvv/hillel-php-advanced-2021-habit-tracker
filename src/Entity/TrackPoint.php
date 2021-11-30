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
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", options={"unsigned"=true})
     */
    private $trackPointId;

    /**
     * @var Habit
     *
     * @ORM\ManyToOne(targetEntity=Habit::class, inversedBy="trackPoints")
     * @ORM\JoinColumn(nullable=false, name="habit_id", referencedColumnName="habit_id")
     */
    private $habit;

    /**
     * @var DateTimeInterface
     *
     * @ORM\Column(type="date_immutable")
     */
    private $occurredAt;

    public function __construct(Habit $habit, DateTimeInterface $occurredAt)
    {
        $this->habit = $habit;
        $this->occurredAt = $occurredAt;
    }

    public function getTrackPointId(): int
    {
        return $this->trackPointId;
    }

    public function getOccurredAt(): DateTimeInterface
    {
        return $this->occurredAt;
    }
}
