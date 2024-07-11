<?php
include('../config.php');
include('../functions.php');

    // Get the PatientID from URL
    $patientID      =   ($_GET['id']) ? intval($_GET['id']) : 0;

    if ($patientID == 0) {

        header("location: index.php");
    }

    // Call the stored procedure to get patient details
    $patientQuery           =      "{CALL GetPatientDetailsByID(?)}";
    $parameters             =       array($patientID);
    $patientResult          =       sqlsrv_query($conn, $patientQuery, $parameters);

    if ($patientResult === false || sqlsrv_has_rows($patientResult) === false) {

        header("location: index.php");
    }

    $patient                =       sqlsrv_fetch_array($patientResult, SQLSRV_FETCH_ASSOC);

    // Call the stored procedure to get survey responses
    $responsesQuery         = "{CALL GetSurveyResponsesByPatientID(?)}";
    $parameters             = array($patientID);
    $responsesResult        = sqlsrv_query($conn, $responsesQuery, $parameters);

    if ($responsesResult === false) {
            header("location: index.php");
    }

    // Prepare responses data
    $responses              = array();
    $totalScore             = getTotalScore($conn, $patientID);  
    $firstResponseSkipped   = false;

    while ($response = sqlsrv_fetch_array($responsesResult, SQLSRV_FETCH_ASSOC)) {
        $responses[]    =   $response;
    }
?>