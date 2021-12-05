<?php

namespace App\Entity;

use App\Repository\HabitRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=HabitRepository::class)
 */
class Habit
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", name="habit_id", options={"unsigned"=true})
     */
    private $id;

    /**
     * @var int
     * @ORM\Column(type="integer", name="user_id", nullable=false, options={"unsigned"=true})
     */
    private $userId;

    /**
     * @var string
     * @ORM\Column(type="string", length=30)
     * @Assert\Length(max=30)
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(type="string", length=20)
     */
    private $pointIcon;

    /**
     * @var string
     * @ORM\Column(type="string", length=20)
     */
    private $pointColor;

    /**
     * @var Collection&TrackPoint[]
     * @ORM\OneToMany(
     *     targetEntity=TrackPoint::class,
     *     mappedBy="habit",
     *     orphanRemoval=true,
     *     cascade={"persist", "remove"}
     * )
     */
    private $trackPoints;

    public function __construct(int $userId, string $name, string $pointIcon, string $pointColor)
    {
        $this->userId = $userId;
        $this->name = $name;
        $this->pointIcon = $pointIcon;
        $this->pointColor = $pointColor;
        $this->trackPoints = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getPointIcon(): string
    {
        return $this->pointIcon;
    }

    public function setPointIcon(string $pointIcon): void
    {
        $this->pointIcon = $pointIcon;
    }

    public function getPointColor(): string
    {
        return $this->pointColor;
    }

    public function setPointColor(string $pointColor): void
    {
        $this->pointColor = $pointColor;
    }

    public function addTrackPoint(DateTimeInterface $occurredAt): void
    {
        $this->trackPoints->add(new TrackPoint($this, $occurredAt));
    }

    public function removeTrackPoint(DateTimeInterface $occurredAt): void
    {
        $occurredAtFormatted = $occurredAt->format('Y-m-d');
        /** @var TrackPoint $trackPoint */
        foreach ($this->trackPoints as $trackPoint) {
            if ($trackPoint->getOccurredAt()->format('Y-m-d') === $occurredAtFormatted) {
                $this->trackPoints->removeElement($trackPoint);

                return;
            }
        }

        throw new \DomainException('TrackPoint not found');
    }
}
