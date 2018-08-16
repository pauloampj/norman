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

Use Damaplan\Norman\Core\Utils\DMPLParams;

DMPLParams::write('DMPL_CLASS_SEPARATOR', '\\');
DMPLParams::write('DMPL_CLASS_MAP', loadClassMap(ROOT));

spl_autoload_register(function ($class_name) {
	
	if(class_exists($class_name, FALSE)){
		return false;
	}
	
	$file = findFile($class_name);

	if($file !== FALSE){
		if(file_exists($file)){
			include $file;
		}else{
			debug("O arquivo " . $file . " (da classe " . $class_name . ") não encontrado.", 'Autoloader');
		}
		
	}else {
		debug("Erro ao identificar arquivo da classe " . $class_name . ".", 'Autoloader');
	}
});