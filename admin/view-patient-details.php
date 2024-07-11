<?php
include('../config.php');
include('../functions.php');
include ('header.php');
?>


<?php
      // Get the PatientID from URL
      $patientID      =   ($_GET['id']) ? intval($_GET['id']) : 0;

      if ($patientID == 0) {
  
          header("location: index.php");
      }
  
      // Call the stored procedure to get patient details
      $patientQuery             =      "{CALL GetPatientDetailsByID(?)}";
      $parameters               =       array($patientID);
      $patientResult            =       sqlsrv_query($conn, $patientQuery, $parameters);
  
      if ($patientResult === false || sqlsrv_has_rows($patientResult) === false) {
  
          header("location: index.php");
      }
  
      $patient                =       sqlsrv_fetch_array($patientResult, SQLSRV_FETCH_ASSOC);
  
      // Call the stored procedure to get survey responses
      $responsesQuery         = "{CALL GetSurveyResponsesByPatientID(?)}";
      $parameters                 = array($patientID);
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
  

 

    <div class="container">
        <div class="card shadow mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
        Patient Details

                <button type="button" class="btn btn-primary ml-auto" onclick="window.location.href = 'index.php'">
                    <i class="fas fa-arrow-left"></i> Back
                </button>

            </div>
            <div class="card-body">
                <!-- Patient Details Table -->
                <table class="table table-striped table-bordered">
                    <tbody>

                        <tr>
                            <th scope="row">Date of submission:</th>
                            <td><?php echo clean_output(formatDate($patient['SubmissionDate'], 'h:i:A - F j, Y')); ?></td>
                        </tr>                     
                        <tr>
                            <th scope="row">First Name:</th>
                            <td><?php echo clean_output($patient['FirstName']); ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Surname:</th>
                            <td><?php echo clean_output($patient['Surname']); ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Age:</th>
                            <td><?php echo clean_output($patient['Age']); ?></td>
                        </tr>                       
                        <tr>
                            <th scope="row">Date of Birth:</th>
                            <td><?php echo clean_output(formatDate($patient['DateOfBirth'], 'F j, Y')); ?></td>
                        </tr>

                        <tr>
                            <th scope="row">Total Score:</th>
                            <td><?php echo $totalScore; ?></td>
                        </tr>
                     
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card shadow mb-3">
            <div class="card-header bg-primary text-white">
                Patient Responses
            </div>
            <div class="card-body">
                <!-- Survey Responses -->
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Question</th>
                            <th>Response</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="Responses">
                        <?php foreach ($responses as $response): ?>
                            <tr>
                                <td><?php echo clean_output($response['Question']); ?></td>
                                <td><?php echo clean_output($response['Answer']) . ' / ' . clean_output($response['RangeMax']); ?>
                                </td>
                                <td>
                                    <button class="btn btn-primary btn-sm edit-single-response-btn" data-toggle="modal"
                                        data-target="#editResponseModal"
                                        data-rangemax="<?php echo $response['RangeMax']; ?>"
                                        data-rangemin="<?php echo $response['RangeMin']; ?>"
                                        data-surveyid="<?php echo $response['SurveyID']; ?>"
                                        data-question="<?php echo clean_output($response['Question']); ?>"
                                        data-answer="<?php echo clean_output($response['Answer']); ?>"><i
                                            class="fas fa-edit"></i></button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
             
                </table>
            </div>
            <div class="card-footer clearfix">

            <button onclick="window.location.href='update-patient-response.php?id=<?php echo $patientID; ?>';" class="btn btn-primary">
            <i class="fas fa-edit"></i> Edit All
            </button>

                <button class="btn btn-danger   btn-delete float-right" 
                                    data-toggle     =   "modal" 
                                    data-target     =   "#deletePatientModal"  >  <i class="fas fa-trash"></i> Delete</button>
            </div>
 

        </div>


    </div>

    <!-- Edit Response Modal -->
    <div class="modal fade" id="editResponseModal" tabindex="-1" role="dialog" aria-labelledby="editResponseModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editResponseModalLabel">Edit Response</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editResponseForm">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="responseQuestion">Question</label>
                            <textarea class="form-control" id="responseQuestion" readonly></textarea>
                        </div>
                        <div class="form-group">
                            <label for="responseAnswer">Response</label>
                            <input type="number" min=" " max=" " class="form-control" id="responseAnswer"
                                name="responseAnswer">
                        </div>
                        <input type="hidden" id="surveyID" name="surveyID">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>








    






    <!-- Delete Patient Modal -->
    <div class="modal fade" id="deletePatientModal" tabindex="-1" role="dialog" aria-labelledby="deleteResponseModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editResponseModalLabel"><i class='fas fa-exclamation-circle'></i>  Delete Patient's Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="delete-patient-details.php"  method="POST" id="editResponseForm">
                    <input type="hidden" name="id" value="<?php echo $patientID; ?>">
                    <div class="modal-body">
                     
                    <p>Are you sure you want to delete this patient's details and their responses?</p>


                        <div class='alert alert-danger alert-dismissible'>
                            <p class="text-danger"> This action cannot be undone. </p>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    
    <?php include("footer.php"); ?>

 
 