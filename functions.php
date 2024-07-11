<?php

    // Function to validate names
    function isValidName($name)
    {
        return preg_match("/^[a-zA-Z\s]+$/", $name);
    }

    // Function to validate date of birth
    function isValidDate($date)
    {
        $d = DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }

    // Function to validate age
    function isValidAge($age)
    {
        $pattern = "/^(\d+)\s*(year|years|month|months|week|weeks|day|days)\s+old$/i";
        return preg_match($pattern, $age);
    }


    //Function to Capitalize First Letter of String
    function capitalizeFirstLetter($string)
    {
        $string = strtolower($string);
        $string = ucfirst($string);
        return $string;
    }

    //function format date with diffrent pattermns e.g  [Y-m-d] [F j, Y]  [Y-m-d H:i:s] 
    function formatDate($date, $pattern)
    {
        if ($date instanceof DateTime) {
            return $date->format($pattern); // Format the DateTime object
        }

        // If the $date is a string, convert it to DateTime and format it
        try {
            $dateTime = new DateTime($date);
            return $dateTime->format($pattern);
        } catch (Exception $e) {
            // Handle exceptions and return 'N/A' if date conversion fails
            return 'N/A';
        }
    }


    //Function to Clean Output 
    function clean_input($string)
    {
        // escape special characters in a string for use in an SQL statement
        $string = trim($string);
        $string = addslashes($string);
        return $string;
    }

    //Function to Clean Input 
    function clean_output($string)
    {
        $string = stripslashes($string);
        return htmlentities($string, ENT_QUOTES, 'UTF-8');
    }

    // Function to set flash message
    function setFlashMessage($message, $type)
    {
        $_SESSION['flash_message'] = array(
            'message' => $message,
            'type' => $type
        );
    }


    // Function to get and clear flash message  
    function getFlashMessage()
    {
        if (isset($_SESSION['flash_message'])) {
            $message = $_SESSION['flash_message'];
            unset($_SESSION['flash_message']);
            return $message;
        } else {
            return '';
        }
    }



    // Function to calculate Total Score  while skipping  questions with CountScore = 0
    function getTotalScore($conn, $patientID) {
        // Call the stored procedure to get survey responses
        $responsesQuery     =   "{CALL GetSurveyResponsesByPatientID(?)}";
        $parameters         =   array($patientID);
        $responsesResult    =   sqlsrv_query($conn, $responsesQuery, $parameters);
    
        if ($responsesResult === false) {
            die(print_r(sqlsrv_errors(), true));
        }
    
        // Calculate the total score
        $totalScore = 0;
    
        while ($response = sqlsrv_fetch_array($responsesResult, SQLSRV_FETCH_ASSOC)) {
            // Add to total score only if CountScore is 1
            if ($response['CountScore'] == 1) {
                $totalScore += $response['Answer'];
            }
        }
    
        return $totalScore;
    }
    
   
    
    
// Function to get total number of patients
function getTotalNumberOfPatients($conn) {
    $sql = "{CALL GetTotalNumberOfPatients}";
    $statement = sqlsrv_query($conn, $sql);
    
    if ($statement === false) {
        die(print_r(sqlsrv_errors(), true));
    }
    
    $row = sqlsrv_fetch_array($statement, SQLSRV_FETCH_ASSOC);
    return $row['TotalPatients'];
}

// Function to get total number of Pain Inventory Questions
function getTotalNumberOfPainInventoryQuestions($conn) {
    $sql = "{CALL GetTotalNumberOfPainInventoryQuestions}";
    $statement = sqlsrv_query($conn, $sql);
    
    if ($statement === false) {
        die(print_r(sqlsrv_errors(), true));
    }
    
    $row = sqlsrv_fetch_array($statement, SQLSRV_FETCH_ASSOC);
    return $row['TotalQuestions'];
}



//function to calculate age using date
function calculateAge($date) {
    // Validate date format (YYYY-MM-DD)
    $pattern = '/^\d{4}-\d{2}-\d{2}$/';
    if (!preg_match($pattern, $date)) {
        return "Please enter a valid date";
    }
    
    $dob = new DateTime($date);
    $today = new DateTime();
    
    // Check if DOB is in the future
    if ($dob > $today) {
        return "Date of birth cannot be in the future";
    }
    
    // Calculate age components
    $ageInYears = $today->diff($dob)->y;
    $ageInMonths = $today->diff($dob)->m + ($ageInYears * 12);
    $ageInDays = $today->diff($dob)->days;
    $ageInWeeks = floor($ageInDays / 7);
    
    // Determine the appropriate time unit to display
    if ($ageInYears > 0) {
        return $ageInYears <= 1 ? "$ageInYears Year Old" : "$ageInYears Years Old";
    } elseif ($ageInMonths > 0) {
        return $ageInMonths <= 1 ? "$ageInMonths Month Old" : "$ageInMonths Months Old";
    } elseif ($ageInWeeks > 0) {
        return $ageInWeeks <= 1 ? "$ageInWeeks Week Old" : "$ageInWeeks Weeks Old";
    } else {
        return $ageInDays <= 1 ? "$ageInDays Day Old" : "$ageInDays Days Old";
    }
}

 


// Function to validate input integers
function validateInteger($value, $name) {
    if (!is_int($value)) {
        $custom_flash_msg = "$name must be an integer.";
        setFlashMessage($custom_flash_msg, 'error');
        echo "<script> window.location.href = '?'; </script>";
        exit;
    }
}

// Function to validate count score must be (0 or 1)
function validateCountScore($value) {
    if ($value !== '0' && $value !== '1') {
        $custom_flash_msg = "CountScore must be 0 or 1!";
        setFlashMessage($custom_flash_msg, 'error');
        echo "<script> window.location.href = '?'; </script>";
        exit;
    }
}

// Function to validate question
function validateQuestion($question) {
    if (empty($question) || strlen($question) > 255) {
        $custom_flash_msg = "Question cannot be empty and must be less than 256 characters!";
        setFlashMessage($custom_flash_msg, 'error');
        echo "<script> window.location.href = '?'; </script>";
        exit;
    }
}


//function to check if all values in array are integer or not
function IFArrayResponseIsIntegers($array) {
    foreach ($array as $value) {
        
        if (!filter_var($value, FILTER_VALIDATE_INT) !== false) {
            return false; 
        }
    }
    return true; 
}



?>

