<?php
/********************************************************************************
** @Company     : Damaplan                                                     **
** @System      : Norman - Gestor de Normativos		                           **
** @Module		: Cache - Classe estática para manipular o serviço de cache    **
**				  das consultas do Norman.									   **
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

Use Damaplan\Norman\Core\Utils\DMPLHash;

class DMPLCache {
	
	private static function _fileHash($aFileName = ''){
		return DMPLHash::sha1($aFileName);
	}
	
	private static function _fileName($aFileName = ''){
		return CACHE_PATH . static::_fileHash($aFileName);
	}
	
	private static function _get($aFileName = ''){
		return file_get_contents(static::_fileName($aFileName));
	}
	
	private static function _set($aFileName= '', $aFileContent = ''){
		return file_put_contents(static::_fileName($aFileName), $aFileContent);
	}
	
	public static function get($aFileName= ''){
		$fileContent = false;

		if(isset($aFileName) && !empty($aFileName)){
			if(static::exists($aFileName)){
				$fileContent = static::_get($aFileName);
			}
		}
		
		return $fileContent;
	}
	
	public static function set($aFileName= '', $aFileContent = ''){
		$result = false;
		
		if(isset($aFileName) && !empty($aFileName)){
			$result = static::_set($aFileName, $aFileContent);
		}
		
		return $result;
	}
	
	public static function exists($aFileName = ''){
		return file_exists(static::_fileName($aFileName));
	}
	
}