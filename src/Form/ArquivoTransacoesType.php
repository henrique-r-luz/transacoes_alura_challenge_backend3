<?php

namespace App\Form;

use App\Entity\ArquivoTransacoes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ArquivoTransacoesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ...
            ->add('arquivo', FileType::class, [
                'label' => 'arquivo (csv ou xml file)',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,
                //'class'=>'form-control form-control-lg',

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => true,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'text/csv',
                            'text/plain',
                            'application/xml',
                            'text/xml'
                        ],
                        'mimeTypesMessage' => 'Please upload a valid csvou xml document. ',
                        'disallowEmptyMessage' => 'O arquivo não pode ser vazio!'
                    ])
                ],
            ])
            ->add('upload', SubmitType::class, ['label' => 'Upload'])
            //->add('limpar', ButtonType::class,['label'=>'Limpar'])
            // ...
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ArquivoTransacoes::class,
        ]);
    }
}
