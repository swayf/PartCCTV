<?php
$mysql = new mysqli('localhost', 'root', 'cctv', 'cctv');
$cam_list = $mysql->query("SELECT * FROM `cam_list`"); 

//ZeroMQ
$context = new ZMQContext();
$requester = new ZMQSocket($context, ZMQ::SOCKET_REQ);
$requester->connect("tcp://127.0.0.1:5555");
$requester->send("status");
$status = $requester->recv();

if (isset($_GET['action'])) {
	switch($_GET['action']) {
		case 'restart':
			$requester->send("kill");
			$reply = $requester->recv();
			if ($reply == "OK") {
				echo "<script>if(!alert('Платформа перезапущена!')){window.location='/list.php';}</script>";
				exit;
			}
			break;
		default:
	}
}
?>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

<script src="https://yandex.st/jquery/1.12.0/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

</head>
<body>

<div class="navbar navbar-default navbar-static-top">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-ex-collapse">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="/list.php"><span>PartCCTV</span></a>
		</div>

		<div class="collapse navbar-collapse" id="navbar-ex-collapse">
			<ul class="nav navbar-nav navbar-right"><li class="active"><a href="/list.php">Камеры</a></li><li><a href="/archive.php">Архив</a></li></ul>
		</div>
	</div>
</div>

<div class="section">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="progress progress-striped">
					<div class="progress-bar progress-bar-success" role="progressbar" style="width: 60%;">На /sda1 занято 60Гб из 240Гб</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<a class="btn btn-lg btn-success">Добавить новую камеру</a>
				<a class="btn btn-lg btn-danger" href="/list.php?action=restart">Перезагрузка платформы (выполнять при изменении параметров)</a>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<hr>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row">
		
			<?php
			while ($cam = $cam_list->fetch_assoc()) {
				echo'			<div class="col-md-12">
				<div class="panel panel-warning">
					<div class="panel-heading">
						<h6 class="panel-title text-warning"><b>'.$cam["title"].'</b></h6>
					</div>
					<div class="panel-body">
						<div class="alert alert-dismissable alert-success">
							<b>Ошибок не обнаружено...</b>
						</div>
						<div class="btn-group btn-group-lg">
							<a href="#" class="btn btn-warning">Настройки</a>
							<a href="#" class="btn btn-danger">Удалить</a>
						</div>
					</div>
				</div>
			</div>';
			}
			?>
		</div>
	</div>
</div>
</body>
</html>