<?php
/********************************************************************************
** @Company     : Damaplan                                                     **
** @System      : Norman - Gestor de Normativos		                           **
** @Module		: Damaplan Authenticate - Classe para autenticações do sistema **
** @Namespace	: Damaplan\Norman\Core\Auth									   **
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
** @Date	 	: 28/07/2018                                           	       **
** @Version	 	: 1.0                                                 	       **
** @Comment	 	: Primeira versão.                                             **
********************************************************************************/


namespace Damaplan\Norman\Core\Auth;

Use Damaplan\Norman\Core\Utils\DMPLHash;
Use Damaplan\Norman\Core\Entity\DMPLEntity_Cad_User;

class DMPLAuth {

	public static function authenticate($aUsername = null, $aPassword = null){
		if(isset($aUsername) && isset($aPassword)){
			$passHash = DMPLHash::encryptPassword($aPassword);
			$entity = new DMPLEntity_Cad_User(array(
					'fields' => array(
						'Login' => $aUsername		
					)					
			));
			
			return ($entity->get('Password') == $passHash);			
		}else{
			return false;
		}
	}
	
}