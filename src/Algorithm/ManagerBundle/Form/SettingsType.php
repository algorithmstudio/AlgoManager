<?php

namespace Algorithm\ManagerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Algorithm\ManagerBundle\Form\Type\CheckboxType;

class SettingsType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', 'text', array('required' => false))
            ->add('password', 'text', array('required' => false))
            ->add('sendMailNewTask', new CheckboxType(), array('required' => false))
            ->add('sendMailNewComment', new CheckboxType(), array('required' => false))
            ->add('sendMailUpdateTask', new CheckboxType(), array('required' => false))
            ->add('sendMailEndTask', new CheckboxType(), array('required' => false))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Algorithm\ManagerBundle\Entity\Settings'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'algorithm_managerbundle_settings';
    }
}
