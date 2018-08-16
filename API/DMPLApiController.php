<?php
/********************************************************************************
** @Company     : Damaplan                                                     **
** @System      : Norman - Gestor de Normativos		                           **
** @Module		: API Controller - Classe pai para os controllers da API.	   **
** @Namespace	: Damaplan\Norman\API										   **
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


namespace Damaplan\Norman\API;

Use Damaplan\Norman\Core\Utils\DMPLParams;

class DMPLApiController{
	
	private $_request = null;
	private $_response = null;

	function __construct($aRequest = null, $aResponse = null){
		$this->init($aRequest, $aResponse);
	}
	
	public function init($aRequest = null, $aResponse = null){
		$this->setRequest($aRequest);
		$this->setResponse($aResponse);
	}
	
	public function setRequest($aRequest = null){
		$this->_request = $aRequest;
	}
	
	public function getRequest(){
		return $this->_request;
	}
	
	public function setResponse($aResponse = null){
		$this->_response = $aResponse;
	}
	
	public function getResponse(){
		return $this->_response;
	}
	
	public function requestMethod(){
		return strtoupper($this->_request->getMethod());
	}
	
	public function requestData(){
		return $this->_request->getData();
	}
	
	public function respondMethodNotAllowed(){
		$this->_response->setContent(array(
			'status_code' => 405,
			'status_name' => 'Método não permitido',
			'message' => 'O método de requisição utilizado [' . $this->requestMethod() . '] não é permitido para esta função.'
		));
		
		$sapi_type = php_sapi_name();
		if (substr($sapi_type, 0, 3) == 'cgi'){
			$this->_response->setHeader('Status', '405 Method Not Allowed');
		}else{
			$this->_response->setHeader('HTTP/1.1', array('content' => '405 Method Not Allowed', 'separator' => ''));
		}
		
		return true;
	}
	
	public function sendToHome(){
		$this->_response->setHeader('Location', DMPLParams::read('HOME_URL'));
		
		return true;
	}

	public function hasMethod($aMethodName = null){
		return method_exists($this, $aMethodName);
	}
	
}