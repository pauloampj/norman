<?php
 /********************************************************************************
 ** @Company     : Damaplan                                                     **
 ** @System      : Norman - Gestor de Normativos		                        **
 ** @Module		 : Paginator_BCB - Driver de manipulação de páginas 			**
 **				   do Banco Central.										    **
 ** @Namespace	 : Damaplan\Norman\Core\ETL\Drivers							    **
 ** @Copyright	 : Damaplan Consultoria LTDA (http://www.damaplan.com.br)       **
 ** @Link		 : http://norman.damaplan.com.br/documentation                  **
 ** @Email		 : sistemas@damaplan.com.br					                    **
 ** @Observation : Esta ferramenta e seu inteiro teor é de propriedade da	    **
 **				   Damaplan Consultoria e Estratégia LTDA. Não é permitida sua  **
 **				   edição, distribuição ou divulgação sem prévia autorização.   **
 ** --------------------------------------------------------------------------- **
 ** @Developer	:                                                               **
 ** @Date	 	:                                                     	        **
 ** @Version	:                                                     	        **
 ** @Comment	:                                                               **
 ** --------------------------------------------------------------------------- **
 ** @Developer	: @pauloampj                                                    **
 ** @Date	 	: 28/06/2018                                           	        **
 ** @Version	: 1.0                                                 	        **
 ** @Comment	: Primeira versão.                                              **
 ********************************************************************************/


namespace Damaplan\Norman\Core\ETL\Drivers;

Use Damaplan\Norman\Core\ETL\DMPLPaginator;
Use Damaplan\Norman\Core\Utils\DMPLParams;
Use Damaplan\Norman\Core\Utils\DMPLContent;
Use Damaplan\Norman\Core\Utils\Domains\DMPLContentTypes;

class DMPLPaginator_BCB extends DMPLPaginator {
	
	private $_config = array();
	private $_inData = null;
	private $_extractDriver = null;
	private $_pagesCount = null;
	private $_itensPerPage = null;
	
	function __construct($aConfig = array()) {
		$this->init($aConfig);
	}
	
	private function _loadExtractDriver($aDriverName = ''){
		if(isset($aDriverName) && !empty($aDriverName)){
			$className = DMPLParams::read ('ETL_DRIVER_NAMESPACE') . '\\' . DMPLParams::read ('EXTRACTOR_DRIVER_PREFIX') . '_' . $aDriverName;
			
			if(class_exists($className, true)){
				$this->setExtractDriver(new $className($this->getConfig()));
			}else{
				$this->addLog("[Paginator] A classe de driver " . $className. " não foi encontrada.");
			}
		}
	}
	
	private function _loadPageData(){
		if(!isset($this->_inData)){
			if(!isset($this->_extractDriver)){
				$this->_loadExtractDriver($this->getConfig()->getDriverName());
			}

			$data = $this->_extractDriver->extract();
			$this->_inData = new DMPLContent($data, DMPLContentTypes::$JSON);
		}

		$results = $this->_inData->json()->getData();

		if(isset($results)){
			$this->_itensPerPage = $results['RowCount'];
			$this->_pagesCount = ceil($results['TotalRows'] / $this->_itensPerPage);
			return true;
		}else{
			return false;
		}
	}
	
	public function init($aConfig = array()){
		if(isset($aConfig)) $this->setConfig($aConfig);
		
		$this->_loadExtractDriver($this->getConfig()->getDriverName());
	}
	
	public function getConfig(){
		return $this->_config;
	}
	
	public function setExtractDriver($aDriver = array()){
		$this->_extractDriver = $aDriver;
	}
	
	public function getExtractDriver(){
		return $this->_extractDriver;
	}
	
	public function setConfig($aConfig = array()){
		$this->_config = $aConfig;
	}
	
	public function getPageParams($aPage = 0, $aParams = array()){
		if(!isset($this->_itensPerPage)){
			$this->_loadPageData();
		}
		
		
		$page = (isset($aPage) && is_numeric($aPage)) ? $aPage : 0;
		
		if(!isset($aParams)){
			$aParams = array();
		}
		
		if(!isset($aParams['Data'])){
			$aParams['Data'] = array();
		}
		
		$aParams['Data']['startRow'] = $page * $this->_itensPerPage;

		return $aParams;
	}
	
	public function getPagesCount(){
		if(!isset($this->_pagesCount)){
			$this->_loadPageData();
		}
		
		return $this->_pagesCount;
	}
	
	public function getItensPerPage(){
		if(!isset($this->_itensPerPage)){
			$this->_loadPageData();
		}
		
		return $this->_itensPerPage;
	}
	
}
