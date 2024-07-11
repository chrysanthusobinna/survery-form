<?php
include ('../config.php');
include ('../functions.php');
include ('header.php');



    // Call the stored procedure to get the questions
    $questionsQuery         =   "{CALL ViewPainInventoryQuestions}";
    $questionsResult        =   sqlsrv_query($conn, $questionsQuery);
    $questions              =   array();

    while ($row = sqlsrv_fetch_array($questionsResult, SQLSRV_FETCH_ASSOC)) {
        $questions[] = $row;
    }


?>
    <div class="container mt-4">
        <div class="card card-outline shadow mb-3">
            <div class="card-body">
                <button class="btn btn-success mb-3" data-toggle="modal" data-target="#createQuestionModal">Create New
                    Question</button>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Question</th>
                            <th>Score Range</th>
                            <th>Count Score</th>
                            <th style="width:10%;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($questions as $index => $question):     ?>
                            <tr>
                                <td><?php echo htmlspecialchars($index + 1); ?></td>
                                <td><?php echo clean_output($question['Question']); ?></td>
                                <td><?php echo htmlspecialchars($question['RangeMin'] . ' - ' . $question['RangeMax']); ?></td>
                                <td><?php echo htmlspecialchars($question['CountScore']) == 1 ? 'Yes' : 'No'; ?>  </td>
                                <td>
                                    <button class="btn btn-primary btn-sm edit-question-btn" data-toggle="modal"
                                        data-target="#editQuestionModal" data-id="<?php echo $question['QuestionID']; ?>"
                                        data-question="<?php echo clean_output($question['Question']); ?>"
                                        data-min="<?php echo $question['RangeMin']; ?>"
                                        data-max="<?php echo $question['RangeMax']; ?>"
                                        data-count-score="<?php echo $question['CountScore']; ?>"><i class="fas fa-edit"></i></button>

                                    <button class="btn btn-danger btn-sm delete-question-btn" data-toggle="modal"
                                        data-target="#deleteQuestionModal"
                                        data-id="<?php echo $question['QuestionID']; ?>"><i
                                            class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Create Question Modal -->
    <div class="modal fade" id="createQuestionModal" tabindex="-1" role="dialog"
        aria-labelledby="createQuestionModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createQuestionModalLabel">Create New Question</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" method="POST">

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="createQuestion">Question</label>
                            <input type="text" class="form-control" id="createQuestion" name="question" required>
                        </div>
                        <div class="form-group">
                            <label for="createRangeMin">Range Min</label>
                            <input type="number" class="form-control" id="createRangeMin" name="rangeMin" required>
                        </div>
                        <div class="form-group">
                            <label for="createRangeMax">Range Max</label>
                            <input type="number" class="form-control" id="createRangeMax" name="rangeMax" required>
                        </div>
                        <div class="form-group">
                            <label for="createCountScore">Count Score</label>
                            <select class="form-control" id="createCountScore" name="countScore" required>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                        <input type="hidden" name="action" value="create">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" name="createQuestionForm" class="btn btn-primary">Create Question</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Question Modal -->
    <div class="modal fade" id="editQuestionModal" tabindex="-1" role="dialog" aria-labelledby="editQuestionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editQuestionModalLabel">Edit Question</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" method="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="editQuestion">Question</label>
                            <input type="text" class="form-control" id="editQuestion" name="question" required>
                        </div>
                        <div class="form-group">
                            <label for="editRangeMin">Range Min</label>
                            <input type="number" class="form-control" id="editRangeMin" name="rangeMin" required>
                        </div>
                        <div class="form-group">
                            <label for="editRangeMax">Range Max</label>
                            <input type="number" class="form-control" id="editRangeMax" name="rangeMax" required>
                        </div>
                        <div class="form-group">
                            <label for="editCountScore">Count Score</label>
                            <select class="form-control" id="editCountScore" name="countScore" required>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                        <input type="hidden" id="editQuestionID" name="questionID">
                        <input type="hidden" name="action" value="update">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" name="editQuestionForm"  class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Question Modal -->
    <div class="modal fade" id="deleteQuestionModal" tabindex="-1" role="dialog"
        aria-labelledby="deleteQuestionModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteQuestionModalLabel">Delete Question</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" method="POST">
                    <div class="modal-body">
                        <p>Are you sure you want to delete this question?</p>
                        <input type="hidden" id="deleteQuestionID" name="questionID">
                        <input type="hidden" name="action" value="delete">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" name="deleteQuestion" class="btn btn-danger">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>









    

    <?php include("footer.php"); ?>



<?php

if(isset($_POST["editQuestionForm"]))
{

    $questionID         =       intval($_POST['questionID']);
    $question           =       $_POST['question'];
    $rangeMin           =       intval($_POST['rangeMin']);
    $rangeMax           =       intval($_POST['rangeMax']);
    $countScore         =       $_POST['countScore'];

    // Prepare the update query
    $updateQuery = "{CALL UpdatePainInventoryQuestion(?, ?, ?, ?, ?)}";
    $parameters = array(
        array($questionID, SQLSRV_PARAM_IN),
        array($question, SQLSRV_PARAM_IN),
        array($rangeMin, SQLSRV_PARAM_IN),
        array($rangeMax, SQLSRV_PARAM_IN),
        array($countScore, SQLSRV_PARAM_IN)
    );

    // Execute updateQuery
    $result = sqlsrv_query($conn, $updateQuery, $parameters);

    // Check for errors
    if ($result === false) {
        // Retrieve and display the error message
        $errorMsg = sqlsrv_errors();
        $custom_flash_msg = "Failed to update record: " . print_r($errorMsg, true);
        setFlashMessage($custom_flash_msg, 'error');

    } 
    else 
    {
        $custom_flash_msg = "Question saved Updated!";
        setFlashMessage($custom_flash_msg, 'success');
    }

    echo "<script> window.location.href = '?'; </script>";

    // Close the connection
    sqlsrv_close($conn);
}

?>




<?php

if(isset($_POST["createQuestionForm"]))
{

    $question               =       $_POST['question'];
    $rangeMin               =       intval($_POST['rangeMin']);
    $rangeMax               =       intval($_POST['rangeMax']);
    $countScore             =       $_POST['countScore'];

    $insertQuery = "{CALL InsertPainInventoryQuestion(?, ?, ?, ?)}";
    $parameters = array(
        array(&$question, SQLSRV_PARAM_IN),
        array(&$rangeMin, SQLSRV_PARAM_IN),
        array(&$rangeMax, SQLSRV_PARAM_IN),
        array(&$countScore, SQLSRV_PARAM_IN)
    );

    // Execute insertQuery
    $result = sqlsrv_query($conn, $insertQuery, $parameters);

    // Check for errors
    if ($result === false) {
        // Retrieve and display the error message
        $errorMsg = sqlsrv_errors();

        $custom_flash_msg = "Failed to Create record: " . print_r($errorMsg, true);
        setFlashMessage($custom_flash_msg, 'error');
    }
    else 
    {
        $custom_flash_msg = "New Question Created!";
        setFlashMessage($custom_flash_msg, 'success');
    }

    echo "<script> window.location.href = '?'; </script>";

    // Close the connection
    sqlsrv_close($conn);
}

?>




<?php

if(isset($_POST["deleteQuestion"]))
{
         $questionID = intval($_POST['questionID']);

        $deleteQuery        =   "{CALL DeletePainInventoryQuestion(?)}";
        $parameters         =   array(
                                      array(&$questionID, SQLSRV_PARAM_IN)
                                     );

         // Execute deleteQuery
        sqlsrv_query($conn, $deleteQuery, $parameters);


      // Check for errors
      if ($result === false) {
        // Retrieve and display the error message
        $errorMsg = sqlsrv_errors();

        $custom_flash_msg = "Delete Question Failed" . print_r($errorMsg, true);
        setFlashMessage($custom_flash_msg, 'error');
    }
    else 
    {
        $custom_flash_msg = "Question Deleted!";
        setFlashMessage($custom_flash_msg, 'success');
    }

    echo "<script> window.location.href = '?'; </script>";

    // Close the connection
    sqlsrv_close($conn);      
}

?>

