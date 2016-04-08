<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name')
            ->add('activity_name')
            ->add('options')
            ->add('language_from')
            ->add('language_to')
            ->add('category')
            ->add('project_briefing')
            ->add('deadline')
            ->add('project_riefing_is_rich')
            ->add('author_should_use_rich_text')
            ->add('work_template')
            ->add('vocabulary_type')
            ->add('grammatical_person')
            ->add('target_reader_groups')
            ->add('documents');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Project'
        ]);
    }
}