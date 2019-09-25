<?php

Namespace Damaplan\Norman\Core\Utils\Domains;

class DMPLLegislationTypes extends DMPLTypes {
	
	public static $GENERIC = 0;
	public static $CONSTITUTION = 1;
	public static $FEDERAL_LAW = 2;
	public static $STATE_LAW = 3;
	public static $MUNICIPAL_LAW = 4;
	public static $BC_BR_RESOLUTION = 5;
	public static $RF_BR_NORMATIVE_INSTRUCTION = 6;
	public static $BC_BR_CIRCULAR = 7;
	public static $BC_BR_CIRCULAR_LETTER = 8;
	public static $BC_BR_BULLETIN = 9;
	public static $BC_BR_JOINT_BULLETIN = 10;
	public static $BC_BR_PRESIDENTS_ACT = 11;
	public static $BC_BR_DIRECTORS_ACT = 12;
	public static $BC_BR_REGULATORY_JOINT_ACT = 13;
	public static $RF_BR_PUBLIC_ANNOUNCEMENT = 14;
	public static $BC_BR_JOINT_DECISION = 15;
	
	private static function _sanitize($aText = ''){
		$text = strtolower(trim($aText));
		
		return $text;
	}
	
	public static function getType($aText = null){
		$text = self::_sanitize($aText);

		if($text == 'comunicado'){
			return self::$BC_BR_BULLETIN;
		}
		
		if(stripos($text, 'resolu') === 0){
			return self::$BC_BR_RESOLUTION;
		}
		
		if(stripos($text, 'comunicado') === 0 && stripos($text, 'conjunto') !== false){
			return self::$BC_BR_JOINT_BULLETIN;
		}
		
		if(stripos($text, 'decisão') === 0 && stripos($text, 'conjunta') !== false){
			return self::$BC_BR_JOINT_DECISION;
		}
		
		if(stripos($text, 'ato') === 0 && stripos($text, 'diretor') !== false){
			return self::$BC_BR_DIRECTORS_ACT;
		}
		
		if(stripos($text, 'ato') === 0 && stripos($text, 'conjunto') !== false){
			return self::$BC_BR_REGULATORY_JOINT_ACT;
		}
		
		if(stripos($text, 'carta') === 0 && stripos($text, 'circular') !== false){
			return self::$BC_BR_CIRCULAR_LETTER;
		}
		
		if(stripos($text, 'circular') === 0){
			return self::$BC_BR_CIRCULAR;
		}
		
		return self::$GENERIC;
	}
	
	public static function getNameByType($aTypeId = 0){
		switch($aTypeId){
			case self::$GENERIC: return 'Genérico';
			case self::$CONSTITUTION: return 'Constituição';
			case self::$FEDERAL_LAW: return 'Lei Federal';
			case self::$STATE_LAW: return 'Lei Estadual';
			case self::$MUNICIPAL_LAW: return 'Lei Municipal';
			case self::$BC_BR_RESOLUTION: return 'Resolução';
			case self::$RF_BR_NORMATIVE_INSTRUCTION: return 'Instrução Normativa';
			case self::$BC_BR_CIRCULAR: return 'Circular';
			case self::$BC_BR_CIRCULAR_LETTER: return 'Carta Circular';
			case self::$BC_BR_BULLETIN: return 'Comunicado';
			case self::$BC_BR_JOINT_BULLETIN: return 'Comunicado Conjunto';
			case self::$BC_BR_PRESIDENTS_ACT: return 'Ato do Presidente';
			case self::$BC_BR_DIRECTORS_ACT: return 'Ato do Diretor';
			case self::$BC_BR_REGULATORY_JOINT_ACT: return 'Ato Normativo Conjunto';
			case self::$RF_BR_PUBLIC_ANNOUNCEMENT: return 'Anúncio Público';
			case self::$BC_BR_JOINT_DECISION: return 'Decisão Conjunta';
		}
	}
	
}