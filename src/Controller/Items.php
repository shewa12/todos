<?php 
//require_once '../../vendor/autoload.php';
require_once realpath('../../vendor/autoload.php');

use App\Controller\TaskCtrl;

	$task = new TaskCtrl;
	$task = $task->getTasks();
	$items=[];
	if($task !==false)
	{
		
		while($row = mysqli_fetch_assoc($task))
		{
			$items[] = ['id'=> $row['id'],'task'=> $row['task'],'status'=> $row['status'],'created_at'=> $row['created_at']];
		}

	}

	echo json_encode($items);
?>