 function updateCourses()
		 {
		 
		 	$("#courselistholder").html("<p>Loading....</p>");
		 
		 list = $("<ul>");
		 
		 $.getJSON(courseDataUrl, function(data) {
		 
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
		 