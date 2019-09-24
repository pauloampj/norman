<?php
/********************************************************************************
** @Company     : Damaplan                                                     **
** @System      : Norman - Gestor de Normativos		                           **
** @Module		: Norman Crawler - Classe principal de busca dos normativos    **
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
** @Date	 	: 28/06/2018                                           	       **
** @Version	 	: 1.0                                                 	       **
** @Comment	 	: Primeira versão.                                             **
********************************************************************************/


namespace Damaplan\Norman;

Use Damaplan\Norman\Core\Utils\DMPLParams;

class DMPLNormanCrawler {

	private $_configs = [];
	private $_gateways = [];
	
	function __construct($aGateways = array()){
		$this->init($aGateways);
	}
	
	public function init($aGateways= array()){
		$this->loadConfig();
		
		if(isset($aGateways) && count($aGateways) > 0){
			foreach($aGateways as $gateway){
				$this->addGateway($gateway);
			}
		}
		
		return true;
	}

	public function loadConfig(){
		$this->_configs = DMPLParams::read('DMPL_GATEWAYS');
		
		return true;
	}
	
	public function addGateway($aGateway = null){
		if(isset($this->_configs[$aGateway])){
			$this->_gateways[$aGateway] = new Core\ETL\DMPLEtl($this->_configs[$aGateway]);
		}
		
		return isset($this->_gateways[$aGateway]);
	}
	
	public function removeGateway($aGateway = null){
		if(isset($aGateway) && isset($this->_gateways[$aGateway])){
			unset($this->_gateways[$aGateway]);
		}
		
		return !isset($this->_gateways[$aGateway]);
	}
	
	public function run(){
		foreach($this->_gateways as $gateway){
			$eResult = $gateway->extract();

			if($eResult === false){
				debug($gateway->getExtractor()->getLog(), 'Crawler - Extractor');
			}

			$tResult = $gateway->transform();

			if($tResult === false){
				debug($gateway->getTransformer()->getLog(), 'Crawler - Transformer');
			}
			
			$lResult = $gateway->load();

			if($lResult === false){
				debug($gateway->getLoader()->getLog(), 'Crawler - Loader');
			}
		}
		return true;
	}
	
	public function getLog($aGateway = ''){
		if(isset($aGateway) && isset($this->_gateways[$aGateway])){
			return $this->_gateways[$aGateway]->getLog();
		}
		
		return false;
	}
	
}

//Criar classes estáticas de configuração, Response, Request, etc
