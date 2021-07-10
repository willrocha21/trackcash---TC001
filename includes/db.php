<?php
class Conexao extends \PDO{
	private static $instance 	= null;
	private static $dbType 		= 'mysql';
	private static $host 		= '162.241.61.95';
	private static $user 		= 'motun093_processo';
	private static $senha 		= 'FHN#1ky4#Wz^';
	private static $db 			= 'motun093_processo';
	private static $charSet 	= "SET NAMES utf8";
	protected static $persistent = true;

	public static function getInstance(){

		if(self::$persistent != false)
			self::$persistent = true;
		if(!isset(self::$instance)){
			try{
				self::$instance = new \PDO(self::$dbType . ':host=' . self::$host . ';dbname=' . self::$db.';charset=utf8', self::$user, self::$senha, array(\PDO::ATTR_PERSISTENT => self::$persistent, \PDO::MYSQL_ATTR_INIT_COMMAND => self::$charSet));
			}catch(\PDOException $ex){
				exit("Erro ao conectar com o banco de dados: " . $ex->getMessage());
			}
		}
		return self::$instance;
	}

	public static function close(){
		if (self::$instance != null)
			self::$instance = null;
	}
}
?>