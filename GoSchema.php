<?php
/********************************************************************************
** @Company     : Damaplan                                                     **
** @System      : Norman - Gestor de Normativos		                           **
** @Module		: GoSchema - Script que instancia o manipulador de schemas     **
**				  do Norman (para estruturar a informação dos comunicados).	   **
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
** @Date	 	: 28/09/2019                                           	       **
** @Version	 	: 1.0                                                 	       **
** @Comment	 	: Primeira versão.                                             **
********************************************************************************/

require 'Config/bootstrap.php';

Use Damaplan\Norman\DMPLNormanSchematic;
Use Damaplan\Norman\Core\DB\DMPLEntityList;

/**
 * Instancia EntityList e pega a lista dos normativos pendentes de preenchimento do conteúdo...
 * */
$entityList = new DMPLEntityList('DMPLEntity_Nor_Legislation');
$entityList->setFilters(array(
		'ContentLoaded' => true,
		'SchemaLoaded' => false
));
$entityList->load();


/**
 * Instancia novo Schematic para estruturar o conteúdo dos normativos...
 * */
$cSchema = new DMPLNormanSchematic($entityList);

/**
 * Roda o esquematizador instanciado e recebe o retorno.
 **/
$result = $cSchema->run();

/**
 * Imprime o resultado da busca...
  * */
var_dump($result);