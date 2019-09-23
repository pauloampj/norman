<?php
/********************************************************************************
** @Company     : Damaplan                                                     **
** @System      : Norman - Gestor de Normativos		                           **
** @Module		: GoCrawl - Script que instancia o Norman e executa o run().   **
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

require 'Config/bootstrap.php';

Use Damaplan\Norman\DMPLNormanCrawler;

/**
 * Instancia novo Crawler para buscar informações do gateway (fonte de dados) informado.
 * Neste caso, está instanciando um crawler para buscar informações do Banco Central.
 * */
$norman = new DMPLNormanCrawler(['BCB_001']);

/**
 * Roda o crawler instanciado e recebe o retorno.
 * */
$result = $norman->run();

/**
 * Imprime o resultado da busca...
  * */
var_dump($result);