<?php
/********************************************************************************
** @Company     : Damaplan                                                     **
** @System      : Norman - Gestor de Normativos		                           **
** @Module		: Content - Classe para armazenar e manipular um conteúdo.	   **
** @Namespace	: Damaplan\Norman\Core\Utils								   **
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

namespace Damaplan\Norman\Core\Utils;

Use Damaplan\Norman\Core\Utils\Domains\DMPLContentTypes;
Use Damaplan\Norman\Core\Parse\DMPLParse;
Use Damaplan\Norman\Core\Parse\DMPLText;
Use Damaplan\Norman\Core\Parse\DMPLJson;
Use Damaplan\Norman\Core\Parse\DMPLXml;
Use Damaplan\Norman\Core\Parse\DMPLHtml;

class DMPLContent {

	private $_content = false;
	private $_contentType = 1;
	private $_textHandler = null;
	private $_jsonHandler = null;
	private $_xmlHandler = null;
	private $_htmlHandler = null;

	function __construct($aContent = '', $aContentType = 1){
		$this->init($aContent, $aContentType);
	}
	
	private function _setContent($aContent = ''){
		$this->_content = $aContent;
	}
	
	private function _getContent(){
		return $this->_content;
	}
	
	private function _setContentType($aContentType = 1){
		$this->_contentType = $aContentType;
	}
	
	private function _getContentType(){
		return $this->_contentType;
	}
	
	private function _textToJson($aContent = ''){
		return DMPLParse::textToJson($aContent);
	}
	
	private function _textToXml($aContent = ''){
		return DMPLParse::textToJson($aContent);
	}
	
	private function _textToHtml($aContent = ''){
		return DMPLParse::textToJson($aContent);
	}
	
	private function _jsonToText($aContent = ''){
		return DMPLParse::textToJson($aContent);
	}
	
	private function _jsonToXml($aContent = ''){
		return DMPLParse::textToJson($aContent);
	}
	
	private function _jsonToHtml($aContent = ''){
		return DMPLParse::textToJson($aContent);
	}
	
	private function _xmlToText($aContent = ''){
		return DMPLParse::textToJson($aContent);
	}
	
	private function _xmlToJson($aContent = ''){
		return DMPLParse::textToJson($aContent);
	}
	
	private function _xmlToHtml($aContent = ''){
		return DMPLParse::textToJson($aContent);
	}
	
	private function _htmlToText($aContent = ''){
		return DMPLParse::textToJson($aContent);
	}
	
	private function _htmlToJson($aContent = ''){
		return DMPLParse::textToJson($aContent);
	}
	
	private function _htmlToXml($aContent = ''){
		return DMPLParse::textToJson($aContent);
	}
	
	private function _loadTextHandler(){
		$this->_textHandler = new DMPLText($this->_getContent());
		
		return ($this->_textHandler !== false);
	}
	
	private function _loadJsonHandler(){
		$convertedData = '';
		
		switch($this->_getContentType()){
			Case DMPLContentTypes::$TEXT: $convertedData = $this->_textToJson($this->_getContent()); break;
			Case DMPLContentTypes::$JSON: $convertedData = $this->_getContent(); break;
			Case DMPLContentTypes::$XML: $convertedData = $this->_xmlToJson($this->_getContent()); break;
			Case DMPLContentTypes::$HTML: $convertedData = $this->_htmlToJson($this->_getContent()); break;
			Default: $convertedData = $this->_getContent();
		}
		
		$this->_jsonHandler = new DMPLJson($convertedData);
		
		return ($this->_jsonHandler!== false);
	}
	
	private function _loadXmlHandler(){
		$convertedData = '';
		
		switch($this->_getContentType()){
			Case DMPLContentTypes::$TEXT: $convertedData = $this->_textToXml($this->_getContent()); break;
			Case DMPLContentTypes::$JSON: $convertedData = $this->_jsonToXml($this->_getContent()); break;
			Case DMPLContentTypes::$XML: $convertedData = $this->_getContent(); break;
			Case DMPLContentTypes::$HTML: $convertedData = $this->_htmlToXml($this->_getContent()); break;
			Default: $convertedData = $this->_getContent();
		}
		
		$this->_xmlHandler = new DMPLXml($convertedData);
		
		return ($this->_xmlHandler!== false);
	}
	
	private function _loadHtmlHandler(){
		$convertedData = '';
		
		switch($this->_getContentType()){
			Case DMPLContentTypes::$TEXT: $convertedData = $this->_textToHtml($this->_getContent()); break;
			Case DMPLContentTypes::$JSON: $convertedData = $this->_jsonToHtml($this->_getContent()); break;
			Case DMPLContentTypes::$XML: $convertedData = $this->_xmlToHtml($this->_getContent()); break;
			Case DMPLContentTypes::$HTML: $convertedData = $this->_getContent(); break;
			Default: $convertedData = $this->_getContent();
		}
		
		$this->_htmlHandler = new DMPLHtml($convertedData);
		
		return ($this->_htmlHandler!== false);
	}
	
	
	
	public function init($aContent = '', $aContentType = 1){
		switch($aContentType){
			Case DMPLContentTypes::$TEXT: return $this->fromText($aContent);
			Case DMPLContentTypes::$JSON: return $this->fromJson($aContent);
			Case DMPLContentTypes::$XML: return $this->fromXml($aContent);
			Case DMPLContentTypes::$HTML: return $this->fromHtml($aContent);
			Default: return $this->fromPlainText($aContent);
		}
	}
	
	public function fromText($aContent = ''){
		$this->_setContent($aContent);
		$this->_setContentType(DMPLContentTypes::$TEXT);
		
		return true;
	}
	
	public function fromJson($aContent = ''){
		$this->_setContent($aContent);
		$this->_setContentType(DMPLContentTypes::$JSON);
		
		return true;
	}
	
	public function fromXml($aContent = ''){
		$this->_setContent($aContent);
		$this->_setContentType(DMPLContentTypes::$XML);
		
		return true;
	}
	
	public function fromHtml($aContent = ''){
		$this->_setContent($aContent);
		$this->_setContentType(DMPLContentTypes::$HTML);
		
		return true;
	}
	
	public function text(){
		if(!$this->_textHandler instanceof DMPLText){
			$this->_loadTextHandler();
		}
		
		return $this->_textHandler;
	}
	
	public function json(){
		if(!$this->_jsonHandler instanceof DMPLJson){
			$this->_loadJsonHandler();
		}
		
		return $this->_jsonHandler;
	}
	
	public function xml(){
		if(!$this->_xmlHandler instanceof DMPLXml){
			$this->_loadXmlHandler();
		}
		
		return $this->_xmlHandler;
	}
	
	public function html(){
		if(!$this->_htmlHandler instanceof DMPLHtml){
			$this->_loadHtmlHandler();
		}
		
		return $this->_htmlHandler;
	}
	
}