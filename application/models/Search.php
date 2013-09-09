<?php
class Application_Model_Search {

	protected $_searchIndexPath;	//path to initial data folder
	protected $db;				//database adapter

	public function __construct() {
		$this->_searchIndexPath = APPLICATION_PATH . '/data/search-index';
		$this->db = Zend_Db_Table_Abstract::getDefaultAdapter();
		set_time_limit(900);
		Zend_Search_Lucene_Analysis_Analyzer::setDefault(
			new Zend_Search_Lucene_Analysis_Analyzer_Common_Utf8_CaseInsensitive());

	}

	/**
	 * Создает новый поисковой индекс
	 */
	public function updateIndex() {
		//удаляем существующий индекс, в большинстве случае эта операция с последующий созданием нового индекса работает гораздо быстрее
		/*$this->recursive_remove_directory($searchIndexPath, TRUE);*/

	  	try {
	  		$index = Zend_Search_Lucene::create($this->_searchIndexPath);
	  	} catch (Zend_Search_Lucene_Exception $e) {
	  	 echo "<p class=\"ui-bad-message\">Не удалось создать поисковой индекс: {$e->getMessage()}</p>";
	  	}

		try {
			//выбираем все слова из БД
			$words = $this->db->fetchAll("SELECT * FROM articles");
			$i = 0;
			foreach ($words as $w) {
				$doc = new Zend_Search_Lucene_Document();
				$doc->addField(Zend_Search_Lucene_Field::Keyword('articleid', $w['ArticleId']));
				$doc->addField(Zend_Search_Lucene_Field::Text('articletitle', $w['ArticleTitle'], 'UTF-8'));
				$doc->addField(Zend_Search_Lucene_Field::Text('articletext', $w['ArticleText'], 'UTF-8'));
				$doc->addField(Zend_Search_Lucene_Field::Text('articledate', $w['ArticleDate'], 'UTF-8'));
				$index->addDocument($doc);
				$i++;
			}
		} catch (Zend_Search_Lucene_Exception $e) {
    		echo "<p class=\"ui-bad-message\">Ошибки индексации: {$e->getMessage()}</p>";
    	}

    	//let's clean up some
    	$index->optimize();

    	/*echo "<p class=\"ui-good-message\">
    			Поисковой индекс слов занового создан. Слов добавлено: {$i}. <br />
    			Индекс оптимизирован.</p>";*/
	}

	/**
	 * recursive_remove_directory( directory to delete, empty )
	 * expects path to directory and optional TRUE / FALSE to empty
	 *
	 * @param $directory
	 * @param $empty TRUE - just empty directory
	 */
	function recursive_remove_directory($directory, $empty=FALSE)
	{
		if(substr($directory,-1) == '/')
		{
			$directory = substr($directory,0,-1);
		}
		if(!file_exists($directory) || !is_dir($directory))
		{
			return FALSE;
		}elseif(is_readable($directory))
		{
			$handle = opendir($directory);
			while (FALSE !== ($item = readdir($handle)))
			{
				if($item != '.' && $item != '..')
				{
					$path = $directory.'/'.$item;
					if(is_dir($path))
					{
						recursive_remove_directory($path);
					}else{
						unlink($path);
					}
				}
			}
			closedir($handle);
			if($empty == FALSE)
			{
				if(!rmdir($directory))
				{
					return FALSE;
				}
			}
		}
		return TRUE;
	}

	/**
	 * Search by query
	 * 
	 * @param $query search query
	 * @return array Zend_Search_Lucene_Search_QueryHit
	 */
	 
	public function search($query) {
		try{
				
			$index = Zend_Search_Lucene::open($this->_searchIndexPath);
				
		} catch (Zend_Search_Lucene_Exception $e) {
			echo "errorrr:{$e->getMessage()}";
		}
		
		$userQuery = Zend_Search_Lucene_Search_QueryParser::parse($query);
		
		return $index->find($userQuery);
	}
}