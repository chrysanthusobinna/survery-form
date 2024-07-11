
 
    <!-- Footer -->
    <footer class="footer text-center mt-100">
        <p class="mb-0">© <?php echo date("Y"); ?> NeuromodulaTon Form. All rights reserved.</p>
    </footer>


    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>



    <!-- Include DataTables JS for Bootstrap 4 -->
    <script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.2/js/dataTables.bootstrap4.min.js"></script>

        <!-- Custom JS Code -->
    <script src="../assets/js/admin-script.js"></script>

    <script>
    $(document).ready(function() {
        $('#patientTable').DataTable({
            "ajax": {
                "url": "fetch-patients-list.php",
                "dataSrc": "data"
            },
            "columns": [
                { "data": "submission_date" },
                { "data": "first_name" },
                { "data": "surname" },
                { "data": "age" },
                { "data": "dob" },
                { "data": "score" },
                { "data": "actions" }
            ],
            "pageLength": 5  
        });
    });
</script>


</body>

</html>
