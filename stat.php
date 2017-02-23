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
		<center>
				<form method=post action="stat.php">
					<table border=0>
					<tr><td align=right>enter the year</td><td><input type="text" name=year required onkeypress='return event.charCode >= 48 && event.charCode <= 57'></input></td><td><h6>note:2017 mean the year 2016-2017</h6></td></tr>
					<tr><td align=right>student id</td><td><input type="text" name=student required></input></td>
					<td><input type="submit" name="show" value="Show" /></td></tr>
					</table>
				</form>
		</center>
		<?php
			if(isset($_POST['show']))
			{
				$year=(int)$_POST['year'];
				if(file_exists('xml/presence_by_years/presence'.$year.'.xml') && file_exists('xml/students_by_year/students'.($year-1).'.xml'))
				{
				$studentid=$_POST['student'];
				$studentnode=null;
				$studentdom=new DOMDocument();
				$studentdom->load('xml/students_by_year/students'.($year-1).'.xml');
				$presencedom=new DOMDocument();
				$presencedom->load('xml/presence_by_years/presence'.$year.'.xml');
				$students=$studentdom->getElementsByTagName('id');
				$studentname;
				foreach($students as $id)
				{
					if($id->nodeValue==$studentid)
					{
						$studentnode=$id->parentNode;
						$studentname=$id->nextSibling->nodeValue;
						echo"<center>student name:".$studentname."</center>";
						break;
					}
				}
				if($studentnode!=null)
				{
					$dom=new DOMDocument();
					$dom->load("xml/cours.xml");
					$cours=$dom->getElementsByTagName('cour');
					foreach($cours as $c)
					{
						$matiere=$c->getElementsByTagName('id')->item(0)->nodeValue;
						$studentsofyears=$c->getElementsByTagName('students');
						foreach($studentsofyears as $stdyear)
						{
							if((int)$stdyear->getAttribute('year')==($year-1))
							{
								$found=0;
								$students=$stdyear->getElementsByTagName('student');
								foreach($students as $s)
								{
									if($s->nodeValue==$studentid)
									{
										$found=1;
										break;
									}
								}
								if($found==1)
								{
									$allcours=$presencedom->getElementsByTagName('matiere');
									foreach($allcours as $alcours)
									if($alcours->getAttribute('mat')==$matiere)
									{
										echo"Cour:".$matiere."<br>";
										echo"<table border=1><tr><td>id</td><td>name</td>";
										$allclass=$alcours->getElementsByTagName('class');
										$studentpresence="";
										foreach($allclass as $class)
										{
											$date=$class->getAttribute('date');
											$temparray=explode('/',$date);
											if(((int)$temparray[0]==$year && (int)$temparray[1]<9)||((int)$temparray[0]==($year-1) && (int)$temparray[1]>9))
											{
												echo"<td>".$date."</td>";
												$presenceofclass=$class->getElementsByTagName('id');
												$foundid=0;
												foreach($presenceofclass as $id)
												{
													
													if($id->nodeValue==$studentid)
													{
														$foundid=1;
														if($id->nextSibling->nodeValue=="true")
															$studentpresence=$studentpresence."t";
														else $studentpresence=$studentpresence."f";
													}
												}
												if($foundid==0)$studentpresence=$studentpresence."n";
											}
										}
										echo"</tr><tr><td>".$studentid."</td><td>".$studentname."</td>";
										$len=strlen($studentpresence);
										for($i=0;$i<$len;$i++)
										{
											if($studentpresence[$i]=="n")
												echo"<td>not registred</td>";
											elseif($studentpresence[$i]=="t")
												echo"<td style=\"background-color:#0066cc\"></td>";
												elseif($studentpresence[$i]=="f")
												echo"<td style=\"background-color:#cccccc\"></td>";
										}
										echo"</tr></table><div style=\"width:100%;height:200px\"></div>";
										break;
									}
								}
								break;
							}
						}
					}
				}
				}
			}
		?>
	</body>
</html>