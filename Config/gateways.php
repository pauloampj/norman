<?php
/********************************************************************************
** @Company     : Damaplan                                                     **
** @System      : Norman - Gestor de Normativos		                           **
** @Module		: Gateways - Carrega os parâmetros dos gateways de extração.   **
**				  Os gateways são fontes de dados para busca do normativos,	   **
**				  como, por exemplo, o Banco Central, a Receita Federal, etc.  **
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

$db = DMPLParams::read ('ENTITY.DEFAULT_DB_PARAMS');

DMPLParams::write ('DMPL_GATEWAYS', array (
		'BCB_001' => array (
				'Subject' 		=> 'Gateway de busca dos normativos do Banco Central',
				'Type'			=> 'CRAWLER',
				'Extractor'		=> array (
						'Driver'		=> 'Curl',
						'Paginator'		=> 'BCB',
						'UseCache'		=> true,
						'AutoPaginate'	=> true,
						'Params'		=> array(
								'URL'		=> 'https://www.bcb.gov.br/api/search/app/normativos/buscanormativos',
								'Data'		=> array(
										'querytext'			=> 'ContentType:normativo%20AND%20contentSource:normativos',
										'rowlimit'			=> '100',
										'startrow'			=> '0',
										'sortlist'			=> 'Data1OWSDATE:descending',
										'refinementfilters'	=> 'Data:range(datetime(2019-08-29),datetime(2019-08-29T23:59:59))'/*'#__TODAY_DATERANGE_FILTER'*/
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
								'DBMS' 			=> $db['DBMS'],
								'Host' 			=> $db['Host'],
								'Port' 			=> $db['Port'],
								'Database'		=> $db['Database'],
								'User'			=> $db['User'],
								'PasswordHash'	=> $db['PasswordHash']
						)
				) 
		),
		'BCB_002' => array (
				'Subject' 		=> 'Gateway de busca dos conteúdos dos normativos do Banco Central (Resolução, Circular e Carta-Circular)',
				'Type'			=> 'CONTENT_SEARCHER',
				'Extractor'		=> array (
						'Driver'		=> 'Curl',
						'Paginator'		=> 'BCB',
						'UseCache'		=> true,
						'AutoPaginate'	=> true,
						'Params'		=> array(
								'URL'		=> 'https://www.bcb.gov.br/api/conteudo/app/normativos/exibenormativo',
								'Data'		=> array(
										'p1'				=> '@__NORMATIVE_TYPE',
										'p2'				=> '@__NORMATIVE_ID'
								)
						)
				),
				'Transformer'	=> array (
						'Driver'		=> 'BCB_002',
						'SourceFormat'	=> 'Json',
						'Params' 		=> array()
						
				),
				'Loader' 		=> array (
						'Driver' 		=> 'DbSql',
						'Params' 		=> array(
								'DBMS' 			=> $db['DBMS'],
								'Host' 			=> $db['Host'],
								'Port' 			=> $db['Port'],
								'Database'		=> $db['Database'],
								'User'			=> $db['User'],
								'PasswordHash'	=> $db['PasswordHash']
						)
				)
		),
		'BCB_003' => array (
				'Subject' 		=> 'Gateway de busca dos conteúdos dos normativos do Banco Central (Comunicado)',
				'Type'			=> 'CONTENT_SEARCHER',
				'Extractor'		=> array (
						'Driver'		=> 'Curl',
						'Paginator'		=> 'BCB',
						'UseCache'		=> true,
						'AutoPaginate'	=> true,
						'Params'		=> array(
								'URL'		=> 'https://www.bcb.gov.br/api/conteudo/app/normativos/exibeoutrasnormas',
								'Data'		=> array(
										'p1'				=> '@__NORMATIVE_TYPE',
										'p2'				=> '@__NORMATIVE_ID'
								)
						)
				),
				'Transformer'	=> array (
						'Driver'		=> 'BCB_002',
						'SourceFormat'	=> 'Json',
						'Params' 		=> array()
						
				),
				'Loader' 		=> array (
						'Driver' 		=> 'DbSql',
						'Params' 		=> array(
								'DBMS' 			=> $db['DBMS'],
								'Host' 			=> $db['Host'],
								'Port' 			=> $db['Port'],
								'Database'		=> $db['Database'],
								'User'			=> $db['User'],
								'PasswordHash'	=> $db['PasswordHash']
						)
				)
		) 
) );