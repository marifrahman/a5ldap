<?php
class test extends CI_Controller {


	function test()
	{
		parent::__construct();
		
	}
	function index()
	{
		
		$this->load->view('test');
	}
	function testdt()
	{
		$this->load->view('testdatatable');

	}
	function ldaptest()
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