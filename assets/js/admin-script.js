

// UPDATING SINGLE RESPONSE
$(document).ready(function () {
    $('.edit-single-response-btn').click(function () {
        var surveyID = $(this).data('surveyid');
        var question = $(this).data('question');
        var answer = $(this).data('answer');

        // Get values from the rangemin and rangemin data attribute
        var min = $(this).data('rangemin');
        var max = $(this).data('rangemax');

        // Set the min and max attributes of the responseAnswer input
        $('#responseAnswer').attr('min', min);
        $('#responseAnswer').attr('max', max);


        $('#surveyID').val(surveyID);
        $('#responseQuestion').val(question);
        $('#responseAnswer').val(answer);
    });

    $('#editResponseForm').submit(function (e) {
        e.preventDefault();

        $.ajax({
            url: 'update-single-response.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function (response) {
                $('#editResponseModal').modal('hide');
                location.reload();
            },
            error: function () {
                alert('Error updating response.');
            }
        });
    });



    // Bind data to edit modal
    $('.edit-question-btn').on('click', function () {
        $('#editQuestionID').val($(this).data('id'));
        $('#editQuestion').val($(this).data('question'));
        $('#editRangeMin').val($(this).data('min'));
        $('#editRangeMax').val($(this).data('max'));
        $('#editCountScore').val($(this).data('count-score'));
    });

    // Bind data to delete modal
    $('.delete-question-btn').on('click', function () {
        $('#deleteQuestionID').val($(this).data('id'));
    });

    // Handle form submission for creating question
    $('#createQuestionForm').on('submit', function (e) {
        e.preventDefault();
        $.post('', $(this).serialize(), function (response) {
            location.reload();
        });
    });

    // Handle form submission for editing question
    $('#editQuestionForm').on('submit', function (e) {
        e.preventDefault();
        $.post('', $(this).serialize(), function (response) {
            //location.reload();
        });
    });

    // Handle form submission for deleting question
    $('#deleteQuestionForm').on('submit', function (e) {
        e.preventDefault();
        $.post('', $(this).serialize(), function (response) {
            location.reload();
        });
    });

});


