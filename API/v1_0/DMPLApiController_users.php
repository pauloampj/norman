<?php
/********************************************************************************
** @Company     : Damaplan                                                     **
** @System      : Norman - Gestor de Normativos		                           **
** @Module		: API Users Controller - Classe para manipulação dos usuários. **
** @Namespace	: Damaplan\Norman\API\v1_0									   **
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
** @Date	 	: 25/07/2018                                           	       **
** @Version	 	: 1.0                                                 	       **
** @Comment	 	: Primeira versão.                                             **
********************************************************************************/


namespace Damaplan\Norman\API\v1_0;

Use Damaplan\Norman\API\DMPLApiController;
Use Damaplan\Norman\Core\Auth\DMPLAuth;
Use Damaplan\Norman\Core\Auth\DMPLSession;
Use Damaplan\Norman\Core\Utils\DMPLErrors;
Use Damaplan\Norman\Core\DB\DMPLEntityList;
Use Damaplan\Norman\Core\Entity\DMPLEntity_Cad_User;
Use Damaplan\Norman\Core\Entity\DMPLEntity_Gen_Session;

class DMPLApiController_users extends DMPLApiController {
	
	private function _login($aStartSession = true){
		if(in_array($this->requestMethod(), array('POST'))){
			$data = $this->requestData();

			if(isset($data) && isset($data['Login']) && isset($data['Password'])){
				if(DMPLAuth::authenticate($data['Login'], $data['Password'])){
					if($aStartSession){
						if(DMPLSession::start($data['Login'], $this->getRequest()->getUserAgent())){
							$this->getResponse()->setContent(DMPLErrors::get('SESSION_SUCCESS'));
							return true;
						}else{
							$this->getResponse()->setContent(DMPLErrors::get('SESSION_NOT_STARTED'));
							return false;
						}
					}else{
						$this->getResponse()->setContent(DMPLErrors::get('AUTH_SUCCESS'));
						return true;
					}					
				}else{
					$this->getResponse()->setContent(DMPLErrors::get('AUTH_WRONG_PASSWORD'));
					return false;
				}
			}else{
				$this->getResponse()->setContent(DMPLErrors::get('BAD_PARAMETERS'));
				return false;
			}
		}else{
			return $this->respondMethodNotAllowed();
		}
	}
	
	private function _logout(){
		if(in_array($this->requestMethod(), array('POST', 'GET'))){
			$data = $this->requestData();
			
			if(isset($data) && isset($data['Login'])){
				if(DMPLSession::close($data['Login'])){
					$this->getResponse()->setContent(DMPLErrors::get('SESSION_CLOSE'));
					return true;
				}else{
					$this->getResponse()->setContent(DMPLErrors::get('SESSION_NOT_CLOSED'));
					return false;
				}
			}else{
				$this->getResponse()->setContent(DMPLErrors::get('BAD_PARAMETERS'));
				return false;
			}
		}else{
			return $this->respondMethodNotAllowed();
		}
	}
	
	private function _loadChildrenMenu($aMenuId = null, $aMenus = null){
		if(isset($aMenuId) && isset($aMenus)){
			$menuChilds = array();
			
			foreach($aMenus as $k => $v){
				if(!isset($v['parent_id'])){
					$v['parent_id'] = '';
				}
				
				if($v['parent_id'] == $aMenuId){
					if(!isset($v['children'])){
						$v['children'] = $this->_loadChildrenMenu($v['id'], $aMenus);
					}
					
					$menuChilds[$v['id']] = $v;
				}
			}
			
			return $menuChilds;
		}else{
			return null;
		}
	}
	
	private function _loadPagesLinks(&$aMenus = null){
		if(isset($aMenus) && is_array($aMenus)){
			$pageKeys = array();
			
			foreach($aMenus as $k => $menu){
				if(isset($menu['page_key']) && strlen($menu['page_key']) > 0){
					$pageKeys[] = $menu['page_key'];
				}
			}
			
			$pageUniqueKeys = array_unique($pageKeys);
			
			//Aqui, carrego a Entidade das pages e verifico o link delas...
			$pagesList = new DMPLEntityList('DMPLEntity_Gen_Page');
			$pagesList->setFilters(array(
					'Key' => $pageUniqueKeys
			));
			$pagesList->load();
			$pages = $pagesList->get();
			
			foreach($aMenus as &$menu){
				if(isset($menu['page_key']) && strlen($menu['page_key']) > 0){
					if(isset($pages[$menu['page_key']])){
						$p = $pages[$menu['page_key']];
						$wl = $p->getAttr('WebLink');
						$ml = $p->getAttr('MobileLink');
						$menu['web_link'] = (isset($wl) ? $wl: '');
						$menu['mobile_link'] = (isset($ml) ? $ml : '');
					}
				}
			}
		}		
	}
	
	private function _organizeMenuTree($aMenus = null){
		if(isset($aMenus)){
			$menuTree = array('root' => array('children' => array()));
			$parent = null;
			
			if(is_array($aMenus)){
				$menuTree['root']['children'] = $this->_loadChildrenMenu('', $aMenus);
			}
			
			return $menuTree;
		}else{
			return false;
		}
	}
	
	public function login(){
		return $this->_login(true);		
	}
	
	public function logout(){
		return $this->_logout();
	}
	
	public function authenticate(){
		return $this->_login(false);
	}
	
	public function menu(){
		if(in_array($this->requestMethod(), array('GET'))){
			$data = array(
					'user' => array(),
					'menus' => array(
							array(
									'id' => '1',
									'name' => 'Geral',
									'description' => 'Módulo geral do sistema',
									'module_id'		=> '1',
									'icon' => '',
									'type_id' => 'LBL',
									'page_key' => '',
									'level'		=> '1',
									'parent_id' => '',
									'class' => 'dmpl-module-gen'
							),array(
									'id' => '2',
									'name' => 'Cadastro',
									'description' => 'Módulo de cadastros do sistema',
									'module_id'		=> '2',
									'icon' => '',
									'type_id' => 'LBL',
									'page_key' => '',
									'level'		=> '1',
									'parent_id' => '',
									'class' => 'dmpl-module-cad'
									
							),array(
									'id' => '3',
									'name' => 'Normativos',
									'description' => 'Módulo de normativos do sistema (Norman)',
									'module_id'		=> '3',
									'icon' => '',
									'type_id' => 'LBL',
									'page_key' => '',
									'level'		=> '1',
									'parent_id' => '',
									'class' => 'dmpl-module-nor'
									
							),array(
									'id' => '4',
									'name' => 'Indicadores',
									'description' => 'Módulo de indicadores do sistema',
									'module_id'		=> '4',
									'icon' => '',
									'type_id' => 'LBL',
									'page_key' => '',
									'level'		=> '1',
									'parent_id' => '',
									'class' => 'dmpl-module-ind'
									
							),array(
									'id' => '5',
									'name' => 'Chamados',
									'description' => 'Módulo de chamados do sistema',
									'module_id'		=> '5',
									'icon' => '',
									'type_id' => 'LBL',
									'page_key' => '',
									'level'		=> '1',
									'parent_id' => '',
									'class' => 'dmpl-module-sos'
									
							),array(
									'id' => '6',
									'name' => 'Meus Painéis',
									'description' => 'Dashboards privados',
									'module_id'		=> '1',
									'icon' => 'pli-cloud',
									'type_id' => 'MN_1',
									'page_key' => '',
									'level'		=> '2',
									'parent_id' => '1',
									'path' => '1_6',
									'class' => 'dmpl-module-gen'
									
							),array(
									'id' => '7',
									'name' => 'Painéis Compartilhados',
									'description' => 'Dashboards compartilhados por outros usuários',
									'module_id'		=> '1',
									'icon' => 'pli-clouds',
									'type_id' => 'MN_1',
									'page_key' => '',
									'level'		=> '2',
									'parent_id' => '1',
									'path' => '1_7',
									'class' => 'dmpl-module-gen'
									
							),array(
									'id' => '8',
									'name' => 'Configurações',
									'description' => 'Configurações gerais do sistema',
									'module_id'		=> '1',
									'icon' => 'demo-pli-gear',
									'type_id' => 'MN_1',
									'page_key' => '',
									'level'		=> '2',
									'parent_id' => '1',
									'path' => '1_8',
									'class' => 'dmpl-module-gen'
									
							),array(
									'id' => '9',
									'name' => 'Geral',
									'description' => 'Cadastro geral',
									'module_id'		=> '2',
									'icon' => 'pli-address-book',
									'type_id' => 'MN_1',
									'page_key' => '',
									'level'		=> '2',
									'parent_id' => '2',
									'path' => '2_9',
									'class' => 'dmpl-module-cad'
									
							),array(
									'id' => '10',
									'name' => 'Configurações',
									'description' => 'Configurações do módulo de cadastros',
									'module_id'		=> '2',
									'icon' => 'demo-pli-gear',
									'type_id' => 'MN_1',
									'page_key' => '',
									'level'		=> '2',
									'parent_id' => '2',
									'path' => '2_10',
									'class' => 'dmpl-module-cad'
									
							),array(
									'id' => '11',
									'name' => 'Pesquisa',
									'description' => 'Pesquisas de normativos',
									'module_id'		=> '3',
									'icon' => 'pli-search-on-cloud',
									'type_id' => 'MN_1',
									'page_key' => '',
									'level'		=> '2',
									'parent_id' => '3',
									'path' => '3_11',
									'class' => 'dmpl-module-nor'
									
							),array(
									'id' => '12',
									'name' => 'Notificações',
									'description' => 'Configuração das notificações do norman',
									'module_id'		=> '3',
									'icon' => 'pli-mail',
									'type_id' => 'MN_1',
									'page_key' => '',
									'level'		=> '2',
									'parent_id' => '3',
									'path' => '3_12',
									'class' => 'dmpl-module-nor'
									
							),array(
									'id' => '13',
									'name' => 'Configurações',
									'description' => 'Configurações gerais do módulo de normativos',
									'module_id'		=> '3',
									'icon' => 'demo-pli-gear',
									'type_id' => 'MN_1',
									'page_key' => '',
									'level'		=> '2',
									'parent_id' => '3',
									'path' => '3_13',
									'class' => 'dmpl-module-nor'
									
							),array(
									'id' => '14',
									'name' => 'Manutenção',
									'description' => 'Manutenção dos indicadores',
									'module_id'		=> '4',
									'icon' => 'pli-formula',
									'type_id' => 'MN_1',
									'page_key' => '',
									'level'		=> '2',
									'parent_id' => '4',
									'path' => '4_14',
									'class' => 'dmpl-module-ind'
									
							),array(
									'id' => '15',
									'name' => 'Configurações',
									'description' => 'Configurações gerais do módulo de indicadores',
									'module_id'		=> '4',
									'icon' => 'demo-pli-gear',
									'type_id' => 'MN_1',
									'page_key' => '',
									'level'		=> '2',
									'parent_id' => '4',
									'path' => '4_15',
									'class' => 'dmpl-module-ind'
									
							),array(
									'id' => '16',
									'name' => 'Meus Chamados',
									'description' => 'Gestão dos chamados do cliente',
									'module_id'		=> '5',
									'icon' => 'pli-megaphone',
									'type_id' => 'MN_1',
									'page_key' => '',
									'level'		=> '2',
									'parent_id' => '5',
									'path' => '5_16',
									'class' => 'dmpl-module-sos'
									
							),array(
									'id' => '17',
									'name' => 'Configurações',
									'description' => 'Configurações gerais do módulo de chamados',
									'module_id'		=> '5',
									'icon' => 'demo-pli-gear',
									'type_id' => 'MN_1',
									'page_key' => '',
									'level'		=> '2',
									'parent_id' => '5',
									'path' => '5_17',
									'class' => 'dmpl-module-sos'
									
							),array(
									'id' => '18',
									'name' => 'Configurar',
									'description' => 'Configurações das notificações de normativos',
									'module_id'		=> '3',
									'icon' => '',
									'type_id' => 'MN_2',
									'page_key' => 'PNOR0001',
									'level'		=> '3',
									'parent_id' => '12',
									'path' => '3_12_18',
									'class' => 'dmpl-module-ind'
									
							),array(
									'id' => '19',
									'name' => 'Histórico',
									'description' => 'Histórico de envio das notificações de normativos',
									'module_id'		=> '3',
									'icon' => '',
									'type_id' => 'MN_2',
									'page_key' => 'PNOR0002',
									'level'		=> '3',
									'parent_id' => '12',
									'path' => '3_12_19',
									'class' => 'dmpl-module-ind'
									
							)
							
					)
			);
			$this->_loadPagesLinks($data['menus']);
			$data['menu_tree'] = $this->_organizeMenuTree($data['menus']);
			unset($data['menus']);
			$this->getResponse()->setContent($data);
			
			return true;
		}else{
			return $this->respondMethodNotAllowed();
		}
	}
	
	public function data(){
		if(in_array($this->requestMethod(), array('GET'))){
			$data = $this->requestData();
			
			if(isset($data)){
				if(isset($data['SessionKey'])){
					$session = new DMPLEntity_Gen_Session(array(
							'filters' => array(
									'Hash' => $data['SessionKey']
							)
					));
					$session->load();
					$sessionData = $session->serialize();
					$userId = $sessionData['UserId'];
				}elseif(isset($data['UserId'])){
					$userId = $data['UserId'];
				}else{
					$this->getResponse()->setContent(DMPLErrors::get('BAD_PARAMETERS'));
					return false;
				}
				
				$entity = new DMPLEntity_Cad_User ( array (
						'filters' => array (
								'Id' => $userId
						) 
				) );
				$entity->load();
				$user = $entity->serialize();
				
				if (isset($user) && is_array($user)) {
					//Removendo o atributo de senha para evitar roubo de informações.
					if(isset($user['Password'])){
						unset($user['Password']);
					}
					
					$this->getResponse()->setContent( $user );
					return true;
				} else {
					$this->getResponse()->setContent(DMPLErrors::get('USER_EMPTY'));
					return false;
				}
			}else{
				$this->getResponse()->setContent(DMPLErrors::get('BAD_PARAMETERS'));
				return false;
			}
		}else{
			return $this->respondMethodNotAllowed();
		}
	}
	
}
