<?php
/********************************************************************************
** @Company     : Damaplan                                                     **
** @System      : Norman - Gestor de Normativos		                           **
** @Module		: Session Entity - Classe entidade das sessões.		           **
** @Namespace	: Damaplan\Norman\Core\Entity\								   **
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
** @Date	 	: 29/07/2018                                           	       **
** @Version	 	: 1.0                                                 	       **
** @Comment		: Primeira versão.                                             **
********************************************************************************/

 
namespace Damaplan\Norman\Core\Entity;

Use Damaplan\Norman\Core\DB\DMPLEntity;

class DMPLEntity_Gen_UserActivity extends DMPLEntity {
	
	protected $_tableName = 'GEN_UserActivities';
	protected $_primaryKey = array('Id');
	public $Id = null;
	public $Name = null;
	public $Description = null;
	public $EventData = null;
	public $EventKey = null;
	public $UserId = null;
	public $ModuleKey = null;
	public $SessionHash = null;
	public $CreateDate = null;
	public $EditDate = null;
	
	
}
