<?php
/********************************************************************************
** @Company     : Damaplan                                                     **
** @System      : Norman - Gestor de Normativos		                           **
** @Module		: Damaplan Request - Classe para manipulação de requisições.   **
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

class DMPLRequest {

	private $_request = null;
	
	function __construct($aRequest = null){
		$this->init($aRequest);
	}
	
	public function init($aRequest = null){
		$this->setRawData($aRequest);
	}
	
	public function getRawData(){
		return $this->_request;
	}
	
	public function setRawData($aRequest = null){
		$this->_request = $aRequest;
	}
	
	public function getFullUri(){
		if(isset($this->_request) && isset($this->_request['REQUEST_URI'])){
			return $this->_request['REQUEST_URI'];
		}else{
			return false;
		}
	}
	
	public function getUri(){
		$fullUri = $this->getFullUri();
		
		if($fullUri !== false){
			$pieces = explode('?', $fullUri);
			
			return $pieces[0];
		}else{
			return false;
		}
	}
	
	public function getMethod(){
		if(isset($this->_request) && isset($this->_request['REQUEST_METHOD'])){
			return $this->_request['REQUEST_METHOD'];
		}else{
			return false;
		}
	}
	
	public function getUserAgent(){
		if(isset($this->_request) && isset($this->_request['HTTP_USER_AGENT'])){
			return $this->_request['HTTP_USER_AGENT'];
		}else{
			return false;
		}
	}
	
	public function getScheme(){
		if(isset($this->_request) && isset($this->_request['REQUEST_SCHEME'])){
			return $this->_request['REQUEST_SCHEME'];
		}else{
			return false;
		}
	}
	
	public function getQuery(){
		if(isset($this->_request) && isset($this->_request['QUERY_STRING'])){
			return $this->_request['QUERY_STRING'];
		}else{
			return false;
		}
	}
	
	public function getData(){
		if(isset($this->_request) && isset($this->_request['REQUEST_DATA'])){
			return $this->_request['REQUEST_DATA'];
		}else{
			return array();
		}
	}
	
	public function getOrigin(){
		if(isset($this->_request) && isset($this->_request['HTTP_ORIGIN'])){
			return $this->_request['HTTP_ORIGIN'];
		}else{
			return array();
		}
	}
	
}