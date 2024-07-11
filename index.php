<?php
include ('config.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Neuromodulation Form</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>

<body>

    <!-- Header -->
    <header class="header d-flex align-items-center p-3 mb-4">
        <a class="logo" href="index.php">
            <h1 class="mb-0">Neuromodulation Form</h1>
        </a>
    </header>


    <div class="container">



        <div id="ProgressBar" class="progress" role="progressbar" aria-label="Default striped example"
            aria-valuenow="10" aria-valuemin="0" aria-valuemax="100">
            <div class="progress-bar progress-bar-striped" style="width: 10%"></div>
        </div>
        <br />


        <!-- Card Patient Details -->
        <div class="card mt-3" id="CardPatientDetails">

            <form id="FormPatientDetails">
                <div class="card-header">Patient Details</div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="firstName">First Name</label>
                        <input type="text" class="form-control" id="firstName" name="firstName" required>
                        <label id="firstName-error" class="text-danger" for="firstName"></label>

                    </div>
                    <div class="form-group">
                        <label for="surname">Surname</label>
                        <input type="text" class="form-control" id="surname" name="surname" required>
                        <label id="surname-error" class="text-danger" for="surname"></label>

                    </div>
                    <div class="form-group">
                        <label for="dateOfBirth">Date of Birth</label>
                        <input type="date" class="form-control" id="dateOfBirth" name="dateOfBirth" required>
                        <label id="dateOfBirth-error" class="text-danger" for="dateOfBirth"></label>

                    </div>
                    <div class="form-group">
                        <label for="age">Age</label>
                        <input type="text" class="form-control" id="age" name="age" readonly>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="submit" id="submit-button" class="btn btn-success">Continue</button>
                </div>
            </form>

        </div>







        <!-- Card Pain Inventory Questions -->
        <div class="card mt-3" id="CardBriefPainInventory">

            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Brief Pain Inventory (BPI)</span>
                <button type="button" class="btn btn-danger btn-sm ml-auto" onclick="location.reload()"> <i
                        class="fa fa-times" aria-hidden="true"></i> Cancel</button>
            </div>

            <div class="card-body">





                <?php
                // Initialize counter
                $counter = -1;

                $query_questions = sqlsrv_query($conn, "SELECT * FROM PainInventoryQuestions ORDER BY QuestionID ASC") or die(print_r(sqlsrv_errors(), true));

                // Check if there are any questions available
                if (sqlsrv_has_rows($query_questions)) {
                    // Fetch and display the questions
                    while ($row = sqlsrv_fetch_array($query_questions, SQLSRV_FETCH_ASSOC)) {
                        $counter++;
                        ?>

                        <div class="question  <?php echo ($counter == 0) ? 'active' : ''; ?>"
                            data-question="<?php echo $counter; ?>" data-questionid="<?php echo $row['QuestionID']; ?>"  data-count_score="<?php echo $row['CountScore']; ?>">
                            Question <?php echo $counter + 1; ?>
                            <hr />
                            <div class="form-group">
                                <label for="question<?php echo $counter; ?>"><?php echo $row['Question']; ?></label>
                                <input type="range" class="custom-range" id="question<?php echo $counter; ?>"
                                    min="<?php echo $row['RangeMin']; ?>" max="<?php echo $row['RangeMax']; ?>" step="1"
                                    value="0" oninput="updateValue(this.value, <?php echo $counter; ?>)">

                                <span id="rangeValue_<?php echo $counter; ?>"
                                    class="range-value"><?php echo $row['RangeMin']; ?></span>
                                /
                                <span id="RangeMax_<?php echo $counter; ?>"><?php echo $row['RangeMax']; ?></span>
                            </div>
                        </div>

                    <?php
                    }
                } else {
                    echo "<p>No questions available.</p>";
                }
                ?>





            </div>
            <div class="card-footer clearfix">

                <button id="prev" class="btn btn-primary">Back</button>
                <button id="next" class="btn btn-success float-right">Next</button>

            </div>

        </div>


        <!-- Card Total Score -->
        <div class="card mt-3" id="CardTotalScore">
            <div class="card-header">Total Score</div>
            <div class="card-body">
                <div id="score">0</div>
            </div>
        </div>


        <!-- Card Success Message -->
        <div class="container mt-5 mb-200" id="CardSuccessMessage">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">Success</h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-success" role="alert">
                        <strong>Thank you!</strong> Your survey form has been successfully submitted.
                    </div>
                </div>
            </div>
        </div>



        <!-- No Data  Message -->
        <div class="container mt-5 mb-200" id="CardNoDataMessage">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h4 class="mb-0">Error!</h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning" role="alert">
                    <strong>Warning!</strong> No questions are available at the moment.

                    </div>
                </div>
            </div>
        </div>

        <!-- Card Review Submission -->
        <div class="card mt-3" id="CardReviewSubmission">
            <div class="card-header">
                Review before Submission
            </div>
            <div class="card-body">
                <!-- Patient Details Table -->
                <h5>Patient Details</h5>
                <table class="table table-bordered table-striped">
                    <tbody>
                        <tr>
                            <th>First Name:</th>
                            <td id="reviewFirstName"> </td>
                        </tr>
                        <tr>
                            <th>Surname:</th>
                            <td id="reviewSurname"> </td>
                        </tr>
                        <tr>
                            <th>Date of Birth:</th>
                            <td id="reviewDateOfBirth"> </td>
                        </tr>
                        <tr>
                            <th>Age:</th>
                            <td id="reviewAge"> </td>
                        </tr>
                    </tbody>
                </table>

                <!-- Survey Responses Table -->
                <h5 class="mt-4">Survey Responses</h5>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Question</th>
                            <th>Response</th>
                        </tr>
                    </thead>
                    <tbody id="reviewResponses">
                        <!-- Questions and Responses will be printed here -->
                    </tbody>

                    <tfoot>
                        <tr>
                            <td><b>Total Score</b></td>
                            <td><b id="reviewtotalScore">0</b></td>
                        </tr>
                    </tfoot>

                </table>



                <!-- Action Buttons -->
                <div class="mt-4">
                    <button id="confirmSubmit" class="btn btn-success">
                        <i class="fa fa-check" aria-hidden="true"></i> Submit Survey</button>

                    <button class="btn btn-danger" onclick="location.reload()">
                        <i class="fa fa-times" aria-hidden="true"></i> Cancel</button>

                </div>
            </div>
        </div>




    </div>



    <!-- Footer -->
    <footer class="footer text-center mt-100">
        <p class="mb-0">© <?php echo date("Y"); ?> NeuromodulaTon Form. All rights reserved.</p>
    </footer>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script src="assets/js/scripts.js"></script>

</body>

</html>