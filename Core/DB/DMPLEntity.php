<?php
/********************************************************************************
** @Company     : Damaplan                                                     **
** @System      : Norman - Gestor de Normativos		                           **
** @Module		: Entity - Classe pai para manipulação das entidades           **
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
** @Comment		:                                                              **
** --------------------------------------------------------------------------- **
** @Developer	: @pauloampj                                                   **
** @Date	 	: 04/07/2018                                           	       **
** @Version	 	: 1.0                                                 	       **
** @Comment		: Primeira versão.                                             **
********************************************************************************/

 
namespace Damaplan\Norman\Core\DB;

Use Damaplan\Norman\Core\Utils\DMPLParams;

class DMPLEntity {
	
	private $_log = array();
	private $_config = null;
	private $_internalId = null;
	private $_driverName = '';
	private $_driver = null;
	private $_loaded = false;
	private $_escapeCreateFields = array(
			'Id',
			'CreateDate',
			'EditDate'
	);
	private $_escapeEditFields = array(
			'Id',
			'CreateDate'
	);
	private $_filters = null;
	protected $_primaryKey = null;
	protected $_tableName = null;
	
	function __construct($aConfig = null){
		$this->init($aConfig);
	}
	
	private function _loadDriver($aDriverName = null){
		if(isset($aDriverName)){
			$this->_driverName = $aDriverName;
		}
		
		if(isset($this->_driverName) && !empty($this->_driverName)){
			$className = DMPLParams::read ('DB_DRIVER_NAMESPACE') . '\\' . DMPLParams::read ('DATABASE_DRIVER_PREFIX') . '_' . $this->_driverName;

			if(class_exists($className, true)){
				$dbParams = $this->getDBParams();
				
				if(!isset($dbParams)){
					$this->setDBParams(DMPLParams::read ('ENTITY.DEFAULT_DB_PARAMS'), false);
				}

				$this->setDriver(new $className($this, $this->getConfig()));
			}else{
				$this->addLog("[Entity] A classe de driver " . $className. " não foi encontrada.");
			}
		}
	}
	
	private function _loadDefaultDriver(){
		if(!isset($this->_driverName) || empty($this->_driverName)){
			$this->_driverName = DMPLParams::read ('ENTITY.DEFAULT_DRIVER');
		}

		return $this->_loadDriver();
	}
	
	private function _clearProperties(){
		$fields = $this->serialize();
		
		if(isset($fields) && is_array($fields)){
			foreach($fields as $field => $value){
				$this->$field = null;
			}
			$this->_loaded = false;
		}else{
			return false;
		}
	}
	
	private function _loadFields($aFields = null){
		if(isset($aFields) && is_array($aFields)){
			foreach($aFields as $field => $value){
				if($this->hasAttr($field)){
					$this->$field = $value;
				}
			}
			return true;
		}else{
			return false;
		}
	}
	
	public function init($aConfig = array()){
		$this->setConfig($aConfig);
		
		if(isset($aConfig) && isset($aConfig['fields'])){
			$this->_loadFields($aConfig['fields']);
		}
		
		if(isset($aConfig) && isset($aConfig['filters'])){
			$this->setFilters($aConfig['filters']);
		}
		
		return true;
	}
	
	public function getName(){
		$path = explode('\\', get_class($this));
		return array_pop($path);
	}
	
	public function addLog($log = ''){
		$this->_log[] = $log;
	}
	
	public function getLog(){
		return $this->_log;
	}
	
	public function setDriverName($aDriverName = ''){
		$this->_driverName = $aDriverName;
		$this->_loadDriver();
	}
	
	public function getDriverName(){
		return $this->_driverName;
	}
	
	public function setFilters($aFilters = null){
		$this->_filters = $aFilters;
	}
	
	public function getFilters(){
		return $this->_filters;
	}
	
	public function getDriver($aLoadDriverIfNotLoaded = true){
		if(!isset($this->_driver) && $aLoadDriverIfNotLoaded){
			$this->_loadDefaultDriver();
		}
		
		return $this->_driver;
	}
	
	public function setDriver($aDriver = null){
		$this->_driver = $aDriver;
	}
	
	public function setConfig($aConfig = null){
		$this->_config = $aConfig;
		$this->_loadDriver();
	}
	
	public function getConfig(){
		return $this->_config;
	}
	
	public function setDBParams($aDBParams = null, $aReloadDriver = true){
		if(!isset($this->_config)){
			$this->_config = array();
		}
		
		if(!isset($this->_config['DB'])){
			$this->_config['DB'] = array();
		}
		
		$this->_config['DB']['Params'] = $aDBParams;
		
		if($aReloadDriver){
			$this->_loadDriver();
		}
	}
	
	public function getDBParams(){
		if(isset($this->_config['DB']) && isset($this->_config['DB']['Params'])){
			return $this->_config['DB']['Params'];
		}else{
			return null;
		}		
	}
	
	public static function getClassName($aClass = ''){
		return DMPLParams::read('ENTITY_NAMESPACE') . '\\' . $aClass;
	}
	
	public function getInternalId(){
		return $this->_internalId;
	}
	
	public function setInternalId($aId = null){
		$this->_internalId = $aId;
	}
	
	public function getCreateEscapeFields(){
		return $this->_escapeCreateFields;
	}
	
	public function getEditEscapeFields(){
		return $this->_escapeEditFields;
	}
	
	public function getPrimaryKey(){
		if(isset($this->_primaryKey)){
			if(!is_array($this->_primaryKey)){
				return array($this->_primaryKey);
			}else{
				return $this->_primaryKey;
			}
		}else{
			return array('Id');
		}
	}
	
	public function setPrimaryKey($aPk = null){
		$this->_primaryKey = $aPk;
	}
	
	public function getTableName(){
		if(isset($this->_tableName) && strlen($this->_tableName) > 0){
			return (DMPLParams::read ('DATABASE.TABLE_NAMESPACE') . $this->_tableName);
		}else{
			return $this->_tableName;
		}
	}
	
	public function isPrimaryKey($aKey = null){
		if(isset($aKey)){
			$pks = $this->getPrimaryKey();
			return in_array($aKey, $pks);
		}else{
			return false;
		}
	}
	
	public function setTableName($aTableName = null){
		$this->_tableName = $aTableName;
	}
	
	public function save(){
		$id = $this->getDriver()->save();

		if ($id !== false) {
			if ($this->hasAttr ( 'Id' )) {
				$this->Id = $id;
			}
			return true;
		} else {
			return false;
		}
	}
	
	public function exists($aFilters = null){
		if(isset($aFilters)){
			$this->setFilters($aFilters);
		}
		
		return $this->getDriver()->exists();
	}
	
	public function delete($aFilters = null){
		if(isset($aFilters)){
			$this->setFilters($aFilters);
		}
		
		if($this->getDriver()->delete()){
			$this->_clearProperties();
			return true;			
		}else{
			return false;
		}
	}
	
	public function get($aAttr = null){
		if(isset($aAttr)){
			if(!$this->_loaded){
				$this->load();
			}

			if($this->_loaded){
				return $this->getAttr($aAttr);
			}else{
				return null;
			}
		}else{
			return null;
		}
	}
	
	public function load($aFilters = null){
		if(isset($aFilters)){
			$this->setFilters($aFilters);
		}
		
		$fields = $this->getDriver()->get();

		if($fields !== FALSE){
			$this->_loadFields($fields);
			$this->_loaded = true;
			return true;
		}else{
			return false;
		}	
	}
	
	public function loadMany($aFilters = null){
		if(isset($aFilters)){
			$this->setFilters($aFilters);
		}

		$items = $this->getDriver()->select();
		
		return $items;
	}
	
	public function reset(){
		return $this->_clearProperties();
	}
	
	public function hasAttr($aAttr = null){
		if(isset($aAttr) && !empty($aAttr)){
			return property_exists($this, $aAttr);
		}else{
			return false;
		}
	}
	
	public function getAttr($aAttr = null){
		if($this->hasAttr($aAttr)){
			return $this->$aAttr;
		}else{
			return null;
		}
	}
	
	public function setAttr($aAttr = null, $aValue = null){
		if($this->hasAttr($aAttr)){
			if($this->isPrimaryKey($aAttr) && $this->_loaded){
				$this->reset();
			}
			$this->$aAttr = $aValue;
			return true;
		}else{
			return false;
		}
	}
	
	public function getFieldList(){
		$reflect = new \ReflectionClass($this);
		$props = $reflect->getProperties(\ReflectionProperty::IS_PUBLIC);
		$fields = array();
		
		foreach ($props as $prop) {
			$fields[] = $prop->getName();
		}
		
		return $fields;
	}
	
	public function serialize(){
		$reflect = new \ReflectionClass($this);
		$props = $reflect->getProperties(\ReflectionProperty::IS_PUBLIC);
		$serial = array();
		
		foreach ($props as $prop) {
			$serial[$prop->getName()] = $prop->getValue($this);
		}
		
		return $serial;
	}
	
}
