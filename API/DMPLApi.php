<?php
/********************************************************************************
** @Company     : Damaplan                                                     **
** @System      : Norman - Gestor de Normativos		                           **
** @Module		: Damaplan API - Classe principal gerenciamento da API         **
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

Use Damaplan\Norman\Core\Network\DMPLRequest;
Use Damaplan\Norman\Core\Network\DMPLResponse;
Use Damaplan\Norman\Core\Utils\DMPLParams;

class DMPLApi {

	private $_request = null;
	private $_response = null;
	private $_controller = null;
	private $_log = array();
	
	function __construct($aRequest = null){
		$this->init($aRequest);
	}
	
	private function _loadController($aControllerName = null, $aVersion = null){
		$version = ((isset($aVersion) && $aVersion !== false) ? $aVersion : DMPLParams::read('API.DEFAULT_VERSION'));
		
		if(isset($aControllerName) && !empty($aControllerName)){
			$className = DMPLParams::read ('API_CONTROLLER_NAMESPACE') . '\\v' . str_replace('.', '_', $version) . '\\' . DMPLParams::read('API_CONTROLLER_PREFIX') . '_' . strtolower($aControllerName);

			if(class_exists($className, true)){
				$this->setResponse(new DMPLResponse(
						null,
						array(
								'Access-Control-Allow-Origin' => $this->getRequest()->getOrigin(),
								'Access-Control-Allow-Credentials' => 'true',
								'Access-Control-Allow-Headers' => 'Origin, Authorization, x-requested-with'
						)
						));
				$this->setController(new $className($this->_request, $this->_response));
			}else{
				$this->addLog("[API] A classe do controller " . $className. " não foi encontrada.");
			}
		}
	}
	
	public function init($aRequest = null){
		$this->setRequest($aRequest);
	}
	
	public function getVersion(){
		$uri = $this->_request->getUri();
		
		if($uri !== false){
			$pieces = explode('/', $uri);
			
			if(isset($pieces[1])){
				if(is_numeric($pieces[1])){
					return $pieces[1];
				}else{
					return false;
				}
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	
	public function getControllerName(){
		$uri = $this->_request->getUri();

		if($uri !== false){
			$pieces = explode('/', $uri);
			$pos = ($this->getVersion() ? 2 : 1);
			return (isset($pieces[$pos]) ? $pieces[$pos] : false);
		}else{
			return false;
		}
	}
	
	public function getControllerMethod(){
		$uri = $this->_request->getUri();
		
		if($uri !== false){
			$pieces = explode('/', $uri);
			$pos = ($this->getVersion() ? 3 : 2);
			return (isset($pieces[$pos]) ? $pieces[$pos] : false);
		}else{
			return false;
		}
	}
	
	public function getRequest(){
		return $this->_request;
	}
	
	public function setRequest($aRequest = null){
		$this->_request = new DMPLRequest($aRequest);
	}
	
	public function getResponse(){
		return $this->_response;
	}
	
	public function setResponse($aResponse = null){
		$this->_response = $aResponse;
	}
	
	public function setController($aController = null){
		$this->_controller = $aController;
	}
	
	public function getController(){
		return $this->_controller;
	}
	
	public function getLog(){
		return $this->_log;
	}
	
	public function addLog($log = ''){
		$this->_log[] = $log;
	}
	
	public function execute(){
		$controllerName = $this->getControllerName();
		$version = $this->getVersion();

		if(!isset($this->_controller)){
			$this->_loadController($controllerName, $version);
		}
		
		if(isset($this->_controller)){
			$method = $this->getControllerMethod();
			
			if($this->_controller->hasMethod($method)){
				return $this->_controller->$method();
			}else{
				$this->addLog("[API] O método $method não foi encontrado no controller " . $controllerName . ".");
				return false;
			}
		}else{
			$this->addLog("[API] O controller " . $controllerName . " não foi carregado corretamente.");
			return false;
		}
	}
	
	public function respond(){
		if(isset($this->_response)){
			$this->_response->send();
		}else{
			$this->addLog("[API] O objeto de resposta não foi inicializado.");
			return false;
		}
	}
	
}