<?php
namespace AppBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleSearchType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',null,array(
                'required' => false,
            ))
            ->add('dateFrom', 'date', array(
                'required' => false,
                'widget' => 'single_text',
            ))
            ->add('dateTo', 'date', array(
                'required' => false,
                'widget' => 'single_text',
            ))
            ->add('isPublished','choice', array(
                'choices' => array('false'=>'non','true'=>'oui'),
                'required' => false,
            ))
            ->add('search','submit')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            // avoid to pass the csrf token in the url (but it's not protected anymore)
            'csrf_protection' => false,
            'data_class' => 'AppBundle\Model\ArticleSearch'
        ));
    }

    public function getName()
    {
        return 'article_search_type';
    }
}