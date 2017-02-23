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
		<div style="float:right"><a href="home.php" class=home>Go to Home</a></div>
		<?php
		if(isset($_POST['add']))
		{ 
			$dom=new DOMDocument();
			$dom->load("xml/cours.xml");
			$s=$dom->getElementsByTagName('cour');
			$courfound=null;
			foreach($s as $cour)
			{
				$id=$cour->getElementsByTagName('id')->item(0)->nodeValue;
				if($id==$_POST['id'])
				{
					$courfound=$cour;
					break;
				}
			}
			if($courfound==null)
			{
				$xml = simplexml_load_file("xml/cours.xml");
				$sxe = new SimpleXMLElement($xml->asXML());
				$s=$sxe->addChild("cour");
				$s->addChild("id",$_POST['id']);
				$sxe->asXML("xml/cours.xml");
			}else{echo"cours already exist";}
		}
		elseif(isset($_POST['remove']))
		{
			//remove selected cour if exist from cour.xml and presence.xml
			$dom=new DOMDocument();
			$dom->load("xml/cours.xml");
			$d=$dom->documentElement;
			$std=$dom->getElementsByTagName('cour');
			$fordelete=null;
			foreach($std as $cour)
			{
				$id=$cour->getElementsByTagName('id')->item(0)->nodeValue;
				if($id==$_POST['selectremove'])
				{
					$fordelete=$cour;
					break;
				}
			}
			if($fordelete!=null)
			{
				$old=$d->removeChild($fordelete);
				$dom->save('xml/cours.xml');
				$this_year=(int)date('Y');
				for($i=2016;$i<=$this_year;$i++)
				{	
					if(file_exists("xml/presence_by_years/presence".$i.".xml"))
					{
						$dom->load("xml/presence_by_years/presence".$i.".xml");
						$d=$dom->documentElement;
						$std=$dom->getElementsByTagName('matiere');
						$fordelete2=null;
						foreach($std as $cour)
						{
							if($cour->getAttribute('mat')==$_POST['selectremove'])
							{
								$fordelete2=$cour;
								break;
							}
						}
						if($fordelete2!=null)
						{
						$old=$d->removeChild($fordelete2);
						$dom->save('presence_by_years/presence'.$i.'.xml');
						}
					}
				}
			}
			else
			{
				echo"<center><b>invalid id</b></center><br>";
			}
		}
		?>
		<div style="float:left; width:30%; margin-top:100px"><center><img style="height:300px;width:300px" src="images/newlab.jpg"></img> </center>
		<center><form action=add_remove_cour.php method=post>
			<table border=0>
				<tr>
					<td><input name=id placeholder=id type=text autofocus required width=40px style="height:30px"/></td>
				</tr>
				<tr>
					<td align=center><input type=submit name=add value=Add /></td>
				</tr>
			</table>
		</form>
		</center>
		</div>
		<div style="float:left; width:30%; margin-top:100px"><center><img style="height:300px;width:300px" src="images/delete.png"></img></center>
		<center><form action=add_remove_cour.php method=post>
			<table border=0>
				<tr>
					<td align=center><input type=text placeholder=id name="selectremove" autofocus required  style="height:30px;width:100px" /></td>
				</tr>
				<tr>
					<td align=center><input style="width:70px" type=submit name=remove value=remove /></td>
				</tr>
			</table>
		</form>
		</center>
		</div>
		<div style="float:left; width:40%; margin-top:100px">
			<table style="width:100%" border=2px>
			<tr><td align=center>id</td></tr>
			<?php 
				$dom=new DOMDocument();
				$dom->load("xml/cours.xml");
				$std=$dom->getElementsByTagName('cour');
				foreach($std as $cour)
				{
					echo"<tr><td align=center>".$cour->getElementsByTagName('id')->item(0)->nodeValue."</td>
					</tr>";
				}
			?>
			</table>
		</div>
		
	</body>	
</html>