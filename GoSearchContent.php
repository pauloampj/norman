<?php
/********************************************************************************
** @Company     : Damaplan                                                     **
** @System      : Norman - Gestor de Normativos		                           **
** @Module		: GoSearchContent - Script que instancia o content searcher    **
**				  do Norman (para encontrar o conteúdo dos normativos).		   **
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
** @Date	 	: 24/09/2019                                           	       **
** @Version	 	: 1.0                                                 	       **
** @Comment	 	: Primeira versão.                                             **
********************************************************************************/

require 'Config/bootstrap.php';

Use Damaplan\Norman\DMPLNormanContentSearcher;
Use Damaplan\Norman\Core\DB\DMPLEntityList;

/**
 * Instancia EntityList e pega a lista dos normativos pendentes de preenchimento do conteúdo...
 * */
$entityList = new DMPLEntityList('DMPLEntity_Nor_Legislation');
$entityList->setFilters(array(
		'ContentLoaded' => false
));
$entityList->load();

/**
 * Instancia novo Content Searcher para buscar o conteúdo dos normativos...
 * */
$cSearcher = new DMPLNormanContentSearcher($entityList);

/**
 * Roda o crawler instanciado e recebe o retorno.
 **/
$result = $cSearcher->run();

/**
 * Imprime o resultado da busca...
  * */
var_dump($result);