<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * A5 ldap Class
 *
 * LDAP Authentication library for Code Igniter.
 *
 * @author		M. Arifur Rahman
 * @version		0.0.1
 * @ci version  1.7 [not tested for version 2 ]
 * @based on	
 * @link		http://strangerzlog.blogspot.com
 * @license		MIT License Copyright (c) 2008 Erick Hartanto
 * @credits		
 */
 
class A5_Ldap
{	
	var $ldap;
	
	function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->config('a5ldap');
		
		
		
	}
	
	function _ldap_connect()
	{
		$this->ldap = ldap_connect($this->CI->config->item('ldapurl'))
          or die("Couldn't connect to AD!");
  		// Set version number
		ldap_set_option($this->ldap, LDAP_OPT_PROTOCOL_VERSION, 3)
			 or die ("Could not set ldap protocol");
		ldap_set_option($this->ldap, LDAP_OPT_REFERRALS,0)
			or die ("Could no set the ldap referrals");
	}
	/*
	* To search if an email exists within the directory
	*/
	function isEmailinDirectory($email)
	{
		if(!isset($this->ldap))$this->_ldap_connect();
		$bd = ldap_bind($this->ldap,$this->CI->config->item('ldapuser')."@".$this->CI->config->item('ldapdomain'),$this->CI->config->item('ldappwd'))
			  or die("Couldn't bind to AD!");
		$ldap_dcs = explode('.',$this->CI->config->item('ldapdomain'));
		
		$dn = "";
		foreach($ldap_dcs as $ldap_dc)
			$dn = $dn."DC=".$ldap_dc.",";
		$dn = rtrim($dn, strrchr($dn, ","));//removes the last ','
		$filter = "(mail=".$email.")";
		
		
		$result = ldap_search($this->ldap,$dn, $filter,array("mail"),0,0) or die ("ldap search failed");
		
		$entries = ldap_get_entries($this->ldap, $result);
		if($entries["count"]>0)
			$retval = true;
		else 
			$retval = false;
		
		
		ldap_unbind($this->ldap);
		return $retval;
		
	}
	/*
	* Retrive information of a user in the directory using his email address 
	* Retruns an array if success else retun false
	*/
	function getuserInfobyEmail($email,$attributes)
	{
		if(!isset($this->ldap))$this->_ldap_connect();
		$bd = ldap_bind($this->ldap,$this->CI->config->item('ldapuser')."@".$this->CI->config->item('ldapdomain'),$this->CI->config->item('ldappwd'))
			  or die("Couldn't bind to AD!");
		$ldap_dcs = explode('.',$this->CI->config->item('ldapdomain'));
		
		$dn = "";
		foreach($ldap_dcs as $ldap_dc)
			$dn = $dn."DC=".$ldap_dc.",";
		$dn = rtrim($dn, strrchr($dn, ","));//removes the last ','
		$filter = "(mail=".$email.")";
		
		/*$attributes = array("displayname", "mail",
		"department",
			"title");*/
		
		$result = ldap_search($this->ldap,$dn, $filter,$attributes,0,0) or die ("ldap search failed");
		
		$entries = ldap_get_entries($this->ldap, $result);
		
		if($entries["count"]>0)
		{
			for ($i=0; $i<$entries["count"]; $i++)
			{
				
				foreach($attributes as $attribute)
					$retval[$attribute] = $entries[$i][$attribute][0];
			}
		}
		else 
			$retval = false;
		
		
		ldap_unbind($this->ldap);
		return $retval;
	
	}
	
	


}