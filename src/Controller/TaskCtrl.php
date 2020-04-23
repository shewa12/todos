<?php 
namespace App\Controller;

class TaskCtrl extends DB
{
	function getTasks()
	{
		$q = "SELECT *FROM tasks ORDER BY id DESC";
		$run = mysqli_query($this->connect(),$q);

		if(mysqli_num_rows($run)>0)
		{
			return $run;
		}
		return false;
	}

	function addTask($task)
	{
		$q= "INSERT INTO tasks (task) VALUES ('$task')";
		$run = mysqli_query($this->connect(),$q);
		if($run)
		{
			return true;
		}
	}	

	function markComplete($id)
	{
		$q= "UPDATE tasks SET status = 1 WHERE id = $id ";
		$run = mysqli_query($this->connect(),$q);
		if($run)
		{
			return true;
		}
	}

	function clearComplteTask()
	{
		$q= "DELETE FROM tasks WHERE status = 1  ";
		$run = mysqli_query($this->connect(),$q);
		if($run)
		{
			return true;
		}		
	}

	function getlast()
	{
		$q = "SELECT *FROM tasks ORDER BY created_at DESC LIMIT 1";
		$run = mysqli_query($this->connect(),$q);

		if(mysqli_num_rows($run)>0)
		{
			return $run;
		}
		return false;
	}
}
?>
