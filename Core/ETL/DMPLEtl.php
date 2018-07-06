<?php
/********************************************************************************
** @Company     : Damaplan                                                     **
** @System      : Norman - Gestor de Normativos		                           **
** @Module		: ETL - Classe de manipulação do ETL                           **
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

Use Damaplan\Norman\Core\Utils\DMPLEtlContext;

class DMPLEtl {
	
	private $_hExtractor;
	private $_hTransformer;
	private $_hLoader;
	private $_context;
	
	function __construct($aContext = array()){
		$this->init($aContext);
	}
	
	public function setContext($aContext = array()){
		$this->_context = new DMPLEtlContext($aContext);
	}
	
	public function getContext(){
		return $this->_context;
	}
	
	public function init($aContext = array()){
		$this->setContext($aContext);
		$this->_hExtractor = new DMPLExtract($this->_context->getEConfig());
		$this->_hTransformer = new DMPLTransform($this->_context->getTConfig());
		$this->_hLoader = new DMPLLoad($this->_context->getLConfig());
		return true;
	}
	
	public function getExtractor(){
		return $this->_hExtractor;
	}
	
	public function getTransformer(){
		return $this->_hTransformer;
	}
	
	public function getLoader(){
		return $this->_hLoader;
	}
	
	public function getExtractedContent(){
		return $this->_hExtractor->getContent();
	}
	
	public function extract(){
		return $this->_hExtractor->extract();
	}
	
	public function transform(){
		return $this->_hTransformer->transform();
	}
	
	public function load(){
		return $this->_hLoader->load();
	}
	
	public function getLog(){
		$log = array(
				'Extractor' => $this->_hExtractor->getLog(),
				'Transformer' => $this->_hTransformer->getLog(),
				'Loader' => $this->_hLoader->getLog()
		);
		
		return $log;
	}
	
}

//Essa classe instanciará as três classes do ETL: Extract, Transform (onde carrega os drivers) e Load (onde salvará no data warehouse
