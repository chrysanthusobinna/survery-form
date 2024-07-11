-- Creating Table for Patients
CREATE TABLE Patients (
    PatientID INT IDENTITY(1,1) PRIMARY KEY,
    FirstName NVARCHAR(50),
    Surname NVARCHAR(50),
    DateOfBirth DATE,
    Age NVARCHAR(20),
    SubmissionDate DATETIME DEFAULT GETDATE()
);
GO

-- Create the PainInventoryQuestions table
CREATE TABLE PainInventoryQuestions (
    QuestionID INT PRIMARY KEY IDENTITY(1,1),
    Question VARCHAR(MAX),
    RangeMin INT,
    RangeMax INT,
    CountScore INT  
);
GO

-- Insert the questions into the PainInventoryQuestions table
INSERT INTO PainInventoryQuestions (Question, RangeMin, RangeMax, CountScore) VALUES
('How much relief have pain treatments or medications FROM THIS CLINIC provided?', 0, 100, 0),
('Please rate your pain based on the number that best describes your pain at its WORST in the past week.', 0, 10, 1),
('Please rate your pain based on the number that best describes your pain at its LEAST in the past week.', 0, 10, 1),
('Please rate your pain based on the number that best describes your pain on the Average.', 0, 10, 1),
('Please rate your pain based on the number that best describes your pain that tells how much pain you have RIGHT NOW.', 0, 10, 1),
('Based on the number that best describes how during the past week pain has INTERFERED with your: General Activity.', 0, 10, 1),
('Based on the number that best describes how during the past week pain has INTERFERED with your: Mood.', 0, 10, 1),
('Based on the number that best describes how during the past week pain has INTERFERED with your: Walking ability.', 0, 10, 1),
('Based on the number that best describes how during the past week pain has INTERFERED with your: Normal work (includes work both outside the home and housework).', 0, 10, 1),
('Based on the number that best describes how during the past week pain has INTERFERED with your: Relationships with other people.', 0, 10, 1),
('Based on the number that best describes how during the past week pain has INTERFERED with your: Sleep.', 0, 10, 1),
('Based on the number that best describes how during the past week pain has INTERFERED with your: Enjoyment of life.', 0, 10, 1);
GO

-- Creating Table for BriefPainInventoryResponses
CREATE TABLE BriefPainInventoryResponses (
    SurveyID INT IDENTITY(1,1) PRIMARY KEY,
    PatientID INT,
    QuestionID INT,
    Answer INT,
    FOREIGN KEY (PatientID) REFERENCES Patients(PatientID),
    FOREIGN KEY (QuestionID) REFERENCES PainInventoryQuestions(QuestionID)
);
GO

-- STORED PROCEDURES FOR CRUD FUNCTIONS

-- Stored Procedure for Viewing Pain Inventory Questions
CREATE PROCEDURE ViewPainInventoryQuestions
AS
BEGIN
    SELECT * FROM PainInventoryQuestions;
END
GO

-- Stored Procedure for Inserting Pain Inventory Question
CREATE PROCEDURE InsertPainInventoryQuestion
    @Question VARCHAR(MAX),
    @RangeMin INT,
    @RangeMax INT,
    @CountScore INT  
AS
BEGIN
    INSERT INTO PainInventoryQuestions (Question, RangeMin, RangeMax, CountScore)
    VALUES (@Question, @RangeMin, @RangeMax, @CountScore);
END
GO

-- Stored Procedure for Updating Pain Inventory Question
CREATE PROCEDURE UpdatePainInventoryQuestion
    @QuestionID INT,
    @Question VARCHAR(MAX),
    @RangeMin INT,
    @RangeMax INT,
    @CountScore INT 
AS
BEGIN
    UPDATE PainInventoryQuestions
    SET Question = @Question,
        RangeMin = @RangeMin,
        RangeMax = @RangeMax,
        CountScore = @CountScore
    WHERE QuestionID = @QuestionID;
END
GO

-- Stored Procedure for Deleting Pain Inventory Question
CREATE PROCEDURE DeletePainInventoryQuestion
    @QuestionID INT
AS
BEGIN
    DELETE FROM PainInventoryQuestions
    WHERE QuestionID = @QuestionID;
END
GO

-- Stored Procedure for Inserting a Patient Record
CREATE PROCEDURE InsertPatient
    @FirstName NVARCHAR(50),
    @Surname NVARCHAR(50),
    @DateOfBirth DATE,
    @Age NVARCHAR(20),
    @PatientID INT OUTPUT
AS
BEGIN
    INSERT INTO Patients (FirstName, Surname, DateOfBirth, Age)
    VALUES (@FirstName, @Surname, @DateOfBirth, @Age);

    SET @PatientID = SCOPE_IDENTITY();
    SELECT @PatientID AS PatientID;
END
GO

-- Stored Procedure for Inserting BriefPainInventoryResponses
CREATE PROCEDURE InsertResponse
    @PatientID INT,
    @QuestionID INT,
    @Answer INT
AS
BEGIN
    INSERT INTO BriefPainInventoryResponses (PatientID, QuestionID, Answer)
    VALUES (@PatientID, @QuestionID, @Answer);
END
GO

-- Stored Procedure for Deleting BriefPainInventoryResponses
CREATE PROCEDURE DeleteBriefPainInventoryResponses
    @PatientID INT
AS
BEGIN
    DELETE FROM BriefPainInventoryResponses
    WHERE PatientID = @PatientID;
END
GO

-- Stored Procedure for Deleting BriefPainInventoryResponses by QuestionID
CREATE PROCEDURE DeleteBriefPainInventoryResponsesByQuestionID
    @QuestionID INT
AS
BEGIN
    DELETE FROM BriefPainInventoryResponses
    WHERE QuestionID = @QuestionID;
END
GO


-- Stored Procedure for Deleting Patients
CREATE PROCEDURE DeletePatient
    @PatientID INT
AS
BEGIN
    DELETE FROM Patients
    WHERE PatientID = @PatientID;
END
GO

-- Stored Procedure for Updating Patient Details
CREATE PROCEDURE UpdatePatientDetails
    @PatientID INT,
    @FirstName NVARCHAR(50),
    @Surname NVARCHAR(50),
    @Age NVARCHAR(20),
    @DateOfBirth DATE
AS
BEGIN
    UPDATE Patients
    SET FirstName = @FirstName,
        Surname = @Surname,
        Age = @Age,
        DateOfBirth = @DateOfBirth
    WHERE PatientID = @PatientID;
END
GO

-- Stored Procedure for Getting All Records from the Patients Table
CREATE PROCEDURE GetAllPatients
AS
BEGIN
    SELECT PatientID, FirstName, Surname, DateOfBirth, Age, SubmissionDate
    FROM Patients;
END
GO

-- Stored Procedure to Get Patient Details by ID
CREATE PROCEDURE GetPatientDetailsByID
    @PatientID INT
AS
BEGIN
    SELECT PatientID, FirstName, Surname, DateOfBirth, Age, SubmissionDate
    FROM Patients
    WHERE PatientID = @PatientID;
END
GO

-- Stored Procedure to Get Brief Pain Inventory Responses for a Patient
CREATE PROCEDURE GetSurveyResponsesByPatientID
    @PatientID INT
AS
BEGIN
    SELECT b.SurveyID, b.QuestionID, q.Question, q.RangeMax, q.RangeMin, q.CountScore, b.Answer
    FROM BriefPainInventoryResponses b
    JOIN PainInventoryQuestions q ON b.QuestionID = q.QuestionID
    WHERE b.PatientID = @PatientID;
END
GO

-- Stored Procedure to Update a Single Brief Pain Inventory Response for a Patient
CREATE PROCEDURE UpdateBriefPainInventoryResponse
    @SurveyID INT,
    @ResponseAnswer INT
AS
BEGIN
    UPDATE BriefPainInventoryResponses
    SET Answer = @ResponseAnswer
    WHERE SurveyID = @SurveyID;
END
GO


-- Stored Procedure to Get Total Number of Patients
CREATE PROCEDURE GetTotalNumberOfPatients
AS
BEGIN
    SELECT COUNT(*) AS TotalPatients
    FROM Patients;
END
GO


-- Stored Procedure to Get Total Number of Pain Inventory Questions
CREATE PROCEDURE GetTotalNumberOfPainInventoryQuestions
AS
BEGIN
    SELECT COUNT(*) AS TotalQuestions
    FROM PainInventoryQuestions;
END
GO
