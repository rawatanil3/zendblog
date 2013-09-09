<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        	
			$res = new Application_Model_DbTable_Comments();
			
			 
			
			
			
        $form = new Application_Form_Login();
        $request = $this->getRequest();
        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
                if ($this->_process($form->getValues())) {
                    // We're authenticated! Redirect to the home page
                    $controller = $this->getRequest()->getControllerName();
					
                    $this->_helper->redirector('index', 'admin');
					
                }
            }
        }
        $this->view->form = $form;
		
			$paginator = Zend_Paginator::factory($this->getModels());
        $paginator->setCurrentPageNumber($this->getRequest()->getParam('page', 1));
        $this->view->paginator = $paginator;
		
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
        if(isset($r))
		{
			$albums = new Application_Model_DbTable_Articles();
			$this->view->albums = $albums->fetchAll('ArticleId = ' . $r);
			
			$comments = new Application_Model_DbTable_Comments();
			$this->view->comments = $comments->fetchAll('ArticleId = ' . $r . ' AND CommentStatus = "Approved"');
			
			$form = new Application_Form_Comment();
			$form->submit->setLabel('Edit');
			$a = 1;
			$this->view->a = $a;
			$this->view->form = $form;
			
			if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
				$articleid = $form->getValue('ArticleId');
				$commentstatus = 'Pending Approval';                	
                $commentusername = $form->getValue('username');
                $commentuseremail = $form->getValue('useremail');
				$commenttext = $form->getValue('commenttext');
				$commentdate = new Zend_Date();
				$comments = new Application_Model_DbTable_Comments();
                $comments->addComment($articleid, $commentstatus, $commentusername, $commentuseremail, $commenttext, $commentdate);
                
                $this->_helper->redirector('view');
            } else {
                $form->populate($formData);
            }
			
			}
			
			 else
			 	 {
            $id = $this->_getParam('id', 0);
            if ($id > 0) {
                $albums = new Application_Model_DbTable_Articles();
                $form->populate($albums->getArticle($id));
            }
        }
		}
		else {
			$albums = new Application_Model_DbTable_Articles();
        $this->view->albums = $albums->fetchAll('ArticleId = 19');
		}
    }

    

   

    public function viewAction()
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
        if(isset($r))
		{
			$albums = new Application_Model_DbTable_Articles();
			$this->view->albums = $albums->fetchAll('ArticleId = ' . $r);
			
			$comments = new Application_Model_DbTable_Comments();
			$this->view->comments = $comments->fetchAll('ArticleId = ' . $r . ' AND CommentStatus = "Approved"');
			$comentslist = $comments->fetchAll('ArticleId = ' . $r . ' AND CommentStatus = "Approved"');
			$commentimages = new Application_Model_DbTable_Images();
			$commentimagesarray = array();
			$i = 0;
			foreach ($comentslist as $a) :
				$commentid = $a->CommentId;
				
				$this->view->$i = $commentimages->fetchAll('CommentId = ' . $commentid);
				$i++;
				
				endforeach;
				
			
			
			$commentform = new Application_Form_Comment();
			$commentform->submit->setLabel('Add Comment');
			$a = 1;
			$this->view->a = $a;
			$this->view->commentform = $commentform;
			
			if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
				
            if ($commentform->isValid($formData)) {
					
					$dest_dir = "uploads/";
			
			/* Uploading Document File on Server */
			$upload = new Zend_File_Transfer_Adapter_Http();
			$upload->setDestination($dest_dir);
			$files = $upload->getFileInfo();
			
			$fname = $upload->getFileName();
			$fname = substr($fname, 8);
			
			
			 
			// debug mode [start]
			echo '<hr />
							<pre>';
			print_r($files);
			echo "files are uploaded";
			echo '	</pre>
						<hr />';
			// debug mode [end]
			
			try 
			{
				// upload received file(s)
				$upload->receive();
				
			} 
			catch (Zend_File_Transfer_Exception $e) 
			{
				$e->getMessage();
				exit;
			}
			
			
					
				$articleid = $commentform->getValue('ArticleId');
				$commentstatus = 'Pending Approval';                	
                $commentusername = $commentform->getValue('username');
                $commentuseremail = $commentform->getValue('useremail');
				$commenttext = $commentform->getValue('commenttext');
				
				$comments = new Application_Model_DbTable_Comments();
                $comments->addComment($articleid, $commentstatus, $commentusername, $commentuseremail, $commenttext);
				
				$vasia = new Application_Model_DbTable_Comments();
				$res = $vasia->fetchAll();
				foreach($res as $a) :
					$result = $a->CommentId;
					
					endforeach;
                
				
				$imagelink = $this->view->baseUrl() . '/uploads/' . $fname;
			
				$connect = new Application_Model_DbTable_Images();
				
				$connect->addImagelink($imagelink, $result);
				
                
                
                
                
                
                $this->_helper->redirector->gotoUrl('/index/view/id/' . $r);
            } else {
                $commentform->populate($formData);
            }
			
			}
			
			 else
			 	 {
            $id = $this->_getParam('id', 0);
            if ($id > 0) {
                $albums = new Application_Model_DbTable_Articles();
                $commentform->populate($albums->getArticle($id));
            }
        }
		}
		else {
			$albums = new Application_Model_DbTable_Articles();
        $this->view->albums = $albums->fetchAll();
		}
        
		
		
		
    }

	private function getModels()
    {
        	
        $albums = new Application_Model_DbTable_Articles();
		
		$r = $this->_request->getParam('sort');
		
		if(isset($r))
		{
			if($r == 'timeasc')
			{
				$a = $albums->fetchAll(null, 'ArticleTime ASC');	
			}
			elseif($r == 'timedesc')
			{
				$a = $albums->fetchAll(null, 'ArticleTime DESC');
			}
			elseif($r == 'namedesc')
			{
				$a = $albums->fetchAll(null, 'ArticleTitle DESC');
			}
			elseif($r == 'nameasc')
			{
				$a = $albums->fetchAll(null, 'ArticleTitle ASC');
			}
			
		}
		
		else
		{
			
				$a = $albums->fetchAll(null, 'ArticleTitle ASC');
			
		}
		
		
                return $a;
		 
        /*return $models; */
    }
	
	public function searchAction() {
    	
    	
		
    $search = new Application_Model_Search();
	$search->updateIndex();
	
	$formsearch = new Application_Form_Search();
	$formsearch->search->setValue($this->getRequest()->getParam('query'));
	if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
            if ($formsearch->isValid($formData)) {
            	$articleid = $formsearch->getValue('search');
				
		    $this->_helper->redirector->gotoUrl('/index/search/query/' . $articleid);    
            } else {
                $form->populate($formData);
            }
            
            
		}
	
	
	
	
	
	$this->view->formsearch = $formsearch; 
	
    $this->view->query = $this->_getParam('query');
    $this->view->hits = $search->search($this->view->query);
	$paginator = Zend_Paginator::factory($this->view->hits);
        $paginator->setCurrentPageNumber($this->getRequest()->getParam('page', 1));
        $this->view->paginator = $paginator;
}


}









