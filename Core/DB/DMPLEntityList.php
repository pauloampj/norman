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

Use Damaplan\Norman\Core\DB\DMPLEntity;

class DMPLEntityList {
	
	private $_entityClass = '';
	private $_entities = array();
	private $_filters = null;
	private $_loaded = false;
	
	function __construct($aEntityClass = null){
		$this->init($aEntityClass);
	}
	
	private function _getNextId(){
		if(isset($this->_entities) && count($this->_entities) > 0){
			$id = max(array_keys($this->_entities)) + 1;
		}else{
			$id = 1;
		}
		
		return $id;
	}
	
	private function _getId($entity = null){
		if(isset($entity)){
			$idKey = '';
			$v = array();
			$pks = $entity->getPrimaryKey();

			foreach($pks as $pk){
				if($entity->hasAttr($pk)){
					$v[] = $entity->getAttr($pk);
				}
			}
			
			$idKey = implode('_', $v);
			return $idKey;
		}else{
			return $this->_getNextId();
		}
	}
	
	private function _each($callback = null, $aParameters = array()){
		if(isset($callback) && is_callable($callback)){
			$results = array();
			$total = count($this->_entities);
			
			if(isset($this->_entities) && $total > 0){
				foreach($this->_entities as $k => $entity){
					if($entity instanceof DMPLEntity){
						$results[$k] = call_user_func_array($callback, array_merge(compact('k', 'entity', 'total'), $aParameters));
					}else{
						debug("A classe informada [$entity] não é compatível com a entidade [DMPLEntity]", "DMPLEntityList");
						$results[$k] = false;
					}
				}
			}
			
			return $results;
		}else{
			debug("O método de callback não foi informado ou não é compatível [$callback].", "DMPLEntityList");
			return false;
		}
	}
	
	public function init($aEntityClass = null){
		$this->setEntityClass($aEntityClass);
	}

	public function setEntityClass($aEntityClass = null){
		$this->_entityClass = $aEntityClass;
	}
	
	public function getEntityClass(){
		return $this->_entityClass;
	}
	
	public function setDBParams($aDBParams = null){
		return $this->_each(function($aId, $aEntity, $aTotal, $aDBP){
			return $aEntity->setDBParams($aDBP);
		}, compact('aDBParams'));
	}
	
	public function getDBParams(){
		return $this->_each(function($aId, $aEntity, $aTotal){
			return $aEntity->getDBParams();
		});
	}
	
	public function setFilters($aFilters = null){
		$this->_filters = $aFilters;
	}
	
	public function getFilters(){
		return $this->_filters;
	}
	
	public function setDriverName($aDriverName = null){
		return $this->_each(function($aId, $aEntity, $aTotal, $aDN){
			return $aEntity->setDriverName($aDN);
		}, compact('aDriverName'));
	}
	
	public function getDriverName(){
		return $this->_each(function($aId, $aEntity, $aTotal){
			return $aEntity->getDriverName();
		});
	}
	
	public function save(){
		return $this->_each(function($aId, $aEntity, $aTotal){
			debug("Salvando entidade $aId de $aTotal...", "ENTITY_LIST");
			return $aEntity->save();
		});
	}
	
	public function delete(){
		
	}
	
	public function get($aId = null){
		if(isset($aId)){
			if(!$this->_loaded){
				$this->load();
			}
			
			if($this->_loaded){
				if(isset($this->_entities[$aId])){
					return $this->_entities[$aId];
				}else{
					return null;
				}
			}else{
				return null;
			}
		}else{
			return $this->_entities;
		}
	}
	
	public function load($aFilters = null){
		if(isset($aFilters)){
			$this->setFilters($aFilters);
		}
		
		$className = DMPLEntity::getClassName($this->_entityClass);
		$entity= new $className();
		$list = $entity->loadMany($this->_filters);
		$this->_loaded = true;

		if(isset($list) && is_array($list) && count($list) > 0){
			foreach($list as $item){
				$this->addElement($item);
			}
			return true;
		}else{
			return false;
		}
	}
	
	public function reset(){
		
	}
	
	public function exists($aId = null){
		return false;
	}
	
	public function editElement($aId = null, $aAttrs = array()){
		
	}
	
	public function addElement($aAttrs = array()){
		$className = DMPLEntity::getClassName($this->_entityClass);
		$element = new $className();

		if(isset($aAttrs)){
			
			if(isset($aAttrs['Id']) && $this->exists($aAttrs['Id'])){
				return $this->editElement($aAttrs['Id'], $aAttrs);
			}
			
			foreach($aAttrs as $attr => $val){
				$element->setAttr($attr, $val);
			}
		}

		$id = $this->_getId($element);
		$element->setInternalId($id);
		$this->_entities[$id] = $element;

		return $element;
	}
	
	public function removeElement(){
		
	}
	
	
}
