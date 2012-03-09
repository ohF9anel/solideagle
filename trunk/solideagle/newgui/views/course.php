<?php

require_once 'baseview.php';

baseview::load(new course);

class course 
{
	
	public function js()
	{
		?>
		$(function() {
			updateUniform();
			
			$("#courselist").load("controllers/course.php?q=courselist");
			
		 });
		<?php
	}
	
	public function addForm()
	{
		
		?>
		
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
		
	}
	
}

?>