<?php
 /********************************************************************************
 ** @Company     : Damaplan                                                     **
 ** @System      : Norman - Gestor de Normativos		                        **
 ** @Module		 : Driver DB SQL - Driver de carregamento através do DB SQL.	**
 ** @Namespace	 : Damaplan\Norman\DB\Drivers								    **
 ** @Copyright	 : Damaplan Consultoria LTDA (http://www.damaplan.com.br)       **
 ** @Link		 : http://norman.damaplan.com.br/documentation                  **
 ** @Email		 : sistemas@damaplan.com.br					                    **
 ** @Observation : Esta ferramenta e seu inteiro teor é de propriedade da	    **
 **				   Damaplan Consultoria e Estratégia LTDA. Não é permitida sua  **
 **				   edição, distribuição ou divulgação sem prévia autorização.   **
 ** --------------------------------------------------------------------------- **
 ** @Developer	:                                                               **
 ** @Date	 	:                                                     	        **
 ** @Version	:                                                     	        **
 ** @Comment	:                                                               **
 ** --------------------------------------------------------------------------- **
 ** @Developer	: @pauloampj                                                    **
 ** @Date	 	: 28/06/2018                                           	        **
 ** @Version	: 1.0                                                 	        **
 ** @Comment	: Primeira versão.                                              **
 ********************************************************************************/


namespace Damaplan\Norman\Core\DB\Drivers;

Use Medoo\Medoo;
Use Damaplan\Norman\Core\Utils\DMPLUtils;
Use Damaplan\Norman\Core\DB\DMPLEntity;
Use Damaplan\Norman\Core\DB\DMPLConnectorsPool;
Use Damaplan\Norman\Core\Utils\DMPLParams;

class DMPLDatabase_DbSql implements DMPLDatabaseInterface {
	
	private $_config = null;
	private $_dbHandlerHash = null;
	private $_entity = null;
	
	function __construct($aEntity = null, $aConfig = null){
		$this->init($aEntity, $aConfig);
	}
	
	private function _loadHandler(){
		if(isset($this->_config)){
			$password = (isset($this->_config['DB']['Params']['Password']) ? $this->_config['DB']['Params']['Password'] : DMPLUtils::decrypt($this->_config['DB']['Params']['PasswordHash']));
			$this->_dbHandlerHash = DMPLUtils::hashArray($this->_config['DB']['Params']);
			DMPLConnectorsPool::setIfNotExists(new Medoo([
					'database_type' => $this->_config['DB']['Params']['DBMS'],
					'database_name' => $this->_config['DB']['Params']['Database'],
					'server' => $this->_config['DB']['Params']['Host'],
					'port'	=> $this->_config['DB']['Params']['Port'],
					'username' => $this->_config['DB']['Params']['User'],
					'password' => $password
			]), $this->_dbHandlerHash);
		}
	}
	
	private function _mountPKFilters($aEntity = null){
		if(isset($aEntity)){
			$filters = array();
			$pks = $aEntity->getPrimaryKey();

			foreach($pks as $pk){
				$attr = $aEntity->getAttr($pk);
				
				if(strtoupper($pk) == 'ID' && !isset($attr)) continue;
				
				$filters[$pk] = $aEntity->getAttr($pk);
			}
			
			return $filters;
		}else{
			return false;
		}
	}
	
	private function _exists($aEntity = null){
		if(isset($aEntity)){
			$dbHandler = DMPLConnectorsPool::get($this->_dbHandlerHash);
			if(isset($dbHandler)){
				$eFilters = $aEntity->getFilters();
				$pkFilters = $this->_mountPKFilters($aEntity);
				$filters = (isset($eFilters) ? $eFilters : ((isset($pkFilters) && count($pkFilters) > 0) ? array("AND" => $pkFilters) : array()));

				return $dbHandler->has($aEntity->getTableName(), $filters);
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	
	private function _get($aEntity = null){
		if(isset($aEntity)){
			$dbHandler = DMPLConnectorsPool::get($this->_dbHandlerHash);
			if(isset($dbHandler)){
				$eFilters = $aEntity->getFilters();
				$pkFilters = $this->_mountPKFilters($aEntity);
				$filters = (isset($eFilters) ? $eFilters : ((isset($pkFilters) && count($pkFilters) > 0) ? array("AND" => $pkFilters) : array()));
				
				return $dbHandler->get($aEntity->getTableName(), $aEntity->getFieldList(), $filters);
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	
	private function _select($aEntity = null){
		if(isset($aEntity)){
			$dbHandler = DMPLConnectorsPool::get($this->_dbHandlerHash);
			if(isset($dbHandler)){
				$eFilters = $aEntity->getFilters();
				$pkFilters = $this->_mountPKFilters($aEntity);
				$filters = (isset($eFilters) ? $eFilters : ((isset($pkFilters) && count($pkFilters) > 0) ? array("AND" => $pkFilters) : array()));
				
				return $dbHandler->select($aEntity->getTableName(), $aEntity->getFieldList(), $filters);
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	
	
	private function _edit($aEntity = null){
		/**
		 * Tenho que verificar se uma entidade possui propriedades que são outras entidades...
		 * Caso sejam outras entidades, tenho que transformá-las em número, que é o ID da entidade gravada,
		 * ou seja, tenho que varrer as propriedades e caso encontre propriedades que sejam outra entidade, salvo essa outra entidade primeiro e assim por diante...
		 * Ex.:
		 * Entidade::Law {propriedades: Id:Number, Subject:String, Publisher:Government, Boundaries:Location}
		 * Entidade::Government {propriedades: Id:Number, Name:String, Description:String}
		 * Entidade::Location {propriedades: Id:Number, Name:String, Description:String}
		 *
		 * Neste exemplo, primieiro salvo Government e Location, pego os IDs deles e coloco no Law, na hora de salvar...
		 *
		 */
		if(isset($aEntity)){
			$dbHandler = DMPLConnectorsPool::get($this->_dbHandlerHash);
			if(isset($dbHandler)){
				$finalProps = array();
				$rawProps = $aEntity->serialize();
				$eFilters = $aEntity->getFilters();
				$pkFilters = $this->_mountPKFilters($aEntity);
				$filters = (isset($eFilters) ? $eFilters : ((isset($pkFilters) && count($pkFilters) > 0) ? array("AND" => $pkFilters) : array()));
				$escapeFields = $aEntity->getEditEscapeFields();
				
				if($aEntity->hasAttr('EditDate')){
					if(!isset($aEntity->EditDate)){
						$aEntity->EditDate = DMPLUtils::dbDateTime();
					}
				}
				
				$sf = (isset($filters['AND']) ? $filters['AND'] : (isset($filters['OR']) ? $filters['OR'] : $filters));
				
				foreach($rawProps as $key => $value){
					if(array_key_exists($key, $sf)) continue;
					if(in_array($key, $escapeFields)) continue;
					
					if($value instanceof DMPLEntity){
						$finalProps[$key] = $this->_save($value);
					}else{
						$finalProps[$key] = $value;
					}
				}
				
				$data = $dbHandler->update($aEntity->getTableName(), $finalProps, $filters);

				if($data->rowCount() > 0){
					return $dbHandler->id();
				}else{
					debug($dbHandler->last(),'DBDriver_Sql');
					debug($dbHandler->error(),'DBDriver_Sql');
					return false;
				}
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	
	private function _insert($aEntity = null){
		/**
		 * Tenho que verificar se uma entidade possui propriedades que são outras entidades...
		 * Caso sejam outras entidades, tenho que transformá-las em número, que é o ID da entidade gravada,
		 * ou seja, tenho que varrer as propriedades e caso encontre propriedades que sejam outra entidade, salvo essa outra entidade primeiro e assim por diante...
		 * Ex.:
		 * Entidade::Law {propriedades: Id:Number, Subject:String, Publisher:Government, Boundaries:Location}
		 * Entidade::Government {propriedades: Id:Number, Name:String, Description:String}
		 * Entidade::Location {propriedades: Id:Number, Name:String, Description:String}
		 * 
		 * Neste exemplo, primieiro salvo Government e Location, pego os IDs deles e coloco no Law, na hora de salvar...
		 * 
		 */
		if(isset($aEntity)){
			$dbHandler = DMPLConnectorsPool::get($this->_dbHandlerHash);
			if(isset($dbHandler)){
				$rawProps = $aEntity->serialize();
				$finalProps = array();
				$escapeFields = $aEntity->getCreateEscapeFields();
				
				foreach($rawProps as $key => $value){
					//Caso seja um campo para "escapar", salta a iteração...
					if(in_array($key, $escapeFields)) continue;
					
					if($value instanceof DMPLEntity){
						$finalProps[$key] = $this->_save($value);
					}else{
						$finalProps[$key] = $value;
					}
				}
				
				$data = $dbHandler->insert($aEntity->getTableName(), $finalProps);
				
				if($data->rowCount() > 0){
					return $dbHandler->id();
				}else{
					debug($dbHandler->last(),'DBDriver_Sql');
					debug($dbHandler->error(),'DBDriver_Sql');
					return false;
				}
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	
	private function _save($aEntity = null){
		if(isset($aEntity)){
			if(strlen($aEntity->getTableName()) > 0){
				if($this->_exists($aEntity)){
					if(DMPLParams::read ('ENTITY.OVERWRITE_EXISTING_ITEMS')){
						return $this->_edit($aEntity);
					}else{
						debug("A entidade [" . $aEntity->getName() . "] existe na base de dados, mas não pode ser sobrescrita por parametrização do sistema.", 'DBDriver_Sql');
						return false;
					}
				}else{
					return $this->_insert($aEntity);
				}
			}else{
				debug("A tabela da entidade [" . $aEntity->getName() . "] não foi informada.", 'DBDriver_Sql');
				return false;
			}
		}else{
			return false;
		}
	}
	
	private function _delete($aEntity = null){
		if(isset($aEntity)){
			$dbHandler = DMPLConnectorsPool::get($this->_dbHandlerHash);
			if(isset($dbHandler)){
				$eFilters = $aEntity->getFilters();
				$pkFilters = $this->_mountPKFilters($aEntity);
				$filters = (isset($eFilters) ? $eFilters : ((isset($pkFilters) && count($pkFilters) > 0) ? array("AND" => $pkFilters) : array()));
				$data = $dbHandler->delete($aEntity->getTableName(), $filters);
				
				if($data->rowCount() > 0){
					return true;
				}else{
					debug($dbHandler->last(),'DBDriver_Sql');
					debug($dbHandler->error(),'DBDriver_Sql');
					return false;
				}
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	
	public function init($aEntity = null, $aConfig = null){
		$this->setEntity($aEntity);
		$this->setConfig($aConfig);
		return true;
	}
	
	public function setEntity($aEntity = null){
		$this->_entity = $aEntity;
	}
	
	public function getEntity(){
		return $this->_entity;
	}
	
	public function setConfig($aConfig = array()){
		$this->_config = $aConfig;
		$this->_loadHandler();
	}
	
	public function getConfig(){
		return $this->_config;
	}
	
	public function save($aEntity = null){
		if(isset($aEntity)){
			$this->setEntity($aEntity);
		}
		
		
		return $this->_save($this->_entity);
	}
	
	public function exists($aEntity = null){
		if(isset($aEntity)){
			$this->setEntity($aEntity);
		}
		
		return $this->_exists($this->_entity);
	}
	
	public function delete($aEntity = null){
		if(isset($aEntity)){
			$this->setEntity($aEntity);
		}
		
		if($this->exists()){
			return $this->_delete($this->_entity);
		}else{
			return false;
		}
	}
	
	public function get($aEntity = null){
		if(isset($aEntity)){
			$this->setEntity($aEntity);
		}
		
		return $this->_get($this->_entity);
	}
	
	public function select($aEntity = null){
		if(isset($aEntity)){
			$this->setEntity($aEntity);
		}
		
		return $this->_select($this->_entity);
	}
	
}
