<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpKernel\KernelInterface;

class ProductFormType extends AbstractType
{
    private $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Nom')
            ->add('description')
            ->add('prix')
            ->add('Lien_de_image', FileType::class, [
                'label' => 'Image (JPEG, PNG, GIF)',
                'required' => true,
            ]);

        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();
            $data = $form->getData();

            // Récupérer le fichier téléchargé
            $file = $form['Lien_de_image']->getData();

            // Vérifier s'il y a un fichier téléchargé
            if ($file) {
                // Définir un emplacement permanent pour stocker le fichier
                $uploadsDirectory = $this->kernel->getProjectDir() . '/public/uploads'; // Changer le chemin selon votre besoin

                // Générez un nom de fichier unique
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();

                // Déplacez le fichier vers l'emplacement permanent
                $file->move($uploadsDirectory, $fileName);

                // Mettez à jour l'entité avec le chemin du fichier
                $data->setLienDeImage($fileName);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
