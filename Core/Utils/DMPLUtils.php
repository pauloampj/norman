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
	
	public static function isArrayInString($aArray = array(), $aStr = ''){
		if(isset($aArray) && count($aArray) > 0){
			foreach($aArray as $k => $v){
				if(strpos($aStr, $v) !== FALSE){
					return true;
				}
			}
		}
		
		return false;
	}
	
	public static function date_toEN($aTimestamp = null){
		if(isset($aTimestamp)){
			$timestamp = $aTimestamp;
		}else{
			$timestamp = mktime();
		}
		
		return date('Y-m-d', $timestamp);
	}
	
	public static function date_fromPT($aDate = null){
		$Y = 0;
		$M = 0;
		$D = 0;
		$h = 0;
		$m = 0;
		$s = 0;
		
		if(isset($aDate)){
			$masterPieces = explode(' ', $aDate);
			
			/**
			 * DATA
			 */
			if(isset($masterPieces[0])){
				$datePieces = explode('/', $masterPieces[0]);
				$Y = isset($datePieces[2]) ? $datePieces[2] : date('Y');
				$M = isset($datePieces[1]) ? $datePieces[1] : date('m');
				$D = isset($datePieces[0]) ? $datePieces[0] : date('d');
			}else{
				$Y = date('Y');
				$M = date('m');
				$D = date('d');
			}
			
			/**
			 * HORA
			 */
			if(isset($masterPieces[1])){
				$timePieces = explode(':', $masterPieces[1]);
				$h = isset($timePieces[0]) ? $timePieces[0] : date('H');
				$m = isset($timePieces[1]) ? $timePieces[1] : date('i');
				$s = isset($timePieces[2]) ? $timePieces[2] : date('s');
			}else{
				$h = date('H');
				$m = date('i');
				$s = date('s');
			}
		}
		
		return mktime($h, $m, $s, $M, $D, $Y);
	}
	
	public static function date_PtToEn($aDate = null){
		return self::date_toEN(self::date_fromPT($aDate));
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
	
	public static function dbDateTime($aTimestamp = null){
		if(!isset($aTimestamp) || !is_numeric($aTimestamp)){
			$aTimestamp = mktime();
		}
		
		return date('Y-m-d H:i:s', $aTimestamp);
	}
	
	public static function encrypt($aOriginalPlainText = ''){
		$cipher = base64_encode($aOriginalPlainText);
		return $cipher;
	}
	
	public static function decrypt($aCipherText = ''){
		$decoded = base64_decode($aCipherText);
		return $decoded;
	}
	
	public static function hashArray($aArray = array()){
		return md5(serialize($aArray));
	}
	
}