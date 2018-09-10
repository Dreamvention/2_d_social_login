<?php
Class ModuleGenerator{

	private $template_files = array();
	private $template_replace = array();
	public $success = array();
	public $error = array();


	public function __construct($replace = array(), $files = array()){
		$this->setTemplateReplace($replace);
		$this->setTemplateFiles($files);
		$this->dir = substr_replace(DIR_SYSTEM, '/', -8);
	}

	public function generate(){
		foreach($this->template_files as $template_file){
			$code = $this->_prepareCode($template_file, $this->template_replace);
			$destination_file = $this->_prepareDestinationFile($template_file, $this->template_replace);
			if($this->_writeCode($destination_file, $code)){
				$this->success[] = $destination_file;
			}else{
				$this->error[] = $destination_file;
			}
		}
		if($this->error){
			return false;
		}
		return true;
	}

	public function setTemplateReplace($replace){
		$this->template_replace = $replace;
	}

	public function setTemplateFiles($files){
		$this->template_files = $files;
	}

	private function _prepareDestinationFile($template_file, $replace){
		$output = $template_file;
		foreach($replace as $match => $value){
			$output = str_replace($match, $value, $output);
		}

		return $output;
	}


	private function _prepareCode($template_file, $replace){

		$output = file_get_contents($this->dir. $template_file);

		foreach($replace as $match => $value){
			$output = str_replace($match, $value, $output);
		}

		return $output;
	}

	private function _writeCode($destination_file, $code){

		$parts = explode('/', $destination_file);
		$file = array_pop($parts);
		$dir = $this->dir;
		foreach($parts as $part){
			if(!is_dir($dir .= $part)) mkdir($dir);
			$dir .= '/';
		}
		return file_put_contents($dir.$file, $code);
	}

}
