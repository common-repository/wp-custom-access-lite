<?php
namespace custom\access\lite;
defined('ABSPATH') or die('No script kiddies please!');

class Seng_Gebruiker{
	private $_id;
	private $_user_name;
	private $_user_email;
	private $_user_joined;
        private $_level;
	private $_fullname;
        
	public function __construct($data = array()){
		if($data){
			$this->setName($data['username']);
			$this->setEmail($data['email']);
			$this->setJoined($data['joined']);
			$this->setId($data['id']);
                        $this->setLevel($data['level']);
                        $this->setFullName($data['fullname']);
		}
	}
	
	public function getName(){
		return $this->_user_name;
	}
	
	public function getEmail(){
		return $this->_user_email;
	}
	
	public function getJoined(){
		return $this->_user_joined;
	}
	
	public function getId(){
		return $this->_id;
	}
        
        public function getLevel(){
		return $this->_level;
	}
        
        public function getFullName(){
		return $this->_fullname;
	}
	
	public function setName($name){
		$this->_user_name = $name;
	}
	
	public function setEmail($email){
		$this->_user_email = $email;
	}
	
	public function setJoined($joined){
		$this->_user_joined = $joined;
	}
	
	public function setId($id){
		$this->_id = $id;
	}
        
        public function setLevel($level){
		$this->_level = $level;
	}
        
        public function setFullName($name){
		$this->_fullname = $name;
	}
}