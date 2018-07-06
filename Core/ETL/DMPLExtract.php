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

Use Damaplan\Norman\Core\DMPLParams;

class DMPLExtract {
	
	private $_log = array();
	private $_config = null;
	private $_driverName = '';
	private $_driver = null;
	private $_content = false;
	
	function __construct($aConfig = null){
		$this->init($aConfig);
	}
	
	private function _loadDriver($aDriverName = ''){
		if(isset($aDriverName) && !empty($aDriverName)){
			$this->setDriverName($aDriverName);
			$className = DMPLParams::read ('DRIVER_NAMESPACE') . '\\' . DMPLParams::read ('EXTRACTOR_DRIVER_PREFIX') . '_' . $aDriverName;
			
			if(class_exists($className, true)){
				$this->setDriver(new $className($this->getConfig()));
			}else{
				$this->addLog("[Extractor] A classe de driver " . $className. " não foi encontrada.");
			}
		}
	}
	
	public function init($aConfig = array()){
		$this->setConfig($aConfig);
		$this->_loadDriver($this->getConfig()->getDriverName());
		
		return true;
	}
	
	public function setDriverName($aDriverName = ''){
		$this->_driverName = $aDriverName;
	}
	
	public function getDriverName(){
		return $this->_driverName;
	}
	
	public function setDriver($aDriver = null){
		$this->_driver = $aDriver;
	}
	
	public function getDriver(){
		return $this->_driver;
	}
	
	public function setConfig($aConfig = null){
		$this->_config = $aConfig;
	}
	
	public function getConfig(){
		return $this->_config;
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
	
	public function extract(){
		if(isset($this->_driver)){
			$this->_content = $this->_driver->extract();
			
			return ($this->_content !== false);
		}else{
			$this->addLog("[Extractor] Driver " . $this->getDriverName() . " não encontrado.");
			return false;
		}
	}

}


//Onde ficará o cURL e onde terá os metodos abstratos de parse e de response.
//Além do cURL, terá métodos de conexão para web service (REST, SOAP, etc)
//Também conseguirá carregar arquivos (locais e remotos), FTP, SCP, etc
