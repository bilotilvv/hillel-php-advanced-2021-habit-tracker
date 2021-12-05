<?php

namespace App\Form;

use App\Entity\Habit;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class HabitFormType extends AbstractType
{
    /** @var TokenStorageInterface */
    private $token;

    public function __construct(TokenStorageInterface $token)
    {
        $this->token = $token;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add(
                'pointColor',
                ChoiceType::class,
                [
                    'choices'    => [
                        '<span style="color: #6c757d"><b>gray</b></span>'   => '#6c757d',
                        '<span style="color: #ffc107"><b>yellow</b></span>' => '#ffc107',
                        '<span style="color: #dc3545"><b>red</b></span>'    => '#dc3545',
                    ],
                    'expanded'   => true,
                    'label_attr' => [
                        'class' => 'radio-inline',
                    ],
                    'label_html' => true,
                ]
            )
            ->add(
                'pointIcon',
                ChoiceType::class,
                [
                    'choices'    => [
                        '<i class="bi bi-circle-fill" style="font-size: 30px"></i>'   => 'circle-fill',
                        '<i class="bi bi-square-fill" style="font-size: 30px"></i>'   => 'square-fill',
                        '<i class="bi bi-triangle-fill" style="font-size: 30px"></i>' => 'triangle-fill',
                    ],
                    'expanded'   => true,
                    'label_attr' => [
                        'class' => 'radio-inline',
                    ],
                    'label_html' => true,
                ]
            )
            ->add(
                'save',
                SubmitType::class,
                [
                    'label' => 'Save',
                    'attr'  => [
                        'class' => 'btn-primary float-end',
                    ],
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        /** @var User $user */
        $user = $this->token->getToken()->getUser();

        $resolver->setDefaults(
            [
                'data_class' => Habit::class,
                'empty_data' => function (FormInterface $form) use ($user) {
                    return new Habit(
                        $user->getUserId(),
                        $form->get('name')->getData(),
                        $form->get('pointIcon')->getData(),
                        $form->get('pointColor')->getData(),
                    );
                },
            ]
        );
    }
}
