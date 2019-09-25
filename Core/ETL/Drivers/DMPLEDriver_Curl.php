<?php
 /********************************************************************************
 ** @Company     : Damaplan                                                     **
 ** @System      : Norman - Gestor de Normativos		                        **
 ** @Module		 : Driver_Curl - Driver de extração através do CURL.			**
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

Use Damaplan\Norman\Core\Utils\DMPLUtils;
Use Damaplan\Norman\Core\Utils\DMPLCache;
Use Damaplan\Norman\Core\ETL\DMPLEDriver;

class DMPLEDriver_Curl extends DMPLEDriver {
	
	private $_config = array();
	private $_entity = null;
	
	function __construct($aConfig = array(), $aEntity = null) {
		$this->init($aConfig, $aEntity);
	}
	
	private function _getParamUrl(){
		return $this->_config->getParams()['URL'];
	}
	
	private function _getParamData(){
		return $this->_config->getParams()['Data'];
	}
	
	private function _getQuery(){
		return DMPLUtils::formatURLQuery($this->_getParamUrl(), $this->_getParamData(), $this->_entity);
	}

	private function _getRemoteContent($aUrl = ''){
		return file_get_contents($aUrl);
	}
	
	private function _loadRemoteContent($aUrl = ''){
		$content = false;
		
		if($this->_config->useCache()){
			$content = DMPLCache::get($aUrl);

			if($content === false){
				$content = $this->_getRemoteContent($aUrl);
				DMPLCache::set($aUrl, $content);
			}
		}else{
			$content = $this->_getRemoteContent($aUrl);
		}

		return $content;
	}
	
	private function _setParams($aParams = array()){
		return $this->_config->setParams($aParams);
	}
	
	public function setEntity($aEntity = null){
		$this->_entity = $aEntity;
	}
	
	public function getEntity(){
		return $this->_entity;
	}
	
	public function init($aConfig = array(), $aEntity = null){
		$this->_config = $aConfig;
		$this->_entity = $aEntity;
	}
	
	public function extract($aParams = null){
		if(isset($aParams) && is_array($aParams)){
			$this->_setParams($aParams);
		}
		
		$content = false;
		$url = $this->_getQuery();
		debug($url);
		$data = $this->_loadRemoteContent($url);

		return $data;
	}
	
}
