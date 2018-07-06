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

Use Damaplan\Norman\Core\Utils\DMPLVariables;

class DMPLUtils {
	
	public static function mktime($m = 0,$d = 0,$y = 0){
		return mktime(0,0,0,$m,$d,$y);
	}
	
	public static function formatURLQuery($aUrl = '', $aParams = array()){
		$url = $aUrl;
		$pUrl = '';
		
		if(isset($aParams) && count($aParams) > 0){
			$url .= '?';
			
			foreach($aParams as $key => $value){
				$pUrl .= ((strlen($pUrl) > 0) ? '&' : '');
				$pUrl .= $key . '=' . DMPLVariables::getVar($value);
			}
			
			$url .= $pUrl;
		}
		
		return $url;
	}
	
}