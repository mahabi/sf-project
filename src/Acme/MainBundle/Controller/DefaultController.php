<?php

namespace Acme\MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Acme\MainBundle\Form\ContactType;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {
        return $this->render('AcmeMainBundle:Default:index.html.twig');
    }
    
    /**
     * @Route("/user/contact", _name="contact")
     * @Template()
     */
    public function contactAction(Request $request)
    {
    	$form = $this->createForm(new ContactType());
    	$form->add('submit', 'submit', array('label' => 'Send'));
    	
    	if ($request->isMethod('POST')) {
    		$form->bind($request);
    	
    		if ($form->isValid()) {
    			$message = \Swift_Message::newInstance()
    				->setSubject($form->get('subject')->getData())
    				->setFrom($form->get('email')->getData())
    				->setTo('kai.ilbertz@gmail.com')
    				->setBody(
    					$this->renderView(
    						'AcmeMainBundle:Mail:contact.html.twig',
    					array(
    						'ip' => $request->getClientIp(),
    						'name' => $form->get('name')->getData(),
    						'message' => $form->get('message')->getData()
    					)
    				)
    			);
    	
    			$this->get('mailer')->send($message);
    	
    			$request->getSession()->getFlashBag()->add('success', 'Your email has been sent! Thanks!');
    	
    			return $this->redirect($this->generateUrl('contact'));
    		}
    	}
    	
    	return array(
    		'form' => $form->createView()
    	);
    }
}
