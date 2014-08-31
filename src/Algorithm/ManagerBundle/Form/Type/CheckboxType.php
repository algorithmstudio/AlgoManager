<?php

namespace Algorithm\ManagerBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CheckboxType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'checkbox' 
        ));
    }

    public function getParent()
    {
        return 'checkbox';
    }

    public function getName()
    {
        return 'checkboxcustom';
    }
}
