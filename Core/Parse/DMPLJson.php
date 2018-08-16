<?php
/********************************************************************************
** @Company     : Damaplan                                                     **
** @System      : Norman - Gestor de Normativos		                           **
** @Module		: JSON - Classe para manipular JSON.		 				   **
** @Namespace	: Damaplan\Norman\Core\Parse								   **
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
** @Comment	 	:                                                              **
** --------------------------------------------------------------------------- **
** @Developer	: @pauloampj                                                   **
** @Date	 	: 29/06/2018                                           	       **
** @Version	 	: 1.0                                                 	       **
** @Comment	 	: Primeira versão.                                             **
********************************************************************************/

namespace Damaplan\Norman\Core\Parse;

class DMPLJson {

	private $_rawData = '';
	private $_data = null;
	
	function __construct($aData = ''){
		$this->init($aData);
	}
	
	private function _loadData($aJsonData = ''){
		if(is_string($aJsonData)){
			$this->_data = json_decode($aJsonData, true);
		}else{
			$this->_data = $aJsonData;
		}
	}
	
	public function init($aData = ''){
		$this->_rawData = $aData;
		$this->_loadData($this->_rawData);
		
		return $this->_data;
	}
	
	public function getData(){
		return $this->_data;
	}
	
	public function find($aQuery = '.'){
		$result = null;
		$el = $this->_data;

		if(isset($aQuery) && !empty($aQuery)){
			
			$pieces = explode('.', $aQuery);
			
			if(count($pieces) > 0){
				foreach($pieces as $p){
					if(isset($el[$p])){
						$el = $el[$p];
					}
				}
				
				$result = $el;
			}
			
		}
		
		return $result;
	}
	
	public function toText(){
		if(isset($this->_data)){
			return json_encode($this->_data);
		}else{
			return '';
		}
	}
	
	
}