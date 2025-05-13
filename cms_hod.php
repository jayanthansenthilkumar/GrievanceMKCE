<?php
require 'config.php';
include("fsession.php");

$hod_id =  $fac_id;

$role = $frole;

$hdept = "SELECT * FROM faculty WHERE id='$hod_id'";
$hdept_run = mysqli_query($db, $hdept);
$hdept_data = mysqli_fetch_array($hdept_run);
$dept = $hdept_data['dept'];
$sql = "
SELECT cd.*, f.name, f.dept
FROM complaints_detail cd
JOIN faculty f ON cd.faculty_id = f.id
WHERE cd.status = '2'AND f.dept = '$dept'
";
$sql1 = "
SELECT cd.*, f.name, f.dept
FROM complaints_detail cd
JOIN faculty f ON cd.faculty_id = f.id
WHERE cd.status IN (4, 6, 7, 10, 11, 13, 14, 15, 17, 18, 22) AND f.dept = '$dept'
";
$sql2 = "
SELECT cd.*, f.name, f.dept
FROM complaints_detail cd
JOIN faculty f ON cd.faculty_id = f.id
WHERE cd.status = '16' AND f.dept = '$dept'
";
$sql3 = "
SELECT cd.*, f.name, f.dept
FROM complaints_detail cd
JOIN faculty f ON cd.faculty_id = f.id
WHERE cd.status IN (19, 20, 23) AND f.dept = '$dept'
";
$result = mysqli_query($db, $sql);
$pending = mysqli_num_rows($result);
$result1 = mysqli_query($db, $sql1);
$approved = mysqli_num_rows($result1);
$result2 = mysqli_query($db, $sql2);
$completed = mysqli_num_rows($result2);
$result3 = mysqli_query($db, $sql3);
$rejected = mysqli_num_rows($result3);

$sql11 = "SELECT * FROM complaints_detail WHERE status IN (11,18,14) AND faculty_id = '$hod_id'";
$result11 = mysqli_query($db, $sql11);
$row_count11 = mysqli_num_rows($result11);
?>

<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>MIC</title>
    <link rel="icon" type="image/png" sizes="32x32" href="../image/icons/mkce_s.png">

    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="tabs.css">
    <link rel="stylesheet" href="cms_style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-5/bootstrap-5.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&family=Rubik:wght@300;400;500;700&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>


    <style>
        :root {
            --sidebar-width: 250px;
            --sidebar-collapsed-width: 70px;
            --topbar-height: 60px;
            --footer-height: 60px;
            --primary-color: #4e73df;
            --secondary-color: #858796;
            --success-color: #1cc88a;
            --dark-bg: #1a1c23;
            --light-bg: #f8f9fc;
            --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* General Styles with Enhanced Typography */

        /* Content Area Styles */
        .content {
            margin-left: var(--sidebar-width);
            padding-top: var(--topbar-height);
            transition: all 0.3s ease;
            min-height: 100vh;
        }

        /* Content Navigation */
        .content-nav {
            background: linear-gradient(45deg, #4e73df, #1cc88a);
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .content-nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            gap: 20px;
            overflow-x: auto;
        }

        .content-nav li a {
            color: white;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .content-nav li a:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .sidebar.collapsed+.content {
            margin-left: var(--sidebar-collapsed-width);
        }

        .breadcrumb-area {
            background: white;
            border-radius: 10px;
            box-shadow: var(--card-shadow);
            margin: 20px;
            padding: 15px 20px;
        }

        .breadcrumb-item a {
            color: var(--primary-color);
            text-decoration: none;
            transition: var(--transition);
        }

        .breadcrumb-item a:hover {
            color: #224abe;
        }



        /* Table Styles */



        .gradient-header {
            --bs-table-bg: transparent;
            --bs-table-color: white;
            background: linear-gradient(135deg, #4CAF50, #2196F3) !important;

            text-align: center;
            font-size: 0.9em;


        }


        td {
            text-align: left;
            font-size: 0.9em;
            vertical-align: middle;
            /* For vertical alignment */
        }






        /* Responsive Styles */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: var(--sidebar-width) !important;
            }

            .sidebar.mobile-show {
                transform: translateX(0);
            }

            .topbar {
                left: 0 !important;
            }

            .mobile-overlay {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 999;
                display: none;
            }

            .mobile-overlay.show {
                display: block;
            }

            .content {
                margin-left: 0 !important;
            }

            .brand-logo {
                display: block;
            }

            .user-profile {
                margin-left: 0;
            }

            .sidebar .logo {
                justify-content: center;
            }

            .sidebar .menu-item span,
            .sidebar .has-submenu::after {
                display: block !important;
            }

            body.sidebar-open {
                overflow: hidden;
            }

            .footer {
                left: 0 !important;
            }

            .content-nav ul {
                flex-wrap: nowrap;
                overflow-x: auto;
                padding-bottom: 5px;
            }

            .content-nav ul::-webkit-scrollbar {
                height: 4px;
            }

            .content-nav ul::-webkit-scrollbar-thumb {
                background: rgba(255, 255, 255, 0.3);
                border-radius: 2px;
            }
        }

        .container-fluid {
            padding: 20px;
        }


        /* loader */
        .loader-container {
            position: fixed;
            left: var(--sidebar-width);
            right: 0;
            top: var(--topbar-height);
            bottom: var(--footer-height);
            background: rgba(255, 255, 255, 0.95);
            display: flex;
            /* Changed from 'none' to show by default */
            justify-content: center;
            align-items: center;
            z-index: 1000;
            transition: left 0.3s ease;
        }

        .sidebar.collapsed+.content .loader-container {
            left: var(--sidebar-collapsed-width);
        }

        @media (max-width: 768px) {
            .loader-container {
                left: 0;
            }
        }

        /* Hide loader when done */
        .loader-container.hide {
            display: none;
        }

        /* Loader Animation */
        .loader {
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-radius: 50%;
            border-top: 5px solid var(--primary-color);
            border-right: 5px solid var(--success-color);
            border-bottom: 5px solid var(--primary-color);
            border-left: 5px solid var(--success-color);
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .breadcrumb-area {
            background-image: linear-gradient(to top, #fff1eb 0%, #ace0f9 100%);
            border-radius: 10px;
            box-shadow: var(--card-shadow);
            margin: 20px;
            padding: 15px 20px;
        }

        .breadcrumb-item a {
            color: var(--primary-color);
            text-decoration: none;
            transition: var(--transition);
        }

        .breadcrumb-item a:hover {
            color: #224abe;
        }
    </style>

</head>

<body>
    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Main Content -->
    <div class="content">

        <div class="loader-container" id="loaderContainer">
            <div class="loader"></div>
        </div>

        <!-- Topbar -->
        <?php include 'topbar.php'; ?>

        <!-- Breadcrumb -->
        <div class="breadcrumb-area custom-gradient">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Complaints</li>
                </ol>
            </nav>
        </div>

        <!-- Content Area -->

        <div class="container-fluid">

            <div class="custom-tabs">
                <ul class="nav nav-tabs" role="tablist">

                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" data-bs-toggle="tab" id="edit-bus-tab" href="#pending"
                            role="tab" aria-selected="true">
                            <span class="hidden-xs-down" style="font-size: 0.9em;"></span>
                            <div id="navref1">
                                <span class="hidden-xs-down">
                                    <i class="fas fa-clock"></i>
                                    &nbsp Pending (<?php echo $pending; ?>)
                                </span>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" data-bs-toggle="tab" id="delete-bus-tab" href="#approved"
                            role="tab" aria-selected="true">
                            <span class="hidden-xs-down" style="font-size: 0.9em;"></span>
                            <div id="navref2">
                                <span class="hidden-xs-down">
                                    <i class="fas fa-check"></i>
                                    &nbsp Approved (<?php echo $approved; ?>)
                                </span>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" data-bs-toggle="tab" id="route-bus-tab" href="#completed"
                            role="tab" aria-selected="true">
                            <span class="hidden-xs-down" style="font-size: 0.9em;"></span>
                            <div id="navref3">
                                <span class="hidden-xs-down">
                                    <i class="fa-solid fa-check-double"></i>
                                    &nbsp Completed (<?php echo $completed; ?>)
                                </span>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" data-bs-toggle="tab" id="settings-bus-tab" href="#rejected"
                            role="tab" aria-selected="true">
                            <span class="hidden-xs-down" style="font-size: 0.9em;"></span>
                            <div id="navref4">
                                <span class="hidden-xs-down">
                                    <i class="fa-solid fa-xmark"></i>
                                    &nbsp Rejected (<?php echo $rejected; ?>)
                                </span>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" data-bs-toggle="tab" id="fleet-management-bus-tab" href="#feedback"
                            role="tab" aria-selected="true">
                            <span class="hidden-xs-down" style="font-size: 0.9em;"></span>
                            <div id="navref33">
                                <span class="hidden-xs-down">
                                    <i class="fa-solid fa-clipboard"></i>
                                    &nbsp Feedback (<?php echo $row_count11; ?>)
                                </span>
                            </div>
                        </a>
                    </li>
                </ul>

                <!-------------------------dashboard------------------------------>

                <div class="tab-content">
                    <!-------------------------pending tab---------------------------->
                    <div class="tab-pane p-20  active show" id="pending" role="tabpanel">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>
                                            Raise Complaint
                                            <button type="button" class="btn btn-info float-end fac"
                                                data-bs-toggle="modal"
                                                data-bs-target="#raisemodal">Raise Complant</button>
                                            <br>
                                        </h4>
                                    </div>

                                    <div class="card-body">
                                        <div class="table-container">
                                            <table id="myTable1"
                                                class="table table-bordered table-striped fixed-size-table">
                                                <thead class="gradient-header">
                                                    <tr>
                                                        <th class="pending status text-center">
                                                            <b>S.No</b>
                                                        </th>
                                                        <th class="text-center">
                                                            <b>Date Registered</b>
                                                        </th>
                                                        <th class="text-center">
                                                            <b>Faculty Name</b>
                                                        </th>
                                                        <th class="text-center">
                                                            <b>Problem Description</b>
                                                        </th>
                                                        <th class="text-center">
                                                            <b>Image</b>
                                                        </th>
                                                        <th class="text-center">
                                                            <b>Forwarded Reason</b>
                                                        </th>
                                                        <th class="text-center">
                                                            <b>Action</b>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $id = 1;
                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                    ?>
                                                        <tr>
                                                            <td class="text-center">
                                                                <?php echo $id; ?>
                                                            </td>
                                                            <td>
                                                                <center>
                                                                    <?php echo $row['date_of_reg']; ?>
                                                                </center>
                                                            </td>
                                                            <td>
                                                                <center>
                                                                    <button type="button"
                                                                        class="btn btn-link faculty"
                                                                        id="facultyinfo"
                                                                        data-value="<?php echo $row['fac_id']; ?>"
                                                                        data-bs-toggle="modal"
                                                                        value="<?php echo $row['id']; ?>"
                                                                        data-bs-target="#facultymodal"
                                                                        style="text-decoration:none;"><?php echo $row['name']; ?>
                                                                    </button>
                                                                </center>
                                                            </td>
                                                            <td>
                                                                <center>
                                                                    <button type="button"
                                                                        data-bs-problemid
                                                                        class="btn btndesc"
                                                                        id="seeproblem"
                                                                        data-bs-toggle="modal"
                                                                        value='<?php echo $row['id']; ?>'
                                                                        data-bs-target="#probdesc">
                                                                        <i class="fas fa-solid fa-eye"
                                                                            style="font-size: 20px;"></i>
                                                                    </button>
                                                                </center>
                                                            </td>
                                                            <td>
                                                                <center>
                                                                    <button type="button"
                                                                        class="btn showImage"
                                                                        value="<?php echo $row['id']; ?>"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#imageModal1"
                                                                        data-bs-task-id='<?php echo htmlspecialchars($row['id']); ?>'>
                                                                        <i class="fas fa-image"
                                                                            style="font-size: 20px;"></i>
                                                                    </button>
                                                                </center>
                                                            </td>

                                                            <td>
                                                                <center>
                                                                    <?php echo $row['h_reason']; ?>
                                                                </center>
                                                            </td>

                                                            <td>
                                                                <center>
                                                                    <button type="button"
                                                                        value="<?php echo $row['id']; ?>"
                                                                        id="detail_id"
                                                                        class="btn btn-success btnapprove">
                                                                        <i class="fas fa-check"></i>
                                                                    </button>
                                                                    <button type="button"
                                                                        value="<?php echo $row['id']; ?>"
                                                                        class="btn btn-danger btnreject"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#rejectmodal">
                                                                        <i class="fas fa-times"></i>
                                                                    </button>
                                                                </center>
                                                            </td>
                                                        <?php
                                                        $id++;
                                                    }
                                                        ?>
                                                        </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--------------approved tab-------------------->
                    <div class="tab-pane p-20" id="approved" role="tabpanel">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-container">
                                            <table id="myTable2"
                                                class="table table-bordered table-striped fixed-size-table">
                                                <thead class="gradient-header">
                                                    <tr>
                                                        <th class="pending status text-center">
                                                            <b>S.No</b>
                                                        </th>
                                                        <th class="text-center">
                                                            <b>Date Registered</b>
                                                        </th>
                                                        <th class="text-center">
                                                            <b>Faculty Name</b>
                                                        </th>
                                                        <th class="text-center">
                                                            <b>Problem Description</b>
                                                        </th>
                                                        <th class="text-center">
                                                            <b>Image</b>
                                                        </th>
                                                        <th class="text-center">
                                                            <b>Status</b>
                                                        </th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    <?php
                                                    $id = 1;
                                                    while ($row = mysqli_fetch_assoc($result1)) {
                                                    ?>
                                                        <tr>
                                                            <td class="text-center">
                                                                <?php echo $id; ?>
                                                            </td>
                                                            <td>
                                                                <center>
                                                                    <?php echo $row['date_of_reg']; ?>
                                                                </center>
                                                            </td>
                                                            <td>
                                                                <center>
                                                                    <button type="button"
                                                                        class="btn btn-link faculty"
                                                                        id="facultyinfo"
                                                                        data-value="<?php echo $row['fac_id']; ?>"
                                                                        data-bs-toggle="modal"
                                                                        value="<?php echo $row['id']; ?>"
                                                                        data-bs-target="#facultymodal"
                                                                        style="text-decoration:none;"><?php echo $row['name']; ?></button>
                                                                </center>
                                                            </td>
                                                            <td>
                                                                <center>
                                                                    <button type="button"
                                                                        value='<?php echo $row['id']; ?>'
                                                                        class="btn btndesc"
                                                                        data-bs-toggle="modal"
                                                                        id="seeproblem"
                                                                        data-bs-target="#probdesc">
                                                                        <i class="fas fa-solid fa-eye"
                                                                            style="font-size: 20px;"></i>
                                                                    </button>
                                                                </center>
                                                            </td>
                                                            <td>
                                                                <center>
                                                                    <button type="button"
                                                                        class="btn showImage"
                                                                        value="<?php echo $row['id']; ?>"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#imageModal1"
                                                                        data-bs-task-id='<?php echo htmlspecialchars($row['id']); ?>'>
                                                                        <i class="fas fa-image"
                                                                            style="font-size: 20px;"></i>
                                                                    </button>
                                                                </center>
                                                            </td>

                                                            <td>
                                                                <center>
                                                                    <?php
                                                                    $statusMessages = [
                                                                        2 => 'Forwarded to HOD',
                                                                        4 => 'Forwaded to Estate Officer',
                                                                        5 => 'Rejected By HOD',
                                                                        6 => 'Sent to principal for approval',
                                                                        8 => 'Accepted by Principal',
                                                                        9 => 'Approved by Manager',
                                                                        10 => 'Approved By Worker',
                                                                        11 => 'Waiting for Approval',
                                                                        13 => 'Sent to Faculty Infra Coordinator for completion',
                                                                        14 => 'Feedback by faculty',
                                                                        15 => 'Work is Reassigned',
                                                                        16 => 'Work is Completed',
                                                                        19 => 'Rejected By Principal',
                                                                        20 => 'Rejected by Manager',
                                                                        22 => 'Accepted by Estate Officer',
                                                                        23 => 'Rejected By Estate Officer',
                                                                    ];

                                                                    $status = $row['status'];
                                                                    $statusMessage = $statusMessages[$status] ?? 'Unknown status';
                                                                    ?>
                                                                    <button type="button"
                                                                        class="btn btn-secondary">
                                                                        <?php echo $statusMessage; ?>
                                                                    </button>
                                                                </center>
                                                            </td>
                                                        </tr>
                                                    <?php
                                                        $id++;
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!------------------Feedback Table----------------->
                    <div class="tab-pane p-20" id="feedback" role="tabpanel">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="feedbackTable"
                                                class="table table-bordered table-striped">
                                                <thead class="gradient-header">
                                                    <tr>
                                                        <th class="text-center"><b>S.No</b></th>
                                                        <th class="text-center"><b>Problem_id</th>
                                                        <th class="text-center"><b>Venue</b></th>
                                                        <th class="text-center"><b>Problem
                                                                description</b></th>
                                                        <th class="text-center"><b>Date Of
                                                                submission</b></th>
                                                        <th class="text-center"><b>Deadline</b></th>
                                                        <th class="text-center"><b>After Image</b>
                                                        </th>
                                                        <th class="text-center"><b>Worker
                                                                Details</b></th>
                                                        <th class="text-center"><b>Feedback</b></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $s = 1;
                                                    while ($row = mysqli_fetch_assoc($result11)) {
                                                    ?>
                                                        <tr>
                                                            <td class="text-center"><?php echo $s; ?>
                                                            </td>
                                                            <td class="text-center">
                                                                <?php echo $row['id']; ?></td>
                                                            <td class="text-center">
                                                                <?php echo $row['block_venue']; ?></td>
                                                            <td class="text-center">
                                                                <?php echo $row['problem_description']; ?>
                                                            </td>
                                                            <td class="text-center">
                                                                <?php echo $row['date_of_reg']; ?></td>
                                                            <td class="text-center">
                                                                <?php if ($row['extend_date'] == 1) { ?>
                                                                    <button type="button"
                                                                        class="btn btn-danger extenddeadline"
                                                                        id="extendbutton"
                                                                        value="<?php echo $row['id']; ?>"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#extendModal"
                                                                        data-bs-reason="<?php echo $row['extend_reason']; ?>">
                                                                        <?php echo $row['days_to_complete']; ?>
                                                                    </button>
                                                                <?php } else { ?>
                                                                    <?php echo $row['days_to_complete']; ?>
                                                                <?php } ?>
                                                            </td>
                                                            <td class="text-center">
                                                                <button type="button"
                                                                    value="<?php echo htmlspecialchars($row['id']); ?>"
                                                                    class="btn viewafterimgcomp"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#aftercomp"
                                                                    data-bs-imgs-id='<?php echo htmlspecialchars($row['id']); ?>'>
                                                                    <i class="fas fa-image"
                                                                        style="font-size: 20px;"></i>
                                                                </button>
                                                            </td>


                                                            <td class="text-center">
                                                                <button type="button"
                                                                    class="btn btn-light showWorkerDetails"
                                                                    value="<?php echo $row['id']; ?>">
                                                                    <?php
                                                                    $prblm_id = $row['id'];
                                                                    $querry = "SELECT worker_first_name FROM worker_details WHERE worker_id = ( SELECT worker_dept FROM manager WHERE problem_id = '$prblm_id')";
                                                                    $querry_run = mysqli_query($db, $querry);
                                                                    $worker_name = mysqli_fetch_array($querry_run);
                                                                    if ($worker_name['worker_first_name'] != null) {
                                                                        echo $worker_name['worker_first_name'];
                                                                    } else {
                                                                        echo "NA";
                                                                    }
                                                                    ?>
                                                                </button>
                                                            </td>
                                                            <td class="text-center">

                                                                <?php
                                                                if ($row['status'] == 14) {
                                                                ?>
                                                                    <button
                                                                        class="btn btn-success">Submitted</button>

                                                                <?php
                                                                } else {
                                                                ?>
                                                                    <!-- Button to open the feedback modal -->
                                                                    <button type="button"
                                                                        class="btn btn-info feedbackBtn"
                                                                        data-bs-problem-id="<?php echo $row['id']; ?>"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#feedback_modal">Feedback</button>

                                                                <?php
                                                                }
                                                                ?>

                                                            </td>
                                                        </tr>
                                                    <?php
                                                        $s++;
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>



                    <!-----------completed tab----------->
                    <div class="tab-pane p-20" id="completed" role="tabpanel">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-container">
                                            <table id="myTable3"
                                                class="table table-bordered table-striped fixed-size-table">
                                                <thead class="gradient-header">
                                                    <tr>
                                                        <th class="pending status text-center">
                                                            <b>S.No</b>
                                                        </th>
                                                        <th class="text-center">
                                                            <b>Date Registered</b>
                                                        </th>
                                                        <th class="text-center">
                                                            <b>Faculty Name</b>
                                                        </th>
                                                        <th class="text-center">
                                                            <b>Problem Description</b>
                                                        </th>
                                                        <th class="text-center">
                                                            <b>Before Image</b>
                                                        </th>
                                                        <th class="text-center">
                                                            <b>After Image</b>
                                                        </th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $id = 1;
                                                    while ($row = mysqli_fetch_assoc($result2)) {
                                                    ?>
                                                        <tr>
                                                            <td class="text-center">
                                                                <?php echo $id; ?>
                                                            </td>
                                                            <td>
                                                                <center>
                                                                    <?php echo $row['date_of_reg']; ?>
                                                                </center>
                                                            </td>
                                                            <td>
                                                                <center>
                                                                    <button type="button"
                                                                        class="btn btn-link faculty"
                                                                        id="facultyinfo"
                                                                        data-value="<?php echo $row['fac_id']; ?>"
                                                                        data-bs-toggle="modal"
                                                                        value="<?php echo $row['id']; ?>"
                                                                        data-bs-target="#facultymodal"
                                                                        style="text-decoration:none;"><?php echo $row['name']; ?></button>
                                                                </center>
                                                            </td>
                                                            <td>
                                                                <center>
                                                                    <button type="button"
                                                                        value="<?php echo $row['id']; ?>"
                                                                        class="btn btndesc"
                                                                        data-bs-toggle="modal"
                                                                        id="seeproblem"
                                                                        data-bs-target="#probdesc">
                                                                        <i class="fas fa-solid fa-eye"
                                                                            style="font-size: 20px;"></i>
                                                                    </button>
                                                                </center>
                                                            </td>
                                                            <td>
                                                                <center>
                                                                    <button type="button"
                                                                        class="btn showImage"
                                                                        value="<?php echo $row['id']; ?>"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#imageModal1"
                                                                        data-bs-task-id='<?php echo htmlspecialchars($row['id']); ?>'>
                                                                        <i class="fas fa-image"
                                                                            style="font-size: 20px;"></i>
                                                                    </button>
                                                                </center>
                                                            </td>
                                                            <td>
                                                                <center>
                                                                    <button type="button"
                                                                        value="<?php echo htmlspecialchars($row['id']); ?>"
                                                                        class="btn viewafterimgcomp"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#aftercomp"
                                                                        data-bs-imgs-id='<?php echo htmlspecialchars($row['id']); ?>'>
                                                                        <i class="fas fa-image"
                                                                            style="font-size: 20px;"></i>
                                                                    </button>
                                                                </center>
                                                            </td>

                                                        </tr>
                                                    <?php
                                                        $id++;
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!----------rejected tab------->
                    <div class="tab-pane p-20" id="rejected" role="tabpanel">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-container">
                                            <table id="myTable4"
                                                class="table table-bordered table-striped fixed-size-table">
                                                <thead class="gradient-header">
                                                    <tr>
                                                        <th class="pending status text-center">
                                                            <b>S.No</b>
                                                        </th>
                                                        <th class="text-center">
                                                            <b>Date Registered</b>
                                                        </th>
                                                        <th class="text-center">
                                                            <b>Faculty Name</b>
                                                        </th>
                                                        <th class="text-center">
                                                            <b>Problem Description</b>
                                                        </th>
                                                        <th class="text-center">
                                                            <b>Image</b>
                                                        </th>
                                                        <th class="text-center">
                                                            <b>Reason</b>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $id = 1;
                                                    while ($row = mysqli_fetch_assoc($result3)) {
                                                    ?>
                                                        <tr>
                                                            <td class="text-center">
                                                                <?php echo $id; ?>
                                                            </td>
                                                            <td>
                                                                <center>
                                                                    <?php echo $row['date_of_reg']; ?>
                                                                </center>
                                                            </td>
                                                            <td>
                                                                <center>
                                                                    <button type="button"
                                                                        class="btn btn-link faculty"
                                                                        id="facultyinfo"
                                                                        data-value="<?php echo $row['fac_id']; ?>"
                                                                        data-bs-toggle="modal"
                                                                        value="<?php echo $row['id']; ?>"
                                                                        data-bs-target="#facultymodal"
                                                                        style="text-decoration:none;"><?php echo $row['name']; ?></button>
                                                                </center>
                                                            </td>
                                                            <td>
                                                                <center>
                                                                    <button type="button"
                                                                        value="<?php echo $row['id']; ?>"
                                                                        class="btn btndesc"
                                                                        data-bs-toggle="modal"
                                                                        id="seeproblem"
                                                                        data-bs-target="#probdesc">
                                                                        <i class="fas fa-solid fa-eye"
                                                                            style="font-size: 20px;"></i>
                                                                    </button>
                                                                </center>
                                                            </td>
                                                            <td>
                                                                <center>
                                                                    <button type="button"
                                                                        value="<?php echo $row['id']; ?>"
                                                                        class="btn showImage"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#imageModal1"
                                                                        data-bs-task-id='<?php echo htmlspecialchars($row['id']); ?>'>
                                                                        <i class="fas fa-image"
                                                                            style="font-size: 20px;"></i>
                                                                    </button>
                                                                </center>
                                                            </td>
                                                            <td>
                                                                <center>
                                                                    <button type="button"
                                                                        value="<?php echo $row['id']; ?>"
                                                                        class="btn btn-danger btnrejfeed"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#problemrejected"
                                                                        id="rejectedfeedback">
                                                                        <i class="fas fa-solid fa-comment"
                                                                            style="font-size: 20px; width:40px;"></i>
                                                                    </button>
                                                                </center>
                                                            </td>
                                                        </tr>
                                                    <?php
                                                        $id++;
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>





    </div>
    <!-- Footer -->
    <?php include 'footer.php'; ?>
    </div>

    <!------------Rejected Feedback modal----->
    <div class="modal fade" id="rejectmodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"
                    style="background: linear-gradient(to bottom right, #cc66ff 1%, #0033cc 100%); color: white;">
                    <h5 class="modal-title" id="exampleModalLabel">
                        Reason for rejection</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form id="rejectdetails">
                    <div class="modal-body" style="font-size:larger;">
                        <textarea class="form-control" placeholder="Enter Reason" name="rejfeed"
                            style="width:460px;height: 180px; resize:none" required></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Worker Details Modal -->
    <div class="modal fade" id="workerModal" tabindex="-1" aria-labelledby="workerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"
                    style="background:linear-gradient(to bottom right, #cc66ff 1%, #0033cc 100%);background-color:#7460ee;">
                    <h5 class="modal-title" id="exampleModalLabel">Worker Phone</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="box"
                        style="background-color: #f7f7f7; border: 1px solid #ccc; padding: 15px; margin-bottom: 10px; border-radius: 5px;">
                        <p><strong>Contact:</strong> <span id="workerContact"></span></p>
                    </div>
                    <div class="d-flex justify-content-end">
                        <a href="#" id="callWorkerBtn" class="btn btn-success">Call Worker</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Feedback Modal -->
    <div class="modal fade" id="feedback_modal" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"
                    style="background:linear-gradient(to bottom right, #cc66ff 1%, #0033cc 100%);background-color:#7460ee;">
                    <h5 class="modal-title" id="exampleModalLabel">Feedback Form</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="add_feedback">
                        <input type="hidden" name="id" id="feedback_id"> <!-- Hidden input for id -->
                        <div class="mb-3">
                            <label for="satisfaction" class="form-label">Satisfaction</label>
                            <select name="satisfaction" id="satisfaction" class="form-control" required>
                                <option value="" disabled selected>Select an option</option>
                                <option value="Satisfied">Satisfied</option>
                                <option value="Not Satisfied">Reassign</option>
                            </select>
                        </div>
                        <div class="stars" id="star-rating">
                            <h5>Give Rating:</h5>
                            <span data-value="1">&#9733;</span>
                            <span data-value="2">&#9733;</span>
                            <span data-value="3">&#9733;</span>
                            <span data-value="4">&#9733;</span>
                            <span data-value="5">&#9733;</span>
                        </div>
                        <p id="rating-value">Rating: <span id="ratevalue">0</span></p>

                        <div class="mb-3">
                            <label for="feedback" class="form-label">Feedback</label>
                            <textarea name="feedback" id="feedback" class="form-control" placeholder="Enter Feedback"
                                style="width: 100%; height: 150px;"></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!--faculty info modal-->
    <div class="modal fade" id="facultymodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"
                    style="background: linear-gradient(to bottom right, #cc66ff 1%, #0033cc 100%); color: white;">
                    <h5 class="modal-title" id="exampleModalLabel">Faculty Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body"
                    style="padding: 15px; font-size: 1.1em; color: #333; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
                    <ol class="list-group list-group-numbered" style="margin-bottom: 0;">
                        <li class="list-group-item d-flex justify-content-between align-items-start"
                            style="padding: 10px; background-color: #fff;">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold" style="font-size: 1.2em; font-weight: 600; color: #007bff;">Faculty
                                    Name</div>
                                <b><span id="faculty_name" style="color: #555;"></span></b>
                            </div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-start"
                            style="padding: 10px; background-color: #fff;">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold" style="font-size: 1.2em; font-weight: 600; color: #007bff;">Faculty
                                    mobile</div>
                                <b><span id="faculty_mobile" style="color: #555;"></span></b>
                            </div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-start"
                            style="padding: 10px; background-color: #fff;">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold" style="font-size: 1.2em; font-weight: 600; color: #007bff;">Faculty
                                    Email</div>
                                <b><span id="faculty_email" style="color: #555;"></span></b>
                            </div>
                        </li>

                    </ol>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!---view problem description modal-->
    <div class="modal fade" id="probdesc" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"
                    style="background: linear-gradient(to bottom right, #cc66ff 1%, #0033cc 100%); color: white;">
                    <h5 class="modal-title" id="exampleModalLabel">
                        Problem Description</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form id="addnewdetails">
                    <div class="modal-body"
                        style="padding: 15px; font-size: 1.1em; color: #333; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
                        <ol class="list-group list-group-numbered" style="margin-bottom: 0;">
                            <li class="list-group-item d-flex justify-content-between align-items-start"
                                style="padding: 10px; background-color: #fff;">
                                <div class="ms-2 me-auto">
                                    <div class="fw-bold" style="font-size: 1.2em; font-weight: 600; color: #007bff;">
                                        Problem ID</div>
                                    <b><span id="id" style="color: #555;"></span></b>
                                </div>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-start"
                                style="padding: 10px; background-color: #fff;">
                                <div class="ms-2 me-auto">
                                    <div class="fw-bold" style="font-size: 1.2em; font-weight: 600; color: #007bff;">
                                        Type of Problem</div>
                                    <b><span id="type_of_problem" style="color: #555;"></span></b>
                                </div>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-start"
                                style="padding: 10px; background-color: #fff;">
                                <div class="ms-2 me-auto">
                                    <div class="fw-bold" style="font-size: 1.2em; font-weight: 600; color: #007bff;">
                                        Block</div>
                                    <b><span id="block_venue" style="color: #555;"></span></b>
                                </div>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-start"
                                style="padding: 10px; background-color: #fff;">
                                <div class="ms-2 me-auto">
                                    <div class="fw-bold" style="font-size: 1.2em; font-weight: 600; color: #007bff;">
                                        Venue Name</div>
                                    <b><span id="venue_name" style="color: #555;"></span></b>
                                </div>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-start"
                                style="padding: 10px; background-color: #fff;">
                                <div class="ms-2 me-auto">
                                    <div class="fw-bold" style="font-size: 1.2em; font-weight: 600; color: #007bff;">
                                        Problem Description</div>
                                    <b><span id="pd" style="color: #555;padding-top:5px;"></span></b>
                                </div>
                            </li>
                        </ol>
                    </div>
                </form>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- After Image Modal -->
    <div class="modal fade" id="afterImageModal" tabindex="-1" role="dialog" aria-labelledby="afterImageModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="afterImageModalLabel">After Picture</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage2" src="" alt="After" class="img-fluid">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Before Image Modal -->

    <div class="modal fade" id="bmodalImage" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"
                    style="background: linear-gradient(to bottom right, #cc66ff 1%, #0033cc 100%); color: white;">
                    <h5 class="modal-title" id="exampleModalLabel">
                        Before Image</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img id="bimg" src="" alt="Image Preview" style="max-width: 100%;" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Raise Complaint -->
    <div class="modal fade" id="raisemodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Raise Complaint</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form id="addnewuser" enctype="multipart/form-data" onsubmit="handleSubmit(event)">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="block" class="form-label">Block <span style="color: red;">*</span></label>
                            <input type="text" class="form-control" name="block_venue" placeholder="Eg:RK-206" required>
                        </div>
                        <div class="mb-3">
                            <label for="venue" class="form-label">Venue <span style="color: red;">*</span></label>
                            <select id="dropdown" class="form-control" name="venue_name" onchange="checkIfOthers()"
                                required>
                                <option value="">Select</option>
                                <option value="class">Class Room</option>
                                <option value="department">Department</option>
                                <option value="lab">Lab</option>
                                <option value="staff_room">Staff Room</option>
                                <option id="oth" value="Other">Others</option>
                            </select>
                        </div>

                        <div id="othersInput" style="display: none;">
                            <label class="form-label" for="otherValue">Please specify: <span
                                    style="color: red;">*</span></label>
                            <input class="form-control" type="text" id="otherValue" name="otherValue"> <br>
                        </div>

                        <div class="mb-3">
                            <label for="type_of_problem" class="form-label">Type of Problem <span
                                    style="color: red;">*</span></label>
                            <select class="form-control" name="type_of_problem" required>
                                <option value="">Select</option>
                                <option value="electrical">ELECTRICAL</option>
                                <option value="civil">CIVIL</option>
                                <option value="itkm">ITKM</option>
                                <option value="transport">TRANSPORT</option>
                                <option value="house">HOUSE KEEPING</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Problem Description <span
                                    style="color: red;">*</span></label>
                            <input type="text" class="form-control" name="problem_description"
                                placeholder="Enter Description" required>
                        </div>
                        <div class="mb-3">
                            <label for="images" class="form-label">Image <span style="color: red;">*</span></label>
                            <input type="file" class="form-control" name="images" id="images"
                                onchange="validateSize(this)" required>
                        </div>
                        <div class="mb-3">
                            <input type="hidden" class="form-control" name="date_of_reg" id="date_of_reg">
                        </div>
                    </div>
                    <input type="hidden" name="status" value="2">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!------------Rejected Reason modal-------------->
    <div class="modal fade" id="problemrejected" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"
                    style="background: linear-gradient(to bottom right, #cc66ff 1%, #0033cc 100%); color: white;">
                    <h5 class="modal-title" id="exampleModalLabel">Reason for Rejection</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form id="addnewdetails">
                    <div class="modal-body"
                        style="padding: 15px; font-size: 1.1em; color: #333; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
                        <ol class="list-group list-group-numbered" style="margin-bottom: 0;">
                            <li class="list-group-item d-flex justify-content-between align-items-start"
                                style="padding: 10px; background-color: #fff;">
                                <div class="ms-2 me-auto">
                                    <div class="fw-bold" style="font-size: 1.2em; font-weight: 600; color: #007bff;">
                                        Rejected By</div>
                                    <b><span id="pdrej2" style="color: #555;"></span></b>
                                </div>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-start"
                                style="padding: 10px; background-color: #fff;">
                                <div class="ms-2 me-auto">
                                    <div class="fw-bold" style="font-size: 1.2em; font-weight: 600; color: #007bff;">
                                        Reason</div>
                                    <b><span id="rejby" style="color: #555;"></span></b>
                                </div>
                            </li>
                        </ol>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <link r el="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs/build/css/alertify.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/alertifyjs/build/alertify.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>


    <!-- Set Today date in Raise Complaint-->
    <script>
        var today = new Date().toISOString().split('T')[0];
        var dateInput = document.getElementById('date_of_reg');
        dateInput.setAttribute('min', today);
        dateInput.setAttribute('max', today);
        dateInput.value = today;
    </script>

    <!--file size and type -->
    <script>
        function validateSize(input) {
            const filesize = input.files[0].size / 1024; // Size in KB
            var ext = input.value.split(".");
            ext = ext[ext.length - 1].toLowerCase();
            var arrayExtensions = ["jpg", "jpeg", "png"];
            if (arrayExtensions.lastIndexOf(ext) == -1) {
                swal("Invalid Image Format, Only .jpeg, .jpg, .png format allowed", "", "error");
                $(input).val('');
            } else if (filesize > 2048) {
                swal("File is too large, Maximum 2 MB is allowed", "", "error");
                $(input).val('');
            }
        }

        //raise complaint others field
        function checkIfOthers() {
            const dropdown = document.getElementById('dropdown');
            const othersInput = document.getElementById('othersInput');

            // Show the input field if "Others" is selected
            if (dropdown.value === 'Other') {
                othersInput.style.display = 'block';
            } else {
                othersInput.style.display = 'none';
            }
        }

        function handleSubmit(event) {
            event.preventDefault(); // Prevent form submission for demo purposes
            const dropdown = document.getElementById('dropdown');
            const selectedValue = dropdown.value;
            let finalValue;

            // Get the appropriate value based on the dropdown selection
            if (selectedValue === 'Other') {
                finalValue = document.getElementById('otherValue').value;
            } else {
                finalValue = selectedValue;
            }

            console.log("Selected Category:", finalValue);
            // You can then send this data to the backend or process it further
            $("#oth").val(finalValue);
        }
    </script>

    <script>
        //Tool Tip
        $(function() {
            // Initialize the tooltip
            $('[data-bs-toggle="tooltip"]').tooltip();

            // You can also set options manually if needed
            $('.btnreject').tooltip({
                placement: 'top',
                title: 'Reject'
            });
        });

        $(function() {
            // Initialize the tooltip
            $('[data-bs-toggle="tooltip"]').tooltip();

            // You can also set options manually if needed
            $('.btnrejfeed').tooltip({
                placement: 'top',
                title: 'Rejected Reason'
            });
        });

        $(function() {
            // Initialize the tooltip
            $('[data-bs-toggle="tooltip"]').tooltip();

            // You can also set options manually if needed
            $('.btndesc').tooltip({
                placement: 'top',
                title: 'Problem Description'
            });
        });

        $(function() {
            // Initialize the tooltip
            $('[data-bs-toggle="tooltip"]').tooltip();

            // You can also set options manually if needed
            $('.viewafterimgcomp').tooltip({
                placement: 'top',
                title: 'After Image'
            });
        });

        $(function() {
            // Initialize the tooltip
            $('[data-bs-toggle="tooltip"]').tooltip();

            // You can also set options manually if needed
            $('.btnraisecomp').tooltip({
                placement: 'top',
                title: 'Raise Complaint'
            });
        });

        $(function() {
            // Initialize the tooltip
            $('[data-bs-toggle="tooltip"]').tooltip();

            // You can also set options manually if needed
            $('.btnapprove').tooltip({
                placement: 'top',
                title: 'Accept'
            });
        });

        $(function() {
            // Initialize the tooltip
            $('[data-bs-toggle="tooltip"]').tooltip();

            // You can also set options manually if needed
            $('.showImage').tooltip({
                placement: 'top',
                title: 'Before Image'
            });
        });

        $(document).ready(function() {
            $('#myTable1').DataTable();
            $('#myTable2').DataTable();
            $('#myTable3').DataTable();
            $('#myTable4').DataTable();
            $('#feedbackTable').DataTable();

        });

        $(document).on("click", ".btnreject", function(e) {
            e.preventDefault();
            var u_id = $(this).val();
            console.log("User ID stored:", u_id);
            $(document).data("user_id_reject", u_id);
        });

        //Reject Button with Feedback
        $('#rejectdetails').on('submit', function(e) {
            e.preventDefault();

            if (confirm('Are you sure you want to reject this complaint?')) {
                var formdata1 = new FormData(this);
                var reject_id = $(document).data("user_id_reject");

                formdata1.append("reject_id", reject_id);
                $.ajax({
                    type: "POST",
                    url: 'cms_backend.php?action=rejectbtn',
                    data: formdata1,
                    processData: false,
                    contentType: false,

                    success: function(response) {
                        var res = jQuery.parseJSON(response);
                        if (res.status == 200) {
                            $('#rejectmodal').modal('hide');
                            $('#rejectdetails')[0].reset();
                            $('#myTable1').DataTable().destroy();
                        $('#myTable2').DataTable().destroy();
                        $('#myTable3').DataTable().destroy();
                        $('#myTable4').DataTable().destroy();
                        $('#feedbackTable').DataTable().destroy();
                        $("#myTable1").load(location.href + " #myTable1 > *", function() {
                            $('#myTable1').DataTable();
                        });
                        $("#myTable2").load(location.href + " #myTable2 > *", function() {
                            $('#myTable2').DataTable();
                        });
                        $("#myTable3").load(location.href + " #myTable3 > *", function() {
                            $('#myTable3').DataTable();
                        });
                        $("#myTable4").load(location.href + " #myTable3 > *", function() {
                            $('#myTable3').DataTable();
                        });
                        $("#feedbackTable").load(location.href + " #feedbackTable > *", function() {
                            $('#feedbackTable').DataTable();
                        });
                        $('#navref1').load(location.href + " #navref1");
                        $('#navref2').load(location.href + " #navref2");
                        $('#navref3').load(location.href + " #navref3");
                        $('#navref33').load(location.href + " #navref3");
                        $('#navref4').load(location.href + " #navref4");

                        } else if (res.status == 500) {
                            alertify.error('Complaint Rejected!');
                            $('#rejectmodal').modal('hide');
                            $('#rejectdetails')[0].reset();
                            console.error("Error:", res.message);
                            alert("Something Went wrong.! try again")
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX error:", error);
                        alert("An error occurred: " + error);
                    }
                });

                sendRejectionMail(reject_id);
            }
        });

        //sending mail for complaint reject

        function sendRejectionMail(id) {
            var user_type = "Head of the Department";
            $.ajax({
                type: "POST",
                url: "cms_mail.php",
                data: {
                    'reject_mail': true,
                    'id': id,
                    'user_type': user_type,
                },
                success: function(response) {
                    var res = jQuery.parseJSON(response);
                    if (res.status == 200) {
                        console.log("Mail sent successfully!!");
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Mail AJAX error:", error);
                }
            });
        }

        //approve button
        $(document).on('click', '.btnapprove', function(e) {
            e.preventDefault();

            var approveid = $(this).val();

                    $.ajax({
                        type: "POST",
                        url: 'cms_backend.php?action=approvebtn',
                        data: {
                            'approve': approveid
                        },
                        success: function(response) {
                            var res = jQuery.parseJSON(response);
                            if (res.status == 500) {
                                alert(res.message);
                            } else {
                                swal({
                            title: "success!",
                            text: "Complaint accepted sucessfully!",
                            icon: "success",
                            button: "Ok",
                            timer: null
                        });
                        $('#myTable1').DataTable().destroy();
                        $('#myTable2').DataTable().destroy();
                        $('#myTable3').DataTable().destroy();
                        $('#myTable4').DataTable().destroy();
                        $('#feedbackTable').DataTable().destroy();
                        $("#myTable1").load(location.href + " #myTable1 > *", function() {
                            $('#myTable1').DataTable();
                        });
                        $("#myTable2").load(location.href + " #myTable2 > *", function() {
                            $('#myTable2').DataTable();
                        });
                        $("#myTable3").load(location.href + " #myTable3 > *", function() {
                            $('#myTable3').DataTable();
                        });
                        $("#myTable4").load(location.href + " #myTable3 > *", function() {
                            $('#myTable3').DataTable();
                        });
                        $("#feedbackTable").load(location.href + " #feedbackTable > *", function() {
                            $('#feedbackTable').DataTable();
                        });
                        $('#navref1').load(location.href + " #navref1");
                        $('#navref2').load(location.href + " #navref2");
                        $('#navref3').load(location.href + " #navref3");
                        $('#navref33').load(location.href + " #navref3");
                        $('#navref4').load(location.href + " #navref4");
                            }
                        }
                    });
                
        });

        // Add Faculty complaints to database
        $(document).on('submit', '#addnewuser', function(e) {
            e.preventDefault(); // Prevent form from submitting normally
            var formData = new FormData(this);
            formData.append("hod", true);
            $.ajax({
                type: "POST",
                url: 'cms_backend.php?action=addcomplaint',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    var res = typeof response === 'string' ? JSON.parse(response) : response;
                    if (res.status === 200) {
                        swal("Complaint Submitted!", "", "success");
                        $('#raisemodal').modal('hide');
                        $('#addnewuser')[0].reset(); // Reset the form
                        $('#myTable1').DataTable().destroy();
                        $('#myTable2').DataTable().destroy();
                        $('#myTable3').DataTable().destroy();
                        $('#myTable4').DataTable().destroy();
                        $('#feedbackTable').DataTable().destroy();
                        $("#myTable1").load(location.href + " #myTable1 > *", function() {
                            $('#myTable1').DataTable();
                        });
                        $("#myTable2").load(location.href + " #myTable2 > *", function() {
                            $('#myTable2').DataTable();
                        });
                        $("#myTable3").load(location.href + " #myTable3 > *", function() {
                            $('#myTable3').DataTable();
                        });
                        $("#myTable4").load(location.href + " #myTable3 > *", function() {
                            $('#myTable3').DataTable();
                        });
                        $("#feedbackTable").load(location.href + " #feedbackTable > *", function() {
                            $('#feedbackTable').DataTable();
                        });
                        $('#navref1').load(location.href + " #navref1");
                        $('#navref2').load(location.href + " #navref2");
                        $('#navref3').load(location.href + " #navref3");
                        $('#navref33').load(location.href + " #navref3");
                        $('#navref4').load(location.href + " #navref4");
                    } else {
                        console.error("Error:", res.message);
                        alert("Something went wrong! Try again.");
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error("AJAX Error:", textStatus, errorThrown);
                    alert("Failed to process response. Please try again.");
                }
            });
        });
        // problem description
        $(document).on('click', '#seeproblem', function(e) {
            e.preventDefault();
            var user_id = $(this).val();
            console.log(user_id)
            $.ajax({
                type: "POST",
                url: "cms_backend.php?action=seeproblem",
                data: {
                    'seedetails': true,
                    'user_id': user_id
                },
                success: function(response) {
                    var res = jQuery.parseJSON(response);
                    console.log(res)
                    if (res.status == 500) {
                        alert(res.message);
                    } else {
                        $("#id").text(res.data.id);
                        $("#type_of_problem").text(res.data.type_of_problem);
                        $("#block_venue").text(res.data.block_venue);
                        $("#venue_name").text(res.data.venue_name);
                        $('#pd').text(res.data.problem_description);
                        $('#probdesc').modal('show');
                    }
                }
            });
        });

        // faculty info
        $(document).on('click', '#facultyinfo', function(e) {
            e.preventDefault();
            var user_id = $(this).val();
            var faculty_id = $(this).data("value");

            console.log(user_id);
            console.log(faculty_id);
            $.ajax({
                type: "POST",
                url: 'cms_backend.php?action=facinfohod',
                data: {
                    'facultydetails': true,
                    'user_id': user_id,
                    'fac_id': faculty_id
                },
                success: function(response) {
                    var res = jQuery.parseJSON(response);
                    console.log(res)
                    if (res.status == 500) {
                        alert(res.message);
                    } else {
                        $("#id").val(res.data.id);
                        $("#faculty_name").text(res.data.fname);
                        $("#faculty_email").text(res.data.email);
                        $("#faculty_mobile").text(res.data.mobile);


                        $('#facultymodal').modal('show');
                    }
                }
            });
        });

        //Image Modal Ajax
        $(document).on('click', '.showImage', function() {
            var task_id = $(this).val();
            console.log(task_id);

            $.ajax({
                type: "POST",
                url: 'cms_backend.php?action=bimgforhod',
                data: {
                    'get_image': true,
                    'task_id': task_id
                },
                success: function(response) {
                    console.log(response);
                    var res = jQuery.parseJSON(response);
                    if (res.status == 200) {
                        $('#bimg').attr('src', "uploads/" + res.data);
                        $('#bmodalImage').modal('show');
                    } else {
                        $('#modalImage').hide();
                        alert(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    alert('An error occurred while retrieving the image.');
                }
            });
        });

        //After Image Modal
        $(document).on('click', '.viewafterimgcomp', function() {
            var task_id = $(this).val();
            console.log(task_id);

            // Fetch the image from the server
            $.ajax({
                type: "POST",
                url: 'cms_backend.php?action=get_aimage',
                data: {
                    'after_image': true,
                    'problem2_id': task_id
                },
                dataType: "json",
                success: function(response) {
                    console.log(response); // Log the parsed JSON response
                    if (response.status == 200) { // Use 'response' instead of 'res'
                        // Dynamically set the image source
                        $("#modalImage2").attr("src", response.data.after_photo);
                        // Show the modal
                        $("#afterImageModal").modal("show");
                    } else {
                        // Handle case where no image is found
                        alert(response.message ||
                            "An error occurred while retrieving the image.");
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error: ", status, error);
                }
            });
        });
        $('#afterImageModal').on('hidden.bs.modal', function() {
            // Reset the image source to a default or blank placeholder
            $("#modalImage2").attr("src", "path/to/placeholder_image.jpg");
        });

        function checkIfOthers() {
            const dropdown = document.getElementById('dropdown');
            const othersInput = document.getElementById('othersInput');

            // Show the input field if "Others" is selected
            if (dropdown.value === 'Other') {
                othersInput.style.display = 'block';
            } else {
                othersInput.style.display = 'none';
            }
        }

        function handleSubmit(event) {
            event.preventDefault(); // Prevent form submission for demo purposes
            const dropdown = document.getElementById('dropdown');
            const selectedValue = dropdown.value;
            let finalValue;

            // Get the appropriate value based on the dropdown selection
            if (selectedValue === 'Other') {
                finalValue = document.getElementById('otherValue').value;
            } else {
                finalValue = selectedValue;
            }

            console.log("Selected Category:", finalValue);
            // You can then send this data to the backend or process it further
            $("#oth").val(finalValue);
        }

        //Rejected Tab Feedback
        $(document).on('click', '#rejectedfeedback', function(e) {
            e.preventDefault();
            var user_idrej = $(this).val();
            console.log(user_idrej)
            $.ajax({
                type: "POST",
                url: 'cms_backend.php?action=rejfeedback',
                data: {
                    'seefeedback': true,
                    'user_idrej': user_idrej

                },
                success: function(response) {
                    var res = jQuery.parseJSON(response);
                    console.log(res);
                    if (res.status == 500) {
                        alert(res.message);
                    } else {
                        let rejectionReason = "";
                        switch (res.data2.status) {
                            case '19':
                                rejectionReason = "Rejected by Manager";
                                break;
                            case '20':
                                rejectionReason = "Rejected by Principal";
                                break;
                            case '23':
                                rejectionReason = "Rejected by Estate Officer";
                                break;
                            default:
                                rejectionReason = "Unknown rejection reason";
                        }
                        $('#pdrej2').text(rejectionReason);
                        $('#rejby').text(res.data2.feedback);
                        $('#problemrejected').modal('show');
                    }
                }

            });
        });

        //Star Rating Coding
        const stars = document.querySelectorAll("#star-rating span");
        const ratingValue = document.getElementById("rating-value");
        const ratevalue = document.getElementById("ratevalue");
        stars.forEach((star, index) => {
            star.addEventListener("click", () => {
                // Remove the "highlighted" class from all stars hidhlited is used in Style
                stars.forEach(s => s.classList.remove("highlighted"));

                // Add the "highlighted" class to all stars up to the clicked one
                for (let i = 0; i <= index; i++) {
                    stars[i].classList.add("highlighted");
                }

                // Update the rating value
                ratingValue.textContent = `Rating: ${index + 1}`;
                ratevalue.textContent = `${index + 1}`;
                var rating = ratevalue.textContent;
                $(document).data("ratings", rating);
            });
        });
        // Open feedback modal and set id
        $(document).on('click', '.feedbackBtn', function() {
            var id = $(this).data('problem-id');
            // Clear the feedback field and dropdown before opening the modal
            $('#feedback').val('');
            $('#satisfaction').val('');
            $('#feedback_id').val(id);
            $('#feedback_modal').modal('show');
        });


        // Handle feedback form submission
        $('#add_feedback').on('submit', function(e) {
            e.preventDefault(); // Prevent default form submission
            var formData = new FormData(this);
            console.log(formData);

            // Get the values of satisfaction and feedback
            var satisfactionValue = $('#satisfaction').val();
            var feedbackValue = $('#feedback').val();
            console.log(satisfactionValue);
            console.log(feedbackValue);

            // Combine satisfaction and feedback into a single value
            var combinedFeedback = satisfactionValue + ": " + feedbackValue;
            formData.append("satisfaction_feedback", combinedFeedback);

            var store_rating = $(document).data("ratings");
            console.log(store_rating);

            formData.append("ratings", store_rating);
            $.ajax({
                type: "POST",
                url: 'cms_backend.php?action=facdetfeedback',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log(response);
                    var res = jQuery.parseJSON(response);
                    if (res.status == 200) {
                        swal("Done!", "Feedback Submitted!", "success");
                        $("#add_feedback")[0].reset();
                        $('#feedback_modal').modal('hide');
                        $('.modal-backdrop').remove(); // Remove lingering backdrop


                        $('#myTable1').DataTable().destroy();
                        $('#myTable2').DataTable().destroy();
                        $('#myTable3').DataTable().destroy();
                        $('#myTable4').DataTable().destroy();
                        $('#feedbackTable').DataTable().destroy();
                        $("#myTable1").load(location.href + " #myTable1 > *", function() {
                            $('#myTable1').DataTable();
                        });
                        $("#myTable2").load(location.href + " #myTable2 > *", function() {
                            $('#myTable2').DataTable();
                        });
                        $("#myTable3").load(location.href + " #myTable3 > *", function() {
                            $('#myTable3').DataTable();
                        });
                        $("#myTable4").load(location.href + " #myTable3 > *", function() {
                            $('#myTable3').DataTable();
                        });
                        $("#feedbackTable").load(location.href + " #feedbackTable > *", function() {
                            $('#feedbackTable').DataTable();
                        });
                        $('#navref1').load(location.href + " #navref1");
                        $('#navref2').load(location.href + " #navref2");
                        $('#navref3').load(location.href + " #navref3");
                        $('#navref33').load(location.href + " #navref3");
                        $('#navref4').load(location.href + " #navref4");
                    } else {
                        alert(response.message || 'An error occurred while submitting feedback.');
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error: ", xhr.responseText);
                    alert('An error occurred while submitting feedback: ' + error);
                }
            });
        });
        // Display worker details in work in progress
        $(document).on('click', '.showWorkerDetails', function() {
            var id = $(this).val(); // Get the id from the button value
            console.log("Fetching worker details for id: " + id); // Debug log
            $.ajax({
                type: "POST",
                url: 'cms_backend.php?action=facworkerdet',
                data: {
                    'id': id
                },
                dataType: "json",
                success: function(response) {
                    if (response.status == 200) {
                        $('#workerName').text(response.worker_first_name);
                        $('#workerContact').text(response.worker_mobile);
                        $('#callWorkerBtn').attr('href', 'tel:' + response.worker_mobile);
                        $('#workerModal').modal('show');
                    } else {
                        alert(response.message || 'No worker details found.');
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error: ", xhr.responseText);
                    alert('An error occurred while fetching the worker details: ' + error);
                }
            });
        });
    </script>
    <script src="script.js"></script>

</body>

</html>