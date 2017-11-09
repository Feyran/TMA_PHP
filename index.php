<?php
	require_once 'includes/functions.php'; // functions needed to run the webpage
	include 'includes/header.php'; //* Header - contains opening body HTML tags, <head> section
?>
	
	<div style="margin-left: 25px;">		
	

		<?php
			$directory = opendir('data');
				
				while (($file = readdir($directory))) {
					
					if (!($file == '.' ||  $file == '..')){
						
						$lines = file('data/'.$file);
						
						# echo '<p>'.$lines[0].'</p>';!!!!!!!!!!!!!

						#$line = explode(',', $lines[0]);
						#echo $line[1], $line[0];					DO USUNIECIA !!!!!

						/*$data = explodeFirstLine($lines[0]);
						echo $data[0];

						echo isValidCode($data[0]);!!!!!!!!!!!!!!!!!*/
						/*echo '<h2> Module header data:</h2>
							<div style="margin-left: 25px;">
								<p>File name: '. $file.'</p>'.
								printModuleHeaderData($lines[0]).
							'</div>';*/
						echo '<h2> Module header data:</h2>
							<div style="margin-left: 25px;">
								<p>File name: '. $file.'</p>';
								$header = printModuleHeaderData($lines[0]); #function returns multiple value in array
								foreach ($header as $data) { #printing out data from the function
									echo $data;
								
								}
						echo '</div>';
					}
				}
				
				closedir($handle);
		?>
	


	</div>
<?php
	include 'includes/footer.php' // Footer - contains closing body HTML tags
?>