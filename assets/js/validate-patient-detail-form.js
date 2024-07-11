
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
    
