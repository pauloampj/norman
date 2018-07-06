<?php
/********************************************************************************
** @Company     : Damaplan                                                     **
** @System      : Norman - Gestor de Normativos		                           **
** @Module		: Bootstrap - Inicializa alguns elementos da aplicação.		   **
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

require 'paths.php';
require UTILS_PATH . 'DMPLParams.php';
require CONFIG_PATH . 'autoload.php';
require CONFIG_PATH . 'gateways.php';

Use Damaplan\Norman\Core\DMPLParams;

DMPLParams::write ('EXTRACTOR_DRIVER_PREFIX', 'DMPLEDriver');
DMPLParams::write ('TRANSFORMER_DRIVER_PREFIX', 'DMPLTDriver');
DMPLParams::write ('LOADER_DRIVER_PREFIX', 'DMPLLDriver');
DMPLParams::write ('DRIVER_NAMESPACE', 'Damaplan\Norman\Core\ETL\Drivers');