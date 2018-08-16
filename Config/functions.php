<?php
/********************************************************************************
** @Company     : Damaplan                                                     **
** @System      : Norman - Gestor de Normativos		                           **
** @Module		: Functions - Contém algumas funções básicas do módulo		   **
** @Namespace	: Damaplan\Norman											   **
** @Copyright	: Damaplan Consultoria LTDA (http://www.damaplan.com.br)       **
** @Link		: http://norman.damaplan.com.br/documentation                  **
** @Email		: sistemas@damaplan.com.br					                   **
** @Observation : Esta ferramenta e seu inteiro teor é de propriedade da	   **
**				  Damaplan Consultoria e Estratégia LTDA. Não é permitida sua  **
**				  edição, distribuição ou divulgação sem prévia autorização.   **
** --------------------------------------------------------------------------- **
** @Developer	:                                                              **
** @Date	 	:                                                     	       **
** @Version	 	:                                                     	       **
** @Comment	 	:                                                              **
** --------------------------------------------------------------------------- **
** @Developer	: @pauloampj                                                   **
** @Date	 	: 28/06/2018                                           	       **
** @Version	 	: 1.0                                                 	       **
** @Comment	 	: Primeira versão.                                             **
********************************************************************************/

Use Damaplan\Norman\Core\Utils\DMPLParams;

function _getType($var){
	if (is_object($var)) {
		return get_class($var);
	}
	if ($var === null) {
		return 'null';
	}
	if (is_string($var)) {
		return 'string';
	}
	if (is_array($var)) {
		return 'array';
	}
	if (is_int($var)) {
		return 'integer';
	}
	if (is_bool($var)) {
		return 'boolean';
	}
	if (is_float($var)) {
		return 'float';
	}
	if (is_resource($var)) {
		return 'resource';
	}
	
	return 'unknown';
}

function _toArray(array $var, $depth, $indent){
	$out = '[';
	$break = $end = null;
	if (!empty($var)) {
		$break = "\n" . str_repeat("\t", $indent);
		$end = "\n" . str_repeat("\t", $indent - 1);
	}
	$vars = [];
	
	if ($depth >= 0) {
		foreach ($var as $key => $val) {
			$val = _export($val, $depth, $indent);
			$vars[] = $break . _export($key, 3, 0) .
			' => ' .
			$val;
		}
	} else {
		$vars[] = $break . '[maximum depth reached]';
	}
	
	return $out . implode(',', $vars) . $end . ']';
}

function _toObject($var, $depth, $indent)	{
	$out = '';
	$props = [];
	
	$className = get_class($var);
	$out .= 'object(' . $className . ') {';
	$break = "\n" . str_repeat("\t", $indent);
	$end = "\n" . str_repeat("\t", $indent - 1);
	
	if ($depth > 0 && method_exists($var, '__debugInfo')) {
		try {
			return $out . "\n" .
					substr(_toArray($var->__debugInfo(), $depth - 1, $indent), 1, -1) .
					$end . '}';
		} catch (Exception $e) {
			$message = $e->getMessage();
			
			return $out . "\n(unable to export object: $message)\n }";
		}
	}
	
	if ($depth > 0) {
		$objectVars = get_object_vars($var);
		foreach ($objectVars as $key => $value) {
			$value = _export($value, $depth - 1, $indent);
			$props[] = "$key => " . $value;
		}
		
		$ref = new \ReflectionObject($var);
		
		$filters = [
				\ReflectionProperty::IS_PROTECTED => 'protected',
				\ReflectionProperty::IS_PRIVATE => 'private',
		];
		foreach ($filters as $filter => $visibility) {
			$reflectionProperties = $ref->getProperties($filter);
			foreach ($reflectionProperties as $reflectionProperty) {
				$reflectionProperty->setAccessible(true);
				$property = $reflectionProperty->getValue($var);
				
				$value = _export($property, $depth - 1, $indent);
				$key = $reflectionProperty->name;
				$props[] = sprintf(
						'[%s] %s => %s',
						$visibility,
						$key,
						$value
						);
			}
		}
		
		$out .= $break . implode($break, $props) . $end;
	}
	$out .= '}';
	
	return $out;
}

function _export($var, $depth, $indent){
	switch (_getType($var)) {
		case 'boolean':
			return $var ? 'true' : 'false';
		case 'integer':
			return '(int) ' . $var;
		case 'float':
			return '(float) ' . $var;
		case 'string':
			if (trim($var) === '' && ctype_space($var) === false) {
				return "''";
			}
			
			return "'" . $var . "'";
		case 'array':
			return _toArray($var, $depth - 1, $indent + 1);
		case 'resource':
			return strtolower(gettype($var));
		case 'null':
			return 'null';
		case 'unknown':
			return 'unknown';
		default:
			return _toObject($var, $depth - 1, $indent + 1);
	}
}

function getShortClassName($class_name = ''){
	$class = false;
	$pieces = explode(DMPLParams::read('DMPL_CLASS_SEPARATOR'), $class_name);
	
	if(isset($pieces) && count($pieces) > 0){
		$class = array_pop($pieces);
	}
	
	return $class;
}

function findFile($class_name){
	$file = FALSE;
	$class = '';
	$cMap = array();
	
	if(isset($class_name) && strlen($class_name) > 0){
		$class = getShortClassName($class_name);

		if($class !== false){
			$cMap = DMPLParams::read('DMPL_CLASS_MAP');
			
			if(isset($class) && isset($cMap[$class])){
				$file = $cMap[$class];
			}			 
		}
		
		return $file;
	}
	
	return $class_name . '.php';	
}

function loadClassMap($currentDir = '', $classMap = null){
	
	if(!isset($classMap)){
		$classMap = array();
	}
	
	$files = scandir($currentDir);
	
	if(isset($files) && count($files) > 0){
		foreach($files as $file){
			if($file === '.' || $file === '..') continue;
			
			if(is_dir($currentDir . DS . $file)){
				$classMap = array_merge($classMap, loadClassMap($currentDir . DS . $file, $classMap));
			}else{
				$filePieces = explode('.', $file);
				
				if(array_pop($filePieces) == 'php'){
					$classMap[implode('.', $filePieces)] = $currentDir . DS . $file;
				}
			}
		}
	}
	
	return $classMap;

}

function debug($message = '', $module = null){
	
	if(DMPLParams::read('DEBUG')){
	
		$moduleName = (isset($module) ? $module : 'Norman');
		$result = _export($message, 25, 0);
		
		$html = <<<HTML
<div class="cake-debug-output" style="direction:ltr">
%s
<pre class="cake-debug">
%s
</pre>
</div>
HTML;
		$text = <<<TEXT
%s
########## DEBUG ##########
%s
###########################

TEXT;

		if ((PHP_SAPI === 'cli' || PHP_SAPI === 'phpdbg')) {
			$template = $text;
		}else{
			$template = $html;
		}
		
		printf($template, "[$moduleName]", $result);
	}
	
	
	
	
	
	
	
	
}