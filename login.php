<html>
	<head>
		<link rel="stylesheet" type="text/css" href="mystyle.css">
		<title>Login Page</title>
	</head>
	<body style="background-image:url('images/bg1.jpg'); background-size: cover;">
	<?php
		setcookie('admin','');
		setcookie('code','');
		if(isset($_POST['login']))
		{
			$code=$_POST['code'];
			$doc=new DOMDocument();
			$doc->load('xml/codes.xml');
			$l=0;
			$elements=$doc->getElementsByTagName('element');
			foreach($elements as $e)
			{
				
				if($e->getElementsByTagName('code')->item(0)->nodeValue==$code)
				{
					if($e->getElementsByTagName('admin')->item(0)->nodeValue=='true')
						setcookie('admin','true');
					setcookie('code',$code);
					setcookie('secure','dhdhdSFDH4e64X');
					$l++;
					break;
				}
			}
			if($l!=0)
			header('location:home.php');
			else
			echo"<center><p style=\"color:#ffffff;font-size:20px\">invalid code</p></center>";
		}
		
	?>
	<form action=login.php method=post>
	<center>
		
		<table style="width:20%;margin-top:200px;height:160px"> 
			<tr><td align=center><p style="color:#ffffff;font-size:28px">Code</p></td></tr>
			<tr><td align=center><input type=password autofocus required class=login name=code></td></tr>
			<tr><td align=center><input style="width:70px;" class=login type=submit name=login value=login /></td></tr>
		</table>
	
	</center>
	</form>
	
	</body>
</html>