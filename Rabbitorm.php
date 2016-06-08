<?php

 use Doctrine\Common\Annotations\AnnotationReader;
 use RabbitRobot\AnnotationParser\AnnotationParser;

// In CI 3 the EXT constant doesn't exist!
if(!defined('EXT'))
{
	define('EXT', '.php');
}


class RabbitORM {

	private $annotations = array('RabbitORM\Annotations\Annotation',
								 'RabbitORM\Annotations\Entity',
								 'RabbitORM\Annotations\Column');

	private $entityAnnotation = '@Entity';

	function __construct()
	{
		$mod_path = APPPATH . 'models' . DIRECTORY_SEPARATOR;
		$this->loadAnnotations();
		if(file_exists($mod_path)) { $this->_read_model_dir($mod_path); }

	}


	private function loadAnnotations() {
		foreach($this->annotations as $annotation) {
			$path =  str_replace("\\", DIRECTORY_SEPARATOR, $annotation) . EXT;
			require_once $path;
		}
	}

	/**
	 * @param $dirpath
     */
	private function _read_model_dir($dirpath)
	{
		$ci =& get_instance();

		$annotationReader = new AnnotationReader();
		$annotationParser = new AnnotationParser();


		$handle = opendir($dirpath);
		if(!$handle) return;

		while (false !== ($filename = readdir($handle)))
		{
			if($filename == "." or $filename == "..") {
				continue;
			}

			$filepath = $dirpath.$filename;
			if(is_dir($filepath)) {
				$this->_read_model_dir($filepath);

			} elseif(strpos(strtolower($filename), '.php') !== false)
			{
				if(!$annotationParser->findAnnotion($filepath,$this->entityAnnotation)) {
					continue;
				}

				require_once $filepath;
			}

			else { continue; }
		}

		closedir($handle);
	}

}

spl_autoload_register(function($class){


	if(strpos($class, "RabbitORM\\") === 0)
	{
		$classname = str_replace("RabbitORM\\", "", $class);
		$path = 'src/' . ucfirst(strtolower(str_replace("\\", DIRECTORY_SEPARATOR, $classname)) ) . EXT;
		require_once $path;
	}

	if(strpos($class, "Doctrine\\") === 0)
	{
		$path =  str_replace("\\", DIRECTORY_SEPARATOR, $class) . EXT;
		require_once $path;
	}

	if(strpos($class, "RabbitRobot\\") === 0)
	{
		$path =  str_replace("\\", DIRECTORY_SEPARATOR, $class) . EXT;
		require_once $path;
	}


});
