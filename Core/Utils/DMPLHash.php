<?php
/********************************************************************************
** @Company     : Damaplan                                                     **
** @System      : Norman - Gestor de Normativos		                           **
** @Module		: Hash - Classe estática para codificar/decodificar as 		   **
**				  strings.													   **
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

Use Damaplan\Norman\Core\Utils\DMPLParams;

class DMPLHash {
	
	public static function toMd5($aString = ''){
		return hash('md5', $aString);
	}
	
	public static function toSha1($aString = ''){
		return hash('sha1', $aString);
	}
	
	public static function encryptPassword($aPassword = ''){
		switch (strtoupper(DMPLParams::read ('SECURITY.PASSWORD_HASH_METHOD'))){
			case 'MD5': 	return static::toMd5($aPassword);
			case 'SHA1':	return static::toSha1($aPassword);
			default:		return static::toMd5($aPassword);
		}
	}
	
}