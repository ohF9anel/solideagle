<?php

require_once 'data_access/Course.php';
use DataAccess\Course;

switch ($_GET["q"])
{
	case "js":?>
	
	$(function() {
			updateUniform();
			
			$("#courselist").load("controllers/course.php?q=courselist");
			
	 });
	
	
	<?php 
	break;
	case "content":?>

		<div id="test">
			<div id="courseform">
				<form action="#" method="post" id="formAddCourse">
					
							<label for="txtNameCourse">Naam vak:</label>
						
							<input type="text" id="txtNameCourse" name="txtNameCourse" />
							<br />
							
							
							
						
							<input type="submit" id="addCourseBtn" name="addCourseBtn"
								value="Voeg toe" />
						
				</form>
			</div>
			<div id="courselist">

			</div>
			
		
			
		</div>


		<?php 
	break;
	case "courselist":
	
		echo "<ul>";
		foreach(Course::getAllCourses() as $course)
		{
			
			echo "<li>" . $course->getName() . "</li>";
		}
		echo "</ul>";
		
		break;

}



?>



