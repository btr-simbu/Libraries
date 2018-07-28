<?php
/**
 * Created by PhpStorm.
 * User: Binary Tech Resonance Pvt. Ltd.
 * Date: 05-07-2018
 * Time: 11:11 AM
 */

trait Database {
	public $con = '';
	protected $process;
	protected $result;
	public $no_of_rows;

	public function db(){
		$this->con = new Mysqli('localhost','root','','asaiyenthi') or die('DB Connection Error.');

		return $this->con;
	}

	protected function query($sql = NULL){
		if(!is_null($sql)){
			$this->process = $this->con->prepare($sql);
			return $this->process;
		}
		return false;
	}

	protected function assign($params){
		$tmp = array();
		foreach($params as $key => $value){
			$tmp[$key] = &$params[$key];
		}
		return call_user_func_array(array($this->process,"bind_param"), $tmp);
	}

	protected function error(){
		return $this->process->error();
	}

	protected function proceed(){
		return $this->process->execute();
	}

//	public function result(){
//		$__res = $this->process->get_result();
//		while ($assoc = $__res->fetch_object()){
//			$this->result[] = $assoc;
//		}
//		return $this->result;
//	}

//	public function row(){
//		$__res = $this->process->get_result();
//		$this->result = $__res->fetch_object();
//		return $this->result;
//	}

	protected function result(){
		$this->process->store_result();
		$meta = $this->process->result_metadata();
		while ($field = $meta->fetch_field()) {
			$params[] = &$row[$field->name];
		}

		$this->no_of_rows = $this->process->num_rows;
		$result = array();

		call_user_func_array(array($this->process, 'bind_result'), $params);


		while ($this->process->fetch()) {
			foreach($row as $key => $val)
			{
				$c[$key] = $val;
			}
			$result[] = $c;
		}
		$this->process->close();
		return $result;
		// return $stmt->fetch();
	}

	protected function row(){
		$this->process->store_result();
		$meta = $this->process->result_metadata();
		while ($field = $meta->fetch_field()) {
			$params[] = &$row[$field->name];
		}

		$this->no_of_rows = $this->process->num_rows;
		$result = array();

		call_user_func_array(array($this->process, 'bind_result'), $params);

		while ($this->process->fetch()) {
			$result = $row;
		}
		$this->process->close();
		return $result;
	}

	public function setToUnicode() {
		if($this->con != ''){
			$this->con->set_charset('utf8');
		}
	}
}