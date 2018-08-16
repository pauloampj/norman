<?php
/********************************************************************************
** @Company     : Damaplan                                                     **
** @System      : Norman - Gestor de Normativos		                           **
** @Module		: Errors - Lista de erros da aplicação.						   **
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
** @Date	 	: 28/07/2018                                           	       **
** @Version	 	: 1.0                                                 	       **
** @Comment	 	: Primeira versão.                                             **
********************************************************************************/

Use Damaplan\Norman\Core\Utils\DMPLParams;

DMPLParams::write ('ERRORS_LIST', array(
		'OK'					=> array(
				'code'			=> 'EGEN0001',
				'name'			=> 'Ok',
				'description'	=> 'Operação concluída com sucesso.'
		),
		'BAD_PARAMETERS'		=> array(
				'code'			=> 'EGEN0002',
				'name'			=> 'Parâmetros inválidos',
				'description'	=> 'Os parâmetros não foram informados ou são inválidos.'
		),
		'SESSION_NOT_STARTED'	=> array(
				'code'			=> 'EGEN0003',
				'name'			=> 'Sessão não inicializada',
				'description'	=> 'Ocorreu um erro ao inicializar a sessão do usuário.'
		),
		'SESSION_SUCCESS'		=> array(
				'code'			=> 'EGEN0004',
				'name'			=> 'Sessão inicializada',
				'description'	=> 'A sessão foi inicializada com sucesso.',
				'redirect_page'	=> DMPLParams::read('HOME_URL')
		),
		'SESSION_CLOSE'			=> array(
				'code'			=> 'EGEN0005',
				'name'			=> 'Sessão encerrada',
				'description'	=> 'A sessão foi encerrada com sucesso.'
		),
		'SESSION_NOT_CLOSED'	=> array(
				'code'			=> 'EGEN0006',
				'name'			=> 'Sessão não encerrada',
				'description'	=> 'Ocorreu um erro ao encerrar a sessão do usuário.'
		),
		'AUTH_WRONG_PASSWORD'	=> array(
				'code'			=> 'ECAD0001',
				'name'			=> 'Senha inválida',
				'description'	=> 'A senha não confere com o usuário informado.'
		),
		'AUTH_SUCCESS'			=> array(
				'code'			=> 'ECAD0002',
				'name'			=> 'Usuário autenticado com sucesso.',
				'description'	=> 'O usuário foi autenticado com sucesso.'
		),
		'USER_EMPTY'			=> array(
				'code'			=> 'ECAD0003',
				'name'			=> 'Erro ao recuperar dados do usuário.',
				'description'	=> 'Ocorreu um erro ao recuperar os dados do usuário informado.'
		)
));