<?php
	require_once 'includes/functions.php'; // functions needed to run the webpage
	include 'includes/header.php'; //* Header - contains opening body HTML tags, <head> section
?>
			

		<?php
			$directory = opendir('data');
				
				while (($file = readdir($directory))) {
					

					if (!($file == '.' ||  $file == '..')){
						
						$fileExtension = pathinfo($file);
						$fileExtension = $fileExtension['extension'];

						if (!($fileExtension == 'txt')) {
							continue;
						}

						$lines = file('data/'.$file);
							
							#CHeck if file is empty or it contains only whitespaces:
							if((empty($lines) || ctype_space($lines[0]))){
								echo '<p id="emptyFile"> Error - file: '.$file.' is empty or contains whitespaces only</p>';
							continue;
						}	
						
						echo '<div id="file">
								<p> Module header data:</p><br>
								
									<div class="data">
										<p>File name: '. $file.'</p>';
										#Print header data:
										$header = printModuleHeaderData($lines[0]); #function returns multiple value in array
										foreach ($header as $data) { #printing out data from the function
											echo $data;
									}	
						echo 		'</div>
								
								<br><p>Student ID and marked data read from the file:</p><br>
								
									<div class="data">';
										$validStudents = array(); #Array will be updated in the function to print out only vailable students
										$marksToAnalyse = array(); #Array will be updated in the function to pass to mmmr() function
										$errorCount = 0; #Global variable updated in function to return how many errors of students data occured
										#Print all student ID's and check if they are valid
										for ($currentLine=1; $currentLine < count($lines) ; $currentLine++) { 
											echo is_valid_id_mark($lines[$currentLine]);
										}

										#echo '<p>Array count:'.$validStudents[1].'</p>';
										#echo '<p>Array count:'.$marksToAnalyse[1].'</p>';
						echo 		'</div>

								<br><p>ID\'s and module marks to be included:</p><br>

									<div class="data">';

										foreach ($validStudents as $student) {
											echo '<p>'.$student.'</p>';
										}

						echo 		'</div>
								
								<br><p>Statistical Analysis of module marks:</p><br>

									<div class="data">
										<p>Mean: '.mmmr($marksToAnalyse).'</p>
										<p>Mode: '.mmmr($marksToAnalyse, 'mode').'</p>
										<p>Range: '.mmmr($marksToAnalyse, 'range').'</p>
										<br>
										<p># of students: '.count($marksToAnalyse).'</p>
									</div>

								<br><p>Grade Distribution of module marks:</p><br>
									<div class="data">.'
										.gradeDistribution($marksToAnalyse, $errorCount).'
									</div>';
						
						echo'</div>'; #closing tag for each file
					}
				}
				
				closedir($handle);
		?>
	


<?php
	include 'includes/footer.php' // Footer - contains closing body HTML tags
?>