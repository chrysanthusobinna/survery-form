<?php
include('../config.php');
include('../functions.php');

    // Call the stored procedure to get all patients
    $procedureCall      =   "EXEC GetAllPatients";
    $statement               =   sqlsrv_query($conn, $procedureCall);

    if ($statement === false) {
        die(json_encode(array('error' => 'Database query failed')));
    }

    $data = array();

    // Loop through each patient and set data for JSON
    while ($patient = sqlsrv_fetch_array($statement, SQLSRV_FETCH_ASSOC)) {
            $PatientID                  =   $patient['PatientID'];
            $FirstName                  =   $patient['FirstName'];
            $Surname                    =   $patient['Surname'];
            $Age                        =   $patient['Age'];
            $DateOfBirth                =   $patient['DateOfBirth'];
            $SubmissionDate             =   $patient['SubmissionDate'];

            $formattedDateOfBirth       = formatDate($DateOfBirth, 'F j, Y');
            $formattedSubmissionDate    = formatDate($SubmissionDate, 'h:i:A - F j, Y');

        $data[] = array(
            'submission_date'   => htmlspecialchars($formattedSubmissionDate),
            'first_name'        => htmlspecialchars($FirstName),
            'surname'           => htmlspecialchars($Surname),
            'age'               => htmlspecialchars($Age),
            'dob'               => htmlspecialchars($formattedDateOfBirth),
            'score'             => getTotalScore($conn, $PatientID),
            'actions'           => ' <button class="btn btn-primary btn-sm" onclick="window.location.href=\'view-patient-details.php?id=' . $PatientID . '\'"><i class="fas fa-eye"></i> VIEW</button>'
        );
    }

// Return data as JSON
echo json_encode(array('data' => $data));
?>
 