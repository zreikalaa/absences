<html>
	<head>
	<link rel="stylesheet" type="text/css" href="mystyle.css">
	</head>
	<body style="background-image:url('images/bg2.jpg'); background-size: cover;">
	<?php
	if(!isset($_COOKIE['secure']) || !$_COOKIE['secure']=='dhdhdSFDH4e64X')
	{
		header('location:login.php');
	}?>
	<div style="float:right"><a href="home.php" class="home">Go to Home</a></div>
		<?php
			if(isset($_POST['add']))
			{
				$name=$_POST['dc_name'];
				$code=$_POST['code'];
				$admin=$_POST['admin'];
				$dom=new DOMDocument();
				$dom->load('xml/codes.xml');
				$element=$dom->getElementsByTagName('element');
				$found=0;
				foreach($element as $e)
				{
					if($e->getElementsByTagName('code')->item(0)->nodeValue==$code)
						$found=1;
				}
				if($found==0)
				{
					$e=$dom->createElement('element');
					$c=$dom->createElement('code',$code);
					$a=$dom->createElement('admin',$admin);
					$dc=$dom->createElement('dc',$name);
					$e->appendChild($dc);
					$e->appendChild($c);
					$e->appendChild($a);
					$root=$dom->getElementsByTagName('codes')->item(0);
					$root->appendChild($e);
					$dom->save('xml/codes.xml');
				}
				else
				{
					echo"<center><h1>can't use this code</h1></center>";
				}
			}
			elseif(isset($_POST['delete']))
			{
				$code=$_POST['code'];
				$dom=new DOMDocument();
				$dom->load('xml/codes.xml');
				$element=$dom->getElementsByTagName('element');
				$found=0;
				foreach($element as $e)
				{
					if($e->getElementsByTagName('code')->item(0)->nodeValue==$code && $e->getElementsByTagName('admin')->item(0)->nodeValue=='false')
					{
						$root=$dom->getElementsByTagName('codes')->item(0);
						$root->removeChild($e);
						$found=1;
						$dom->save('xml/codes.xml');
						break;
					}
				}
				if($found==0)echo"<center><h1>can't delete this code</h1></center>";
			}
			elseif(isset($_POST['addcour']))
			{
				$code=$_POST['code'];
				$courid=$_POST['courid'];
				$dom=new DOMDocument();
				$dom->load('xml/codes.xml');
				$element=$dom->getElementsByTagName('element');
				$found=0;
				foreach($element as $e)
				{
					if($e->getElementsByTagName('code')->item(0)->nodeValue==$code)
					{
						$found=1;
						$cours=$e->getElementsByTagName('cour');
						foreach($cours as $c)
						{
							if($c->nodeValue==$courid)
							{
								$found=2;
								break;
							}
							
						}
						if($found!=2)
						{
								$c=$dom->createElement('cour',$courid);
								$e->appendChild($c);
								$dom->save('xml/codes.xml');
						}
						break;
					}
					
				}
				if($found==2)echo"<center><h1>this cour already for that account</h1></center>";
				elseif($found==0)echo"<center>false code</center>";
			}elseif(isset($_POST['deletecour']))
			{
				$code=$_POST['code'];
				$courid=$_POST['courid'];
				$dom=new DOMDocument();
				$dom->load('xml/codes.xml');
				$element=$dom->getElementsByTagName('element');
				$el=null;
				$found=null;
				foreach($element as $e)
				{
					if($e->getElementsByTagName('code')->item(0)->nodeValue==$code)
					{
						$el=$e;
						$cours=$e->getElementsByTagName('cour');
						foreach($cours as $c)
						{
							if($c->nodeValue==$courid)
							{
								$found=$c;
								break;
							}
						}
						break;
					}
				}
				if($el!=null && $found!=null)
				{
					$el->removeChild($found);$dom->save('xml/codes.xml');
				}
				elseif($el==null)echo"invalid code";
				else echo"invalid cour of that code";
			}
		?>
		<div style="float:left;margin-top:200px;width:30%">
			<center><h2>add account</h2><br>
			<form action=add_remove_account.php method=post>
			<table style="font-size:25px">
				<tr>
					<td>dc name:</td><td><input type=text required name=dc_name /></td>
				</tr>
				<tr>
					<td>code:</td><td><input type=text required name=code /></td>
				</tr>
				<tr>
					<td>Admin</td><td><select name=admin><option>true</option><option>false</option></select></td>
				</tr>
				<tr>
					<td align=center><input type=submit name=add value=add /></td>
				</tr>
			</table>
			</form>
			</center>
		</div>
		<div style="float:left;margin-top:200px;width:30%">
		<center><h2>remove account</h2><br>
			<form action=add_remove_account.php method=post>
			<table style="font-size:25px">
				<tr>
					<td>code:<input type=text required name=code /></td>
				</tr>
				<tr>
					<td align=center><input type=submit name=delete value=delete /></td>
				</tr>
			</table>
			</form>
			</center>
		</div>
		<div style="float:left;margin-top:200px;width:40%">
			<center><h2>add cour to a docteur</h2><br>
			<form action=add_remove_account.php method=post>
			<table style="font-size:25px">
				<tr>
					<td>code:<input type=text required name=code /></td>
				</tr>
				<select type=text placeholder=id name="courid" autofocus required  style="height:30px;width:100px">
				<?php
				$dom=new DOMDocument();
				$dom->load("xml/cours.xml");
				$cours=$dom->getElementsByTagName('cour');
				foreach($cours as $cour)
				{
					echo"<option>".$cour->getElementsByTagName('id')->item(0)->nodeValue."</option>";
				}
				?>
				</select>
				<tr>
					<td align=center><input type=submit name=addcour value=add /><input type=submit name=deletecour value=delete /></td>
				</tr>
			</table>
			</form>
			</center>
		</div>
	</body>
</html>