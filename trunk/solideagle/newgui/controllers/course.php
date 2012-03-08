<?php


switch ($_GET["q"])
{
	case "js":?>
	
	$(function() {
			updateUniform();

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
				<ul>
				
				<li>1</li>
					<li>1</li>
						<li>1</li>
							<li>1</li>
								<li>1</li>
									<li>1</li>
				
				</ul>
			</div>
			
			
			     <form>
            <div class='select-radio'>
              <label>Select Dropdown</label>
              <select>
                <option value='option1'>Option 1</option>
                <option value='option2'>Option 2</option>

                <option value='option3'>Option 3</option>
              </select>
              <label>
                <input name='rgroup' type='radio' value='radio1' />
                Radio 1
              </label>
              <label>
                <input checked='checked' name='rgroup' type='radio' value='radio2' />
                Radio 2
              </label>

              <label>
                <input disabled='disabled' name='rgroup' type='radio' value='radio3' />
                Radio 3
              </label>
            </div>
            <div class='file-checkbox'>
              <label>File Upload</label>
              <input class='file' type='file' />
              <label>

                <input type='checkbox' value='check1' />
                Checkbox 1
              </label>
              <label>
                <input checked='checked' type='checkbox' value='check2' />
                Checkbox 2
              </label>
              <label>
                <input disabled='disabled' type='checkbox' value='check3' />
                Checkbox 3
              </label>

            </div>
          </form>
			
		</div>


		<?php 
	break;
}



?>



