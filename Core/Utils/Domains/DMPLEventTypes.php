<?php

Namespace Damaplan\Norman\Core\Utils\Domains;

class DMPLEventTypes extends DMPLTypes {
	
	public static $SESSION = 1; //eventos de cessão, como: login, logout, etc
	public static $CREATE = 2; //[entidade] eventos de criação de itens na base de dados
	public static $READ = 3; //[entidade] eventos de leitura de informações
	public static $UPDATE = 4; //[entidade] eventos de atualização de informações
	public static $DELETE = 5; //[entidade] eventos de exclusão de informações
	public static $ACCESS = 6; //eventos de acesso a telas ou menus
	
}