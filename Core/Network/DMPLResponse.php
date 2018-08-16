<?php
/********************************************************************************
** @Company     : Damaplan                                                     **
** @System      : Norman - Gestor de Normativos		                           **
** @Module		: Damaplan Response - Classe para manipulação de respostas.    **
** @Namespace	: Damaplan\Norman\Core\Network								   **
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
** @Date	 	: 25/07/2018                                           	       **
** @Version	 	: 1.0                                                 	       **
** @Comment	 	: Primeira versão.                                             **
********************************************************************************/


namespace Damaplan\Norman\Core\Network;

Use Damaplan\Norman\Core\Utils\DMPLContent;
Use Damaplan\Norman\Core\Utils\Domains\DMPLContentTypes;

class DMPLResponse {

	private $_content = null;
	private $_headers = array();
	
	function __construct($aRawData = null, $aHeaders = null){
		$this->init($aRawData, $aHeaders);
	}
	
	private function _sendHeaders(){
		if(isset($this->_headers) && is_array($this->_headers)){
			foreach($this->_headers as $h => $val){
				if(is_string($val)){
					header($h . ': ' . $val);
				}else{
					if(is_array($val) && isset($val['content'])){
						$separator = isset($val['separator']) ? $val['separator'] : ':';
						header($h . $separator . ' ' . $val['content']);
					}
				}
				
			}
		}
	}
	
	private function _sendContent($aContent = ''){
		if (!is_string($aContent) && is_callable($aContent)) {
			$aContent = $aContent();
		}
		
		echo $aContent;
	}
	
	public function init($aRawData= null, $aHeaders = null){
		$this->setContent($aRawData);
		$this->setHeaders($aHeaders);
	}
	
	public function getContent(){
		return $this->_content;
	}
	
	public function setContent($aRawData = null){
		$this->_content = new DMPLContent($aRawData, DMPLContentTypes::$JSON);
	}
	
	public function getHeaders(){
		return $this->_headers;
	}
	
	public function setHeaders($aHeaders = null){
		$this->_headers = $aHeaders;
	}
	
	public function getHeader($aHeader = null){
		return (isset($this->_headers[$aHeader])? $this->_headers[$aHeader] : false);
	}
	
	public function setHeader($aHeader = null, $aData = ''){
		if(isset($aHeader) && is_string($aHeader)){
			if(!isset($this->_headers)){
				$this->_headers = array();
			}
			
			$this->_headers[$aHeader] = $aData;
			
			return $this->getHeader($aHeader);
		}else{
			return false;
		}
	}
	
	public function send(){
		if(isset($this->_content)){
			$this->_sendHeaders();
			$this->_sendContent($this->_content->json()->toText());
			return true;
		}else{
			return false;
		}
	}
	
}