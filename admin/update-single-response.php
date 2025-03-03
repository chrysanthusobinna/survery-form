<?php
include('../config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $surveyID       = intval($_POST['surveyID']);
    $responseAnswer = intval($_POST['responseAnswer']);

    // Validate that responseAnswer is an integer
    if (filter_var($responseAnswer, FILTER_VALIDATE_INT) !== false) {
        $responseAnswer = intval($responseAnswer);  

        if ($surveyID > 0 && $responseAnswer >= 0) {
            // Call the stored procedure
            $updateQuery    =   "{CALL UpdateBriefPainInventoryResponse(?, ?)}";
            $parameters     =   array($surveyID, $responseAnswer);
            $updateResult   =   sqlsrv_query($conn, $updateQuery, $parameters);


            if ($updateResult === false) {
                echo json_encode(array('status' => 'error', 'message' => 'Failed to update response.'));
            } else {
                echo json_encode(array('status' => 'success', 'message' => 'Response updated successfully.'));
            }
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Invalid input.'));
        }
    } else {
        // If responseAnswer is not a valid integer, ignore the request
        echo json_encode(array('status' => 'error', 'message' => 'Invalid response answer.'));
    }
} else {
    echo json_encode(array('status' => 'error', 'message' => 'Invalid request method.'));
}
?>
 