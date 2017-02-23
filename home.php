<html>
	<head>
		<link rel="stylesheet" type="text/css" href="mystyle.css">
		<title>Home Page</title>
	</head>
	<body>
	<header style="width:100%;height:300px;background-image:url('images/uni.png'); background-size: 100% 300px;">
	<center><p style="background-color:#cccccc;width:600px;font-size:50px;color:black;opacity:0.8;border-radius:40%;">Lebanese university</p></center></header>
	<?php
	if(!isset($_COOKIE['secure']) || !$_COOKIE['secure']=='dhdhdSFDH4e64X')
	{
		header('location:login.php');
	}
	?>
	<center>
		<table style="margin-top:20px;height:100px;width:70%"> 
			<tr style="height:50px">
				<td colspan=5 align=center><a href="new_presence.php" class=choose style="font-size:38px">New Presence</a></td>
			</tr>
			<tr>
				<td align=right style="height:60px"><a href="oldpresence.php" class=choose>Old Presence</a></td>
				<td align=center style="height:60px"><a href="stat.php" class=choose>Statistic about students</a></td>
			</tr>
		</table>
		<table style="margin-top:10px;height:100px;width:70%">
			<?php
			if(isset($_COOKIE['admin']))
				echo"<tr>
				<td align=left style=\"height:50px\"><a href=\"add_remove_student.php\" class=choose>Add Or Remove student</a></td>
				<td align=center style=\"height:50px\"><a href=\"add_student_to_cour.php\" class=choose>Add Student To a Cour</a></td>
				<td align=right style=\"height:50px\"><a href=\"add_remove_cour.php\" class=choose>Add Or Remove Cour</a></td>
				<td align=right style=\"height:50px\"><a href=\"add_remove_account.php\" class=choose>Add Or Remove Account</a></td>
			</tr>";
			?>
		</table>
	</center>
	</body>
</html>