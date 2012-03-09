<?php

require_once 'baseview.php';

baseview::load(new course);

class course 
{
	
	public function js()
	{
		?>
		 function updateCourses()
		 {
		 
		 	$("#courselistholder").html("<p>Loading....</p>");
		 
		 list = $("<ul>");
		 
		 $.getJSON('controllers/course.getCourseList', function(data) {
		 
		  	$("#courselistholder").html("");
		 
  			$.each(data, function() {
				    	lielem = $("<li/>");
				    	lielem.html(this.name);
				    	lielem.attr("courseid",this.id);
				    
				    	  $('<a/>', {html: " | delete",href: "javascript:void(0);" })
				    	  .click(function(){
				    	
				    		$.post("controllers/course.delete", { id: $(this).parent().attr("courseid") }, function(data)
				    		{
				    			updateCourses();
				    		});

				    	})

				    	  .appendTo(lielem);
				    	list.append(lielem).appendTo($("#courselistholder"));
				    	
				    		
				    	
				    });

			});
		 
		

		 
		 }
		
		$(function() {
			
			
		
			
			updateCourses();
			
			$("#formAddCourse").ajaxForm(function(responseText, statusText, xhr, $form){
			
			
				if(responseText.length > 3)
					alert(responseText);
			
				updateCourses();
			});
			
		 });
		 

		 
		<?php
	}
	
	public function getcontent()
	{
		
		?>
		
		<div id="test">
			<div id="courseform">
				<form action="controllers/course.addNew" method="post" id="formAddCourse">
					
					<p>
							<label for="txtNameCourse">Naam vak:</label>
						
							<input type="text" id="txtNameCourse" name="coursename" />
							<br />
							
							<input type="submit" id="addCourseBtn" name="addCourseBtn"
								value="Voeg toe" />
					</p>
					
					
				</form>
			</div>
			<div id="courselist">
				<h3>Vakken</h3>
				<div id="courselistholder" ></div>
			</div>
			
		
			
		</div>
		
		<?php
		
	}
	
}

?>