<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class SupprEventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('event_ids', ChoiceType::class, [
                'choices' => $options['events'],
                'expanded' => true,
                'multiple' => true,
                'label' => 'Sélectionnez les événements à supprimer',
                'attr' => ['class' => 'event-form-label']
            ])
            ->add('supprimer', SubmitType::class, [
                'label' => 'Supprimer',
                'attr' => ['class' => 'event-delete'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'events' => []
        ]);
    }
}
