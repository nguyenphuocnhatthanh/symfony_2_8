<?php
namespace AppBundle\Form\Type;

use AppBundle\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class)
            ->add('content', TextType::class)
            ->add('created_at', 'datetime', [
                'widget'    => 'single_text'
            ])
            ->add('published_at', 'datetime', [
                'widget'    => 'single_text'
            ])
            ->add('lattitude', TextType::class)
            ->add('longtitude', TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Article'
        ]);
    }
}