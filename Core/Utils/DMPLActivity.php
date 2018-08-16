<?php
/********************************************************************************
** @Company     : Damaplan                                                     **
** @System      : Norman - Gestor de Normativos		                           **
** @Module		: Atividades - Classe para gestão das atividades do usuário.   **
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
** @Date	 	: 02/08/2018                                           	       **
** @Version	 	: 1.0                                                 	       **
** @Comment	 	: Primeira versão.                                             **
********************************************************************************/

namespace Damaplan\Norman\Core\Utils;

Use Damaplan\Norman\Core\Entity\DMPLEntity_Gen_UserActivity;
Use Damaplan\Norman\Core\Auth\DMPLSession;
Use Damaplan\Norman\Core\Utils\DMPLContent;
Use Damaplan\Norman\Core\Utils\Domains\DMPLContentTypes;

class DMPLActivity {

    public static function add($aName = '', $aDescription = '', $aEventData = null, $aEventKey = '', $aModuleKey = '') {
    	if(!isset($aEventData)){
    		$eventData = '';
    	}else{
    		if(is_string($aEventData)){
    			$eventData = $aEventData;
    		}else{
    			$content = new DMPLContent($aEventData, DMPLContentTypes::$JSON);
    			$eventData = $content->json()->toText();
    		}
    	}
    	
    	$entity = new DMPLEntity_Gen_UserActivity(array(
    			'fields'	=> array(
    					'Name'				=>	$aName,
    					'Description'		=>	$aDescription,
    					'EventData'			=>	$eventData,
    					'EventKey'			=>	$aEventKey,
    					'UserId'			=>	DMPLSession::data('User.Id'),
    					'ModuleKey'			=> 	$aModuleKey,
    					'SessionHash'		=> 	DMPLSession::data('Session.Hash')
    			)
    	));
  	
    	if($entity->save()){
    		//Feito desta forma pra adicionar log (interno), caso não consiga adicionar a atividade...
    		return true;	
    	}else{
    		return false;
    	}
    }

}
