<html>
<head>
	<link href="mystyle.css" type="text/css" rel="stylesheet">
</head>
<body style="background-image:url('images/bg2.jpg'); background-size: cover;">
<?php
if(!isset($_COOKIE['secure']) || !$_COOKIE['secure']=='dhdhdSFDH4e64X')
	{
		header('location:login.php');
	}?>
<div style="float:left"><p style="color:#0066cc">blue:presence</p></div>
<div style="float:right"><a href="home.php" class=home>Go to Home</a></div>
<?php 
				if(isset($_POST['update']))
				{
					$selectedcour=$_POST['matiere'];
					$dom=new DOMDocument();
					$dom->load('xml/cours.xml');
					$found=null;
					$dom2=new DOMDocument();
					$yearfile=(int)date('Y');
					$mon=(int)date('m');
					if($mon>8) $yearfile++;
					if(file_exists('xml/presence_by_years/presence'.$yearfile.'.xml'))
					$dom2->load('xml/presence_by_years/presence'.$yearfile.'.xml');
					else $dom2->loadXML('<presence/>');
					$courclass=$dom2->getElementsByTagName('matiere');
					foreach($courclass as $data)
					{
						if($data->getAttribute('mat')==$selectedcour)
							$found=$data;
					}
					$cours=$dom->getElementsByTagName('cour');
					foreach($cours as $c1)
					{
						if($c1->getElementsByTagName('id')->item(0)->nodeValue==$selectedcour)
						{
							$studentsofyears=$c1->getElementsByTagName('students');
							$year=(int)date("Y");
							$month=(int)date("m");
							if($month<9)
							$year--;
							foreach($studentsofyears as $c)
							{
								if((int)$c->getAttribute('year')==$year)
									$students=$c->getElementsByTagName('student');
							}
								$class=$dom2->createElement("class");
								$class->setAttribute("date",date("Y/m/d"));
								foreach($students as $s)
								{	
									$ids=$s->nodeValue;
									$bool="";
									if(isset($_POST[$ids]))
										$bool="true";
									else $bool="false";
									$std=$dom2->createElement("student");
									$stdid=$dom2->createElement("id",$ids);
									$ishere=$dom2->createElement("ishere",$bool);
									$std->appendChild($stdid);
									$std->appendChild($ishere);
									$class->appendChild($std);
								}
							if($found==null)
							{
								$found=$dom2->createElement("matiere");
								$found->setAttribute("mat",$selectedcour);
								$found->appendChild($class);
								$dom2->getElementsByTagName('presence')->item(0)->appendChild($found);
							}
							else $found->appendChild($class);
							$dom2->save('xml/presence_by_years/presence'.$yearfile.'.xml');
							break;
						}
						
					}
					
				}
			?>
<center><form method=post action="new_presence.php"><select id=selectcours name=selectcour>
<?php
$dom=new DOMDocument();
$dom->load('xml/codes.xml');
$elements=$dom->getElementsByTagName('element');
foreach($elements as $e)
{
	if($e->getElementsByTagName('code')->item(0)->nodeValue==$_COOKIE['code'])
	{
		echo"here";
		$cours=$e->getElementsByTagName('cour');
		foreach($cours as $c)
		echo"<option>".$c->nodeValue."</option>";
	}
}
?>
</select><input type="submit" name="show" value="Show" /></form></center>
	<form method="post" action="new_presence.php">
	
	<?php if(isset($_POST['show'])||isset($_POST['update']))
	{
		echo"<table border=1>";
		if(isset($_POST['show']))
		{
			echo"<b>".$_POST['selectcour']."</b><br>";
			echo"<input type=hidden name=matiere value=\"".$_POST['selectcour']."\" />";
			$selectcour=$_POST['selectcour'];
		}
		else
		{
			$selectcour=$_POST['matiere'];
			echo"<b>".$_POST['matiere']."</b><br>";
			echo"<input type=hidden name=matiere value=\"".$_POST['matiere']."\"/>";
		}
	
						echo"<tr><th width=20px>id</th><th width=50px>name</th>";
						$dom=new DOMDocument();
						$yearfile=(int)date('Y');
						$mon=(int)date('m');
						if($mon>8) $yearfile++;
						if(file_exists('xml/presence_by_years/presence'.$yearfile.'.xml'))
							$dom->load('xml/presence_by_years/presence'.$yearfile.'.xml');
						else 
							$dom->loadXML('<presence/>');
						$s=$dom->getElementsByTagName('matiere');
						$found=null;
						foreach($s as $matiere)
						{
							if($matiere->getAttribute('mat')==$selectcour)
							{
								$class=$matiere->GetElementsByTagName('class');
								$found=$class;
								foreach($class as $sience)
								{
									$temparray=explode('/',$sience->getAttribute('date'));
									if(((int)$temparray[0]==(int)date("Y") && (int)$temparray[1]<9)||((int)$temparray[0]==((int)date("Y")-1) && (int)$temparray[1]>9))
									echo"<th>".$sience->getAttribute('date')."</th>";
								}
								break;
							}
							
						}
						echo"<td>".date("Y/m/d")."</td></tr>";
						$dom2=new DOMDocument();
						$dom2->load('xml/cours.xml');
						$s2=$dom2->getElementsByTagName('cour');
						$dom3=new DOMDocument();
						$this_year=(int)date('Y');
						if((int)date('m')<9)$this_year--;
						if(file_exists('xml/students_by_year/students'.$this_year.'.xml'))
						$dom3->load('xml/students_by_year/students'.$this_year.'.xml');
						else $dom3->loadXML('<students/>');
						$students=$dom3->getElementsByTagName('student');
						$id="";
						foreach($s2 as $cour)
						{
							if($cour->getElementsByTagName('id')->item(0)->nodeValue==$selectcour)
							{
								$studentofyear=$cour->getElementsByTagName('students');
								$year=(int)date("Y");
								$month=(int)date("m");
								if($month<9)
								$year--;
								foreach($studentofyear as $stds)
									if((int)$stds->getAttribute('year')==$year)
									{	
										$std=$stds->getElementsByTagName('student');
										foreach($std as $student)
										{
											foreach($students as $origin)
											{	
												$id=$origin->getElementsByTagName('id')->item(0)->nodeValue;
												if($id==$student->nodeValue)
												{
													echo"<tr><td>".$id."</td><td>".$origin->getElementsByTagName('name')->item(0)->nodeValue."</td>";
													break;
												}
											}
											if($found!=null)
											{
												foreach($found as $science)
												{
													$temparray=explode('/',$science->getAttribute('date'));
													if(((int)$temparray[0]==(int)date("Y") && (int)$temparray[1]<9)||((int)$temparray[0]==((int)date("Y")-1) && (int)$temparray[1]>9))
													{		
														$studentfound=0;
														$studentdata=$science->getElementsByTagName('student');
														foreach($studentdata as $data)
														{
															if($data->getElementsByTagName('id')->item(0)->nodeValue==$student->nodeValue)
															{
																$studentfound=1;
																if($data->getElementsByTagName('ishere')->item(0)->nodeValue=="true")
																{
																	echo"<td style=\"background-color:#0066cc\"></td>";
																}
																else echo"<td style=\"background-color:#cccccc\"></td>";
															}
														}
														if($studentfound==0)echo"<td>not registred</td>";
													}
												}
											}
											echo"<td><input type=checkbox checked=\"checked\" value=".$id." name=\"".$id."\" /></td></tr>";
										}
										
									}
								break;
							}
								
						}
						echo"</table>";
						echo"<input type=submit name=update value=Add />";
					}
					
				?>
	</form>
	
</body>
</html>