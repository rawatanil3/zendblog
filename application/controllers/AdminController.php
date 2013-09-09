<?php

class AdminController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $search = new Application_Form_Search();
		$this->view->search = $search;
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
            if ($search->isValid($formData)) {
            	$articleid = $search->getValue('search');
				
		    $this->_helper->redirector->gotoUrl('/index/search/query/' . $articleid);    
            } else {
                $form->populate($formData);
            }
            
            
		}
    }

	public function commentsAction()
	{
		
		$search = new Application_Form_Search();
		$this->view->search = $search;
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
            if ($search->isValid($formData)) {
            	$articleid = $search->getValue('search');
				
		    $this->_helper->redirector->gotoUrl('/index/search/query/' . $articleid);    
            } else {
                $form->populate($formData);
            }
            
            
		}
			$paginator = Zend_Paginator::factory($this->getComments());
        $paginator->setCurrentPageNumber($this->getRequest()->getParam('page', 1));
		$this->view->paginator = $paginator;
		$comments = new Application_Model_DbTable_Comments();
		$this->view->comments = $comments->fetchAll();
	}
	
	public function articlesAction()
	{
			
			$search = new Application_Form_Search();
		$this->view->search = $search;
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
            if ($search->isValid($formData)) {
            	$articleid = $search->getValue('search');
				
		    $this->_helper->redirector->gotoUrl('/index/search/query/' . $articleid);    
            } else {
                $form->populate($formData);
            }
            
            
		}
		$paginator = Zend_Paginator::factory($this->getModels());
        $paginator->setCurrentPageNumber($this->getRequest()->getParam('page', 1));
        $this->view->paginator = $paginator;	
		$articles = new Application_Model_DbTable_Articles();
		$this->view->articles = $articles->fetchAll();
	}
	
	public function editAction()
    {
        	
			
        $form = new Application_Form_Album();
        $form->submit->setLabel('Edit Article');
        $this->view->form = $form;
        
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $id = (int)$form->getValue('ArticleId');
                $artist = $form->getValue('ArticleText');
                $title = $form->getValue('ArticleTitle');
                $albums = new Application_Model_DbTable_Articles();
                $albums->updateArticles($id, $artist, $title);
                
                $this->_helper->redirector('index');
            } else {
            	
				$search = new Application_Form_Search();
		$this->view->search = $search;
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
            if ($search->isValid($formData)) {
            	$articleid = $search->getValue('search');
				
		    $this->_helper->redirector->gotoUrl('/index/search/query/' . $articleid);    
            } else {
                $form->populate($formData);
            }
            
            
		}
                $form->populate($formData);
            }
        } else {
            $id = $this->_getParam('id', 0);
            if ($id > 0) {
                $albums = new Application_Model_DbTable_Articles();
                $form->populate($albums->getArticle($id));
            }
        }
        
    }
	
	public function deleteAction()
    {
        	
			
        if ($this->getRequest()->isPost()) {
            $del = $this->getRequest()->getPost('del');
            if ($del == 'Yes') {
                $id = $this->getRequest()->getPost('ArticleId');
                $albums = new Application_Model_DbTable_Articles();
                $albums->deleteArticle($id);
            }
            $this->_helper->redirector->gotoUrl('/admin/articles');
        } else {
        	$search = new Application_Form_Search();
		$this->view->search = $search;
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
            if ($search->isValid($formData)) {
            	$articleid = $search->getValue('search');
				
		    $this->_helper->redirector->gotoUrl('/index/search/query/' . $articleid);    
            } else {
                $form->populate($formData);
            }
            
            
		}
            $id = $this->_getParam('id', 0);
            $albums = new Application_Model_DbTable_Articles();
            $this->view->album = $albums->getArticle($id);
			
        }
    }
	
	public function addAction()
    {
        	
			
        $form = new Application_Form_Album();
        $form->submit->setLabel('Add');
        $this->view->form = $form;
        
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $artist = $form->getValue('ArticleText');
                $title = $form->getValue('ArticleTitle');
				
				$date = new Zend_Date();
				$date = $date->get(Zend_Date::HOUR, 'uk_UA') . ':' . $date->get(Zend_Date::MINUTE, 'uk_UA') . ' ' . $date->get(Zend_Date::DAY, 'en_UK') . ' ' . $date->get(Zend_Date::MONTH_NAME, 'en_US') . " " . $date->get(Zend_Date::YEAR, 'uk_UA');
				
                $albums = new Application_Model_DbTable_Articles();
                $albums->addArticle($artist, $title, $date);
                
                $this->_helper->redirector->gotoUrl('admin/articles');
            } else {
            	$search = new Application_Form_Search();
		$this->view->search = $search;
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
            if ($search->isValid($formData)) {
            	$articleid = $search->getValue('search');
				
		    $this->_helper->redirector->gotoUrl('/index/search/query/' . $articleid);    
            } else {
                $form->populate($formData);
            }
            
            
		}
                $form->populate($formData);
            }
        }
            
    }
	
	public function moderateAction()
	{
		$search = new Application_Form_Search();
		$this->view->search = $search;
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
            if ($search->isValid($formData)) {
            	$articleid = $search->getValue('search');
				
		    $this->_helper->redirector->gotoUrl('/index/search/query/' . $articleid);    
            } else {
                $form->populate($formData);
            }
            
            
		}	
			
		$r = $this->_request->getParam('id');
		$comments = new Application_Model_DbTable_Comments();
		$this->view->comments = $comments->fetchAll('CommentId = ' . $r);
		
		$form = new Application_Form_CommentReview();
		$form->approvesubmit->setLabel('Approve');
		$form->declinesubmit->setLabel('Decline');
		$form->commentid->setValue($r);
		$this->view->form = $form;
		
		if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData))
            {
				$commentid = (int)$form->getValue('commentid');
				$commentstatus = $form->getValue('declinesubmit');
				if (!isset($commentstatus))
				{
					$commentstatus = 'Approved';
				}
				
				else {
					$commentstatus = 'Declined';
				}
                $comments = new Application_Model_DbTable_Comments();
				$comments->approveComment($commentid, $commentstatus);
			    $this->_helper->redirector->gotoUrl('/admin/comments');
            } 
			
			}
			
	}
	
	private function getModels()
    {
        	
        $albums = new Application_Model_DbTable_Articles();
        $a = $albums->fetchAll();
        return $a;
		 
        
    }
	
	private function getComments()
    {
        	$models = array();
        
        $comments = new Application_Model_DbTable_Comments();
        $a = $comments->fetchAll();
        return $a;
		 
        
    }
}

