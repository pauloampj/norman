<?php
/********************************************************************************
** @Company     : Damaplan                                                     **
** @System      : Norman - Gestor de Normativos		                           **
** @Module		: Entity - Classe pai para manipulação das entidades           **
** @Namespace	: Damaplan\Norman\Core\										   **
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

class DMPLEntity_Cad_User extends DMPLEntity {
	
	protected $_tableName = 'CAD_Users';
	protected $_primaryKey = array('Login');
	public $Id = null;
	public $Name = null;
	public $Description = null;
	public $PersonId = null;
	public $Login = null;
	public $Password = null;
	public $LastSessionId = null;
	public $CompanyId = null;
	public $StartPageId = null;
	public $RoleId = null;
	public $SituationId = null;
	public $CreateDate = null;
	public $EditDate = null;
	
	
}
