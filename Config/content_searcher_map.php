<?php
/********************************************************************************
** @Company     : Damaplan                                                     **
** @System      : Norman - Gestor de Normativos		                           **
** @Module		: ContentSearcherMap - Carrega os parâmetros de mapeamento	   **
**				  dos content searchers por tipo de normativo.				   **
** @Namespace	: Damaplan\Norman											   **
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
** @Date	 	: 23/09/2019                                           	       **
** @Version	 	: 1.0                                                 	       **
** @Comment	 	: Primeira versão.                                             **
********************************************************************************/

Use Damaplan\Norman\Core\Utils\DMPLParams;
Use Damaplan\Norman\Core\Utils\Domains\DMPLLegislationTypes;

DMPLParams::write ('DMPL_CONTENT_SEARCHER_MAP', array (
		DMPLLegislationTypes::$BC_BR_RESOLUTION			=> 'BCB_002',
		DMPLLegislationTypes::$BC_BR_CIRCULAR			=> 'BCB_002',
		DMPLLegislationTypes::$BC_BR_CIRCULAR_LETTER	=> 'BCB_002',
		DMPLLegislationTypes::$BC_BR_BULLETIN			=> 'BCB_003',
		'*'												=> 'BCB_003'
		) 
);