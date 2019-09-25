<?php
 /********************************************************************************
 ** @Company     : Damaplan                                                     **
 ** @System      : Norman - Gestor de Normativos		                        **
 ** @Module		 : Driver_BCB_002 - Driver de manipulação do conteúdo dos		**
 **				   normativos do Banco Central.								    **
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
 ** @Date	 	: 25/09/2019                                           	        **
 ** @Version	: 1.0                                                 	        **
 ** @Comment	: Primeira versão.                                              **
 ********************************************************************************/


namespace Damaplan\Norman\Core\ETL\Drivers;

Use Damaplan\Norman\Core\DB\DMPLEntityList;
Use Damaplan\Norman\Core\ETL\DMPLTDriver;
Use Damaplan\Norman\Core\Utils\Domains\DMPLLegislationTypes;
Use Damaplan\Norman\Core\Utils\DMPLCompress;
Use Damaplan\Norman\Core\Entity\DMPLEntity_Nor_Legislation;

class DMPLTDriver_BCB_002 extends DMPLTDriver {
	
	private $_config = array();
	private $_inData = array();
	private $_entity = null;
	
	function __construct($aConfig = array(), $aInData = array()) {
		$this->init($aConfig);
	}
	
	private function _transform($aData = null){
		$text = '';
		$table = $aData->json()->find("conteudo");
		
		if(isset($table) && count($table) > 0){
			if(isset($table[0]) && isset($table[0]['Texto'])){
				$text = $table[0]['Texto'];
			}
		}
		
		$zipText = DMPLCompress::zip($text);
		
		if(!isset($this->_entity)){
			$this->_entity = new DMPLEntity_Nor_Legislation();
		}
		
		/**
		 * Se for lista de entidades, primeiro procuro a entidade pelo ID
		 * 
		 * Senão, gravo direto na entidade...
		 */
		if($this->_entity instanceof DMPLEntityList){
			$legalId = isset($table[0]['Numero']) ? $table[0]['Numero'] : '';
			$legType= isset($table[0]['Tipo']) ? $table[0]['Tipo'] : '';
			$typeId = DMPLLegislationTypes::getType($legType);
			$id = $legalId . '_' . $typeId;
			
			$this->_entity->editElement($id, array(
					'Content' 			=> $zipText,
					'ContentLoaded' 	=> 1
			));
		}else{
			$this->_entity->setAttr('Content', $zipText);
			$this->_entity->setAttr('ContentLoaded', 1);
		}
		
		return $this->_entity;
	}
	
	public function getConfig(){
		return $this->_config;
	}
	
	public function setConfig($aConfig = array()){
		$this->_config = $aConfig;
	}
	
	public function getInData(){
		return $this->_inData;
	}
	
	public function setInData($aInData = array()){
		$this->_inData = $aInData;
	}
	
	public function getEntity(){
		return $this->_entity;
	}
	
	public function setEntity($aEntity = null){
		$this->_entity = $aEntity;
	}
	
	public function init($aConfig = array(), $aInData = array()){
		$this->setConfig($aConfig);
		$this->setInData($aInData);
		$this->setEntity(new DMPLEntityList('DMPLEntity_Nor_Legislation'));
	}
	
	public function transform($aContent = null, $aPage = null){
		if(isset($aContent)){
			$this->setInData($aContent);
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
