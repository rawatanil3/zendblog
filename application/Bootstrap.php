<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	protected function _initAcl()
	{
		$acl = new Zend_Acl();
		
		$acl->addResource('index');
		$acl->addResource('view', 'index');
		$acl->addResource('search', 'index');
		
		$acl->addResource('admin');
		
		$acl->addResource('auth');
		$acl->addResource('logout', 'auth');
		
		$acl->addResource('error');
		
		
		
		$acl->addRole('guest');
		$acl->addRole('admin', 'guest');
		
		$acl->allow('guest', 'index', array('index', 'view', 'search'));
		$acl->allow('guest', 'auth', array('logout'));
		
		$acl->allow('admin', 'index');
		$acl->allow('admin', 'admin');
		$acl->allow('admin', 'error');
		$acl->allow('admin', 'search');
		
		$fc = Zend_Controller_Front::getInstance();
		$fc->registerPlugin(new Application_Plugin_AccessCheck($acl, Zend_Auth::getInstance()));
	}

}

