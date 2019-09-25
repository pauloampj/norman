<?php
/********************************************************************************
** @Company     : Damaplan                                                     **
** @System      : Norman - Gestor de Normativos		                           **
** @Module		: Extract - Classe de manipulação dos Extratores do ETL.       **
** @Namespace	: Damaplan\Norman\ETL										   **
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
** @Comment		:                                                              **
** --------------------------------------------------------------------------- **
** @Developer	: @pauloampj                                                   **
** @Date	 	: 28/06/2018                                           	       **
** @Version	 	: 1.0                                                 	       **
** @Comment		: Primeira versão.                                             **
********************************************************************************/

namespace Damaplan\Norman\Core\ETL;

Use Damaplan\Norman\Core\Utils\DMPLParams;
Use Damaplan\Norman\Core\Utils\DMPLContent;
Use Damaplan\Norman\Core\Utils\Domains\DMPLContentTypes;

class DMPLExtract {
	
	private $_log = array();
	private $_config = null;
	private $_driverName = '';
	private $_paginatorName = '';
	private $_driver = null;
	private $_paginator = null;
	private $_content = false;
	private $_pagesCount = 0;
	private $_entity = null;
	
	function __construct($aContext = null){
		$this->init($aContext);
	}
	
	private function _loadDriver($aDriverName = ''){
		if(isset($aDriverName) && !empty($aDriverName)){
			$this->setDriverName($aDriverName);
			$className = DMPLParams::read ('ETL_DRIVER_NAMESPACE') . '\\' . DMPLParams::read ('EXTRACTOR_DRIVER_PREFIX') . '_' . $aDriverName;
			
			if(class_exists($className, true)){
				$this->setDriver(new $className($this->getConfig(), $this->getEntity()));
			}else{
				$this->addLog("[Extractor] A classe de driver " . $className. " não foi encontrada.");
			}
		}
	}
	
	private function _loadPaginator($aPaginatorName = ''){
		if(isset($aPaginatorName) && !empty($aPaginatorName)){
			$this->setPaginatorName($aPaginatorName);
			$className = DMPLParams::read ('ETL_PAGINATOR_NAMESPACE') . '\\' . DMPLParams::read ('EXTRACTOR_PAGINATOR_PREFIX') . '_' . $aPaginatorName;
			
			if(class_exists($className, true)){
				$this->setPaginator(new $className($this->getConfig()));
			}else{
				$this->addLog("[Extractor] A classe de paginação " . $className. " não foi encontrada.");
			}
		}
	}
	
	private function _extractPage($aPage = 0){
		$this->getConfig()->setParams($this->_paginator->getPageParams($aPage, $this->getConfig()->getParams()));
		return $this->_extract();
	}
	
	private function _extract(){
		return $this->_driver->extract($this->getConfig()->getParams());
	}
	
	public function init($aContext = array()){
		$this->setConfig($aContext->getEConfig());
		$this->setEntity($aContext->getEntity());
		$this->_loadDriver($this->getConfig()->getDriverName());
		$this->_loadPaginator($this->getConfig()->getPaginatorName());
		$this->_content = array();
		
		return true;
	}
	
	public function setDriverName($aDriverName = ''){
		$this->_driverName = $aDriverName;
	}
	
	public function getDriverName(){
		return $this->_driverName;
	}
	
	public function setEntity($aEntity = null, $aCascade = true){
		$this->_entity = $aEntity;
		
		if($aCascade && isset($this->_driver)){
			$this->getDriver()->setEntity($aEntity);
		}
	}
	
	public function getEntity(){
		return $this->_entity;
	}
	
	public function setPaginatorName($aName = ''){
		$this->_paginatorName = $aName;
	}
	
	public function getPaginatorName(){
		return $this->_paginatorName;
	}
	
	public function setDriver($aDriver = null){
		$this->_driver = $aDriver;
	}
	
	public function getDriver(){
		return $this->_driver;
	}
	
	public function setPaginator($aPaginator = null){
		$this->_paginator = $aPaginator;
	}
	
	public function getPaginator(){
		return $this->_paginator;
	}
	
	public function setConfig($aConfig = null){
		$this->_config = $aConfig;
	}
	
	public function getConfig(){
		return $this->_config;
	}
	
	public function setPagesCount($aPagesCount = null){
		$this->_pagesCount = $aPagesCount;
	}
	
	public function getPagesCount(){
		return $this->_pagesCount;
	}
	
	public function getLog(){
		return $this->_log;
	}
	
	public function addLog($log = ''){
		$this->_log[] = $log;
	}
	
	public function getContent(){
		return $this->_content;
	}
	
	
	
	public function extract($page = 0){
		if(!isset($page) || !is_numeric($page)){
			$page = 0;
		}		
		
		if(isset($this->_driver)){
			if(isset($this->_paginator)){
				if($this->getConfig()->autoPaginate()){
					$pagesCount = $this->_paginator->getPagesCount();
					$this->setPagesCount($pagesCount);
					
					for($i = $page; $i < $pagesCount; $i++){
						$data = $this->_extractPage($i);

						if($data !== false){
							$this->_content[$i] = new DMPLContent($data, DMPLContentTypes::$JSON);
						}
					}

					return (count($this->_content) == $pagesCount);
				}else{
					$data = $this->_extractPage($page);
					
					if($data !== false){
						$this->_content[$page] = new DMPLContent($data, DMPLContentTypes::$JSON);
					}
					
					return ($this->_content !== false);
				}
			}else{
				$data = $this->_extract();
				
				if($data !== false){
					$this->_content[0] = new DMPLContent($data, DMPLContentTypes::$JSON);
				}
				
				return ($this->_content !== false);
			}
		}else{
			$this->addLog("[Extractor] Driver " . $this->getDriverName() . " não encontrado.");
			return false;
		}
	}

}


//Onde ficará o cURL e onde terá os metodos abstratos de parse e de response.
//Além do cURL, terá métodos de conexão para web service (REST, SOAP, etc)
//Também conseguirá carregar arquivos (locais e remotos), FTP, SCP, etc
