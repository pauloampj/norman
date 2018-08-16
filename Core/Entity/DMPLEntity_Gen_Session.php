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

class DMPLEntity_Gen_Session extends DMPLEntity {
	
	protected $_tableName = 'GEN_Sessions';
	protected $_primaryKey = array('Hash');
	public $Id = null;
	public $Hash = null;
	public $UserAgent = null;
	public $UserId = null;
	public $SituationId = null;
	public $LastActivity = null;
	public $ElapsedTime = null;
	public $ExpirationDate = null;
	public $CreateDate = null;
	public $EditDate = null;
	
	
}
