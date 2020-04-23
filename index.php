<?php 
	require_once realpath('vendor/autoload.php');
/*
	use App\Controller\TaskCtrl;
	$task = new TaskCtrl;
	$task = $task->getlast();
	while($row = mysqli_fetch_assoc($task))
		{
			$item = ['id'=> $row['id'],'task'=> $row['task'],'status'=> $row['status'],'created_at'=> $row['created_at']];
		}
	print_r($item);
*/
?>
<!DOCTYPE html>
<html>
<head>
	<title>To do List</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.1/css/all.css" integrity="sha384-O8whS3fhG2OnA5Kas0Y9l3cfpmYjapjI0E4theH4iuMD+pLhbf6JI0jIMfYcK3yZ" crossorigin="anonymous">
	<style type="text/css">
		.wrapper {
			margin-top: 50px;
		}
		.item 
		{
			padding: 10px;
			border-bottom: 1px solid #e3e3e3;
		}
		.item:last-child{
			border:0px;
		}
		i {
			cursor: pointer;
		}
		.done, .remove{
			visibility: hidden;
			position: relative;
		}
		.item:hover >.done
		{
			visibility: visible;
		}		
		.item:hover >.remove
		{
			visibility: visible;
		}
		.marked{
			color:green;
		}
		.active {
			border: 1px solid #e3e3e3;
			padding:5px;
		}
	</style>
</head>
<body>

<div class="container">
	<div class="wrapper col-lg-10 offset-lg-1">
		<h3 align="center">
			<strong>
				Todos
			</strong>
		</h3>
		<div class="card">

			<div class="input">
				<input class="form-control" name="task" placeholder="What needs to be done...">
			</div>

			<div class="card-body">
		
			</div>

			<div class="card-footer">
				<div class="row">
					<div class="col-sm-4 col-lg-3">
						<a href="#" id="leftitem"></a>
					</div>
					<div class="col-sm-4 col-lg-2">
						<a href="#" id="all">All</a>
					</div>
					<div class="col-sm-4 col-lg-2">
						<a href="#" id="active">Active</a>
					</div>					
					<div class="col-sm-4 col-lg-2">
						<a href="#" id="completed">Completed</a>
					</div>
					<div class="col-sm-4 col-lg-3">
						<a href="#" id="clear">Clear Completed</a>
					</div>					
				</div>

			</div>
		</div>
	</div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<script type="text/javascript">
	$(document).ready(function(){
		const getUrl = "src/Controller/Items.php";
		const handleUrl = "src/Controller/Handle.php";
		let leftItem = 0;
		let items;
		let left = 0;
		getData();

		function getData()
		{
			let response = fetch(getUrl);
			response.then(res => res.json()).then(res=>{
				
				items = res;
				
				showLeft();
			}).catch((err) =>{console.log(`Error: ${err}`)});
		}

		function showLeft()
		{
			leftItem=0;
		
			$('.card-body').empty();
			for(let i of items)
			{
				$("body").removeClass('active');
				$("#active").addClass('active');
				if(i.status==0)
				{
					leftItem++;
					$(".card-body").append(`
						<div class="item row">
							<div class="col-1 done" id="${i.id}">
								<i class="fas fa-check"></i>
							</div>
							<div class="col-10 task">
								${i.task}
							</div>
							<div class="col-1 remove" id="${i.id}">
								<i class="far fa-trash-alt"></i>
							</div>						
						</div>
					`);
				}

			}
			$("#leftitem").html(`${leftItem} Items Left`)
			
		}
		/*add record*/
		$('[name="task"]').keypress( function(event){
		    var keycode = (event.keyCode ? event.keyCode : event.which);
		    if(keycode == '13'){
		        let task = $(this).val();
		        $('[name="task"]').val('');
		        if(task !=='')
		        {
	
					$.ajax({
						url:handleUrl,
						type:'post',
						data:{task:task},
						dataType:'json',
						success:(data)=>{

				        	let item = data;
				        
				        	$("#leftitem").html(`${++leftItem} Items Left`);
				        	items.unshift(item);
				        	$(".card-body").prepend(
				        	`
							<div class="item row">
								<div class="col-1 done" id="${item.id}">
									<i class="fas fa-check"></i>
								</div>
								<div class="col-10 task">
									${item.task}
								</div>
								<div class="col-1 remove" id="${item.id}">
									<i class="far fa-trash-alt"></i>
								</div>						
							</div>
				        	`
				        	)
						},
						error:()=>{
							console.log('error: add task');
						}
					});

		        } 
		    }
		});
		/*add record end*/
		/*remove record from list*/
	
		$(document).on( "click", ".remove", function(){
			$(this).parent(".item").remove();
		});
		/*remove record from list*/

		//clear complted items
		$(document).on('click','#clear',function(){


				$.ajax({
					url: handleUrl,
					type:'post',
					data:{clear:'clear'},
					dataType:'html',
					success:(data)=>{
						
						let left = [];
						for(let i of items)
						{
							if(i.status==0)
							{
								left.push({id:i.id,task:i.task,status:i.status});
							}
						}
						items = left;
						$("#leftitem").html(`${items.length} Items Left`);
						showLeft();
					},
					error:()=>{
						console.log("Error: clear complted");
					}
				})

    	});
		//clear complted items end

		$("#all").on('click',function()
		{
			$("body").find('.active').removeClass('active');
			$("#all").addClass('active');
			$(".card-body").empty();
			for(let i of items)
			{

				if(i.status==1)
				{
					$(".card-body").append(`
						<div class="item row">
							<div class="col-1 done" id="${i.id}">
								<i class="fas fa-check"></i>
							</div>
							<div class="col-10 task">
								<del>${i.task}</del>
							</div>
							<div class="col-1 remove" id="${i.id}">
								<i class="far fa-trash-alt"></i>
							</div>						
						</div>
					`);
				}
				else
				{

					$(".card-body").append(`
						<div class="item row">
							<div class="col-1 done" id="${i.id}">
								<i class="fas fa-check"></i>
							</div>
							<div class="col-10 task">
								${i.task}
							</div>
							<div class="col-1 remove" id="${i.id}">
								<i class="far fa-trash-alt"></i>
							</div>						
						</div>
					`);					
				}
					
			
			}			
		});

		$("#active").on('click',function()
		{
			$("body").find('.active').removeClass('active');
			$("#active").addClass('active');
			$(".card-body").empty();
			for(let i of items)
			{
				if(i.status==0)
				{
					$(".card-body").append(`
					<div class="item row">
						<div class="col-1 done" id="${i.id}">
							<i class="fas fa-check"></i>
						</div>
						<div class="col-10 task">
							<span>${i.task}</span>
						</div>
						<div class="col-1 remove" id="${i.id}">
							<i class="far fa-trash-alt"></i>
						</div>						
					</div>
					`);					
				}

			}			
		});

		$(document).on('click','#completed',function()
		{
			$("body").find('.active').removeClass('active');
			$("#completed").addClass('active');

			$(".card-body").empty();
			for(let i of items)
			{
				if(i.status==1)
				{
					$(".card-body").append(`
					<div class="item row">
						<div class="col-1 done" id="${i.id}">
							<i class="fas fa-check"></i>
						</div>
						<div class="col-10 task">
							<del>${i.task}</del>
						</div>
						<div class="col-1 remove" id="${i.id}">
							<i class="far fa-trash-alt"></i>
						</div>						
					</div>
					`);					
				}

			}			
		});		
		//mark completed
		$(document).on('click','.done',function(){
			$(this).addClass('marked');
			$(this).removeClass('done');
			let id = $(this).attr('id');
		
			let text = $(this).siblings(".task").html();
				

			$.ajax({
				url:handleUrl,
				type:'post',
				data:{id:id},
				dataType:'json',
				success:(data)=>{
				
					$("#leftitem").html(`${--leftItem} Items Left`);
					for(let j of items)
					{
						if(j.id ==id)
						{
							j.status=1;
						}
					}
					$(this).siblings(".task").html(`<span><del>${text}</del></span>`);
				},
				error:()=>{
					console.log("Error: mark completed");
				}				
			});
		});

		//clear completed
		$(document).on('click',"#leftitem",function(){
			$("body").find('.active').removeClass('active');
			$("#active").addClass('active');
			$(".card-body").empty();
			for(let i of items)
			{
				if(i.status==0)
				{
					$(".card-body").append(`
					<div class="item row">
						<div class="col-1 done" id="${i.id}">
							<i class="fas fa-check"></i>
						</div>
						<div class="col-10 task">
							<span>${i.task}</span>
						</div>
						<div class="col-1 remove" id="${i.id}">
							<i class="far fa-trash-alt"></i>
						</div>						
					</div>
					`);					
				}

			}			
		});
	});
</script>
</body>
</html>