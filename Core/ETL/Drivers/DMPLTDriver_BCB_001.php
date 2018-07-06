<?php
 /********************************************************************************
 ** @Company     : Damaplan                                                     **
 ** @System      : Norman - Gestor de Normativos		                        **
 ** @Module		 : Driver_BCB_001 - Driver de manipulação da lista de			**
 **				   normativos do Banco Central.								    **
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

Use Damaplan\Norman\Core\Entity\DMPLEntity_Legislation;

class DMPLTDriver_BCB_001 extends DMPLTDriver {
	
	private $_config = array();
	private $_inData = array();
	private $_entity = null;
	
	function __construct($aConfig = array(), $aInData = array()) {
		$this->init($aConfig);
	}
	
	private function _transform(){
		return false;
	}
	
	public function getConfig(){
		return $this->_config;
	}
	
	public function setConfig($aConfig = array()){
		$this->_config = $aConfig;
	}
	
	public function getInData(){
		return $this->_inData;
	}
	
	public function setInData($aInData = array()){
		$this->_inData = $aInData;
	}
	
	public function getEntity(){
		return $this->_model;
	}
	
	public function setEntity($aEntity = null){
		$this->_entity = $aEntity;
	}
	
	public function init($aConfig = array(), $aInData = array()){
		$this->setConfig($aConfig);
		$this->setInData($aInData);
		$this->setEntity(new DMPLEntity_Legislation());
	}
	
	public function transform(){
		return $this->_tranform();
	}
	
	
}
