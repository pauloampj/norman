<?php
/********************************************************************************
** @Company     : Damaplan                                                     **
** @System      : Norman - Gestor de Normativos		                           **
** @Module		: EConfig - Classe de armazenamento das configurações da	   **
**				  etapa de extração do ETL.									   **
** @Namespace	: Damaplan\Norman\Utils										   **
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

 
namespace Damaplan\Norman\Core\Utils;


class DMPLEConfig extends DMPLConfig{
	
	private $_driverName = '';
	private $_params = array();
	private $_useCahche = false;
	
	function __construct($aConfig = array()) {
		$this->init($aConfig);
	}
	
	public function init($aConfig = array()){
		if(isset($aConfig) && is_array($aConfig)){
			if(isset($aConfig['Driver'])) $this->setDriverName($aConfig['Driver']);
			if(isset($aConfig['Params'])) $this->setParams($aConfig['Params']);
			
			if(isset($aConfig['UseCache'])){
				$this->_useCache = $aConfig['UseCache'];
			}
			
			return true;
		}else{
			return false;
		}
	}
	
	public function getDriverName(){
		return $this->_driverName;
	}
	
	public function setDriverName($aName){
		$this->_driverName = $aName;
	}
	
	public function getParams(){
		return $this->_params;
	}
	
	public function setParams($aParams){
		$this->_params = $aParams;
	}
	
	public function useCache(){
		return $this->_useCache;
	}
		
}
