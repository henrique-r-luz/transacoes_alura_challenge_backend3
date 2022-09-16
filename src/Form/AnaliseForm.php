<?php

namespace App\Form;


use App\Entity\Analise;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
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
                    'Janeiro' => 1,
                    'Fevereiro' => 2,
                    'Março' => 3,
                    'Abril' => 4,
                    'Maio' => 5,
                    'Junho' => 6,
                    'Julho' => 7,
                    'Agosto' => 8,
                    'Setembro' => 9,
                    'Otubro' => 10,
                    'Novembro' => 11,
                    'Desembro' => 12
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
