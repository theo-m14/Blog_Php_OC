<?php

namespace App\Core;

	class Bdd
	{
		private $bdd;
		private static $instance;
		
		public static function getInstance()
		{
			if(empty(self::$instance))
			{
				self::$instance = new Bdd();
			}
			return self::$instance->bdd;
		}
		private function __construct()
		{
			$this->bdd = new \PDO('mysql:dbname=' . $_ENV['DB_NAME'] . ';host=' . $_ENV['DB_HOST'],
								  $_ENV['DB_USER'],
								  $_ENV['DB_PASSWORD']);
		}
		
		public function getBdd()
		{
			return $this->bdd;
		}
	}