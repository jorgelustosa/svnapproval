<?php


class FileController extends Zend_Controller_Action
{
	
	
	public function init()
	{
		/* Initialize action controller here */
		
	}
	
	public function indexAction() 
	{
		
		$project = $this->loadprojectdata() ; 
		
		 
		$project->name  ;
		
		
		$this->view->folder = $project->directory  ; ; 
		$this->view->project_name = $project->name  ; ;
		
		//$this->home() ; 
		
		
	}
	
	public function svnstAction()
	{
	
		$this->_helper->layout()->disableLayout();
		$project = $this->loadprojectdata() ;
		//$this->_helper->viewRenderer->setNoRender(true);
	
	
		//$svn_st = shell_exec("/usr/bin/svn status /srv/svnapproval/. ") ;
		
		
		$svn_st = exec("/usr/bin/svn  st ".$project->directory.". 2>&1", $output , $returnStatus);

		if($svn_st != "")
		{
			
				for($i = 0 ; sizeof($output) > $i ; $i ++)
				{
					$parts = explode("       ",$output[$i]) ;
					$array_status[$i]["NAME"]=$parts[1] ;
					$array_status[$i]["TYPE"]=$parts[0] ;
				}
		
				$this->view->content  = $array_status ;
		}
	
	
	
		}
	
	
	public function svnaddAction()
	{
	
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		
		for($i = 0 ; sizeof($_REQUEST['tags']) > $i ; $i ++)
		{
			
			exec("/usr/bin/svn  add ".$_REQUEST['tags'][$i]." 2>&1", $output, $returnStatus);
			if ( $returnStatus )
			{
				    print_r($output);
			}
			else
			{ 
				
				echo "The selected files are added !" ; 
			}

		}
	
	}
 
	
	public function svncommitAction()
	{
	
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$project = $this->loadprojectdata() ;
	
		for($i = 0 ; sizeof($_REQUEST['tags']) > $i ; $i ++)
		{

			$command  .=  " ".$_REQUEST['tags'][$i]." "   ; 

		}
	
		   
		exec("/usr/bin/sudo /usr/bin/svn -q --username ".$project->svn_username." --password ".$project->svn_password." commit ".$command ." -m \"teste classe\" 2>&1", $output, $returnStatus);
		if ( $returnStatus )
		{
			print_r($output);         
		}   
		else
		{ 
			
			echo "All files are commited !" ; 
			
		}
	
	}
	
	
	public function listfilesAction()
	{
	
		$this->_helper->layout()->disableLayout();
		
		$project = $this->loadprojectdata() ;
		
		global $folder, $tbcolor1, $tbcolor2, $tbcolor3, $filefolder, $HTTP_HOST;
	
		$op = $_REQUEST['op'];
		$folder = $_REQUEST['folder'];
		
		
		if($folder=="") $folder = $project->directory ;
	
	
		$count = "0";
		$style = opendir($folder);
		$a=1;
		$b=1;
	
	
		if ($folder) {
			if (ereg("/home/",$folder)) {
				$folderx = ereg_replace("$filefolder", "", $folder);
				$folderx = "http://".$HTTP_HOST."/".$folderx;
			} else {
				$folderx = $folder;
			}
		}
	
		while($stylesheet = readdir($style)) {
			if (strlen($stylesheet)>40) {
				$sstylesheet = substr($stylesheet,0,40)."...";
			} else {
				$sstylesheet = $stylesheet;
			}
			if ($stylesheet[0] != "." && $stylesheet[0] != ".." ) {
				if (is_dir($folder.$stylesheet) && is_readable($folder.$stylesheet)) {
						
					$content[$a]["NAME"] = $folder.$stylesheet ;
					$content[$a]["TYPE"] = "D";
					$a++;
				} elseif (!is_dir($folder.$stylesheet) && is_readable($folder.$stylesheet)) {
						
					$content[$a]["NAME"] = $folderx.$stylesheet ;
					$content[$a]["TYPE"] = "F";
					$a++;
						
				} else {
					//	echo "Directory is unreadable\n";
				}
				$count++;
			}
		}
		closedir($style);
	
	
		$svn_st = shell_exec("/usr/bin/svn status ".$_REQUEST['folder'].". ") ;
	
		if($svn_st != "")
		{ 
			//echo "erro" ;
				
			$status = explode("\n",$svn_st) ;
	
				
			//$status = explode("       ",$svn_st) ;
	
			for($i = 0 ; sizeof($status) > $i ; $i ++)
			{
			$parts = explode("       ",$status[$i]) ;
			//print_r($parts) ;
			//echo $status[$i]."<br>" ;
			$array_status["".$parts[1].""]=$parts[0] ;
			}
				
				
			}
	
			//print_r($array_status) ;
	
			$this->view->content  = $content ;
			$this->view->status = $array_status ;
			$this->view->folder = $folder ;
				
	}
	
	
	
	private function loadprojectdata()
	{ 
		
		$data = new Application_Model_DbTable_Projects() ;
		$project = $data->find('1') ;
		$row = $project->current();
		return $row  ;
		
	}
	
	

	
	
	
	
	
} 
?>