<?php

namespace App\Form;


use App\Entity\Analise;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AnaliseForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('ano', NumberType::class, [
            'trim' => true,
            'required' => true,
            'attr' => [
                'pattern' => "\d{4}",
                'maxlength' => "4",
                'onkeypress' => "return event.charCode >= 48 && event.charCode <= 57"
            ]
        ])
            ->add('mes', ChoiceType::class, [
                'label' => 'Mês',
                'required' => true,
                'placeholder' => 'selecione um mês',
                'choices' => [
                    'The Solar System' => 1,
                    'Near a star' => 2,
                    'Interstellar Space' => 3
                ],
            ])
            ->add('buscar', SubmitType::class, ['label' => 'Buscar']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Analise::class,
        ]);
    }
}
