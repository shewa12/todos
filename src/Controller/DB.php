<?php 
namespace App\Controller;
	class DB 
	{
		const HOST = "localhost";
		const USER = "root";
		const PASS = "";
		const DB   = "wedevs";

		function connect()
		{
			$con = mysqli_connect(Self::HOST,Self::USER,Self::PASS,Self::DB);
			if(!$con)
			{
				echo "DB connection error";
				exit();
			}
			return $con;
		} 
	}
?>