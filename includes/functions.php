<?php
#Web Programming using PHP (P1) - TMA Functions file to be included in TMA web pages

function mmmr($array, $output = 'mean'){ 
    #Provides basic statistical functions - default is mean; other $output parammeters are; 'median', 'mode' and 'range'.
	#Ian Hollender 2016 - adapted from the following, as it was an inacurate solution
	#http://phpsnips.com/45/Mean,-Median,-Mode,-Range-Of-An-Array#tab=snippet
	#Good example of PHP overloading variables with different data types - see the Mode code
	if(!is_array($array)){ 
        echo '<p>Invalid parammeter to mmmr() function: ' . $array . ' is not an array</p>';
		return FALSE; #input parammeter is not an array
    }else{ 
        switch($output){ #determine staistical output required
            case 'mean': #calculate mean or average
                $count = count($array); 
                $sum = array_sum($array); 
                $total = $sum / $count; 
            break; 
            case 'median': #middle value in an ordered list; caters for odd and even lists
                $count = count($array); 
				sort($array); #sort the list of numbers
				if ($count % 2 == 0) { #even list of numbers
					$med1 = $array[$count/2];
					$med2 = $array[($count/2)-1];
					$total = ($med1 + $med2)/2;
				}
				else { #odd list of numbers
					$total = $array[($count-1)/2]; 	
				}				
            break; 
            case 'mode': #most frequent value in a list; N.B. will only find a unique mode or no mode; 
                $v = array_count_values($array); #create associate array; keys are numbers in array, values are counts
                arsort($v); #sort the list of numbers in ascending order				
				
				if (count(array_unique($v)) == 1) { #all frequency counts are the same, as array_unique returns array with all duplicates removed!
					return 'No mode';
				}				
				$i = 0; #used to keep track of count of associative keys processes
                $modes = '';
				foreach($v as $k => $v){ #determine if a unique most frequent number, or return NULL by only looking at first two keys and frequency numbers in the sorted array					
					if ($i == 0) { #first number and frequency in array
						$max1 = $v;	#highest frequency of first number in array
						$modes = $k . ' ';
						$total = $k; #first key is the most frequent number;
					}
					if ($i > 0) { #second number and frequency in array
						$max2 = $v;	#highest frequency of second number in array					
						if ($max1 == $max2) { #two or more numbers with same max frequency; return NULL
							$modes = $modes . $k . ' ';
						}
						else {
							break;  
						}
					}
					$i++; #next item in $v array to be counted
				}
				$total = $modes;				
            break; 
            case 'range': #highest value - lowest value
                sort($array); #find the smallest number
                $sml = $array[0]; 
                rsort($array); #find the largest number
                $lrg = $array[0]; 
                $total = $lrg - $sml; #calculate the range
            break; 
			default :
				echo '<p>Invalid parammeter to mmmr() function: ' . $output . '</p>';
				$total= 0;
				return FALSE;
        } 
        return $total; 
    } 
}


############################
# PRINT MODULE HEADER DATA #
############################

function printModuleHeaderData($firstLine){
	#Function validates data of first line using other functions and returns an array with module header data, and if needed error message
	#Parammeter: first line of the .txt file
	#Rafal Fajkowski
	$elements = explode(',', $firstLine);; #explode first line of the .txt file
	return array( #data to be returned
	isValidCode($elements[0]), 		#Prints out module code, and error message if needed
	isValidTitleAndTutor($elements[1], 'Title'),		#Prints out module title, and error message if needed
	isValidTitleAndTutor($elements[2], 'Tutor name'),		#Prints out module tutor, and error message if needed
	isValidDate($elements[3]));		#Prints out date marked, and error message if needed
}



##########################################################################################
## FUNCTIONS VALIDATING MODULE HEADER DATA ###############################################
##########################################################################################

function isValidCode($element0) {
	#Validates module code. If module code is wrong, prints out accurate error message. 
	#Parammeter: First element from exploded string.
	#Rafal Fajkowski

	#2 char. code validation:
	$moduleCode = substr($element0, 0, 2); #Extract 2 characters of the code
	$availableCode = array( 'PP', 'P1', 'DT'); #array for existing module code, adding new code available.
	
	if (!in_array($moduleCode, $availableCode)) { #check if code is not in array
		$errorMessage = ' - Error, wrong module code format';
	}

	#Academic year validation:
	#check if all characters are integers
	$academicYear = substr($element0, 2, 4); #extract year from the code
	if (!ctype_digit($academicYear)) { 
		$errorMessage = ' - Error, wrong characters format (Check if all characters are numbers)';
	}
	#check if difference between years is 1, 15/16 - true, 16/15, 1517 - false
	#chenge string to integers and check the difference
	$pair1 = substr($academicYear, 0, 2);
	$pair2 = substr($academicYear, 2, 2);
	$pair1 = intval($pair1);
	$pair2 = intval($pair2);
	$difference = $pair2 - $pair1;
	if (!($difference == 1)) {
		$errorMessage = ' - Error, "academic year" must be 1 year long (code format XXYY - where YY is greater than XX) ';
	}

	#Term valdiation:
	#Check if term is in valid format - available terms are T1, T2 and T3.
	$term = substr($element0, 6, 7); #Substract last 2 characters of module cofe
	$availableTerm = array('1', '2', '3'); #Available term numbers
	if (!($term[0] == 'T' and (in_array($term[1], $availableTerm)))) {
		$errorMessage = ' - Error, wrong "term" format (Check if first character is T and second in range 1-3)';
		# code...
	}

	$output = '<p>Module code: '.$element0.$errorMessage.'</p>'; #if errorMessage is not declared, print out Module code
	return $output;
}

function isValidTitleAndTutor($element1, $string) {
	#Validates module title and tutor name. Check if it is in right format and is not empty or contains whitespaces
	#Parammeters: second(for title) or third(for tutor) element from first line of the file, $string as "Tutor name" or "Title"
	#Rafal Fajkowski

	#Check if is empty
	$isEmpty = empty($element1);
	if($isEmpty){
		$errorMessage = ' - Error, '.$string.' is empty';
	}
	#Check if contains whitespace
	if(ctype_space($element1)){
		$errorMessage = ' - Error, '.$string.' contains only whitespaces';
	}

	#Check if contains non-printable characters:
	if(!ctype_print($element1)){
		$errorMessage = ' - Error, '.$string.' contains non-printable characters';
	}

	$output = '<p>'.$string.': '.$element1.$errorMessage.'</p>';
	return $output;
}


function isValidDate($element3) {
	#Checks if date is in the right format: DD/MM/YEAR. Used checkdate() function is used.
	#The data from .txt file need to be changed to integer, exploded and passed in the right order to the 
	#Parammeter: fourth element of first line of .txt file.
	#Rafal Fajkowski
	
	$separator = array('/' , '.' , '-'); #Available separators in case different were used.
	$validDate = str_replace($separator, '/' ,$element3); #Change separator to universal format
	$explodedDate = explode('/', $validDate);
	$day = intval($explodedDate[0]);
	$month = intval($explodedDate[1]);
	$year = intval($explodedDate[2]);
	#$validDate = array_map('intval', $validDate )); #Change string to integers !!!!!!!
	$validDate = checkdate($day, $month, $year);
	if($validDate){
		$errorMessage = ' - Error, invalid "date", date format DD/MM/YEAR';
	}

	#Check if year is in range
	if($year < 2000) {
		$errorMessage = ' - Error, date is not in available range';
	}

	$output = '<p>Date marked: '.$element3.$errorMessage.'</p>';
	return $output;
}

##########################################################################################
##  FUNCTION VALIDATING STUDENTS IDS AND MARKS ##########################################
##########################################################################################


function is_valid_id_mark($lineNumber){
	#Function returns student id and their mark. If data is invalid, prints out meaningfull error message
	#Parammeter: line number of opened .txt file
	#Rafal Fajkowski
	$flag = false; #IF error appears, flag the data so it will not be added to the valid student array.
	$studentData = explode(',', $lineNumber);
	global $validStudents; #Set as global to update array outside of function scope
	global $marksToAnalyse; #Sets array as global so will be able to update data inside the function
	global $errorCount; # Error count to return to grade distribution function
	#########################
	# STUDENT ID VALIDATION #
	#########################
	$studentData[0] = trim($studentData[0]);
	
	#Check for amount of numeric characters in student ID data:
	if (!(strlen($studentData[0]) == 8)) {
		$errorMessage = ' - Error, invalid student ID length';
		$flag = true;
	}

	#Check if all characters are integers:
	if (!ctype_digit($studentData[0])) {
		$errorMessage = ' - Error, invalid alphabetical character in student ID ';
		$flag = true;
	}
	#Check if student id is missing or contains whitespaces:
	if (ctype_space($studentData[0]) || empty($studentData[0])) {
		$errorMessage = ' - Error, "Student ID" is missing';
		$flag = true;
	}

	###########################
	# STUDENT MARK VALIDATION #
	###########################
	$studentData[1] = trim($studentData[1]); # clean data from any whitespaces
	
	#Check if grade is in range:
	if(($studentData[1] > 100) || ($studentData[1] < 0)) {
		$errorMessage = ' - Error, "Studen mark" is not in range 1-100';
		$flag = true;
	}

	#Check if student mark is empty or contains whitespaces:
	if (ctype_space($studentData[1]) || empty($studentData[1])) {
		$errorMessage = ' - Error, "Student mark" is missing';
		$flag = true;
	}

	#Check if all characters are digits
	if (!ctype_digit($studentData[1])) {
		$errorMessage = ' - Error, invalid student mark format (need to contain only digits)';
		$flag = true;
	}

	#Update validStudents and marksToAnalyse array, if error appear add 1 to $errorCount:
	if(!$flag){
		$validStudents[] = $studentData[0].' : '.$studentData[1];
		$marksToAnalyse[] = $studentData[1];
	}
	else{
		$errorCount ++;

	}


	$output = $studentData[0].' : '.$studentData[1].$errorMessage;
	return '<p>'.$output.'</p>';
}


function gradeDistribution($array, $errorCount){
	#Function analyse students grades and put them in right group.
	#Parammeter: array of vaild grades:
	#Rafal Fajkowski
	$distinction = 0;
	$merit = 0;
	$pass = 0;
	$fail = 0;
	foreach ($array as $key) {
		if ($key > 69) {
			$distinction ++;

		}
		elseif ($key > 59) {
			$merit ++;

		}
		elseif ($key > 39) {
			$pass ++;
			# code...

		}
		else{
			$fail ++;
		}
	}
	return 	'<p>Distinction: '.$distinction.'</p>
			<p>Merit '.$merit.'</p>
			<p>Pass: '.$pass.'</p>
			<p>Fail: '.$fail.'</p>
			<p>Errors: '.$errorCount.'</p>';
}








?>