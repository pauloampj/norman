<?php

Namespace Damaplan\Norman\Core\Utils\Domains;

class DMPLLegislationTypes extends DMPLTypes {
	
	public static $CONSTITUTION = 1;
	public static $FEDERAL_LAW = 2;
	public static $STATE_LAW = 3;
	public static $MUNICIPAL_LAW = 4;
	public static $RESOLUTION = 5;
	public static $NORMATIVE_INSTRUCTION = 6;
	public static $CIRCULAR = 7;
	public static $CIRCULAR_LETTER = 8;
	public static $BULLETIN = 9;
	public static $JOINT_BULLETIN = 10;
	public static $PRESIDENTS_ACT = 11;
	public static $DIRECTORS_ACT = 12;
	public static $REGULATORY_JOINT_ACT = 13;
	public static $PUBLIC_ANNOUNCEMENT = 14;
	public static $JOINT_DECISION = 15;
	
	private static function _sanitize($aText = ''){
		$text = strtolower(trim($aText));
		
		return $text;
	}
	
	public static function getType($aText = null){
		$text = self::_sanitize($aText);

		if($text == 'comunicado'){
			return self::$BULLETIN;
		}
		
		if(stripos($text, 'resolu') === 0){
			return self::$RESOLUTION;
		}
		
		if(stripos($text, 'comunicado') === 0 && stripos($text, 'conjunto') !== false){
			return self::$JOINT_BULLETIN;
		}
		
		if(stripos($text, 'decisão') === 0 && stripos($text, 'conjunta') !== false){
			return self::$JOINT_DECISION;
		}
		
		if(stripos($text, 'ato') === 0 && stripos($text, 'diretor') !== false){
			return self::$DIRECTORS_ACT;
		}
		
		if(stripos($text, 'ato') === 0 && stripos($text, 'conjunto') !== false){
			return self::$REGULATORY_JOINT_ACT;
		}
		
		if(stripos($text, 'carta') === 0 && stripos($text, 'circular') !== false){
			return self::$CIRCULAR_LETTER;
		}
		
		if(stripos($text, 'circular') === 0){
			return self::$CIRCULAR;
		}
		
		return self::$GENERIC;
	}
	
}