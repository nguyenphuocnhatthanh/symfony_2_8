<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DocumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title')
            ->add('type')
            ->add('word_count')
            ->add('word_count_rule')
            ->add('perform_word_count')
            ->add('original_content')
            ->add('markup_in_content')
            ->add('remote_file_url')
            ->add('remote_file_help_url')
            ->add('instructions')
            ->add('keyword_list')
            ->add('keywords_repeat_count')
            ->add('callback');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Document',
            'csrf_protection' => false,
            'allow_extra_fields' => true
        ]);;
    }
}