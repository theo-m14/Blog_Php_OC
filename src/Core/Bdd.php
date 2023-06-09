<?php

namespace App\Core;

use PDO;

	class Bdd
	{
		private PDO $bdd;
		private static Bdd $instance;

		public static function getInstance() : PDO
		{
			if (empty(self::$instance)) {
				self::$instance = new Bdd();
			}
			return self::$instance->bdd;
		}
		private function __construct()
		{
			$this->bdd = new \PDO(
	  			'mysql:dbname=' . $_ENV['DB_NAME'] . ';host=' . $_ENV['DB_HOST'],
				$_ENV['DB_USER'],
				$_ENV['DB_PASSWORD']
			);
		}

		public function getBdd() : PDO
		{
			return $this->bdd;
		}
	}
