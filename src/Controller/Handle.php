<?php 
require_once realpath('../../vendor/autoload.php');

use App\Controller\TaskCtrl;

$task = new TaskCtrl;
if(isset($_POST['task']))
{
	$add = $task->addTask($_POST['task']);
	if($add)
	{
		
		$last = $task->getlast();
		while($row = mysqli_fetch_assoc($last))
			{
				$item = ['id'=> $row['id'],'task'=> $row['task'],'status'=> $row['status'],'created_at'=> $row['created_at']];
			}
		echo json_encode($item);
	}
	else
	{	
		$res = ['status'=> 'fail'];
		echo json_encode($res);
	}
}

if(isset($_POST['id']))
{
	$complete = $task->markComplete($_POST['id']);
	if($complete)
	{
		echo json_encode(['status'=>'ok']);
	}	
	else
	{
		echo json_encode(['status'=>'fail']);
	}
}

if(isset($_POST['clear']))
{
	$complete = $task->clearComplteTask();
	if($complete)
	{
		//echo json_encode(['status'=>'ok']);
		echo "ok";
	}	
	else
	{
		//echo json_encode(['status'=>'fail']);
		echo "fail";
	}
}
?>