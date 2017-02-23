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
		<div style="float:left"><p style="color:#0066cc">blue:is here</p></div>
		<div style="float:right"><a href="home.php" class=home>Go to Home</a></div>
		<center><form method=post action="oldpresence.php">
					enter the year<input type="text" name=year required onkeypress='return event.charCode >= 48 && event.charCode <= 57'></input><h6>note:2017 mean the year 2016-2017</h6>
					select cour<select id=selectcours name=selectcour>
					<?php
						$dom=new DOMDocument();
						$dom->load('xml/codes.xml');
						$elements=$dom->getElementsByTagName('element');
						foreach($elements as $e)
						{
							if($e->getElementsByTagName('code')->item(0)->nodeValue==$_COOKIE['code'])
							{
								$cours=$e->getElementsByTagName('cour');
								foreach($cours as $c)
								echo"<option>".$c->nodeValue."</option>";
							}
						}
					?>
					</select>
					<input type="submit" name="show" value="Show" />
				</form>
		</center>
		<?php if(isset($_POST['show']))
		{
		$year=(int)$_POST['year'];
		if(file_exists('xml/presence_by_years/presence'.$year.'.xml') && file_exists('xml/students_by_year/students'.($year-1).'.xml'))
		{
		echo"<table border=1>";
			echo"<b>".$_POST['selectcour']."</b><br>";
			echo"<input type=hidden name=matiere value=\"".$_POST['selectcour']."\" />";
			$selectcour=$_POST['selectcour'];
			
						echo"<tr><th width=20px>id</th><th width=50px>name</th>";
						$dom=new DOMDocument();
						$dom->load("xml/presence_by_years/presence".$year.".xml");
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
									if(((int)$temparray[0]==$year && (int)$temparray[1]<9)||((int)$temparray[0]==($year-1) && (int)$temparray[1]>9))
										echo"<th>".$sience->getAttribute('date')."</th>";
								}
								break;
							}
							
						}
						$dom2=new DOMDocument();
						$dom2->load('xml/cours.xml');
						$s2=$dom2->getElementsByTagName('cour');
						$dom3=new DOMDocument();
						$dom3->load('xml/students_by_year/students'.($year-1).'.xml');
						$students=$dom3->getElementsByTagName('student');
						$id="";
						foreach($s2 as $cour)
						{
							if($cour->getElementsByTagName('id')->item(0)->nodeValue==$selectcour)
							{
								$studentofyear=$cour->getElementsByTagName('students');
								foreach($studentofyear as $stds)
									if((int)$stds->getAttribute('year')==($year-1))
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
													if(((int)$temparray[0]==$year && (int)$temparray[1]<9)||((int)$temparray[0]==($year-1) && (int)$temparray[1]>=9))
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
										}
										break;
									}
								}
								
							}
						echo"</table>";
					}else echo"<h3>invalid year</h3>";
		}
					
				?>

		
	</body>
</html>