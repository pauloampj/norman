<?php
/********************************************************************************
** @Company     : Damaplan                                                     **
** @System      : Norman - Gestor de Normativos		                           **
** @Module		: Transform - Classe de manipulação dos Transformers do ETL.   **
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

class DMPLTransform {
	
	private $_log = array();
	private $_config = null;
	private $_driverName = '';
	private $_driver = null;
	private $_inData = false;
	private $_transformedData = false;
	
	function __construct($aContext = null, $aContent = null){
		$this->init($aContext, $aContent);
	}
	
	private function _loadDriver($aDriverName = ''){
		if(isset($aDriverName) && !empty($aDriverName)){
			$this->setDriverName($aDriverName);
			$className = DMPLParams::read ('ETL_DRIVER_NAMESPACE') . '\\' . DMPLParams::read ('TRANSFORMER_DRIVER_PREFIX') . '_' . $aDriverName;
			
			if(class_exists($className, true)){
				$this->setDriver(new $className($this->getConfig(), $this->getInData()));
			}else{
				$this->addLog("[Transformer] A classe de driver " . $className. " não foi encontrada.");
			}
		}
	}
	
	public function init($aContext = array(), $aContent = null){
		$this->setConfig($aContext->getTConfig());
		$this->setInData($aContent);
		
		return true;
	}
	
	public function addLog($log = ''){
		$this->_log[] = $log;
	}
	
	public function setInData($aInData = ''){
		$this->_inData = $aInData;
	}
	
	public function getInData(){
		return $this->_inData;
	}
	
	public function setTransformedData($aTransformedData = '', $aCascade = false){
		$this->_transformedData = $aTransformedData;
		
		if($aCascade && isset($this->_driver)){
			$this->_driver->setEntity($aTransformedData);
		}
	}
	
	public function getTransformedData(){
		return $this->_transformedData;
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
		$this->_loadDriver($this->getConfig()->getDriverName());
	}
	
	public function getConfig(){
		return $this->_config;
	}
	
	public function getLog(){
		return $this->_log;
	}
	
	public function transform($aContent = null){
		if(isset($aContent)){
			$this->setInData($aContent);
		}
		
		if(isset($this->_driver)){
			$this->_transformedData = $this->_driver->transform($this->getInData());

			return ($this->_transformedData !== false);
		}else{
			$this->addLog("[Transformer] Driver " . $this->getDriverName() . " não encontrado.");
			return false;
		}
	}
	
}



