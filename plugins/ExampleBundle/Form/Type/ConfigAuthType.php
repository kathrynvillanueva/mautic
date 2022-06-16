<?php

namespace MauticPlugin\ExampleBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ConfigAuthType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
          'key',
          TextType::class,
          [
            'label'    => 'kate.form.key',
            'required' => true,
            'attr'     => [
              'class' => 'form-control',
            ],
            'constraints' => [
              new NotBlank(['message' => 'kate.form.key.required']),
            ],
          ]
        );

        $builder->add(
          'secret',
          TextType::class,
          [
            'label'      => 'kate.form.secret',
            'label_attr' => ['class' => 'control-label'],
            'required'   => true,
            'attr'       => [
              'class' => 'form-control',
            ],
            'constraints' => [
              new NotBlank(['message' => 'kate.form.secret']),
            ],
          ]
        );

        $choices = [
          'Production Endpoint (https://zammad-staging.wattum.pro/api/v1)'    => 'https://zammad-staging.wattum.pro/api/v1',
          'Local Endpoint (`https://dua-lipa.zammad.com/api/v1`)'             => '`https://dua-lipa.zammad.com/api/v1`',
        ];

        $builder->add(
          'host',
          ChoiceType::class,
          [
            'label'      => 'kate.form.host',
            'label_attr' => ['class' => 'control-label'],
            'required'   => true,
            'attr'       => [
              'class' => 'form-control',
            ],
            'choices'  => $choices,
          ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            [
                'integration' => null,
            ]
        );
    }
}
