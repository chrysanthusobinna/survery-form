<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Neuromodulation Form</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">

    <!-- jQuery UI -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

    <!-- DataTables Bootstrap 4 CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/scroller/2.4.3/css/scroller.bootstrap4.min.css">

</head>

<body>

    <!-- Header -->
    <header class="header d-flex align-items-center p-3 mb-4">
        <a class="logo" href="index.php">
            <h1 class="mb-0">ADMIN</h1>
        </a>
    </header>

    <div class="container mt-4">
        <div class="card card-outline shadow mb-3">
            <div class="card-body">

                <?php
                //FLASH MESSAGE
                
                $flash = getFlashMessage();

                if (is_array($flash)) {
                    $message        =   $flash['message'];
                    $type           =   $flash['type'];

                    if ($type === 'error') {
                        echo "<div class='alert alert-danger alert-dismissible'> <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a> <i class='fas fa-exclamation-circle'></i> $message  </div>";
                    } elseif ($type === 'success') {
                        echo "<div class='alert alert-success alert-dismissible'> <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a> <i class='far fa-check-circle'></i> $message  </div>";
                    }
                }

                ?>



                <div class="list-group">
                    <a href="index.php" class="list-group-item d-flex justify-content-between align-items-center">
                        Manage Patients Details and Responses
                        <span class="badge badge-primary badge-customx">
                            <?php echo getTotalNumberOfPatients($conn); ?>
                        </span>
                    </a>
                    <a href="manage-questions.php"
                        class="list-group-item d-flex justify-content-between align-items-center">
                        Manage Questions
                        <span class="badge badge-primary badge-customx">
                            <?php echo getTotalNumberOfPainInventoryQuestions($conn); ?>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </div>