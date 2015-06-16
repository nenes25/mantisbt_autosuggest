<?php
/*
  Plugin AutoSuggest pour Mantis BugTracker :
 */
 class AutoSuggestTest extends PHPUnit_Extensions_Selenium2TestCase {
 
	protected $default_admin_user = 'administrator';
	protected $default_admin_password = 'root';
	protected $site_url = '';
	
	 /**
	  * A l'instanciation du test on récupère les données d'environnement si elles sont définies
	  */
	 public function __construct($name = NULL, array $data = array(), $dataName = '') {
        
		if ( $_ENV['WEBSITE_URL'] && $_ENV['WEBSITE_URL'] != '')
			$this->site_url = $_ENV['WEBSITE_URL'];
		else
			throw new Exception("Impossible de commencer les tests , pas d'url définie");
			
		if ( $_ENV['MANTIS_ADMIN_USER'] )
			$this->default_admin_user = $_ENV['MANTIS_ADMIN_USER'];
		
		if ( $_ENV['MANTIS_ADMIN_PASSWORD'] )
			$this->default_admin_password = $_ENV['MANTIS_ADMIN_PASSWORD'];
			
		
        parent::__construct($name, $data, $dataName);
    }
	
	/**
	 * @ToDo : Mettre les fonctions génériques dans une classe parente
	 * Voir pour utiliser les tests mantis
	 * Faire un script de vérification avec travis Ci
	 *  - Installer la dernière version de mantis ( récupérer et adapter les scripts travis-ci de mantisBT )
	 *  - Créer un certains nombres de bugs et d'utilisateurs
	 *  - Installer le plugin 
	 *  - Vérifier son bon fonctionnement
	 *  - Mettre en place des fixtures pour tester que tout est OK et les affiner si il subsiste des problèmes
	 */
	
	function isTextPresent($search)
	{
		$source = $this->source();
		if ( strpos((string)$source,$search) !== FALSE)
		return true;
		else return false;
	}	
	
	/**
	 * Actions en début de tests
	 */
	public function setUp(){
	
		$this->setBrowser('firefox');
        $this->setBrowserUrl($this->site_url);
	}
	
	public function setUpPage(){
		$this->_login();
	}
	
	/**
	 * Identification 
	 */
	protected function _login() {
	
		$this->url('login_page.php');
		$this->byName('username')->value($this->default_admin_user);
		$this->byName('password')->value($this->default_admin_password);
		$this->ByClassName('button')->click();
		
		//On vérifie que l'identification est si on est bien redirigé
		$this->timeouts()->implicitWait(3000);
		$this->assertEquals($this->site_url.'my_view_page.php',$this->url());	
	}
	
	/**
	 * Création de bug
	 * @ToDo : Utiliser les tests mantis de base si possible
	 * ( Mais tests très anciens ... )
	 */
	protected function _createBug(){
		#@ToDo : Gérer le cas ou il existe plusieurs projets
		$this->url('bug_report_page.php');
		
		#On remplis uniquement les champs obligatoires ( Category / Summary / Description )
		$this->select($this->byName("category_id"),"value=1");
		$this->byName("summary")->value("New bug with selenium ".date('Ymd'));
		$this->byName("description")->value("New bug creation with selenium test");
		$this->byClassName('button')->click();
		
		$this->timeouts()->implicitWait(3000);
	}
	
	/**
	 * Création d'un utilisateur
	 */
	protected function _createUser(){
	
	}
	
	/**
	 * Déconnexion
	 */
	protected function _logout(){
		$this->url('logout_page.php');
	}
	
	/**
	 * Test de la suggestion du nom des utilisateurs
	 * (Effectué avec l'utilisateur par défaut admin)
	 */
	public function testUserSuggestion(){
		
		$this->url('view.php?id=38');
		$this->ByName('username')->value("adm");
		$this->timeouts()->implicitWait(3000);
		
		try {
			$this->byCssSelector('#ui-id-10 li:first-child')->click();
		}
		catch ( Exception $e ) {
			return $this->fail($e->getMessage());
		}
		
		$this->assertEquals("administrator",$this->ByName('username')->value());	
	}
	
	
	/**
	 * Test de la suggestion des Bugs
	 * ( Haut de page )
	 */
	public function testBugSuggestionTop(){
		
		$this->url('my_view_page.php');	
		#Test par id du bug
		$this->_searchBug('bug_id',3,'ui-id-1');
		#Test par libellé du bug
		$this->_searchBug('bug_id','test','ui-id-1');
		
		return true;
	}
	
	
	/**
	 * Test de la suggestion des Bugs
	 * ( Page My View )
	 */
	public function testBugSuggestionBugView(){
	
		$this->_createBug();
	
		$this->url('view.php?id=38');
		#Test par id du bug
		$this->_searchBug('dest_bug_id',3,'ui-id-6');
		#Test par libellé du bug
		$this->_searchBug('dest_bug_id','test','ui-id-6');
	}
	
	/**
	 * Test de la recherche d'un bug
	 * @param $element : Input texte ou se tape la recherche
	 * @param $search : Terme de recherche
	 * @param $autoSuggestId : Identifiant de la liste AutoSuggest
	 */
	protected function _searchBug( $element , $search , $autoSuggestId ) {	
		
		$this->byName($element)->value($search);
		try {
			if ( $this->byId($autoSuggestId) ) {
				$this->byCssSelector('#'.$autoSuggestId.' li:first-child')->click();
				$this->assertEquals(38,$this->byName($element)->value());
			}
		}
		catch ( Exception $e ) {
			$this->fail($e->getMessage());
		}
	}
	
	/**
	 * Actions en fin de test
	 */
	public function tearDown(){
		$this->_logout();
	}	
	
 }
 ?>