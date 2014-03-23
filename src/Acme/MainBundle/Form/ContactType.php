<?php

namespace Acme\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;

class ContactType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('name', 'text', array(
				'max_length' => 100,
				'required' => false,
				'attr' => array(
					'placeholder' => 'What\'s your name?',
					'pattern' => '.{2,}'	//minlenght
				)
			))
			->add('email', 'email', array(
				'max_length' => 100,
                'required' => false,
				'attr' => array(
					'placeholder' => 'So I can get back to you.'
				)
			))
			->add('subject', 'text', array(
            	'max_length' => 78,
            	'required' => false,
				'attr' => array(
					'placeholder' => 'The subject of your message.',
					'pattern' => '.{3,}'	//minlength
				)
        	))
			->add('message', 'textarea', array(
            	'max_length' => 900,
            	'required' => false,
				'attr' => array(
					'cols' => 90,
					'rows' => 10,
					'placeholder' => 'And you message to me...'
				)
            ))
		;	
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$collectionConstraint = new Collection(array(
			'name' => array(
				new NotBlank(array('message' => 'Name should not be blank')),
				new Length(array('min' => 2))
			),
			'email' => array(
					new NotBlank(array('message' => 'Email should not be blank')),
					new Email(array('message' => 'Invalid email address.'))
			),
			'subject' => array(
					new NotBlank(array('message' => 'Subject should not be blank')),
					new Length(array('min' => 3))
			),
			'message' => array(
					new NotBlank(array('message' => 'Message should not be blank')),
					new Length(array('min' => 2))
			)
		));
		
		$resolver->setDefaults(array(
			'constraints' => $collectionConstraint
		));
	}
	
	public function getName()
	{
		return 'contact';
	}
}