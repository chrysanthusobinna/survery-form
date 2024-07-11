<?php
include ('../config.php');
include ('../functions.php');
include ('header.php');
?>


    <div class="container">
        <div class="card card-patient-list">
            <div class="card-header">
                <h4 class="card-title">Patient List</h4>
            </div>
            <div class="card-body">
             
                <table class="table table-striped table-bordered" id="patientTable">
                    <thead>
                        <tr>
                            <th>Date Submitted</th>                           
                            <th>First Name</th>
                            <th>Surname</th>
                            <th>Age</th>
                            <th>Date of Birth</th>
                            <th>Total Score</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be populated here -->
                    </tbody>
                </table>
                <!-- Pagination -->
                <nav aria-label="Page navigation">
                    <ul class="pagination" id="pagination">
                        <!-- Pagination links will be generated here -->
                    </ul>
                </nav>
            </div>
        </div>
    </div>

 
<?php include("footer.php"); ?>


