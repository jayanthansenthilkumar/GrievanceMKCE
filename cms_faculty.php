<?php
require 'config.php';
include("fsession.php");
$role = $frole;



if (!empty($fac_id)) {
    $faculty_id = $fac_id;
    $qrydata = "SELECT dept FROM faculty WHERE id = '$faculty_id'";
    $run = mysqli_query($db, $qrydata);
    $runs = mysqli_fetch_array($run);
    $dept = $runs['dept'];
}

if (isset($_POST['feedcheck'])) {
    $query = "SELECT * FROM complaints_detail WHERE status='11' and faculty_id='$faculty_id'";
    $query_run = mysqli_query($db, $query);
    while ($row = mysqli_fetch_array($query_run)) {
        $submitdate = $row['date_of_completion'];
        $nowdate = date('Y-m-d H:i:s');

        $submitTimestamp = strtotime($submitdate);
        $nowTimestamp = strtotime($nowdate);

        if ($nowTimestamp - $submitTimestamp > 172800) {
            $id = $row['id'];
            $query = "UPDATE complaints_detail SET nofeed = '1' WHERE id = '$id'";
            $run = mysqli_query($db, $query);
            if ($run) {
                $res = [
                    'status' => 200,
                    "message" => "done"
                ];
                echo json_encode($res);
                exit;
            } else {
                $res = [
                    'status' => 400,
                    "message" => "done"
                ];
                echo json_encode($res);
                exit;
            }
        }
    }
}
$nofeedvar = 0;

if (isset($_POST['nofeedvar'])) {
    $sql = "SELECT * FROM complaints_detail WHERE id='$faculty_id' AND nofeed='1'";
    $sql_run = mysqli_query($db, $sql);
    $data = mysqli_fetch_array($sql_run);
    while ($data) {
        $nofeedvar += 1;
    }
    $res = [
        "status" => 200,
        "message" => "value fetched",
    ];
    echo json_encode($res);
    exit;
}





$query = "SELECT * FROM complaints_detail WHERE faculty_id = '$faculty_id'";
$result = mysqli_query($db, $query);

$sql5 = "SELECT * FROM complaints_detail WHERE status IN (1,2,4,6,8,9,22) AND faculty_id = '$faculty_id'";
$sql1 = "SELECT * FROM complaints_detail WHERE status IN (7,10,17) AND faculty_id = '$faculty_id'";
$sql11 = "SELECT * FROM complaints_detail WHERE status IN (11,18,14) AND faculty_id = '$faculty_id'";
$sql2 = "SELECT * FROM complaints_detail WHERE status = 16 AND faculty_id = '$faculty_id'";
$sql3 = "SELECT * FROM complaints_detail WHERE status IN (23,5,19,20) AND faculty_id = '$faculty_id'";
$sql4 = "SELECT * FROM complaints_detail WHERE status = 15 AND faculty_id = '$faculty_id'";

$result5 = mysqli_query($db, $sql5);
$result1 = mysqli_query($db, $sql1);
$result11 = mysqli_query($db, $sql11);

$result2 = mysqli_query($db, $sql2);
$result3 = mysqli_query($db, $sql3);
$result4 = mysqli_query($db, $sql4);

$row_count5 = mysqli_num_rows($result5);
$row_count1 = mysqli_num_rows($result1);
$row_count11 = mysqli_num_rows($result11);

$row_count2 = mysqli_num_rows($result2);
$row_count3 = mysqli_num_rows($result3);
$row_count4 = mysqli_num_rows($result4);


/* $facquery = "SELECT * FROM faculty WHERE dept =(SELECT dept from faculty WHERE id='$faculty_id')";
$resultfac = mysqli_query($db, $facquery); */

if (isset($_POST['facdet'])) {
    $sql8 =  "SELECT * FROM faculty WHERE dept = '$dept'";
    $result8 = mysqli_query($db, $sql8);

    $options = '';
    $options .= '<option value="">Select a Faculty</option>';



    while ($row = mysqli_fetch_assoc($result8)) {
        $options .= '<option value="' . $row['id'] . '">' . $row['id'] . ' - ' . $row['name'] . '</option>';
    }


    echo $options;
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon.png">
    <title>MIC - MKCE</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="tabs.css">
    <link rel="stylesheet" href="cms_style.css">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- CSS Alertify-->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build/css/alertify.min.css" />
    <!-- Bootstrap theme alertify-->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build/css/themes/bootstrap.min.css" />

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

        <!--It contain's all files(Nav,Table,Modal)-->
        <div class="container-fluid">

            <!--Navbar card-->
            <div class="custom-tabs">
                <div id="navref">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist" id="navli">

                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" data-bs-toggle="tab" id="edit-bus-tab" href="#home"
                                role="tab" aria-selected="true">
                                <span class="hidden-xs-down" style="font-size: 0.9em;">
                                    <i class="fas fa-exclamation-circle tab-icon"></i> &nbsp Complaints (<?php echo $row_count5; ?>)
                                </span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" data-bs-toggle="tab" id="delete-bus-tab" href="#inprogress" role="tab"
                                aria-selected="true">
                                <span class="hidden-xs-down" style="font-size: 0.9em;">
                                    <i class="fas fa-tools tab-icon"></i>&nbsp Work-In Progress (<?php echo $row_count1; ?>)
                                </span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" data-bs-toggle="tab" id="route-bus-tab" href="#waitfeed" role="tab"
                                aria-selected="true">
                                <span class="hidden-xs-down" style="font-size: 0.9em;">
                                    <i class="fas fa-comment-alt tab-icon"></i> &nbsp Feedback (<?php echo $row_count11; ?>)
                                </span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" data-bs-toggle="tab" id="settings-bus-tab" href="#completed" role="tab"
                                aria-selected="true">
                                <span class="hidden-xs-down" style="font-size: 0.9em;">
                                    <i class="fas fa-check-circle tab-icon"></i> &nbsp Completed Work (<?php echo $row_count2; ?>)
                                </span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" data-bs-toggle="tab" id="revenue-bus-tab" href="#parents" role="tab"
                                aria-selected="true">
                                <span class="hidden-xs-down" style="font-size: 0.9em;">
                                    <i class="fas fa-times-circle tab-icon"></i>&nbsp Rejected Work (<?php echo $row_count3; ?>)
                                </span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" data-bs-toggle="tab" id="fleet-management-bus-tab" href="#reassign" role="tab"
                                aria-selected="true">
                                <span class="hidden-xs-down" style="font-size: 0.9em;">
                                    <i class="fas fa-redo tab-icon"></i>&nbsp Reassigned Work (<?php echo $row_count4; ?>)
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>
            


            <!--Container for table and modal-->
            <div class="tab-content">

            

                <!------------------Pending Work Modal----------------->
                <div class="tab-pane p-3 active" id="home" role="tabpanel">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div id="raise_complaint">
                                        <?php if ($nofeedvar > 0) { ?>
                                            <button type="button" class="btn btn-info float-end" data-bs-toggle="modal" data-bs-target="#cmodal" disabled>Raise Complaint</button>
                                        <?php } else { ?>
                                            <button type="button" class="btn btn-info float-end" data-bs-toggle="modal" data-bs-target="#cmodal">Raise Complaint</button>
                                        <?php } ?>
                                        <br><br>
                                    </div>
                                    <div class="table-responsive">
                                        <table id="user" class="table table-bordered table-striped">
                                            <thead class="gradient-header">
                                                <tr>
                                                    <th class="text-center">S.No</th>
                                                    <th class="text-center">Problem ID</th>
                                                    <th class="text-center">Venue</th>
                                                    <th class="text-center">Problem</th>
                                                    <th class="text-center">Problem description</th>
                                                    <th class="text-center">Date Of Submission</th>
                                                    <th class="text-center">Photo</th>
                                                    <th class="text-center">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                                                $s = 1;
                                                                while ($row = mysqli_fetch_assoc($result5)) {
                                                                    $statusMessage = '';
                                                                    switch ($row['status']) {
                                                                        case 1:
                                                                            $statusMessage = 'Pending';
                                                                            break;
                                                                        case 2:
                                                                            $statusMessage = 'Forwarded to Head of the Department';
                                                                            break;
                                                                        case 4:
                                                                            $statusMessage = 'Forwarded to Estate Officer';
                                                                            break;
                                                                        case 6:
                                                                            $statusMessage = 'Forwarded to Principal';
                                                                            break;
                                                                        case 8:
                                                                            $statusMessage = 'Approved by Principal ';
                                                                            break;
                                                                        case 9:
                                                                            $statusMessage = ' Approved by Manager';
                                                                            break;
                                                                        case 22:
                                                                            $statusMessage = ' Forwarded to Manager';
                                                                            break;
                                                                        default:
                                                                            $statusMessage = 'Unknown Status';
                                                                    }
                                                                ?>
                                                    <tr>
                                                        <td class="text-center"><?php echo $s; ?></td>
                                                        <td class="text-center"><?php echo $row['id']; ?></td>
                                                        <td class="text-center"><?php echo $row['block_venue']; ?></td>
                                                        <td class="text-center"><?php echo $row['type_of_problem']; ?></td>
                                                        <td class="text-center"><?php echo $row['problem_description']; ?></td>
                                                        <td class="text-center"><?php echo $row['date_of_reg']; ?></td>
                                                        <td class="text-center">
                                                            <button type="button" class="btn btn-light btn-sm showImage" value="<?php echo $row['id']; ?>">
                                                                <i class="fas fa-image fs-4"></i>
                                                            </button>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php if ($row['status'] == 1) { ?>
                                                                <center>
                                                                    <button class="btn btn-danger btndelete" type="button" value="<?php echo $row['id']; ?>">
                                                                        <i class="fas fa-times"></i>
                                                                    </button>
                                                                </center>
                                                            <?php } else { ?>
                                                                <span class="badge bg-success fs-6 text-white p-2"> <?php echo $statusMessage; ?> </span>
                                                            <?php } ?>
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

                <!------------------Work in Progress Starts----------------->
                <div class="tab-pane p-3" id="inprogress" role="tabpanel">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="ProgressTable" class="table table-bordered table-striped">
                                    <thead class="gradient-header">
                                        <tr>
                                            <th class="text-center"><b>S.No</b></th>
                                            <th class="text-center"><b>Problem ID</b></th>
                                            <th class="text-center"><b>Venue</b></th>
                                            <th class="text-center"><b>Problem Description</b></th>
                                            <th class="text-center"><b>Date of Submission</b></th>
                                            <th class="text-center"><b>Deadline</b></th>
                                            <th class="text-center"><b>Worker Details</b></th>
                                            <th class="text-center"><b>Status</b></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $s = 1;
                                        while ($row = mysqli_fetch_assoc($result1)) {
                                        ?>
                                            <tr>
                                                <td class="text-center"><?php echo $s; ?></td>
                                                <td class="text-center"><?php echo $row['id']; ?></td>
                                                <td class="text-center"><?php echo $row['block_venue']; ?></td>
                                                <td class="text-center"><?php echo $row['problem_description']; ?></td>
                                                <td class="text-center"><?php echo $row['date_of_reg']; ?></td>
                                                <td class="text-center">
                                                    <?php if ($row['extend_date'] == 1) { ?>
                                                        <button type="button" class="btn btn-danger extenddeadline"
                                                            value="<?php echo $row['id']; ?>"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#extendModal"
                                                            data-reason="<?php echo $row['extend_reason']; ?>">
                                                            <?php echo $row['days_to_complete']; ?>
                                                        </button>
                                                    <?php } else { ?>
                                                        <?php echo $row['days_to_complete']; ?>
                                                    <?php } ?>
                                                </td>

                                                <td class="text-center">
                                                    <button type="button" class="btn btn-light showWorkerDetails" value="<?php echo $row['id']; ?>">
                                                        <?php
                                                        $prblm_id = $row['id'];
                                                        $query = "SELECT worker_first_name FROM worker_details WHERE worker_id = ( SELECT worker_dept FROM manager WHERE problem_id = '$prblm_id')";
                                                        $query_run = mysqli_query($db, $query);
                                                        $worker_name = mysqli_fetch_array($query_run);
                                                        echo $worker_name['worker_first_name'] ?? "NA";
                                                        ?>
                                                    </button>
                                                </td>
                                                <td class="text-center">In Progress</td>
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

                <!------------------Feedback Table----------------->
                <div class="tab-pane p-3" id="waitfeed" role="tabpanel">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="feedbackTable" class="table table-bordered table-striped">
                                    <thead class="gradient-header">
                                        <tr>
                                            <th class="text-center"><b>S.No</b></th>
                                            <th class="text-center"><b>Problem ID</b></th>
                                            <th class="text-center"><b>Venue</b></th>
                                            <th class="text-center"><b>Problem Description</b></th>
                                            <th class="text-center"><b>Date of Submission</b></th>
                                            <th class="text-center"><b>Deadline</b></th>
                                            <th class="text-center"><b>After Image</b></th>
                                            <th class="text-center"><b>Worker Details</b></th>
                                            <th class="text-center"><b>Feedback</b></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $s = 1;
                                        while ($row = mysqli_fetch_assoc($result11)) {
                                        ?>
                                            <tr>
                                                <td class="text-center"><?php echo $s; ?></td>
                                                <td class="text-center"><?php echo $row['id']; ?></td>
                                                <td class="text-center"><?php echo $row['block_venue']; ?></td>
                                                <td class="text-center"><?php echo $row['problem_description']; ?></td>
                                                <td class="text-center"><?php echo $row['date_of_reg']; ?></td>
                                                <td class="text-center">
                                                    <?php if ($row['extend_date'] == 1) { ?>
                                                        <button type="button" class="btn btn-danger extenddeadline"
                                                            value="<?php echo $row['id']; ?>"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#extendModal"
                                                            data-reason="<?php echo $row['extend_reason']; ?>">
                                                            <?php echo $row['days_to_complete']; ?>
                                                        </button>
                                                    <?php } else { ?>
                                                        <?php echo $row['days_to_complete']; ?>
                                                    <?php } ?>
                                                </td>
                                                <td class="text-center">
                                                    <button value="<?php echo $row['id']; ?>" type="button"
                                                        class="btn btn-light btn-sm imgafter"
                                                        data-bs-toggle="modal">
                                                        <i class="fa-solid fa-images" style="font-size: 25px;"></i>
                                                    </button>
                                                </td>

                                                <td class="text-center">
                                                    <button type="button" class="btn btn-light showWorkerDetails" value="<?php echo $row['id']; ?>">
                                                        <?php
                                                        $prblm_id = $row['id'];
                                                        $query = "SELECT worker_first_name FROM worker_details WHERE worker_id = ( SELECT worker_dept FROM manager WHERE problem_id = '$prblm_id')";
                                                        $query_run = mysqli_query($db, $query);
                                                        $worker_name = mysqli_fetch_array($query_run);
                                                        echo $worker_name['worker_first_name'] ?? "NA";
                                                        ?>
                                                    </button>
                                                </td>
                                                <td class="text-center">
                                                    <?php if ($row['status'] == 14) { ?>
                                                        <button class="btn btn-success">Submitted</button>
                                                    <?php } else { ?>
                                                        <button type="button" class="btn btn-info feedbackBtn"
                                                            data-problem-id="<?php echo $row['id']; ?>"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#feedback_modal">
                                                            Feedback
                                                        </button>
                                                    <?php } ?>
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

                <!----------------Completed Work Table starts--------------------->
                <div class="tab-pane p-3" id="completed" role="tabpanel">
                    <div class="table-responsive">
                        <table id="completedTable" class="table table-bordered table-striped">
                            <thead class="gradient-header">
                                <tr>
                                    <th class="text-center">S.No</th>
                                    <th class="text-center">Problem ID</th>
                                    <th class="text-center">Venue</th>
                                    <th class="text-center">Problem</th>
                                    <th class="text-center">Date Of Submission</th>
                                    <th class="text-center">Date of Completion</th>
                                    <th class="text-center">Feedback</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $s = 1;
                                while ($row = mysqli_fetch_assoc($result2)) { ?>
                                    <tr>
                                        <td class="text-center"><?php echo $s++; ?></td>
                                        <td class="text-center"><?php echo $row['id']; ?></td>
                                        <td class="text-center"><?php echo $row['block_venue']; ?></td>
                                        <td class="text-center"><?php echo $row['problem_description']; ?></td>
                                        <td class="text-center"><?php echo $row['date_of_reg']; ?></td>
                                        <td class="text-center"><?php echo $row['date_of_completion']; ?></td>
                                        <td class="text-center"><?php echo $row['feedback']; ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!----------------Rejected Work Table Starts-------------------------->
                <div class="tab-pane p-3" id="parents" role="tabpanel">
                    <div class="table-responsive">
                        <table id="RejectionTable" class="table table-bordered table-striped">
                            <thead class="gradient-header">
                                <tr>
                                    <th class="text-center">S.No</th>
                                    <th class="text-center">Problem ID</th>
                                    <th class="text-center">Block</th>
                                    <th class="text-center">Venue</th>
                                    <th class="text-center">Problem Description</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Reason</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $s = 1;
                                while ($row = mysqli_fetch_assoc($result3)) { ?>
                                    <tr>
                                        <td class="text-center"><?php echo $s++; ?></td>
                                        <td class="text-center"><?php echo $row['id']; ?></td>
                                        <td class="text-center"><?php echo $row['block_venue']; ?></td>
                                        <td class="text-center"><?php echo $row['venue_name']; ?></td>
                                        <td class="text-center"><?php echo $row['problem_description']; ?></td>
                                        <td class="text-center">
                                            <span class="badge bg-danger text-white"><?php echo $statusMessage; ?></span>
                                        </td>
                                        <td class="text-center"><?php echo $row['feedback']; ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!------------------Reassigned Work Table Starts----------------->
                <div class="tab-pane p-3" id="reassign" role="tabpanel">
                    <div class="table-responsive">
                        <table id="reassignTable" class="table table-bordered table-striped">
                            <thead class="gradient-header">
                                <tr>
                                    <th class="text-center">S.No</th>
                                    <th class="text-center">Problem ID</th>
                                    <th class="text-center">Venue</th>
                                    <th class="text-center">Problem</th>
                                    <th class="text-center">Problem Description</th>
                                    <th class="text-center">Date Of Submission</th>
                                    <th class="text-center">Worker Details</th>
                                    <th class="text-center">Feedback</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $s = 1;
                                while ($row = mysqli_fetch_assoc($result4)) { ?>
                                    <tr>
                                        <td class="text-center"><?php echo $s++; ?></td>
                                        <td class="text-center"><?php echo $row['id']; ?></td>
                                        <td class="text-center"><?php echo $row['block_venue']; ?></td>
                                        <td class="text-center"><?php echo $row['type_of_problem']; ?></td>
                                        <td class="text-center"><?php echo $row['problem_description']; ?></td>
                                        <td class="text-center"><?php echo $row['date_of_reg']; ?></td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-info showWorkerDetails" value="<?php echo $row['id']; ?>">View</button>
                                        </td>
                                        <td class="text-center"><?php echo $row['feedback']; ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                </div>
            </div>
        </div>


        <!-- Footer -->
        <?php include 'footer.php'; ?>
    </div>

     <!------------------All Modals start----------------->

                <!------------------Raise Complaint Modal----------------->
                <div class="modal fade" id="cmodal" tabindex="-1" aria-labelledby="raiseComplaintLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="raiseComplaintLabel">Raise Complaint</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="addnewuser" enctype="multipart/form-data" onsubmit="handleSubmit(event)">
                                    <input type="hidden" id="hidden_faculty_id" value="<?php echo $faculty_id; ?>">
                                    <?php if (!empty($fac_id)) { ?>
                                        <input type="hidden" class="form-control" name="faculty_id" id="faculty_id" value="<?php echo $faculty_id; ?>" readonly>
                                    <?php } ?>

                                    <div class="mb-3">
                                        <label for="type_of_problem" class="form-label">Type of Problem <span class="text-danger">*</span></label>
                                        <select class="form-select" name="type_of_problem">
                                            <option>Select</option>
                                            <option value="electrical">ELECTRICAL</option>
                                            <option value="civil">CIVIL</option>
                                            <option value="itkm">ITKM</option>
                                            <option value="transport">TRANSPORT</option>
                                            <option value="house">HOUSE KEEPING</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="block" class="form-label">Block <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="block_venue" placeholder="Eg: RK-206 / Transport: Bus No" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="venue" class="form-label">Venue <span class="text-danger">*</span></label>
                                        <select id="dropdown" class="form-select" name="venue_name" onchange="checkIfOthers()">
                                            <option>Select</option>
                                            <option value="class">Class Room</option>
                                            <option value="department">Department</option>
                                            <option value="lab">Lab</option>
                                            <option value="staff_room">Staff Room</option>
                                            <option id="oth" value="Other">Others</option>
                                        </select>
                                    </div>

                                    <div id="othersInput" class="mb-3 d-none">
                                        <label for="otherValue" class="form-label">Please specify: <span class="text-danger">*</span></label>
                                        <input class="form-control" type="text" id="otherValue" name="otherValue">
                                    </div>

                                    <div class="mb-3">
                                        <label for="description" class="form-label">Problem Description <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="problem_description" placeholder="Enter Description" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="itemno" class="form-label">Item Number (for electrical/ITKM work)</label>
                                        <input type="text" class="form-control" name="itemno" placeholder="Eg: AC-102">
                                    </div>

                                    <div class="mb-3">
                                        <label for="images" class="form-label">Image (less than 2MB) <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control" name="images" id="images" onchange="validateSize(this)" required>
                                    </div>

                                    <div class="mb-3">
                                        <input type="hidden" class="form-control" name="date_of_reg" id="date_of_reg" required>
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

                <!-- Extend Modal -->
                <div class="modal fade" id="extendModal" tabindex="-1" aria-labelledby="extendModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="extendModalLabel">Deadline Extended</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="rejectForm">
                                    <input type="hidden" name="id" id="complaint_id99">
                                    <div class="mb-3">
                                        <label for="extendReasonTextarea" class="form-label">Reason for Deadline Extension:</label>
                                        <textarea id="extendReasonTextarea" class="form-control" readonly rows="3"></textarea>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Worker Details Modal -->
                <div class="modal fade" id="workerModal" tabindex="-1" aria-labelledby="workerModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="workerModalLabel">Worker Phone</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="p-3 bg-light border rounded">
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
                            <div class="modal-header">
                                <h5 class="modal-title" id="feedbackModalLabel">Feedback Form</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="add_feedback">
                                    <input type="hidden" name="id" id="feedback_id"> <!-- Hidden input for id -->
                                    <div class="mb-3">
                                        <label for="satisfaction" class="form-label">Satisfaction</label>
                                        <select name="satisfaction" id="satisfaction" class="form-select" required>
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
                                        <textarea name="feedback" id="feedback" class="form-control" placeholder="Enter Feedback" style="width: 100%; height: 150px;"></textarea>
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

                <!-- After Image Modal -->
                <div class="modal fade" id="afterImageModal" tabindex="-1" aria-labelledby="afterImageModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="afterImageModalLabel">After Image</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Before Image</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <img id="modalImage" src="" class="img-fluid">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>


    <script src="script.js"></script>
    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <!-- Datatables -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

    <!-- Perfect Scrollbar -->
    <script src="assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>

    <!-- Bootstrap tether Core JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>


    <!-- SweetAlert -->
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
    </script>


    <script>
        // DataTables
        $(document).ready(function() {
            $('#user').DataTable();
            $('#ProgressTable').DataTable();
            $('#completedTable').DataTable();
            $('#RejectionTable').DataTable();
            $('#reassignTable').DataTable();
            $('#feedbackTable').DataTable();

        });
    </script>


    <script>
        // Add Faculty complaints to database
        $(document).on('submit', '#addnewuser', function(e) {
            e.preventDefault(); // Prevent form from submitting normally
            var formData = new FormData(this);
            $.ajax({
                type: "POST",
                url: 'cms_backend.php?action=facraisecomp',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    var res = jQuery.parseJSON(response);
                    if (res.status === 200) {
                        swal("Complaint Submitted!", "", "success");
                        $('#cmodal').modal('hide');
                        $('#addnewuser')[0].reset(); // Reset the form
                        $('#navref1').load(location.href + " #navref1");
                        $('#navref2').load(location.href + " #navref2");
                        $('#navref3').load(location.href + " #navref3");
                        $('#dashref').load(location.href + " #dashref");
                        $('#raise_complaint').load(location.href + " #raise_complaint");

                        $('#user').DataTable().destroy();
                        $("#user").load(location.href + " #user > *", function() {
                            $('#user').DataTable();
                        });
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


        // Delete complaints given by faculty
        $(document).on('click', '.btndelete', function(e) {
            e.preventDefault();
            if (confirm('Are you sure you want to delete this data?')) {
                var user_id = $(this).val();
                $.ajax({
                    type: "POST",
                    url: 'cms_backend.php?action=facdelcomp',
                    data: {
                        'user_id': user_id
                    },
                    success: function(response) {
                        console.log(response);
                        var res = typeof response === 'string' ? JSON.parse(response) : response;
                        if (res.status === 500) {
                            alert(res.message);
                        } else {
                            swal("Complaint deleted successfully", "", "success");
                            $('#navref1').load(location.href + " #navref1");
                            $('#navref2').load(location.href + " #navref2");
                            $('#navref3').load(location.href + " #navref3");
                            $('#dashref').load(location.href + " #dashref");
                            $('#raise_complaint').load(location.href + " #raise_complaint");

                            $('#user').DataTable().destroy();
                            $("#user").load(location.href + " #user > *", function() {
                                $('#user').DataTable();
                            });
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error("AJAX Error:", textStatus, errorThrown);
                        alert("Failed to delete data.");
                    }
                });
            }
        });



        //Before image
        $(document).on("click", ".showImage", function() {
            var problem_id = $(this).val(); // Get the problem_id from button value
            console.log(problem_id); // Ensure this logs correctly
            $.ajax({
                type: "POST",
                url: 'cms_backend.php?action=facbimg',
                data: {
                    problem_id: problem_id, // Correct POST key
                },
                dataType: "json", // Automatically parses JSON responses
                success: function(response) {
                    console.log(response); // Log the parsed JSON response
                    if (response.status == 200) {
                        // Dynamically set the image source
                        $("#modalImage").attr("src", "uploads/" + response.data.images);
                        // Show the modal
                        $("#imageModal").modal("show");
                    } else {
                        // Handle case where no image is found
                        alert(
                            response.message || "An error occurred while retrieving the image."
                        );
                    }
                },
                error: function(xhr, status, error) {
                    // Log the full error details for debugging
                    console.error("AJAX Error: ", xhr.responseText);
                    alert(
                        "An error occurred: " +
                        error +
                        "\nStatus: " +
                        status +
                        "\nDetails: " +
                        xhr.responseText
                    );
                },
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


                        $('#navref1').load(location.href + " #navref1");
                        $('#navref3').load(location.href + " #navref3");
                        $('#navref4').load(location.href + " #navref4");
                        $('#navref6').load(location.href + " #navref6");
                        $('#navref33').load(location.href + " #navref33");

                        $('#dashref').load(location.href + " #dashref");

                        $('#ProgressTable').DataTable().destroy();
                        $("#ProgressTable").load(location.href + " #ProgressTable > *", function() {
                            $('#ProgressTable').DataTable();
                        });

                        $('#feedbackTable').DataTable().destroy();
                        $("#feedbackTable").load(location.href + " #feedbackTable > *", function() {
                            $('#feedbackTable').DataTable();
                        });

                        $('#completedTable').DataTable().destroy();
                        $("#completedTable").load(location.href + " #completedTable > *", function() {
                            $('#completedTable').DataTable();
                        });

                        $('#reassignTable').DataTable().destroy();
                        $("#reassignTable").load(location.href + " #reassignTable > *", function() {
                            $('#reassignTable').DataTable();
                        });
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

        //after image
        $(document).on("click", ".imgafter", function() {
            var problem_id = $(this).val(); // Get the problem_id from button value
            console.log(problem_id); // Ensure this logs correctly
            $.ajax({
                type: "POST",
                url: 'cms_backend.php?action=get_aimage',
                data: {
                    problem2_id: problem_id, // Correct POST key
                },
                dataType: "json", // Automatically parses JSON responses
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
    </script>

    <script>
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


        $(document).on('click', '.fac', function(e) {
            e.preventDefault();

            $.ajax({
                url: 'completedtable.php',
                type: "POST",
                data: {
                    'facdet': true,
                },
                success: function(response) {
                    console.log(response);
                    $('#cfaculty').html(response);
                }
            });
        });

        $(document).on("click", ".limitovr", function(e) {
            e.preventDefault();
            swal("Warning!", "You have crossed your complaint limit!", "warning");
        })


        $(document).on('click', '.extenddeadline', function() {
            // Get the reason from the button's data attribute
            var reason = $(this).data('reason');

            // Set the reason in the modal's textarea
            $('#extendReasonTextarea').val(reason);
        });

        $(document).on("submit", "#passwordform", function(e) {
            e.preventDefault();
            var formdata = new FormData(this);
            console.log(formdata);
            console.log("hii");
            $.ajax({
                type: "POST",
                url: 'cms_backend.php?action=facchangepass',
                data: formdata,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log(response);
                    var res = jQuery.parseJSON(response);
                    if (res.status == 200) {
                        $('#passmodal').modal('hide');
                        swal("Done!", "Password Changed!", "success");
                    } else {
                        alert('error');
                    }
                }
            })
        });

        $(document).ready(function() {
            $.ajax({
                type: "POST",
                url: "completedtable.php",
                data: {
                    "feedcheck": true
                },
                success: function(response) {
                    var res = jQuery.parseJSON(response);
                    if (res.status == 200) {
                        console.log("feedback checked");
                    }
                }
            });
            $.ajax({
                type: "POST",
                url: "completedtable.php",
                data: {
                    "nofeedvar": true,
                },
                success: function(response) {
                    var res = jQuery.parseJSON(response);
                    if (res.status == 200) {
                        console.log("done")
                    } else {
                        console.log("done")
                    }
                }
            })
        })
    </script>
</body>

</html>