<?php

namespace App\Controller;

use App\Entity\Habit;
use App\Form\HabitFormType;
use App\Repository\HabitRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class HabitController extends AbstractController
{
    /**
     * @Route("/habits", methods={"GET"}, name="app_habit_list")
     */
    public function list(
        UserInterface $user,
        HabitRepository $habitRepository
    ): Response {
        $habits = $habitRepository->findBy(['userId' => $user->getUserId()]);

        return $this->render('habit/list.html.twig', [
            'habits' => $habits,
        ]);
    }

    /**
     * @Route("/habits/add", methods={"GET", "POST"}, name="app_habit_add")
     */
    public function addHabit(
        Request $request,
        EntityManagerInterface $entityManger
    ): Response {
        $form = $this->createForm(HabitFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Habit $habit */
            $habit = $form->getData();
            $entityManger->persist($habit);
            $entityManger->flush();

            return $this->redirectToRoute('app_habit_list');
        }

        return $this->renderForm('habit/add.html.twig', [
            'form' => $form,
        ]);
    }


    /**
     * @Route("/habits/{habitId}/edit", methods={"GET", "POST"}, name="app_habit_edit")
     */
    public function editHabit(
        int $habitId,
        UserInterface $user,
        Request $request,
        HabitRepository $habitRepository,
        EntityManagerInterface $entityManger
    ): Response {
        $habit = $habitRepository->findOneBy(['id' => $habitId, 'userId' => $user->getUserId()]);
        if (null === $habit) {
            throw new NotFoundHttpException('Habit not found');
        }

        $form = $this->createForm(HabitFormType::class, $habit);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManger->flush();

            return $this->redirectToRoute('app_habit_list');
        }

        return $this->renderForm('habit/edit.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @Route("/habits/{habitId}/delete", methods={"POST"}, name="app_habit_delete")
     */
    public function deleteHabit(
        int $habitId,
        UserInterface $user,
        HabitRepository $habitRepository,
        EntityManagerInterface $entityManger
    ): Response {
        $habit = $habitRepository->findOneBy(['id' => $habitId, 'userId' => $user->getUserId()]);
        if (null === $habit) {
            throw new NotFoundHttpException('Habit not found');
        }

        $entityManger->remove($habit);
        $entityManger->flush();

        return $this->redirectToRoute('app_habit_list');
    }

    /**
     * @Route("/habits/week/{year}/{week}", methods={"GET"}, name="app_habit_week")
     */
    public function week(
        UserInterface   $user,
        HabitRepository $habitRepository,
        string          $year = null,
        string          $week = null
    ): Response {
        $today = new DateTimeImmutable('midnight');
        $year = $year ?? $today->format('Y');
        $week = $week ?? $today->format('W');
        $fromDate = $today->setISODate($year, $week);
        $toDate = $fromDate->modify('Next Sunday');

        $habits = $habitRepository->findBy(['userId' => $user->getUserId()]);

        return $this->render('habit/week.html.twig', [
            'fromDate' => $fromDate,
            'toDate'   => $toDate,
            'habits'   => $habits,
        ]);
    }

    /**
     * @Route("/habit/{habitId}/track-point/{date}/add", methods={"POST"}, name="app_habit_add_track_point")
     */
    public function addTrackPoint(
        int                    $habitId,
        string                 $date,
        UserInterface          $user,
        HabitRepository        $habitRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $habit = $habitRepository->findOneBy(['id' => $habitId, 'userId' => $user->getUserId()]);
        if (null === $habit) {
            throw new NotFoundHttpException('Habit not found');
        }

        $occurredAt = new DateTimeImmutable($date);
        $habit->addTrackPoint($occurredAt);

        $entityManager->flush();

        return $this->redirectToRoute(
            'app_habit_week',
            ['year' => $occurredAt->format('Y'), 'week' => $occurredAt->format('W')]
        );
    }

    /**
     * @Route("/habit/{habitId}/track-point/{date}/delete", methods={"POST"}, name="app_habit_delete_track_point")
     */
    public function deleteTrackPoint(
        int                    $habitId,
        string                 $date,
        UserInterface          $user,
        HabitRepository        $habitRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $habit = $habitRepository->findOneBy(['id' => $habitId, 'userId' => $user->getUserId()]);
        if (null === $habit) {
            throw new NotFoundHttpException('Habit not found');
        }

        $occurredAt = new DateTimeImmutable($date);
        $habit->removeTrackPoint($occurredAt);

        $entityManager->flush();

        return $this->redirectToRoute(
            'app_habit_week',
            ['year' => $occurredAt->format('Y'), 'week' => $occurredAt->format('W')]
        );
    }
}
