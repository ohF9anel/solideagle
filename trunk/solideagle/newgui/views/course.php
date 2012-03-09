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
	
	public function getcontent()
	{
		
		?>
		
		<div id="test">
			<div id="courseform">
				<form action="#" method="post" id="formAddCourse">
					
					<p>
							<label for="txtNameCourse">Naam vak:</label>
						
							<input type="text" id="txtNameCourse" name="txtNameCourse" />
							<br />
							
							
							
						
							<input type="submit" id="addCourseBtn" name="addCourseBtn"
								value="Voeg toe" />
					</p>
					
					
				</form>
			</div>
			<div id="courselist">

			</div>
			
		
			
		</div>
		
		<?php
		
	}
	
}

?>