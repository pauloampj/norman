<?php
/********************************************************************************
** @Company     : Damaplan                                                     **
** @System      : Norman - Gestor de Normativos		                           **
** @Module		: Paths - Define os diretórios da aplicação.				   **
** @Namespace	: Damaplan													   **
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
** @Date	 	: 28/06/2018                                           	       **
** @Version	 	: 1.0                                                 	       **
** @Comment	 	: Primeira versão.                                             **
********************************************************************************/

if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

define('ROOT', dirname(__DIR__));

define('CORE_PATH', ROOT . DS . 'Core' . DS);
define('CONFIG_PATH', ROOT . DS . 'Config' . DS);
define('CACHE_PATH', ROOT . DS . 'Cache' . DS);
define('DB_PATH', CORE_PATH . DS . 'DB' . DS);
define('ETL_PATH', CORE_PATH . DS . 'ETL' . DS);
define('PARSE_PATH', CORE_PATH . DS . 'Parse' . DS);
define('UTILS_PATH', CORE_PATH . DS . 'Utils' . DS);
define('CORE_INCLUDE_PATH', ROOT . DS . 'Core');