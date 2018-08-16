<?php
/********************************************************************************
** @Company     : Damaplan                                                     **
** @System      : Norman - Gestor de Normativos		                           **
** @Module		: Gateways - Carrega os parâmetros dos gateways de extração.   **
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
** @Date	 	: 28/06/2018                                           	       **
** @Version	 	: 1.0                                                 	       **
** @Comment	 	: Primeira versão.                                             **
********************************************************************************/

Use Damaplan\Norman\Core\Utils\DMPLParams;

DMPLParams::write ('DMPL_GATEWAYS', array (
		'BCB_001' => array (
				'Subject' 		=> 'Gateway de busca dos normativos do Banco Central',
				'Extractor'		=> array (
						'Driver'		=> 'Curl',
						'Paginator'		=> 'BCB',
						'UseCache'		=> true,
						'AutoPaginate'	=> true,
						'Params'		=> array(
								'URL'		=> 'http://www.bcb.gov.br/pre/normativos/busca/buscaSharePoint.asp',
								'Data'		=> array(
										'dataInicioBusca'	=> '#__TODAY_DDMMYYYY_SLASH',
										'dataFimBusca'		=> '#__TODAY_DDMMYYYY_SLASH'
								)
						)
				),
				'Transformer'	=> array (
						'Driver'		=> 'BCB_001',
						'SourceFormat'	=> 'Json',
						'Params' 		=> array()
						
				),
				'Loader' 		=> array (
						'Driver' 		=> 'DbSql',
						'Params' 		=> array(
								'DBMS' 			=> 'mariadb',
								'Host' 			=> '35.239.232.72',
								'Port' 			=> '3306',
								'Database'		=> 'norman',
								'User'			=> 'norman',
								'PasswordHash'	=> 'Tm9ybWFOaDV3MTlNeCwuIUA=',
						)
				) 
		) 
) );