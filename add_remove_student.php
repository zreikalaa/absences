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
			$year=(int)date('Y');
			if((int)date('m')<9)$year--;
			if(file_exists("xml/students_by_year/students".$year.".xml"))
			$dom->load("xml/students_by_year/students".$year.".xml");
			else $dom->loadXML('<students/>');
			$s=$dom->getElementsByTagName('id');
			$studentfound=null;
			foreach($s as $std)
			{
				$id=$std->nodeValue;
				if($id==$_POST['id'])
				{
					$studentfound=$std;
					break;
				}
			}
			if($studentfound==null)
			{
				$root=$dom->getElementsByTagName('students')->item(0);
				$student=$dom->createElement('student');
				$id=$dom->createElement('id',$_POST['id']);
				$name=$dom->createElement('name',$_POST['name']);
				$student->appendChild($id);
				$student->appendChild($name);
				$root->appendChild($student);
				$dom->save("xml/students_by_year/students".$year.".xml");
				
			}
			else echo"that id of student already exist";
		}
		elseif(isset($_POST['remove']))
		{
			$dom=new DOMDocument();
			$year=(int)date('Y');
			if((int)date('m')<9)$year--;
			if(file_exists("xml/students_by_year/students".$year.".xml"))
			$dom->load("xml/students_by_year/students".$year.".xml");
			else $dom->loadXML('<students/>');
			$d=$dom->documentElement;
			$std=$dom->getElementsByTagName('student');
			$fordelete=null;
			foreach($std as $student)
			{
				$name=$student->getElementsByTagName('id')->item(0)->nodeValue;
				if($name==$_POST['selectremove'])
				{
					$fordelete=$student;
					break;
				}
			}
			if($fordelete!=null)
			{
				$old=$d->removeChild($fordelete);
				$dom->save('xml/students_by_year/students'.$year.'.xml');
				$dom->load("xml/cours.xml");
				$d=$dom->documentElement;
				$std=$dom->getElementsByTagName('student');
				$fordelete2=null;
				foreach($std as $student)
				{
					if($student->nodeValue==$_POST['selectremove'])
					{
						$fordelete2=$student;
						$old=$student->parentNode->removeChild($fordelete2);
					}
				}
				$dom->save('xml/cours.xml');
			}
			else{echo"<center><b>invalid id</b></center><br>";}
		}
		?>
		<div style="float:left; width:30%; margin-top:100px"><center><img style="height:300px;width:300px" src="images/addstudent.jpg"></img> </center>
		<center><form action=add_remove_student.php method=post>
			<table border=0>
				<tr>
					<td><input name=id placeholder=id type=text autofocus required width=40px style="height:30px"/></td>
				</tr>
				<tr>
					<td><input name=name placeholder=name type=text autofocus required width=40px style="height:30px"/></td>
				</tr>
				<tr>
					<td align=center><input type=submit name=add value=Add /></td>
				</tr>
			</table>
		</form>
		</center>
		</div>
		<div style="float:left; width:30%; margin-top:100px"><center><img style="height:300px;width:300px" src="images/removestudent.jpg"></img></center>
		<center><form action=add_remove_student.php method=post>
			<table border=0>
				<tr>
					<td align=center><input type=text placeholder=id "sel" name="selectremove" autofocus required  style="height:30px;width:100px" /></td>
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
			<tr><td align=center>id</td><td align=center>name</td></tr>
			<?php 
				$dom=new DOMDocument();
				$year=(int)date('Y');
				if((int)date('m')<9)$year--;
				if(file_exists("xml/students_by_year/students".$year.".xml"))
				$dom->load("xml/students_by_year/students".$year.".xml");
				else $dom->loadXML('<students/>');
				$std=$dom->getElementsByTagName('student');
				foreach($std as $student)
				{
					echo"<tr><td align=center>".$student->getElementsByTagName('id')->item(0)->nodeValue."</td>
					<td align=center>".$student->getElementsByTagName('name')->item(0)->nodeValue."</td></tr>";
				}
			?>
			</table>
		</div>
		
	</body>	
</html>