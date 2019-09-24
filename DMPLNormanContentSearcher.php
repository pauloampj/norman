<?php
/********************************************************************************
** @Company     : Damaplan                                                     **
** @System      : Norman - Gestor de Normativos		                           **
** @Module		: Norman Content Searcher - Classe de busca do conteúdo dos    **
**				  normativos.												   **
**				  - Carrega as configurações								   **
**				  - Cria as instâncias da classe ETL						   **
**				  - Aceita gateways, que são portais de fontes de dados,	   **
**				  como o Banco Central, a Receita Federal, etc.				   **
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
** @Date	 	: 23/09/2019                                           	       **
** @Version	 	: 1.0                                                 	       **
** @Comment	 	: Primeira versão.                                             **
********************************************************************************/


namespace Damaplan\Norman;

Use Damaplan\Norman\Core\Utils\DMPLParams;

class DMPLNormanContentSearcher {

	private $_configs = [];
	private $_entities = null;
	private $_gateways = [];
	
	function __construct($aEntities = null){
		$this->init($aEntities);
	}
	
	private function _getGateway($aGateway = null){
		if(isset($aGateway)){
			if(isset($this->_gateways[$aGateway])){
				return $this->_gateways[$aGateway];
			}else{
				return new Core\ETL\DMPLEtl($this->_configs[$aGateway]);
			}
		}
		
		return false; 
	}
	
	public function init($aEntities = null){
		$this->loadConfig();
		$this->_entities = $aEntities;
		
		return true;
	}

	public function loadConfig(){
		$this->_configs = DMPLParams::read('DMPL_GATEWAYS');
		
		return true;
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
				$contentSearcher = $entity->getAttr('ContentSearcher');
				
				if(isset($contentSearcher)){
					$gateway = $this->_getGateway($contentSearcher);
					$gateway->setEntity($entity);
					$eResult = $gateway->extract();
					
					if($eResult === false){
						debug($gateway->getExtractor()->getLog(), 'ContentSearcher - Extractor');
					}
					
					$tResult = $gateway->transform();
					
					if($tResult === false){
						debug($gateway->getTransformer()->getLog(), 'ContentSearcher - Transformer');
					}
					
					$lResult = $gateway->load();
					
					if($lResult === false){
						debug($gateway->getLoader()->getLog(), 'ContentSearcher - Loader');
					}
				}
			}
			return true;
		}else{
			return false;
		}		
	}
	
	public function getLog($aGateway = ''){
		if(isset($aGateway) && isset($this->_gateways[$aGateway])){
			return $this->_gateways[$aGateway]->getLog();
		}
		
		return false;
	}
	
}

//Criar classes estáticas de configuração, Response, Request, etc
