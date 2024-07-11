<?php
include ('../config.php');
include ('../functions.php');
include ('header.php');
 


// Get the PatientID from URL
$patientID = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($patientID == 0) {
    header("location: index.php");
    exit;
}


        // Fetch patient details
        $patientQuery = "{CALL GetPatientDetailsByID(?)}";
        $parameters = [$patientID];
        $patientResult = sqlsrv_query($conn, $patientQuery, $parameters);

            if ($patientResult === false || sqlsrv_has_rows($patientResult) === false) {
                header("location: index.php");
                exit;
            }

        $patient = sqlsrv_fetch_array($patientResult, SQLSRV_FETCH_ASSOC);

        // Fetch survey responses
        $responsesQuery     = "{CALL GetSurveyResponsesByPatientID(?)}";
        $parameters         = [$patientID];
        $responsesResult    = sqlsrv_query($conn, $responsesQuery, $parameters);

            if ($responsesResult === false) {
                header("location: index.php");
                exit;
            }

        // Prepare responses data
        $responses = [];
        while ($response = sqlsrv_fetch_array($responsesResult, SQLSRV_FETCH_ASSOC)) {
            $responses[$response['SurveyID']] = $response;
        }
?>


    <div class="container">
        <div class="card shadow mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                Patient Details

                <button type="button" class="btn btn-primary ml-auto" onclick="window.location.href = 'index.php'">
                    <i class="fas fa-arrow-left"></i> Back
                </button>
            </div>
            <div class="card-body">
                <form  id="FormPatientDetails" method="post" action="">
                    <input type="hidden" name="patientID" value="<?php echo $patientID; ?>">
                 
                    <!-- Patient Details Form -->
                    <div class="form-group">
                        <label for="firstName">First Name:</label>
                        <input type="text" class="form-control" id="firstName" name="firstName" value="<?php echo htmlspecialchars($patient['FirstName']); ?>" required>
                        <label id="firstName-error" class="text-danger" for="firstName"></label>
                    </div>
                    <div class="form-group">
                        <label for="surname">Surname:</label>
                        <input type="text" class="form-control" id="surname" name="surname" value="<?php echo htmlspecialchars($patient['Surname']); ?>" required>
                        <label id="surname-error" class="text-danger" for="surname"></label>
                    </div>

                    <div class="form-group">
                        <label for="dateOfBirth">Date of Birth:</label>
                        <input type="date" class="form-control" id="dateOfBirth" name="dateOfBirth" value="<?php echo htmlspecialchars($patient['DateOfBirth']->format('Y-m-d')); ?>" required>
                        <label id="dateOfBirth-error" class="text-danger" for="dateOfBirth"></label>
                        </div>

                        <div class="form-group">
                        <label for="age">Age</label>
                        <input type="text" class="form-control" id="age" value="<?php echo htmlspecialchars($patient['Age']); ?>"  readonly>
                        <input type="hidden" class="form-control" id="age_x" name="age"  value="<?php echo htmlspecialchars($patient['Age']); ?>">
                    </div>
           
                    <hr/>

                    <!-- Update  Brief Pain Inventory   Form -->
                    <h3>Brief Pain Inventory (BPI)</h3>

                    <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Question</th>
                            <th>Response</th>
                        </tr>
                    </thead>
                    <tbody>
					<?php foreach ($responses as $responseID => $response): ?>
					
                            <tr>
                                <td><?php echo htmlspecialchars($response['Question']); ?></td>
                                <td>
									<div class="form-group">
									<input type="number" class="form-control" name="responses[<?php echo $responseID; ?>]" value="<?php echo htmlspecialchars($response['Answer']); ?>" min="0" max="<?php echo htmlspecialchars($response['RangeMax']); ?>" required>
									</div>							
								</td>
                            </tr>
							
					<?php endforeach; ?>  
                    </tbody>
             
                </table>

                    <button id="submit-button"  type="submit" class="btn btn-primary"><i class="fas fa-check"></i>  Save Changes</button>
                </form>
            </div>
        </div>
    </div>

 

    <?php include("footer.php"); ?>

    
<?php

// Handle form submission for updates
if ($_SERVER['REQUEST_METHOD'] == 'POST') {


    $patientID              =       $_POST['patientID']; 
    $firstName              =       $_POST['firstName'];
    $surname                =       $_POST['surname'];
    $age                    =       $_POST['age'];
    $dateOfBirth            =       $_POST['dateOfBirth'];


    $responses              =       $_POST['responses'];

    // Validate inputs
    if (!isValidName($firstName)) {

            $custom_flash_msg = "Invalid first name";
            setFlashMessage($custom_flash_msg, 'error');
            echo "<script> window.location.href = '?id=$patientID'; </script>";
            exit;
    }

    if (!isValidName($surname)) {

            $custom_flash_msg = "Invalid surname";
            setFlashMessage($custom_flash_msg, 'error');
            echo "<script> window.location.href = '?id=$patientID'; </script>";
            exit;       
    }

    if (!isValidDate($dateOfBirth)) {

            $custom_flash_msg = "Invalid date of birth";
            setFlashMessage($custom_flash_msg, 'error');
            echo "<script> window.location.href = '?id=$patientID'; </script>";
            exit;          
    }

    if (!isValidAge($age)) {

        $custom_flash_msg = "Invalid age";
        setFlashMessage($custom_flash_msg, 'error');
        echo "<script> window.location.href = '?id=$patientID'; </script>";
        exit;  
    }

    
    
    if (!IFArrayIsIntegers($responses)) {

        $custom_flash_msg = "Invalid Response";
        setFlashMessage($custom_flash_msg, 'error');
        echo "<script> window.location.href = '?id=$patientID'; </script>";
        exit; 
    }  



    // Update patient details
    $updatePatientQuery = "{CALL UpdatePatientDetails(?, ?, ?, ?, ?)}";
    $parameters = [
        $patientID,
        $_POST['firstName'],
        $_POST['surname'],
        $_POST['age'],
        $_POST['dateOfBirth']
    ];
    sqlsrv_query($conn, $updatePatientQuery, $parameters);

    // Update survey responses
    foreach ($_POST['responses'] as $responseID => $answer) {
        $updateResponseQuery = "{CALL UpdateBriefPainInventoryResponse(?, ?)}";
        $responseParams = [
            $responseID,
            intval($answer)
        ];
        sqlsrv_query($conn, $updateResponseQuery, $responseParams);
    }

 
    $custom_flash_msg = "Record Saved successfully!";
    setFlashMessage($custom_flash_msg, 'success'); //set error or success
    echo "<script> window.location.href = 'view-patient-details.php?id=$patientID'; </script>";

    
    
}


?>