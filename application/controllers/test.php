<?php
class test extends Controller {


	function __construct()
	{
		parent::Controller();
		
	}
	function index()
	{
		
		$this->load->library('A5_Ldap');
		echo $this->a5_ldap->isEmailinDirectory('arifur.rahman@afghan-wireless.com');
		
		$attributes = array("displayname",
		"department",
			"title");
		var_dump($this->a5_ldap->getuserInfobyEmail('arifur.rahman@afghan-wireless.com',$attributes));
		
	}
	
	
	
}

?>