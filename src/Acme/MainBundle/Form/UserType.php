<?php

namespace Acme\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Valid;

class UserType extends AbstractType
{
     /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', 'text', array(
            	'max_length' => 40,
            	'required' => true,
            	'attr' => array(
            		'autocomplete' => 'off',
					'placeholder' => 'Unique username',
            		'pattern' => '.{2,}'	//minlength
            	)
            ))
            ->add('email', 'email', array(
            	'max_length' => 100,
            	'required' => true,
                'attr' => array(
					'placeholder' => 'Unique email'
                )
            ))
            ->add('salt', 'hidden')
            ->add('password', 'password', array(
            	'max_length' => 40,
            	'required' => true,
            	'always_empty' => true,
            	'attr' => array(
            		'autocomplete' => 'off',
                    'placeholder' => 'min. 6 characters',
            		'pattern' => '.{6,}'	//minlength
            	)
            ))
            ->add('isActive', 'checkbox', array(
                'required' => false,
            	'label' => 'Is active',
            	'label_attr' => array(
            		'class' => 'col-sm-offset-2 col-sm-10'
            	)
            	//////'attr' => array('class' => 'checkbox')
            ))
            ->add('roles', 'entity', array(
            	'class' => 'AcmeMainBundle:Role',
            	'property' => 'name',
            	'multiple' => true
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
    	$resolver->setDefaults(array(
    		'data_class' => 'Acme\MainBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'user';
    }
}
