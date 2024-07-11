# The Walton Centre NHS Foundation Trust – Coding Challenge

## Technologies Used
1. Latest version of PHP (NO FRAMEWORK )
2. jQuery
3. Bootstrap
4. MSSQL
5. SQL Server Management Studio
6. IIS as the web server or XAMP

## Setup and Run the Application

### Database Setup
1. Open SQL Server Management Studio.
2. Create a new database.
3. Copy all the queries from `database/database.sql` and execute them to create the necessary tables and stored procedures.

### Environment Variables
1. Rename `.env.example` to `.env`.
2. Set the following variables in the `.env` file:
    ```
    DB_SERVER_NAME=
    DB_DATABASE=
    DB_UID=
    DB_PWD=
    ```

### Running the Website
The website is now ready to run.

### Features
- Users can view questions and respond to them seamlessly without page reloading.
- User details and responses are stored in the database.
- Admin page can be accessed by visiting `http://localhost/admin` where you can:
  - View responses
  - Edit responses
  - Delete responses
  - Add questions
  - Delete questions
  - Set range like min score and max score
  - Choose which question score should be counted or skipped when the total score is computed.

## Development Choices

### jQuery
I used jQuery to create a user-friendly form and seamless responses for users without page loading.

### jQuery DataTable Library
I utilized the jQuery DataTable library to make loading patients seamless, with integrated search and sort functionalities.

### Dynamic Website
  -The website is dynamic, allowing more questions to be added and providing flexibility in choosing which question scores should be counted or not.
  - Displaying one question at a time to improve the user experience and make the form more manageable.
  - Offering options for both single response edits and bulk response edits to streamline data management.




### Validation and Data Sanitization
To ensure data integrity and security:
- **Validation**: All input fields are validated to ensure that only correct and expected data is processed.
- **Data Sanitization**: Input data is sanitized to prevent injection attacks and maintain data consistency.

 

Please Feel free to reach out if you encounter any issues or have any questions about setting up and running the application.