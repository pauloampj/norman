<?php
/********************************************************************************
** @Company     : Damaplan                                                     **
** @System      : Norman - Gestor de Normativos		                           **
** @Module		: Page Entity - Classe entidade das páginas.		           **
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
** @Date	 	: 15/08/2018                                           	       **
** @Version	 	: 1.0                                                 	       **
** @Comment		: Primeira versão.                                             **
********************************************************************************/

 
namespace Damaplan\Norman\Core\Entity;

Use Damaplan\Norman\Core\DB\DMPLEntity;

class DMPLEntity_Gen_Page extends DMPLEntity {
	
	protected $_tableName = 'GEN_Pages';
	protected $_primaryKey = array('Key');
	public $Id = null;
	public $Name = null;
	public $Description = null;
	public $Key = null;
	public $Title = null;
	public $EventId = null;
	public $EventKey = null;
	public $ModuleId = null;
	public $WebLink = null;
	public $MobileLink = null;
	public $CreateDate = null;
	public $EditDate = null;
	
}
