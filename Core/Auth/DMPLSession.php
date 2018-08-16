<?php
/********************************************************************************
** @Company     : Damaplan                                                     **
** @System      : Norman - Gestor de Normativos		                           **
** @Module		: Damaplan Session - Classe para manipulação da sessão.		   **
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

Use Damaplan\Norman\Core\Utils\DMPLParams;
Use Damaplan\Norman\Core\Utils\DMPLHash;
Use Damaplan\Norman\Core\Utils\DMPLUtils;
Use Damaplan\Norman\Core\Utils\DMPLActivity;
Use Damaplan\Norman\Core\Utils\Domains\DMPLSessionSituationTypes;
Use Damaplan\Norman\Core\Entity\DMPLEntity_Cad_User;
Use Damaplan\Norman\Core\Entity\DMPLEntity_Gen_Session;

class DMPLSession {

	private static $_data = null;
	private static $_user = null;
	
	private static function _getUserEntity($aUsername = null){
		if(!isset(static::$_user)){
			static::$_user = new DMPLEntity_Cad_User(array(
					'filters'	=> array(
							'Login'	=> $aUsername
					)
			));
		}
		
		return static::$_user;
	}
	
	private static function _loadUserData($aUsername = null){
		$loadUser = false;
		
		if(isset($aUsername)){
			if(isset(static::$_data['User'])){
				if(static::$_data['User']['Login'] != $aUsername){
					$loadUser = true;
				}
			}else{
				$loadUser = true;
			}
			
			if($loadUser){
				$user = static::_getUserEntity($aUsername);
				$user->load();
				static::$_data['User'] = $user->serialize();
			}
			
			return true;
		}else{
			return false;
		}
	}
	
	private static function _cleanData(){
		static::$_data = array('User' => null, 'Session' => null);
	}
	
	private static function _generateHash($aPrefix = ''){
		$rawString = $aPrefix . '_' . date('Ymd') . '_' . rand();
		
		return DMPLHash::toMd5($rawString);
	}
	
	private static function _close($aUsername = null){
		if(isset($aUsername)){
			static::_loadUserData($aUsername);
			$userId = static::$_data['User']['Id'];
			$session = new DMPLEntity_Gen_Session(array(
					'filters' => array(
							'UserId' => $userId
					)
			));
			$session->load();
			$sessionData = $session->serialize();
			
			if(!isset(static::$_data['Session'])){
				static::$_data['Session'] = $sessionData;
			}
			
			if($session->delete()){
				DMPLActivity::add('Sessão encerrada', 'A sessão [' . $sessionData['Hash'] . '], do usuário ' . $aUsername . ', foi encerrada com sucesso.', $sessionData, 'SES_CLOSE', 'GEN');
				
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	
	private static function _start($aUsername = null, $aUserAgent = null){
		static::_loadUserData($aUsername);
		$userData = static::$_data['User'];
		$userId = $userData['Id'];
		$hash = static::_generateHash($userId);
		$now = mktime();
		$nowDate = DMPLUtils::dbDateTime($now);
		$expirationDate = DMPLUtils::dbDateTime($now + DMPLParams::read ('SESSION.TIME_TO_EXPIRE'));
		$session = new DMPLEntity_Gen_Session(array(
				'fields'	=> array(
						'Hash'				=>	$hash,
						'UserAgent'			=>	$aUserAgent,
						'UserId'			=>	$userId,
						'SituationId'		=>	DMPLSessionSituationTypes::$ACTIVE,
						'LastActivity'		=>	$nowDate,
						'ElapsedTime'		=>	'0',
						'ExpirationDate'	=> 	$expirationDate
				)
		));

		if($session->save()){
			static::_cleanData();
			static::$_data['User'] = $userData;
			static::$_data['Session'] = $session->serialize();
			DMPLActivity::add('Login efetuado', 'O usuário ' . static::$_data['User']['Login'] . ' fez o login com sucesso e a sessão foi iniciada.', static::$_data['Session'], 'SES_LOGIN', 'GEN');
			static::setCookie(DMPLParams::read('SESSION.KEY'), static::$_data['Session']['Hash']);

			return true;
		}else{
			return false;
		}
	}
	
	private static function _refresh($aUsername = null, $aUserAgent = null){
		static::_loadUserData($aUsername);
		$userId = static::$_data['User']['Id'];
		
		if(!isset($userId) || $userId === false){
			return false;
		}
		
		$session = new DMPLEntity_Gen_Session();
		
		if($session->load(array('UserId' => $userId))){
			$now = mktime();
			$nowDate = DMPLUtils::dbDateTime($now);
			$expirationDate = DMPLUtils::dbDateTime($now + DMPLParams::read ('SESSION.TIME_TO_EXPIRE'));
			
			$session->setAttr('UserAgent', $aUserAgent);
			$session->setAttr('SituationId', DMPLSessionSituationTypes::$ACTIVE);
			$session->setAttr('LastActivity', $nowDate);
			$session->setAttr('ElapsedTime', '0');
			$session->setAttr('ExpirationDate', $expirationDate);

			if($session->save()){
				static::_cleanData();
				static::$_data['Session'] = $session->serialize();
				
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	
	private static function _exists($aUsername = null){
		static::_loadUserData($aUsername);
		$userId = static::$_data['User']['Id'];
		$session = new DMPLEntity_Gen_Session();
		
		return $session->exists(array('UserId' => $userId));
	}
	
	public static function setCookie($aName = null, $aValue = null){
		if(isset($aName)){
			setcookie ($aName, $aValue, mktime() + 172800, '/', DMPLParams::read('DEFAULT_DOMAIN'), false, true);
		}else{
			return false;
		}
	}
	
	public static function getCookie($aName = null){
		if(isset($aName) && isset($_COOKIE[$aName])){
			return $_COOKIE[$aName];
		}else{
			return null;
		}
	}
	
	public static function start($aUsername = null, $aUserAgent = null){
		if(static::_exists($aUsername)){
			static::close($aUsername);
		}

		return static::_start($aUsername, $aUserAgent);
	}
	
	public static function data($aFilter = null){
		if(isset($aFilter) && is_string($aFilter)){
			$pieces = explode('.', $aFilter);
			
			if(isset($pieces[0])){
				$entity = $pieces[0];
				
				if(isset(static::$_data[$entity])){
					if(isset($pieces[1])){
						$field = $pieces[1];
						
						if(isset(static::$_data[$entity][$field])){
							return static::$_data[$entity][$field];
						}else{
							return null;
						}
					}else{
						return static::$_data[$entity];
					}
				}else{
					return null;
				}
			}else{
				return null;
			}			
		}else{
			return static::$_data;
		}
	}
	
	public static function refresh($aUsername = null, $aUserAgent = null){
		static::_refresh($aUsername, $aUserAgent);
	}
	
	public static function close($aUsername = null){
		return static::_close($aUsername);
	}
	
	public static function isValid(){
		return true;
	}
	
}