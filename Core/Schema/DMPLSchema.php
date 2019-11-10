<?php
/********************************************************************************
** @Company     : Damaplan                                                     **
** @System      : Norman - Gestor de Normativos		                           **
** @Module		: Schema - Classe de manipulação de schemas.                   **
** @Namespace	: Damaplan\Norman\Core\Schema								   **
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
** @Date	 	: 28/09/2019                                           	       **
** @Version	 	: 1.0                                                 	       **
** @Comment		: Primeira versão.                                             **
********************************************************************************/

 
namespace Damaplan\Norman\Core\Schema;

Use Damaplan\Norman\Core\Utils\DMPLParams;

class DMPLSchema {
	
	private $_driver = null;
	private $_driverName = '';
	private $_context = null;
	private $_entity = null;
	
	function __construct($aContext = array()){
		$this->init($aContext);
	}
	
	private function _loadDriver($aDriverName = ''){
		if(isset($aDriverName) && !empty($aDriverName)){
			$this->setDriverName($aDriverName);
			$className = DMPLParams::read ('SCHEMA_DRIVER_NAMESPACE') . '\\' . DMPLParams::read ('SCHEMA_DRIVER_PREFIX') . '_' . $aDriverName;
			
			if(class_exists($className, true)){
				$this->setDriver(new $className($this->getContext(), $this->getEntity()));
			}else{
				$this->addLog("[Schema] A classe de driver " . $className. " não foi encontrada.");
			}
		}
	}
	
	public function setContext($aContext = array()){
		$this->_context = $aContext;
		$this->_loadDriver($this->_context['Driver']);
	}
	
	public function getContext(){
		return $this->_context;
	}
	
	public function setEntity($aEntity = null){
		$this->_entity = $aEntity;
	}
	
	public function getEntity(){
		return $this->_entity;
	}
	
	public function init($aContext = array()){
		$this->setContext($aContext);
		
		return true;
	}
	
	public function getDriver(){
		return $this->_driver;
	}
	
	public function setDriver($aDriver = null){
		$this->_driver = $aDriver;
	}
	
	public function setDriverName($aDriverName = ''){
		$this->_driverName = $aDriverName;
	}
	
	public function getDriverName(){
		return $this->_driverName;
	}
	
	public function transform($aEntity = null){
		if(isset($aEntity)){
			$this-setEntity($aEntity);
		}
		
		return $this->getDriver()->transform($this->getEntity());
	}
	
	public function isMine($aEntity = null){
		if(isset($aEntity)){
			$this-setEntity($aEntity);
		}
		
		return $this->getDriver()->isMine($this->getEntity());
	}
	
	public function getLog(){
		return $this->getDriver()->getLog();
	}
	
}