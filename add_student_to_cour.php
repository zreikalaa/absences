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
<center><b style="font-size:25px">+Add student to cour<br>-Remove student from cour</b></center>
<div style="float:left; width:25%; margin-top:100px">
			<center><b>Students</b></center>
			<table style="width:100%" border=2px>
			<tr><th align=center>select</th><th align=center>id</th><th align=center>name</th></tr>
			<?php 
				$dom=new DOMDocument();
				$year=(int)date('Y');
				if((int)date('m')<9)
						$year--;
				if(file_exists("xml/students_by_year/students".$year.".xml"))
					$dom->load("xml/students_by_year/students".$year.".xml");
				else 
					$dom->loadXML('<students/>');
				$std=$dom->getElementsByTagName('student');
				
				echo"<form action=add_student_to_cour.php method=\"post\">";
				foreach($std as $student)
				{
					$id=$student->getElementsByTagName('id')->item(0)->nodeValue;
					echo"<tr><td><input type=checkbox name=\"list_check[]\" value=\"".$id."\" /></td><td align=center>".$id."</td>
					<td align=center>".$student->getElementsByTagName('name')->item(0)->nodeValue."</td></tr>";
				}
			?>
			</table>
</div>
<div style="float:left; width:24%; margin-top:100px;margin-left:1%">
<center><b>Cours</b></center>
			<table style="width:100%" border=2px>
			<tr><th>select</th><th align=center>Cour Id</th></tr>
			<?php 
				$dom=new DOMDocument();
				$dom->load("xml/cours.xml");
				$std=$dom->getElementsByTagName('cour');
				foreach($std as $cour)
				{
					$id=$cour->getElementsByTagName('id')->item(0)->nodeValue;
					echo"<tr><td><input type=checkbox name=courid[] value=\"".$id."\" </td><td align=center>".$id."</td>
					</tr>";
				}
			?>
			</table>
		</div>
<div style="float:left; width:25%; margin-top:120px">

<center><table>

<tr><td align=right><input type="submit" name="add_button" value="Add" /></td>
<td align=right><input type="submit" name="delete_button" value="Delete" /></td></tr>
<tr><td colspan=2 align=center>
<?php 
if(isset($_POST['add_button']))
{
	$dom=new DOMDocument();
	$dom->load("xml/cours.xml");
	$s=$dom->getElementsByTagName('cour');
	$courfound=null;
	$userfound=null;
	$yearfound=null;
	
	
	$year=(int)date("Y");
	$month=(int)date("m");
	if($month<9)
	$year--;
	if(!empty($_POST['list_check']) && !empty($_POST['courid']))
	{
		foreach($_POST['courid'] as $courid)
		{
		foreach($s as $cour)
		{
			$id=$cour->getElementsByTagName('id')->item(0)->nodeValue;
			if($id==$courid)
			{
				$courfound=$cour;
				break;
			}
		}
		$studentofyear=$courfound->getElementsByTagName('students');
	$existstudent=0;
		foreach($_POST['list_check'] as $check)
		{
			$existstudent=0;
			foreach($studentofyear as $mystd)
			{
				if((int)$mystd->getAttribute('year')==$year)
				{
					$yearfound=$mystd;
					$studentdofyear=$mystd->getElementsByTagName('student');
					foreach($studentdofyear as $mystd1)
					{
						if($mystd1->nodeValue==$check)
						{
							$existstudent=1;
							break;
						}
					}
					break;
				}
			}
	
			if($courfound!=null && $existstudent==0)
			{
					$element=$dom->createElement('student',$check);
					if($yearfound!=null)
					{
						$yearfound->appendChild($element);
					}
					else
					{
						$students=$dom->createElement('students');
						$students->setAttribute("year",$year);
						$students->appendChild($element);
						$courfound->appendChild($students);
					}	
					$dom->save('xml/cours.xml');
				
			}
		}
	}
	}
}
elseif(isset($_POST['delete_button']))
{
	$dom=new DOMDocument();
	$dom->load("xml/cours.xml");
	$s=$dom->getElementsByTagName('cour');
	if(!empty($_POST['list_check']) && !empty($_POST['courid']))
	foreach($_POST['courid'] as $courid)
	{
	$courfound=null;
	$userfound=null;
	$styearfound=null;
	
	foreach($s as $cour)
	{
		$id=$cour->getElementsByTagName('id')->item(0)->nodeValue;
		if($id==$courid)
		{
			$courfound=$cour;
			$year=(int)date("Y");
			$month=(int)date("m");
			if($month<9)
			$year--;
			$studentofyear=$cour->getElementsByTagName('students');
			foreach($studentofyear as $styear)
			{
				if($styear->getAttribute('year')==$year)
				{
					$student=$styear->getElementsByTagName('student');
					if(!empty($_POST['list_check']))
					{
					foreach($_POST['list_check'] as $check)	
					foreach($student as $std)
					{
						if($std->nodeValue==$check)
						{
							$styearfound=$styear;
							$userfound=$std;
							$styearfound->removeChild($userfound);
							break;
						}
					}
					}
				}
			}			
			break;
		}
	}
	}
	echo"done";$dom->save('xml/cours.xml');
}
?>
</td>
</tr>
</table>
</center>
</form>
</div>
<div style="float:right; width:25%; margin-top:100px">
			<center><b>Show students of specific cour in this year</b><br>
			<form action=add_student_to_cour.php method="post">
			<select type=text placeholder=id name="showid" autofocus required  style="height:30px;width:100px">
			<?php
				$dom=new DOMDocument();
				$dom->load("xml/cours.xml");
				$std=$dom->getElementsByTagName('cour');
				foreach($std as $cour)
				{
					echo"<option>".$cour->getElementsByTagName('id')->item(0)->nodeValue."</option>";
				}
			?>
			</select>
			<input style="width:70px" type=submit name=show value=Show />
			</form>
			</center>
			<table style="width:100%" border=2px>
			<tr><td align=center colspan=2>Cour Id</td></tr>
			<?php 
				if(isset($_POST['show']))
				{
					$dom=new DOMDocument();
					$dom->load("xml/cours.xml");
					$dom2=new DOMDocument();
					$year=(int)date('Y');
					if((int)date('m')<9)
						$year--;
					if(file_exists("xml/students_by_year/students".$year.".xml"))
						$dom2->load("xml/students_by_year/students".$year.".xml");
					else 
						$dom2->loadXML('<students/>');
					$std=$dom->getElementsByTagName('cour');
					foreach($std as $cours)
					{
						$c=$cours->getElementsByTagName('id')->item(0)->nodeValue;
						if($c==$_POST['showid'])
						{
							echo"<tr><td align=center colspan=2>".$c."</td>
						</tr><tr><td align=center>student id</td><td align=center>student name</td></tr>";
							$mystd=$cours->getElementsByTagName('students');
							
							foreach($mystd as $cour)
							{ 
								if((int)$cour->getAttribute('year')==(int)(date('Y')-1))
								{
									$id=$cour->getElementsByTagName('student');
									foreach($id as $stdid)
									{
										$students=$dom2->getElementsByTagName('student');
										foreach($students as $data)
										{
											if($data->getElementsByTagName('id')->item(0)->nodeValue==$stdid->nodeValue)
											{
												echo"<tr><td align=center>".$data->getElementsByTagName('id')->item(0)->nodeValue."</td><td align=center>".$data->getElementsByTagName('name')->item(0)->nodeValue."</td></tr>";
												break;
											}
										}
									}
									break;
								}
							}
							
						}
						 
					}
				}
			?>
			</table>
		</div>
</body>
</html>