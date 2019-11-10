<?php
/********************************************************************************
** @Company     : Damaplan                                                     **
** @System      : Norman - Gestor de Normativos		                           **
** @Module		: Norman Schematic - Classe de transformação do conteúdo dos   **
**				  normativos (comunicados) em dados estruturados.			   **
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
** @Date	 	: 28/09/2019                                           	       **
** @Version	 	: 1.0                                                 	       **
** @Comment	 	: Primeira versão.                                             **
********************************************************************************/


namespace Damaplan\Norman;

Use Damaplan\Norman\Core\Utils\DMPLParams;
Use Damaplan\Norman\Core\Schema\DMPLSchema;

class DMPLNormanSchematic {

	private $_configs = null;
	private $_entities = null;
	private $_schemas = [];
	
	function __construct($aEntities = null){
		$this->init($aEntities);
	}
	
	private function _getSchema($aEntity = null){
		foreach($this->_schemas as $schema){
			if($schema->isMine($aEntity)){
				return $shema;
			}
		}
		
		return false;
	}
	
	public function init($aEntities = null){
		$this->_entities = $aEntities;
		$this->loadConfig();
		$this->loadSchemas();
		
		return true;
	}

	public function loadConfig(){
		$this->_configs = DMPLParams::read('DMPL_SCHEMAS');
		
		return true;
	}
	
	public function loadSchemas(){
		if(!isset($this->_configs)){
			$this->loadConfig();
		}
		
		foreach($this->_configs as $schema => $params){
			$this->_schemas[$schema] = new DMPLSchema($params);
		}
		
		return (count($this->_schemas) > 0);
	}
	
	public function setEntities($aEntities = null){
		$this->_entities = $aEntities;
		
		return true;
	}
	
	public function getEntities(){
		return $this->_entities;
	}
	
	public function run(){
		if($this->_entities instanceof Core\DB\DMPLEntityList){
			$entities = $this->_entities->get();
			
			foreach($entities as $entity){
				$schema = $this->_getSchema($entity);
				
				if($schema !== false){
					$schema->transform($entity);
				}
			}
			return true;
		}else{
			return false;
		}		
	}
	
	public function getLog($aSchema = ''){
		if(isset($aSchema) && isset($this->_schemas[$aSchema])){
			return $this->_schemas[$aSchema]->getLog();
		}
		
		return false;
	}
	
}

//Criar classes estáticas de configuração, Response, Request, etc
