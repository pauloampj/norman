<?php
/********************************************************************************
** @Company     : Damaplan                                                     **
** @System      : Norman - Gestor de Normativos		                           **
** @Module		: Load - Classe de manipulação dos Loaders do ETL.		       **
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

Use Damaplan\Norman\Core\DB\DMPLEntity;
Use Damaplan\Norman\Core\DB\DMPLEntityList;

class DMPLLoad {
	
	private $_log = array();
	private $_config = null;
	private $_data = null;
	
	function __construct($aConfig = null){
		$this->init($aConfig);
	}
	
	public function init($aConfig = array()){
		$this->setConfig($aConfig);
		return true;
	}
	
	public function getConfig(){
		return $this->_config;
	}
	
	public function setConfig($aConfig = null){
		$this->_config = $aConfig;
	}
	
	public function getLog(){
		return $this->_log;
	}
	
	public function addLog($log = ''){
		$this->_log[] = $log;
	}
	
	public function getData(){
		return $this->_data;
	}
	
	public function setData($aData = null){
		$this->_data = $aData;
		$this->_data->setDriverName($this->_config->getDriverName());
		$this->_data->setDBParams($this->_config->getParams());
	}
	
	public function load($aData = null){
		if(isset($aData)){
			$this->setData($aData);

			if($this->_data instanceof DMPLEntity || $this->_data instanceof DMPLEntityList){
				
				return $this->_data->save();
			}else{
				$this->addLog("[Loader] Falha ao fazer o salvar informações, pois a entidade é inválida.");
				return false;
			}
		}else{
			$this->addLog("[Loader] Falha ao fazer o salvar informações, pois a entidade não foi informada.");
			return false;
		}
	}
	
}


//Essa classe instanciará a classe abstrata de conexão (que poderá ser um arquivo, base de dados, tela, etc)...
