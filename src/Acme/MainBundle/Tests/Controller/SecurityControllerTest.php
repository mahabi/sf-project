<?php

namespace Acme\MainBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Acme\MainBundle\Entity\User;

class SecurityControllerTest extends WebTestCase
{
	public function setUp()
	{
		static::$kernel = static::createKernel();
		static::$kernel->boot();
	}
	
	protected function debug($obj)
	{
		fwrite(STDERR, print_r($obj, true) . "\n");
	}
	
	public function testInsert()
	{
		$u = new User();
		$u->setUsername("info");
		$u->setEmail('info@iit.ch');
		
		// Set encrypted password
		$factory = static::$kernel->getContainer()->get('security.encoder_factory');
		$encoder = $factory->getEncoder($u);
		$password = $encoder->encodePassword('sfinfo', $u->getSalt());
		$u->setPassword($password);
		
		$em = static::$kernel->getContainer()->get("doctrine")->getManager();
	
		$em->persist($u);
		$em->flush();
	
		$this->debug($u->getId());
	}
	
    public function _testFind()
    {
    	$repo = static::$kernel->getContainer()
    			->get("doctrine")->getRepository("AcmeMainBundle:User");
    	
    	$user = $repo->find(11);
    	$this->debug($user);
    }
    
    public function _testUpdate()
    {
    	$repo = static::$kernel->getContainer()
    			->get("doctrine")->getRepository("AcmeMainBundle:User");
    	$em = static::$kernel->getContainer()->get("doctrine")->getManager();
    	
    	$user = $repo->find(11);
    	$user->setUsername("InfoNew");
    	
    	$em->flush();
    	$this->debug($user);
    }
    
    public function _testDelete()
    {
    	$repo = static::$kernel->getContainer()
    			->get("doctrine")->getRepository("AcmeMainBundle:User");
    	$em = static::$kernel->getContainer()->get("doctrine")->getManager();
    	 
    	$user = $repo->find(11);
    	if ($user != null)
    	{
    		$em->remove($user); 
    		$em->flush();
    	}
    }
    
    public function _testQueryManual()
    {
    	$em = static::$kernel->getContainer()->get("doctrine")->getManager();
    	
    	$querytext = "SELECT u from " .
      				 "AcmeMainBundle:User u " .
      				 "where u.username = :username";
    	
    	$query = $em->createQuery($querytext);
    	$query->setParameter('username', 'info');
    	
    	$list = $query->getResult();
    	foreach ($list as $user)
    	{
    		$this->debug($user);
    	}		 
    }
    
    public function _testQueryWithBuilder()
    {
    	$repo = static::$kernel->getContainer()
    			->get("doctrine")->getRepository("AcmeMainBundle:User");
    	
    	$qb = $repo->createQueryBuilder('u');
    	$qb->where('u.username = :username')
    	   ->setParameter('username', 'info')
    	   ->orderBy('u.username');
    	 
    	$query = $qb->getQuery();
    	 
    	$list = $query->getResult();
    	foreach ($list as $user)
    	{
    		$this->debug($user);
    	}
    }
}
