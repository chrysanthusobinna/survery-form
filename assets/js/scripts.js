
// Update the value displayed on each of the range input
function updateValue(value, id) {
    document.getElementById('rangeValue_' + id).textContent = value;
}


$(document).ready(function () {
    
    $('#CardNoDataMessage').hide();
    $('#ProgressBar').hide();
    $('#CardBriefPainInventory').hide();
    $('#CardTotalScore').hide();
    $('#CardSuccessMessage').hide();
    $('#CardReviewSubmission').hide();




    let currentQuestion = 0;
    const answers = [];
    const QuestionIDs = [];
    const totalQuestions = $('.question').length;

    
    if(totalQuestions === 0){

        
        $('#CardPatientDetails').hide();
        $('#CardNoDataMessage').show();



    }


    // VALIDATE firstName
    $('#firstName').change(function () {
        let firstName = $('#firstName').val();
        const pattern = /^[a-zA-Z\s]+$/;

        if (firstName.length < 2 || firstName.length > 50 || !pattern.test(firstName)) {
            $('#firstName-error').text("First name must be between 2 and 50 characters long and contain only letters and spaces.");
            $('#submit-button').prop('disabled', true);
        } else {
            $('#firstName-error').text("");
            $('#submit-button').prop('disabled', false);
        }
    });

    // VALIDATE surname
    $('#surname').change(function () {
        let surname = $('#surname').val();
        const pattern = /^[a-zA-Z\s]+$/;

        if (surname.length < 2 || surname.length > 50 || !pattern.test(surname)) {
            $('#surname-error').text("Surname must be between 2 and 50 characters long and contain only letters and spaces.");
            $('#submit-button').prop('disabled', true);
        } else {
            $('#surname-error').text("");
            $('#submit-button').prop('disabled', false);
        }
    });

    $('#dateOfBirth').change(function () {
        const dateOfBirth = $('#dateOfBirth').val();
        const pattern = /^\d{4}-\d{2}-\d{2}$/;
    
        if (!pattern.test(dateOfBirth)) {
            $('#dateOfBirth-error').text("Please enter a valid date");
            $('#submit-button').prop('disabled', true);
        } else {
            var dob = new Date(dateOfBirth);
            var today = new Date();
            today.setHours(0, 0, 0, 0);
    
            if (dob > today) {
                $('#dateOfBirth-error').text("Date of birth cannot be in the future or Today");
                $('#age').val("");
                $('#submit-button').prop('disabled', true);
            } else {
                $('#dateOfBirth-error').text("");
                $('#submit-button').prop('disabled', false);
    
                // Calculate age in years, months, days, and weeks
                var ageInYears = today.getFullYear() - dob.getFullYear();
                var ageInMonths = today.getMonth() - dob.getMonth() + (ageInYears * 12);
                var ageInDays = Math.floor((today - dob) / (1000 * 60 * 60 * 24));
                var ageInWeeks = Math.floor(ageInDays / 7);
    
                if (today.getDate() < dob.getDate()) {
                    ageInMonths--;
                }
    
                if (today.getMonth() < dob.getMonth() || (today.getMonth() === dob.getMonth() && today.getDate() < dob.getDate())) {
                    ageInYears--;
                }
    
                // Determine which time unit to display
                let ageText = "";
                if (ageInYears > 0) {
                    ageText = ageInYears <= 1 ? `${ageInYears} Year Old` : `${ageInYears} Years Old`;                            
                } else if (ageInMonths > 0) {
                    ageText = ageInMonths <= 1 ? `${ageInMonths} Month Old` : `${ageInMonths} Months Old`;                            

                } else if (ageInWeeks > 0) {
                    ageText = ageInWeeks <= 1 ? `${ageInWeeks} Week Old` : `${ageInWeeks} Weeks Old`;                            

                } else {
                    ageText = ageInDays <= 1 ? `${ageInDays} Day Old` : `${ageInDays} Days Old`;                            

                }
    
                // Display the calculated age
                $('#age').val(ageText);
            }
        }
    });
    


    // Event handler for FormPatientDetails submit  
    $('#FormPatientDetails').on('submit', function (event) {
        event.preventDefault();

        $('#ProgressBar').show();
        $('#CardPatientDetails').hide();
        $('#CardBriefPainInventory').show();
        $('#CardTotalScore').show();
        $('#CardSuccessMessage').hide();
    });

    // Function to display the current question and update button states
    function showQuestion(index) {
        $('.question').removeClass('active');
        $('.question[data-question="' + index + '"]').addClass('active');
        $('#prev').toggle(index !== 0);
        $('#next').text(index === totalQuestions - 1 ? 'Next and Review' : 'Next');
    }

    // Function to calculate the total score from the answers array, excluding the first value
    function calculateScore() {
        let totalScore = 0;
        $('.question').each(function(index) {
            // Check if the current question has data-count_score set to 1
            if ($(this).data('count_score') === 1) {
                // Get the value from the input element
                const answer = $(`#question${index}`).val();
                if (answer !== undefined) {
                    totalScore += parseInt(answer, 10) || 0;
                }
            }
        });
        $('#score').text(totalScore);
    }
    

    // function to update the progress bar
    function updateProgress(percentage) {
        percentage = Math.max(0, Math.min(100, percentage));
        $('.progress-bar').css('width', percentage + '%');
        $('.progress').attr('aria-valuenow', percentage);
    }

 

    $('#next').click(function () {
        const currentInput = $('#question' + currentQuestion);
        let QuestionID = $('.question.active').data('questionid');
    
        answers[currentQuestion] = currentInput.val();
        QuestionIDs[currentQuestion] = QuestionID;
    
        calculateScore();
    
        if (currentQuestion < totalQuestions - 1) {
            currentQuestion++;
            showQuestion(currentQuestion);
            let progressPercentage = Math.round(((currentQuestion + 1) / totalQuestions) * 100);
            updateProgress(progressPercentage);
        } else {
            $('#CardBriefPainInventory').hide();
            $('#CardTotalScore').hide();
            $('#CardSuccessMessage').hide();
            $('#CardReviewSubmission').show();
    
            // Populate review card
            $('#reviewFirstName').text($('#firstName').val());
            $('#reviewSurname').text($('#surname').val());
            $('#reviewDateOfBirth').text($('#dateOfBirth').val());
            $('#reviewAge').text($('#age').val());
            $('#reviewtotalScore').text($('#score').html());
    
            let responsesHtml = '';
            $('.question').each(function(index) {
                // Fetch the label text for the current question
                let QuestionText = $(`label[for="question${index}"]`).text();
                // Fetch the value from the input element for the current question
                let answerValue = $(`#question${index}`).val();
                let RangeMax = $(`#RangeMax_${index}`).text();
    
                // Generate the HTML for each question and its response
                responsesHtml += `<tr><td>Question ${index + 1}: ${QuestionText}</td><td>${answerValue} / ${RangeMax}</td></tr>`;
            });
    
            $('#reviewResponses').html(responsesHtml);
        }
    });



    // Event handler for the "Previous" button
    $('#prev').click(function () {
        if (currentQuestion > 0) {
            currentQuestion--;
            let progressPercentage = Math.round(((currentQuestion + 1) / totalQuestions) * 100);
            updateProgress(progressPercentage);
            showQuestion(currentQuestion);
        }
    });

    // Event handler for the "Confirm Submit" button
    $('#confirmSubmit').click(function () {
        var firstName = $('#firstName').val();
        var surname = $('#surname').val();
        var dateOfBirth = $('#dateOfBirth').val();
        var age = $('#age').val();

        var data = {
            firstName: firstName,
            surname: surname,
            dateOfBirth: dateOfBirth,
            age: age,
            QuestionIDs: QuestionIDs,
            answers: answers

        };

        $.post('save-patient-response.php', data, function (response) {
            console.log('Server Response:', response);
            $('#CardReviewSubmission').hide();
            $('#CardSuccessMessage').show();



        }).fail(function (xhr, status, error) {
            console.error('Error:', status, error);
        });
    });



    showQuestion(currentQuestion);
});

