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
function printModuleHeaderData($firstLine){
	$elements = explode(',', $firstLine); #explode first line of the .txt file
	return array(isValidCode($elements[0]), #data to be returned
	isValidTitle($elements[1]));
}


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
	$difference = (intval($academicYear[2].$academicYear[3])) - (intval($academicYear[0].$academicYear[1]));
	if (!$difference = 1) {
		$errorMessage = ' - Error, wrong academic year (code format XXYY - where YY is greater than XX and difference is 1) ';
	}

	#Term valdiation:
	#Check if term is in valid format - available terms are T1, T2 and T3.
	$term = substr($element0, 6, 7); #Substract last 2 characters of module cofe
	$availableTerm = array('1', '2', '3'); #Available term numbers
	if (!($term[0] == 'T' and (in_array($term[1], $availableTerm)))) {
		$errorMessage = ' - Error, wrong "term" format (Check if first character is T and second in range 1-3';
		# code...
	}

	$output = '<p>Module code: '.$element0.$errorMessage.'</p>'; #if errorMessage is not declared, print out Module code
	return $output;
}

function isValidTitle($element1) {
	#Validates module title if it is in right format and is not empty or contain whitespaces
	#Parammeters: second element from first line of the file.
	#Rafal Fajkowski

	#Check if is empty
	$isEmpty = empty($element1);
	if($isEmpty){
		$errorMessage = ' - Error, title is empty';
	}
	#Check if contains whitespace
	if(ctype_space($element1)){
		$errorMessage = ' - Error, title data contains only whitespaces';
	}

	#Check if contains non-printable characters:
	if(!ctype_print($element1)){
		$errorMessage = ' - Error, title contains non-printable characters';
	}

	$output = '<p>Module title: '.$element1.$errorMessage.'</p>';
	return $output;
}










?>