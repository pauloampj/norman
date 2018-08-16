<?php
/********************************************************************************
** @Company     : Damaplan                                                     **
** @System      : Norman - Gestor de Normativos		                           **
** @Module		: Erros - Cria classe estática para gestão da lista de erros   **
**				  da aplicação.												   **
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
** @Date	 	: 28/06/2018                                           	       **
** @Version	 	: 1.0                                                 	       **
** @Comment	 	: Primeira versão.                                             **
********************************************************************************/

namespace Damaplan\Norman\Core\Utils;

Use Damaplan\Norman\Core\Utils\DMPLParams;

class DMPLErrors {

	private static $_errors = null;
	
	private static function _loadErrorsList(){
		if(!isset(static::$_errors)){
			static::$_errors = DMPLParams::read ('ERRORS_LIST');
		}
		
		return (static::$_errors !== false);
	}
	
	public static function hasError($aKey = null){
		if(isset($aKey)){
			static::_loadErrorsList();
			
			return isset(static::$_errors[$aKey]);
		}else{
			return false;
		}
	}
	
    public static function get($aKey = null) {
    	if(isset($aKey)){
    		static::_loadErrorsList();
    		
    		if(static::hasError($aKey)){
    			return static::$_errors[$aKey];
    		}else{
    			return false;
    		}
    	}else{
    		return false;
    	}
    }

}
