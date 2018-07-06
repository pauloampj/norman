<?php
/********************************************************************************
** @Company     : Damaplan                                                     **
** @System      : Norman - Gestor de Normativos		                           **
** @Module		: Entity - Classe pai para manipulação das entidades           **
** @Namespace	: Damaplan\Norman\Core\DB									   **
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
** @Date	 	: 04/07/2018                                           	       **
** @Version	 	: 1.0                                                 	       **
** @Comment		: Primeira versão.                                             **
********************************************************************************/

 
namespace Damaplan\Norman\Core\DB;

class DMPLEntity {
	
	
	function __construct(){
		$this->init();
	}
	
	public function init(){

	}
	
	public function save(){
		
	}
	
	public function exists(){
		
	}
	
	public function delete(){
		
	}
	
	public function get(){
		
	}
	
	public function new(){
		
	}
	
	public function hasAttribute($aAttr = null){
		if(isset($aAttr) && !empty($aAttr)){
			
		}else{
			return false;
		}
	}
	
	public function getAttribute($aAttr = null){
		if($this->hasAttribute($aAttr)){
			
		}else{
			return null;
		}
	}
	
	public function setAttribute($aAttr = null, $aValue = null){
		if($this->hasAttribute($aAttr)){
			
			return true;
		}else{
			return false;
		}
	}
	
	
}
