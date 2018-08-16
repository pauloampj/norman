<?php
/********************************************************************************
** @Company     : Damaplan                                                     **
** @System      : Norman - Gestor de Normativos		                           **
** @Module		: Connector Pool - Cria classe estática para armazenamento dos **
**				  conectores DB (para não criar várias instâncias.			   **
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
** @Comment	 	:                                                              **
** --------------------------------------------------------------------------- **
** @Developer	: @pauloampj                                                   **
** @Date	 	: 28/06/2018                                           	       **
** @Version	 	: 1.0                                                 	       **
** @Comment	 	: Primeira versão.                                             **
********************************************************************************/

namespace Damaplan\Norman\Core\DB;

class DMPLConnectorsPool {

    protected static $_connectors = array();

    private static function _exists($aHash = null){
    	if(!isset($aHash)){
    		return false;
    	}
    	
    	return isset(static::$_connectors[$aHash]);
    }
    
    public static function set($aConnector = null, $aHash = null) {
    	if(!isset($aHash)){
    		return false;
    	}
    	
    	static::$_connectors[$aHash] = $aConnector;
    	
    	return true;
    }
    
    public static function setIfNotExists($aConnector = null, $aHash = null) {
    	if(!isset($aHash)){
    		return false;
    	}
    	
    	if(!static::_exists($aHash)){
    		static::set($aConnector, $aHash);
    	}
    	
    	return true;
    }
    
    public static function get($aDriver = null) {
        if(!isset($aDriver)){
        	return false;
        }
        
        return (isset(static::$_connectors[$aDriver]) ? static::$_connectors[$aDriver] : false);
    }
}
