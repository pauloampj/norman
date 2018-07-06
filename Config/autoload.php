<?php
/********************************************************************************
** @Company     : Damaplan                                                     **
** @System      : Norman - Gestor de Normativos		                           **
** @Module		: Autoload - Carrega automaticamente as classes solicitadas    **
**				  - Carrega as configurações								   **
**				  - Cria as instâncias da classe ETL						   **
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

Use Damaplan\Norman\Core\DMPLParams;

DMPLParams::write('DMPL_CLASS_SEPARATOR', '\\');
DMPLParams::write('DMPL_CLASS_MAP', loadClassMap(ROOT));

spl_autoload_register(function ($class_name) {
	$file = findFile($class_name);

	if($file !== FALSE){
		if(file_exists($file)){
			include $file;
		}else{
			echo "\n[Autoloader] O arquivo " . $file . " (da classe " . $class_name . ") não encontrado.\n";
		}
		
	}else {
		echo "\n[Autoloader] Erro ao identificar arquivo da classe " . $class_name . ".\n";
	}
});

function findFile($class_name){
	$file = FALSE;
	$class = '';
	$cMap = array();
	
	if(isset($class_name) && strlen($class_name) > 0){
		$pieces = explode(DMPLParams::read('DMPL_CLASS_SEPARATOR'), $class_name);

		if(isset($pieces) && count($pieces) > 0){
			$class = array_pop($pieces);
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