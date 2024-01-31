<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Cart;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ProductsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Nom')
            ->add('description')
            ->add('prix')
            ->add('nb_article', IntegerType::class, [
            'label' => 'Nombre articles en stocks',
            'required' => true,
            ])
            ->add('lien_de_image', FileType::class,[
                'attr' => [
                    'accept' => 'image/*',
                ],
                'label' => false,
                'required' => true,
                'mapped' => false,
                'constraints' => [
                    new File([
                        'maxSize' => "4096k",
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/webp',
                            'image/gif',
                        ],
                        'mimeTypesMessage' => 'Upload une image !'
                    ])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
