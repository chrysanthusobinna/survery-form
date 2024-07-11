<?php
include('../config.php');
include('../functions.php');

// verify that the ID is provided
if (isset($_POST['id'])) {
    $patientID = $_POST['id'];

    // Begin a transaction
    sqlsrv_begin_transaction($conn);

    try {
        // delete records from BriefPainInventoryResponses
        $deleteBriefPainInventoryResponsesQuery = "EXEC DeleteBriefPainInventoryResponses @PatientID = ?";
        $parameters = array($patientID);
        $query_1 = sqlsrv_query($conn, $deleteBriefPainInventoryResponsesQuery, $parameters);

        if ($query_1 === false) {
            throw new Exception('Error deleting from BriefPainInventoryResponses: ' . print_r(sqlsrv_errors(), true));
        }

        // delete the patient from Patients table
        $deletePatientQuery = "EXEC DeletePatient @PatientID = ?";
        $query_2 = sqlsrv_query($conn, $deletePatientQuery, $parameters);

        if ($query_2 === false) {
            throw new Exception('Error deleting from Patients: ' . print_r(sqlsrv_errors(), true));
        }

        // Commit the transaction
        sqlsrv_commit($conn);

        $custom_flash_msg = "Patient Record Deleted Successfully!";
        setFlashMessage($custom_flash_msg, 'success'); //set error or success
        header("location: index.php");

    } catch (Exception $e) {
        // Rollback the transaction if there is an error
        sqlsrv_rollback($conn);
        echo 'error: ' . $e->getMessage();
    }
} else {


    $custom_flash_msg = "Failed to Patient Record Deleted!";
    setFlashMessage($custom_flash_msg, 'error'); //set error or success
    header("location: index.php");
}
?>
