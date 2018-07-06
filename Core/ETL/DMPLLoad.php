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

class DMPLLoad {
	
	private $_log = array();
	private $_config = null;
	
	function __construct($aConfig = null){
		$this->init($aConfig);
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
	
	public function init($aConfig = array()){
		$this->setConfig($aConfig);
		return true;
	}
	
	public function load(){
		echo "\nCarregando...\n";
	}
	
}


//Essa classe instanciará a classe abstrata de conexão (que poderá ser um arquivo, base de dados, tela, etc)...
