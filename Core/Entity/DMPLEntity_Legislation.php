<?php
/********************************************************************************
** @Company     : Damaplan                                                     **
** @System      : Norman - Gestor de Normativos		                           **
** @Module		: Entity - Classe pai para manipulação das entidades           **
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
** @Date	 	: 04/07/2018                                           	       **
** @Version	 	: 1.0                                                 	       **
** @Comment		: Primeira versão.                                             **
********************************************************************************/

 
namespace Damaplan\Norman\Core\Entity;

Use Damaplan\Norman\Core\DB\DMPLEntity;

class DMPLEntity extends DMPLEntity {
	
	public $Id = null;
	public $Subject = null;
	public $LegalId = null;
	public $Revoked = null;
	public $PublishDate = null;
	public $StartDate = null;
	public $EndDate = null;
	public $Jurisdiction = null;
	public $Purpose = null;
	public $Inspector = null;
	public $Creator = null;
	public $Subscriber = null;
	public $ModifyDate = null;
	public $IntangibleId = null;
	public $ThingId = null;
	public $CreateDate = null;
	public $EditDate = null;
	public $UserId = null;
	
	function __construct(){
		$this->init();
	}
	
	public function init(){

	}
	
	
}
