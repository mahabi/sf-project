<?php

namespace Acme\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Acme\MainBundle\Entity\Role;
use Acme\MainBundle\Entity\User;
use Acme\MainBundle\Form\UserType;

class SecurityController extends Controller
{
    /**
     * @Route("/login")
     * @Template()
     */
    public function loginAction()
    {
    	$request = $this->getRequest();
    	$session = $request->getSession();
    	
    	// get the login error if there is one
    	if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
    		$error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
    	} else {
    		$error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
    		$session->remove(SecurityContext::AUTHENTICATION_ERROR);
    	}
    	
    	return $this->render('AcmeMainBundle:Security:login.html.twig', array(
    			// last username entered by the user
    			'last_username' => $session->get(SecurityContext::LAST_USERNAME),
    			'error'         => $error,
    	));
    }

    /**
     * @Route("/register")
     * @Template()
     */
    public function registerAction()
    {
        $user = new User();

        // get default role
        $em = $this->getDoctrine()->getManager();
        $role = $em->getRepository('AcmeMainBundle:Role')->findOneBy(array('name' => 'ROLE_USER'));
        if ($role) {
            $user->addRole($role);
        }

        $form = $this->createForm(new UserType(), $user);
        $form->add('isActive', 'hidden');
        $form->add('roles', 'entity', array(
            'class' => 'AcmeMainBundle:Role',
            'property' => 'name',
            'multiple' => true,
            'attr'=> array('style'=>'display:none'),
            'label_attr'=> array('style'=>'display:none')
        ));
        $form->add('submit', 'submit', array('label' => 'Register'));

        $request = $this->getRequest();

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                // Encrypt password
                $factory = $this->get('security.encoder_factory');
    	        $encoder = $factory->getEncoder($user);
    	        $user->setPassword($encoder->encodePassword($user->getPassword(), $user->getSalt()));

                $em->persist($user);
                $em->flush();

                $session = $request->getSession();
                $session->set(SecurityContext::LAST_USERNAME, $user->getUsername());

                return $this->redirect($this->generateUrl('_welcome'));
            }
        }

        return array(
            'form' => $form->createView(),
        );
    }
}
