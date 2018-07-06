<?php
/********************************************************************************
** @Company     : Damaplan                                                     **
** @System      : Norman - Gestor de Normativos		                           **
** @Module		: Variables - Classe estática para o fazer o parse de 	       **
**				  variáveis de ambiente.									   **
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

class DMPLVariables {
	
	private static function getVarMap(){
		return array(
			'#__TODAY_DDMMYYYY_SLASH'	=> 'TODAY_DDMMYYYY_SLASH'
		);
	}
	
	public static function isVariable($aVar = ''){
		$map = static::getVarMap();
		
		if(isset($map) && isset($map[$aVar])){
			return true;
		}else{
			return false;
		}
	}
	
	public static function getVar($aVar = ''){
		if(static::isVariable($aVar)){
			$method = '_getVar_' . static::getVarMap()[$aVar];
			
			if(method_exists(new DMPLVariables(), $method)){
				return self::{$method}();
			}else{
				return false;
			}
		}else{
			return $aVar;
		}
	}
	
	private static function _getVar_TODAY_DDMMYYYY_SLASH(){
		return date('d/m/Y', mktime());
	}
	
}