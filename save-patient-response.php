<?php
include('config.php');  
include('functions.php');  

// Retrieve data from the POST request
$firstName              =   trim(capitalizeFirstLetter($_POST['firstName']));
$surname                =   trim(capitalizeFirstLetter($_POST['surname']));
$dateOfBirth            =   trim($_POST['dateOfBirth']);
$age                    =   trim($_POST['age']);
$answers                =   $_POST['answers']; // Array of answers
$questionIDs            =   $_POST['QuestionIDs']; // Array of question IDs




    // Validate inputs
    if (!isValidName($firstName)) {
        die(json_encode(array("status" => "error", "message" => "Invalid first name")));
    }

    if (!isValidName($surname)) {
        die(json_encode(array("status" => "error", "message" => "Invalid surname")));
    }

    if (!isValidDate($dateOfBirth)) {
        die(json_encode(array("status" => "error", "message" => "Invalid date of birth")));
    }

    if (!isValidAge($age)) {
        die(json_encode(array("status" => "error", "message" => "Invalid age")));
    }



    if (!IFArrayResponseIsIntegers($answers)) {
        die(json_encode(array("status" => "error", "message" => "Invalid Response Value")));
    }  

    if (!IFArrayResponseIsIntegers($questionIDs)) {
        die(json_encode(array("status" => "error", "message" => "Invalid Response Value")));
    }  

    // Insert into Patients table using stored procedure

    $patientID = 0; // Initialize the variable that will store the output value

$sql = "{CALL InsertPatient(?, ?, ?, ?, ?)}";
$parameters = array(
    array($firstName, SQLSRV_PARAM_IN),
    array($surname, SQLSRV_PARAM_IN),
    array($dateOfBirth, SQLSRV_PARAM_IN),
    array($age, SQLSRV_PARAM_IN),
    array(&$patientID, SQLSRV_PARAM_OUT)
);

$statement = sqlsrv_query($conn, $sql, $parameters);

if ($statement === false) {
    die(json_encode(array("status" => "error", "message" => print_r(sqlsrv_errors(), true))));
}

// Fetch the result set containing the PatientID
if (sqlsrv_next_result($statement)) {
    $patientIDResult = sqlsrv_fetch_array($statement, SQLSRV_FETCH_ASSOC);
    $patientID = $patientIDResult['PatientID'];
} else {
    die(json_encode(array("status" => "error", "message" => "Failed to fetch PatientID ")));
}

// Validate and insert responses into BriefPainInventoryResponses table using stored procedure
$sql = "{CALL InsertResponse(?, ?, ?)}";
for ($i = 0; $i < count($answers); $i++) {
    $questionID     = (int) $questionIDs[$i];
    $answer         = (int) $answers[$i];

    // Validate questionID and answer
    if ($questionID <= 0 || $answer < 0) {
        die(json_encode(array("status" => "error", "message" => "Invalid question ID or answer")));
    }

    $parameters = array(
        array($patientID, SQLSRV_PARAM_IN),
        array($questionID, SQLSRV_PARAM_IN),
        array($answer, SQLSRV_PARAM_IN)
    );

    $query = sqlsrv_query($conn, $sql, $parameters);

    if ($query === false) {
        die(json_encode(array("status" => "error", "message" => print_r(sqlsrv_errors(), true))));
    }
}

// Close the connection
sqlsrv_close($conn);

// Return success response
echo json_encode(array("status" => "success"));
?>
