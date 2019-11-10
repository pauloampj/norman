<?php
 /********************************************************************************
 ** @Company     : Damaplan                                                     **
 ** @System      : Norman - Gestor de Normativos		                        **
 ** @Module		 : Driver_BCB_SCHEMA_001 - Driver de manipulação do schema  	**
 **				   de aprovação dos diretores de instituições financeiras.	    **
 ** @Namespace	 : Damaplan\Norman\Core\Schema\Drivers						    **
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


namespace Damaplan\Norman\Core\Schema\Drivers;

Use Damaplan\Norman\Core\Schema\DMPLSDriver;
Use Damaplan\Norman\Core\Utils\Domains\DMPLLegislationTypes;
Use Damaplan\Norman\Core\Utils\DMPLUtils;
Use Damaplan\Norman\Core\Utils\DMPLParams;

class DMPLSDriver_BCB_SCHEMA_001 extends DMPLSDriver {
	
	private $_config = array();
	private $_inData = array();
	private $_entity = null;
	
	function __construct($aConfig = array(), $aEntity = array()) {
		$this->init($aConfig, $aEntity);
	}
	
	private function _transform($aData = null){
		$table = $aData->json()->find('Rows');

		if(isset($table) && count($table) > 0){
			foreach($table as $rr){
				if(isset($rr['title'])){
					$title = '';
					$hitHighlightedSummary = '';
					$legID = '';
					$legType = '';
					$legDate = '';
					$link = '';
					$revoked = '0';
					$inspector = '';
					
					$title = $rr['title'];
					$hitHighlightedSummary = $rr['HitHighlightedSummary'];
					$legID = round($rr['NumeroOWSNMBR'], 0);
					$legType = $rr['TipodoNormativoOWSCHCS'];
					$revoked = $rr['RevogadoOWSBOOL'];
					$inspector = $rr['ResponsavelOWSText'];
					$dtNorma = str_replace("string;#", "", $rr['RefinableString01']);
					$dtPieces = explode(" ", $dtNorma);
					$legDate = $dtPieces[0];
					
					$typeId = DMPLLegislationTypes::getType($legType);
					$contentSearcher = $this->getContentSearcher($typeId);
					$inspectorId = 1; //1: ID do Bacen
					$creatorId = ($typeId == DMPLLegislationTypes::$BC_BR_RESOLUTION) ? 2 : $inspectorId; //2: ID do CMN
					$link = "http://www.bcb.gov.br/pre/normativos/busca/normativo.asp?numero=$legID&tipo=$legType&data=$legDate";
					$this->_entity->addElement(array(
							'Subject'					=> $title,
							'LegalId'					=> $legID,
							'PublishDate'				=> DMPLUtils::date_PtToEn($legDate),
							'StartDate'					=> DMPLUtils::date_PtToEn($legDate),
							'Purpose'					=> $hitHighlightedSummary,
							'Link'						=> $link,
							'InspectorDepartment'		=> $inspector,
							'InspectorId'				=> $inspectorId, 
							'CreatorId'					=> $creatorId, 
							'Revoked'					=> $revoked,
							'TypeId'					=> $typeId,
							'UserId'					=> DMPLParams::read ('CRAWLER_USER_ID'),
							'ContentSearcher'			=> $contentSearcher,
							'ContentLoaded'				=> 0
					));
				}
			}
		}
		
		return $this->_entity;
	}
	
	public function getConfig(){
		return $this->_config;
	}
	
	public function setConfig($aConfig = array()){
		$this->_config = $aConfig;
	}
	
	public function getEntity(){
		return $this->_inData;
	}
	
	public function setEntity($aEntity = array()){
		$this->_inData = $aEntity;
	}
	
	public function getEntity(){
		return $this->_entity;
	}
	
	public function setEntity($aEntity = null){
		$this->_entity = $aEntity;
	}
	
	public function init($aConfig = array(), $aEntity = array()){
		$this->setConfig($aConfig);
		$this->setEntity($aEntity);
	}
	
	public function getContentSearcher($aType = null){
		$map = DMPLParams::read ('DMPL_CONTENT_SEARCHER_MAP');
		
		if(isset($map[$aType])){
			return $map[$aType];
		}else{
			return $map['*'];
		}
		
	}
	
	public function transform($aContent = null, $aPage = null){
		if(isset($aContent)){
			$this->setEntity($aContent);
		}

		if(is_array($this->_inData) && count($this->_inData) > 0){
			if(isset($aPage) && is_numeric($aPage)){
				return $this->_transform($this->_inData[$aPage]);
			}else{
				foreach($this->_inData as $page => $data){
					$this->_transform($data);
				}
				
				return $this->_entity;
			}
		}else{
			return false;
		}
		
	}
	
}
