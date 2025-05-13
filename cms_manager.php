<?php
require "config.php";
include("fsession.php");
$role = 'Manager';
if ($role !== "Manager") {
    header("Location:index.php");
}
//query for 1st table input 
//Faculty complaint table
$sql1 = "
SELECT cd.*, f.name, f.dept
FROM complaints_detail cd
JOIN faculty f ON cd.faculty_id = f.id
WHERE cd.status IN ('22', '9')
ORDER BY 
    CASE WHEN f.id = 'principal' THEN 0 ELSE 1 END, 
    cd.status = '9'
";

$result1 = mysqli_query($db, $sql1);
$row_count1 = mysqli_num_rows($result1);
//manager table
$sql2 = "SELECT * FROM worker_details";
$result2 = mysqli_query($db, $sql2);
//worker details fetch panna
$sql3 = "SELECT * FROM complaints_detail WHERE status IN ('9','10','11')";
$result3 = mysqli_query($db, $sql3);
$row_count3 = mysqli_num_rows($result3);

//worker details fetch panna
$sql4 = "SELECT * FROM complaints_detail WHERE status IN ('8','19')";
$result4 = mysqli_query($db, $sql4);

$sql9 = "SELECT * FROM complaints_detail WHERE status IN ('8')";
$result9 = mysqli_query($db, $sql9);
$row_count4 = mysqli_num_rows($result9);

//work finished table
$sql5 = "SELECT * FROM complaints_detail WHERE status = '14'";
$result5 = mysqli_query($db, $sql5);
$row_count5 = mysqli_num_rows($result5);
//work completed table
$sql6 = "SELECT * FROM complaints_detail WHERE status='16'";
$result6 = mysqli_query($db, $sql6);
$row_count2  = mysqli_num_rows($result6);
//work reassigned table
$sql7 = "SELECT * FROM complaints_detail WHERE status IN ('15','17','18')";
$result7 = mysqli_query($db, $sql7);
$row_count7 = mysqli_num_rows($result7);

//display all users
$sql10 = "SELECT * FROM complaints_detail";
$result10 = mysqli_query($db, $sql10);

//display all workers
$sql11 = "SELECT * FROM worker_details";
$result11 = mysqli_query($db, $sql11);


if (isset($_POST['fdept'])) {
    $fdept = "SELECT * FROM departments";
    $fdept_run = mysqli_query($db, $fdept);
    $options = '';


    while ($row = mysqli_fetch_assoc($fdept_run)) {
        $options .= '<option value="' . $row['dname'] . '">' . $row['dname'] . '</option>';
    }
    echo $options;
    exit;
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
    <link rel="icon" type="image/png" sizes="16x16" href="../image/icons/mkce_s.png">
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
    <link href="css/dboardstyles.css" rel="stylesheet">
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
                    <li class="breadcrumb-item active" aria-current="page">Complaint</li>
                </ol>
            </nav>
        </div>


        <!-- Button trigger modal -->



        <div class="container-fluid">

            <!--Navbar card-->
            <div class="custom-tabs">
                <div id="navref">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist" id="navli">

                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" data-bs-toggle="tab" id="edit-bus-tab" href="#complain" role="tab" aria-selected="true">
                                <div id="navref1"> <span class="hidden-xs-down" style="font-size: 0.9em;">
                                        <i class="fas fa-exclamation-triangle tab-icon"></i> Complaint Raised (<?php echo $row_count1; ?>)
                                    </span> </div>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" data-bs-toggle="tab" id="delete-bus-tab" href="#principal" role="tab" aria-selected="true">
                                <div id="navref2"> <span class="hidden-xs-down" style="font-size: 0.9em;">
                                        <i class="fas fa-user-check tab-icon"></i> Principal Approval (<?php echo $row_count4; ?>)
                                    </span> </div>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" data-bs-toggle="tab" id="route-bus-tab" href="#worker" role="tab" aria-selected="true">
                                <div id="navref3"> <span class="hidden-xs-down" style="font-size: 0.9em;">
                                        <i class="fas fa-user-cog tab-icon"></i> Assigned (<?php echo $row_count3; ?>)
                                    </span> </div>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" data-bs-toggle="tab" id="fleet-management-bus-tab" href="#finished" role="tab" aria-selected="true">
                                <div id="navref4"> <span class="hidden-xs-down" style="font-size: 0.9em;">
                                        <i class="fas fa-comment-dots tab-icon"></i> Response (<?php echo $row_count5; ?>)
                                    </span> </div>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" data-bs-toggle="tab" id="settings-bus-tab" href="#reassigned" role="tab" aria-selected="true">
                                <div id="navref5"> <span class="hidden-xs-down" style="font-size: 0.9em;">
                                        <i class="fas fa-redo tab-icon"></i> Reassigned (<?php echo $row_count7; ?>)
                                    </span> </div>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" data-bs-toggle="tab" id="view-bus-tab" href="#completed" role="tab" aria-selected="true">
                                <span class="hidden-xs-down" style="font-size: 0.9em;">
                                    <i class="fas fa-check-circle tab-icon"></i> Completed Works
                                </span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" data-bs-toggle="tab" id="balance-bus-tab" href="#allrecords" role="tab" aria-selected="true">
                                <span class="hidden-xs-down" style="font-size: 0.9em;">
                                    <i class="fas fa-folder-open tab-icon"></i> Records
                                </span>
                            </a>
                        </li>
                    </ul>

                </div>




                <!--Container for table and modal-->
                <div class="tab-content">

                    <!--Complaint start-->
                    <div class="tab-pane p-20 active show" id="complain" role="tabpanel">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <h5 class="card-title">Complaint Raised</h5><br>
                                    <div class="table-responsive">
                                        <table id="complain_table" class="table table-striped table-bordered">
                                            <thead class="gradient-header">
                                                <tr>
                                                    <th class="text-center">
                                                        <h5>S.No</h5>
                                                    </th>
                                                    <th class="text-center">
                                                        <h5>Raised Date</h5>
                                                    </th>
                                                    <th class="text-center">
                                                        <h5>Department / Venue</h5>
                                                    </th>
                                                    <th class="text-center">
                                                        <h5>Complaint</h5>
                                                    </th>
                                                    <th class="text-center">
                                                        <h5>Picture</h5>
                                                    </th>
                                                    <th class="text-center">
                                                        <h5>Action</h5>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $s = 1;
                                                while ($row = mysqli_fetch_assoc($result1)) {
                                                ?>
                                                    <tr <?php if ($row['faculty_id'] == "principal")
                                                            echo 'style="background-color:#f3f57a"'; ?>>
                                                        <td class="text-center"><?php echo $s ?></td>
                                                        <td class="text-center"><?php echo $row['date_of_reg'] ?></td>
                                                        <td class="text-center"><?php echo $row['dept'] ?> /
                                                            <?php echo $row['block_venue'] ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <button type="button" value="<?php echo $row['id']; ?>"
                                                                class="btn btn-sm viewcomplaint"
                                                                data-value="<?php echo $row['fac_id']; ?>"
                                                                data-bs-toggle="modal" data-bs-target="#complaintDetailsModal">
                                                                <i class="fas fa-eye" style="font-size: 25px;"></i>
                                                            </button>
                                                        </td>
                                                        <td class="text-center">
                                                            <button type="button" class="btn btn-light btn-sm showImage"
                                                                value="<?php echo $row['id']; ?>" data-bs-toggle="modal"
                                                                data-bs-target="#imageModal">
                                                                <i class="fas fa-image" style="font-size: 25px;"></i>
                                                            </button>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php if ($row['status'] == 9) { ?>
                                                                <div class="dropdown">
                                                                    <button type="button"
                                                                        class="btn btn-warning dropdown-toggle reassign"
                                                                        id="reassignbutton" value="<?php echo $row['id']; ?>"
                                                                        data-bs-toggle="dropdown">
                                                                        Reassign
                                                                    </button>
                                                                    <ul class="dropdown-menu text-center">
                                                                        <li><a class="dropdown-item reass1" href="#"
                                                                                data-value="electrical">ELECTRICAL</a></li>
                                                                        <li><a class="dropdown-item reass1" href="#"
                                                                                data-value="civil">CIVIL</a></li>
                                                                        <li><a class="dropdown-item reass1" href="#"
                                                                                data-value="itkm">ITKM</a></li>
                                                                        <li><a class="dropdown-item reass1" href="#"
                                                                                data-value="transport">TRANSPORT</a></li>
                                                                        <li><a class="dropdown-item reass1" href="#"
                                                                                data-value="house">HOUSE KEEPING</a></li>
                                                                    </ul>
                                                                </div>
                                                            <?php } else { ?>
                                                                <div class="d-flex justify-content-center">
                                                                    <button type="button"
                                                                        class="btn btn-success managerapprove me-1"
                                                                        value="<?php echo $row['id']; ?>" data-bs-toggle="dropdown">
                                                                        <i class="fas fa-check"></i>
                                                                    </button>
                                                                    <ul class="dropdown-menu text-center">
                                                                        <li><a class="dropdown-item worker" href="#"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#managerapproveModal"
                                                                                data-value="electrical">ELECTRICAL</a></li>
                                                                        <li><a class="dropdown-item worker" href="#"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#managerapproveModal"
                                                                                data-value="civil">CIVIL</a></li>
                                                                        <li><a class="dropdown-item worker" href="#"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#managerapproveModal"
                                                                                data-value="itkm">ITKM</a></li>
                                                                        <li><a class="dropdown-item worker" href="#"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#managerapproveModal"
                                                                                data-value="transport">TRANSPORT</a></li>
                                                                        <li><a class="dropdown-item worker" href="#"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#managerapproveModal"
                                                                                data-value="house">HOUSE KEEPING</a></li>
                                                                    </ul>

                                                                    <button type="button"
                                                                        class="btn btn-danger rejectcomplaint me-1"
                                                                        id="rejectbutton" value="<?php echo $row['id']; ?>"
                                                                        data-bs-toggle="modal" data-bs-target="#rejectModal">
                                                                        <i class="fas fa-times"></i>
                                                                    </button>

                                                                    <?php if ($row['faculty_id'] != "principal") { ?>
                                                                        <button type="button" class="btn btn-primary principalcomplaint"
                                                                            id="principalbutton" value="<?php echo $row['id']; ?>"
                                                                            data-bs-toggle="modal" data-bs-target="#principalModal">
                                                                            <i class="fas fa-paper-plane"></i>
                                                                        </button>
                                                                    <?php } ?>
                                                                </div>
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

                    <!-- Principal Table -->
                    <div class="tab-pane p-20" id="principal" role="tabpanel">
                        <div class="row">
                            <div class="col-md-12">
                                <h5 class="card-title">Principal Approval</h5><br>
                                <table id="principal_table" class="table table-striped table-bordered">
                                    <thead class="gradient-header">
                                        <tr>
                                            <th class="text-center"><b>
                                                    <h5>S.No</h5>
                                                </b></th>
                                            <th class="col-md-2 text-center"><b>
                                                    <h5>Raised Date</h5>
                                                </b></th>
                                            <th class="text-center"><b>
                                                    <h5>Venue</h5>
                                                </b></th>
                                            <th class="text-center"><b>
                                                    <h5>Complaint</h5>
                                                </b></th>
                                            <th class="text-center"><b>
                                                    <h5>Picture</h5>
                                                </b></th>
                                            <th class="col-md-2 text-center"><b>
                                                    <h5>Action</h5>
                                                </b></th>
                                            <th class="col-md-2 text-center"><b>
                                                    <h5>Status</h5>
                                                </b></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $s = 1;
                                        while ($row4 = mysqli_fetch_assoc($result4)) {
                                        ?>
                                            <tr>
                                                <td class="text-center"><?php echo $s ?></td>
                                                <td class="text-center"><?php echo $row4['date_of_reg'] ?></td>
                                                <td class="text-center"><?php echo $row4['block_venue'] ?></td>
                                                <td class="text-center">
                                                    <button type="button" value="<?php echo $row4['id']; ?>"
                                                        class="btn viewcomplaint"
                                                        data-value="<?php echo $row4['fac_id']; ?>">
                                                        <i class="fas fa-eye" style="font-size: 25px;"></i>
                                                    </button>
                                                </td>

                                                <td class="text-center">
                                                    <button type="button" class="btn btn-light btn-sm showImage"
                                                        value="<?php echo $row4['id']; ?>" data-bs-toggle="modal"
                                                        data-bs-target="#imageModal">
                                                        <i class="fas fa-image" style="font-size: 25px;"></i>
                                                    </button>
                                                </td>
                                                <td class="text-center">
                                                    <?php if ($row4['status'] == '8') { ?>
                                                        <div class="dropdown">
                                                            <button type="button" class="btn btn-success managerapprove"
                                                                value="<?php echo $row4['id']; ?>" data-bs-toggle="dropdown">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li><a class="dropdown-item worker" href="#"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#managerapproveModal"
                                                                        data-value="electrical">ELECTRICAL</a></li>
                                                                <li><a class="dropdown-item worker" href="#"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#managerapproveModal"
                                                                        data-value="civil">CIVIL</a></li>
                                                                <li><a class="dropdown-item worker" href="#"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#managerapproveModal"
                                                                        data-value="itkm">ITKM</a></li>
                                                                <li><a class="dropdown-item worker" href="#"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#managerapproveModal"
                                                                        data-value="transport">TRANSPORT</a></li>
                                                                <li><a class="dropdown-item worker" href="#"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#managerapproveModal"
                                                                        data-value="house">HOUSE KEEPING</a></li>
                                                            </ul>
                                                        </div>
                                                    <?php }
                                                    if ($row4['status'] == '19') { ?>
                                                        <button type="button" class="btn btn-primary
                                                        rejectreasonbtn"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#princerejectres"
                                                            value="<?php echo $row4['id']; ?>">
                                                            Reason
                                                        </button>
                                                    <?php } ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php if ($row4['status'] == '8') { ?>
                                                        <span class="btn btn-success">Approved</span>
                                                    <?php }
                                                    if ($row4['status'] == '19') { ?>
                                                        <button type="button" class="btn btn-danger">Rejected</button>
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

                    <!-- Worker Table -->
                    <div class="tab-pane p-20" id="worker" role="tabpanel">
                        <div class="row">
                            <div class="col-md-12">
                                <h5 class="card-title">Ongoing Works</h5><br>
                                <div class="table-responsive">
                                    <table id="worker_table" class="table table-striped table-bordered">
                                        <thead class="gradient-header">
                                            <tr>
                                                <th class="text-center">S.No</th>
                                                <th class="text-center">Complaint</th>
                                                <th class="text-center">Worker</th>
                                                <th class="text-center">Deadline</th>
                                                <th class="text-center">Picture</th>
                                                <th class="text-center">Status</th>
                                                <th class="text-center">Principal Query</th>
                                                <th class="text-center">Your Reply</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $s = 1;
                                            $current_date = date('Y-m-d');
                                            while ($row3 = mysqli_fetch_assoc($result3)) {
                                                $deadline = $row3['days_to_complete'];
                                                $h = $row3['id']; // complaint id

                                                // Fetch query from manager table
                                                $querydisplay = "SELECT * FROM manager WHERE problem_id=$h";
                                                $resultdisplay = mysqli_query($db, $querydisplay);
                                                $rowdis = mysqli_fetch_assoc($resultdisplay);
                                                $comment_query = $rowdis['comment_query'];
                                                $comment_reply = $rowdis['comment_reply'];
                                                $reply_date = $rowdis['reply_date'];
                                                $task_id = $rowdis['task_id'];
                                                $buttonClass = empty($comment_reply) ? 'btn-primary' : 'btn-success';
                                                $rowBackground = ($current_date >= $deadline) ? 'table-danger' : '';
                                            ?>
                                                <tr class="<?php echo $rowBackground; ?>">
                                                    <td class="text-center"><?php echo $s; ?></td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-light viewcomplaint"
                                                            value="<?php echo $row3['id']; ?>"
                                                            data-value="<?php echo $row3['fac_id']; ?>"
                                                            data-bs-toggle="modal" data-bs-target="#complaintDetailsModal">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                    </td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-light worker_det"
                                                            value="<?php echo $row3["id"]; ?>" data-bs-toggle="modal"
                                                            data-bs-target="#workerdetailmodal">
                                                            <?php
                                                            $prblm_id = $row3['id'];
                                                            $querry = "SELECT worker_first_name FROM worker_details WHERE worker_id = ( SELECT worker_dept FROM manager WHERE problem_id = '$prblm_id')";
                                                            $querry_run = mysqli_query($db, $querry);
                                                            $worker_name = mysqli_fetch_array($querry_run);
                                                            echo $worker_name['worker_first_name'];
                                                            ?>
                                                        </button>
                                                    </td>
                                                    <td class="text-center">
                                                        <button
                                                            class="btn <?php echo ($row3['extend_date'] != 0 || $row3['status'] == '11' || $current_date >= $deadline) ? '' : 'btn-primary deadline_extend'; ?>"
                                                            value="<?php echo $row3["id"]; ?>" data-bs-toggle="modal"
                                                            data-bs-target="#extend_date">
                                                            <?php echo $row3['days_to_complete']; ?>
                                                        </button>
                                                    </td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-light btn-sm showImage"
                                                            value="<?php echo $row3['id']; ?>" data-bs-toggle="modal"
                                                            data-bs-target="#imageModal">
                                                            <i class="fas fa-image"></i>
                                                        </button>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php
                                                        if ($row3['status'] == '10')
                                                            echo '<span class="btn btn-warning">In Progress</span>';
                                                        elseif ($row3['status'] == '11')
                                                            echo '<span class="btn btn-success">Completed</span>';
                                                        elseif ($row3['status'] == '9')
                                                            echo '<span class="btn btn-danger">Pending</span>';
                                                        ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <button type="button"
                                                            class="btn <?php echo $buttonClass; ?> openQueryModal"
                                                            data-task-id="<?php echo $task_id; ?>"
                                                            data-comment-query="<?php echo $comment_query; ?>"
                                                            data-bs-toggle="modal" data-bs-target="#principalQueryModal"
                                                            <?php echo empty($comment_query) ? 'disabled' : ''; ?>>
                                                            <?php echo empty($comment_query) ? 'No Query' : 'View Query'; ?>
                                                        </button>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php if (!empty($comment_reply)): ?>
                                                            <span><?php echo $comment_reply; ?></span>
                                                            <br>
                                                            <span class="text-muted">Reply Date:
                                                                <?php echo $reply_date; ?></span>
                                                        <?php else: ?>
                                                            <span class="text-bg-secondary">No Reply Yet</span>
                                                        <?php endif; ?>
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

                    <!-- Work Finished Table -->
                    <div class="tab-pane p-20" id="finished" role="tabpanel">
                        <div class="row">
                            <div class="col-md-12">
                                <h5 class="card-title">Works for Response</h5><br>
                                <table id="finished_table" class="table table-striped table-bordered">
                                    <thead class="gradient-header">
                                        <tr>
                                            <th class="text-center"><b>
                                                    <h5>S.No</h5>
                                                </b></th>
                                            <th class="col-md-2 text-center"><b>
                                                    <h5>Complaint</h5>
                                                </b></th>
                                            <th class="text-center"><b>
                                                    <h5>Worker</h5>
                                                </b></th>
                                            <th class="text-center"><b>
                                                    <h5>Completion Date</h5>
                                                </b></th>
                                            <th class="col-md-2 text-center"><b>
                                                    <h5>Picture</h5>
                                                </b></th>
                                            <th class="text-center"><b>
                                                    <h5>Faculty Feedback</h5>
                                                </b></th>
                                            <th class="col-md-2 text-center"><b>
                                                    <h5>Status</h5>
                                                </b></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $s = 1;
                                        while ($row5 = mysqli_fetch_assoc($result5)) {
                                        ?>
                                            <tr>
                                                <td class="text-center"><?php echo $s ?></td>
                                                <td class="text-center">
                                                    <button type="button" value="<?php echo $row5['id']; ?>"
                                                        class="btn viewcomplaint"
                                                        data-value="<?php echo $row5['fac_id']; ?>" data-bs-toggle="modal"
                                                        data-bs-target="#complaintDetailsModal">
                                                        <i class="fas fa-eye" style="font-size: 25px;"></i>
                                                    </button>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-light worker_det"
                                                        value="<?php echo $row5["id"]; ?>" data-bs-toggle="modal"
                                                        data-bs-target="#workerdetailmodal">
                                                        <?php
                                                        $prblm_id = $row5['id'];
                                                        $querry = "SELECT worker_first_name FROM worker_details WHERE worker_id = ( SELECT worker_dept FROM manager WHERE problem_id = '$prblm_id')";
                                                        $querry_run = mysqli_query($db, $querry);
                                                        $worker_name = mysqli_fetch_array($querry_run);
                                                        echo $worker_name['worker_first_name'] ?? "NA";
                                                        ?>
                                                    </button>
                                                </td>
                                                <td class="text-center"><?php echo $row5['date_of_completion'] ?></td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-light btn-sm showImage"
                                                        value="<?php echo $row5['id']; ?>" data-bs-toggle="modal"
                                                        data-bs-target="#imageModal">
                                                        <i class="fas fa-image" style="font-size: 25px;"></i>
                                                    </button>
                                                    <button value="<?php echo $row5['id']; ?>" type="button"
                                                        class="btn btn-light btn-sm imgafter" data-bs-toggle="modal">
                                                        <i class="fas fa-images" style="font-size: 25px;"></i>
                                                    </button>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-primary facfeed"
                                                        value="<?php echo $row5['id']; ?>" data-bs-toggle="modal"
                                                        data-bs-target="#exampleModal">
                                                        Feedback
                                                    </button>
                                                </td>
                                                <td class="text-center">
                                                    <?php
                                                    if ($row5['task_completion'] == 'Fully Completed') {
                                                    ?>
                                                        <span
                                                            class="btn btn-success"><?php echo $row5['task_completion'] ?></span>
                                                    <?php
                                                    } else {
                                                    ?>
                                                        <button class="btn btn-warning partially" data-bs-toggle="modal"
                                                            data-bs-target="#partially_reason"
                                                            value="<?php echo $row5['id'] ?>">
                                                            <?php echo $row5['task_completion'] ?>
                                                        </button>
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

                    <!-- Resigned Table -->
                    <div class="tab-pane p-20" id="reassigned" role="tabpanel">
                        <div class="row">
                            <div class="col-md-12">
                                <h5 class="card-title">Reassigned Works</h5><br>
                                <table id="reassigned_table" class="table table-striped table-bordered">
                                    <thead class="gradient-header">
                                        <tr>
                                            <th class="text-center"><b>
                                                    <h5>S.No</h5>
                                                </b></th>
                                            <th class="col-md-2 text-center"><b>
                                                    <h5>Complaint</h5>
                                                </b></th>
                                            <th class="text-center"><b>
                                                    <h5>Worker</h5>
                                                </b></th>
                                            <th class="text-center"><b>
                                                    <h5>Date of Reassigned</h5>
                                                </b></th>
                                            <th class="col-md-2 text-center"><b>
                                                    <h5>Deadline</h5>
                                                </b></th>
                                            <th class="text-center"><b>
                                                    <h5>Picture</h5>
                                                </b></th>
                                            <th class="col-md-2 text-center"><b>
                                                    <h5>Faculty Feedback</h5>
                                                </b></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $s = 1;
                                        while ($row7 = mysqli_fetch_assoc($result7)) {
                                        ?>
                                            <tr>
                                                <td class="text-center"><?php echo $s ?></td>
                                                <td class="text-center">
                                                    <button type="button" value="<?php echo $row7['id']; ?>"
                                                        class="btn viewcomplaint"
                                                        data-value="<?php echo $row7['fac_id']; ?>" data-bs-toggle="modal"
                                                        data-bs-target="#complaintDetailsModal">
                                                        <i class="fas fa-eye" style="font-size: 25px;"></i>
                                                    </button>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-light worker_det"
                                                        value="<?php echo $row7["id"]; ?>" data-bs-toggle="modal"
                                                        data-bs-target="#workerdetailmodal">
                                                        <?php
                                                        $prblm_id = $row7['id'];
                                                        $querry = "SELECT worker_first_name FROM worker_details WHERE worker_id = ( SELECT worker_id FROM manager WHERE problem_id = '$prblm_id')";
                                                        $querry_run = mysqli_query($db, $querry);
                                                        $worker_name = mysqli_fetch_array($querry_run);
                                                        echo $worker_name['worker_first_name']; ?>
                                                    </button>
                                                </td>
                                                <td class="text-center"><?php echo $row7['reassign_date'] ?></td>
                                                <td class="text-center"><?php echo $row7['days_to_complete'] ?></td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-light btn-sm showImage"
                                                        value="<?php echo $row7['id']; ?>" data-bs-toggle="modal"
                                                        data-bs-target="#imageModal">
                                                        <i class="fas fa-image" style="font-size: 25px;"></i>
                                                    </button>
                                                    <button value="<?php echo $row7['id']; ?>" type="button"
                                                        class="btn btn-light btn-sm imgafter" data-bs-toggle="modal">
                                                        <i class="fas fa-images" style="font-size: 25px;"></i>
                                                    </button>
                                                </td>
                                                <td class="text-center"><?php echo $row7['feedback']; ?></td>
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

                    <!-- Completed Table -->
                    <div class="tab-pane p-20" id="completed" role="tabpanel">
                        <div class="row">
                            <div class="col-md-12">

                                <table id="completed_table" class="table table-striped table-bordered">
                                    <thead class="gradient-header">
                                        <tr>
                                            <th class="text-center"><b>
                                                    <h5>S.No</h5>
                                                </b></th>
                                            <th class="col-md-2 text-center"><b>
                                                    <h5>Complaint</h5>
                                                </b></th>
                                            <th class="text-center"><b>
                                                    <h5>Worker</h5>
                                                </b></th>
                                            <th class="text-center"><b>
                                                    <h5>Date of Completion</h5>
                                                </b></th>
                                            <th class="col-md-2 text-center"><b>
                                                    <h5>Picture</h5>
                                                </b></th>
                                            <th class="col-md-2 text-center"><b>
                                                    <h5>Faculty Feedback</h5>
                                                </b></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $s = 1;
                                        while ($row6 = mysqli_fetch_assoc($result6)) {
                                        ?>
                                            <tr>
                                                <td class="text-center"><?php echo $s ?></td>
                                                <td class="text-center">
                                                    <button type="button" value="<?php echo $row6['id']; ?>"
                                                        class="btn viewcomplaint"
                                                        data-value="<?php echo $row6['fac_id']; ?>" data-bs-toggle="modal"
                                                        data-bs-target="#complaintDetailsModal">
                                                        <i class="fas fa-eye" style="font-size: 25px;"></i>
                                                    </button>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-light worker_det"
                                                        value="<?php echo $row6["id"]; ?>" data-bs-toggle="modal"
                                                        data-bs-target="#workerdetailmodal">
                                                        <?php
                                                        $prblm_id = $row6['id'];
                                                        $query = "SELECT worker_first_name FROM worker_details WHERE worker_id = 
                                              (SELECT worker_dept FROM manager WHERE problem_id = '$prblm_id')";
                                                        $query_run = mysqli_query($db, $query);
                                                        $worker_name = mysqli_fetch_array($query_run);
                                                        echo $worker_name['worker_first_name']; ?>
                                                    </button>
                                                </td>
                                                <td class="text-center"><?php echo $row6['date_of_completion'] ?></td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-light btn-sm showImage"
                                                        value="<?php echo $row6['id']; ?>" data-bs-toggle="modal"
                                                        data-bs-target="#imageModal">
                                                        <i class="fas fa-image" style="font-size: 25px;"></i>
                                                    </button>
                                                    <button value="<?php echo $row6['id']; ?>" type="button"
                                                        class="btn btn-light btn-sm imgafter" data-bs-toggle="modal"
                                                        data-bs-target="#imageAfterModal">
                                                        <i class="fas fa-images" style="font-size: 25px;"></i>
                                                    </button>
                                                </td>
                                                <td class="text-center">
                                                    <?php echo $row6['feedback']; ?>
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

                    <!--All record's-->
                    <div class="tab-pane fade" id="allrecords" role="tabpanel">
                        <ul class="nav navs-tabs justify-content-center ">
                            <li class="nav-item" style="margin-right: 10px;"> <!-- Add margin between tabs -->
                                <a class="nav-link active" style="font-size: 0.9em;" id="add-bus-tab" data-bs-toggle="tab" href="#record" role="tab" aria-selected="true">
                                    Work's Records
                                </a>
                            </li>
                            <li class="nav-item "> <!-- Add margin between tabs -->
                                <a class="nav-link" id="add-bus-tab" data-bs-toggle="tab" style="font-size: 0.9em;"
                                    href="#workersr" role="tab" aria-selected="false">
                                    Worker's Records
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">

                            <!--Work Record-->
                            <div class="tab-pane p-20 active" id="record" role="tabpanel">
                                <h5 class="card-title">Work's Completed</h5><br>

                                <!-- Date Range Filter Form -->
                                <form class="data_filter_form" id="date-filter-form">
                                    <div class="mb-3">
                                        <label for="from_date" class="form-label">From Date:</label>
                                        <input type="date" id="from_date" name="from_date" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="to_date" class="form-label">To Date:</label>
                                        <input type="date" id="to_date" name="to_date" class="form-control" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                </form>

                                <!-- Download Button -->
                                <button id="download" class="btn btn-success float-end">Download as Excel</button>
                                <br><br>

                                <h5 class="card-title">Work Completed Records</h5><br>
                                <div class="table-responsive">
                                    <table id="record_table" class="table table-striped table-bordered">
                                        <thead class="gradient-header">
                                            <tr>
                                                <th class="text-center">S.No</th>
                                                <th class="text-center">Work ID</th>
                                                <th class="text-center">Venue Details</th>
                                                <th class="text-center">Completed Details</th>
                                                <th class="text-center">Item No</th>
                                                <th class="text-center">Amount Spent</th>
                                                <th class="text-center">Faculty Feedback</th>
                                                <th class="text-center">Point</th>
                                                <th class="text-center">Completed On</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Rows dynamically added -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>



                            <!--workers record-->
                            <div class="tab-pane p-20" id="workersr" role="tabpanel">
                                <h5 class="card-title">Worker's Record</h5><br>

                                <form id="date-form" class="data_filter_form">
                                    <div class="mb-3">
                                        <label for="from_date" class="form-label">From Date:</label>
                                        <input type="date" name="from_date" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="to_date" class="form-label">To Date:</label>
                                        <input type="date" name="to_date" class="form-control" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                </form>

                                <button id="download1" class="btn btn-success float-end">Download as Excel</button>
                                <br><br>

                                <div class="table-responsive">
                                    <table id="Rworkers" class="table table-striped table-bordered">
                                        <thead class="gradient-header">
                                            <tr>
                                                <th class="text-center">S.No</th>
                                                <th class="text-center">Worker ID</th>
                                                <th class="text-center">Worker Name</th>
                                                <th class="text-center">Department</th>
                                                <th class="text-center">Completed Works</th>
                                                <th class="text-center">Faculty Ratings</th>
                                                <th class="text-center">Total Points</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Rows dynamically added -->
                                        </tbody>
                                    </table>
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



    <!--All Modals -->

    <!-- Manage Workers Modal -->
    <div class="modal fade" id="manageworkermodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addworker">
                        <i class="bi bi-person-plus"></i> Add Worker
                    </button>

                    <div class="table-responsive">
                        <table id="worker_display" class="table table-striped table-bordered">
                            <thead class="table-primary">
                                <tr class="text-center">
                                    <th>S.No</th>
                                    <th>Name</th>
                                    <th>Department</th>
                                    <th>Role</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <tbody>
                                <?php
                                $s = 1;
                                while ($row = mysqli_fetch_assoc($result11)) {
                                ?>
                                    <tr>
                                        <td class="text-center"><?php echo $s ?></td>
                                        <td class="text-center">
                                            <?php echo $row['worker_first_name'] ?></td>
                                        <td class="text-center">
                                            <?php echo $row['worker_dept'] ?></td>

                                        <td class="text-center">
                                            <?php echo $row['usertype'] ?></td>

                                        <td class="text-center"><button type="button"
                                                class="btn btn-danger deleteworker"
                                                value="<?php echo $row["id"] ?>">Delete</button>
                                        </td>
                                    </tr>
                                <?php
                                    $s++;
                                }
                                ?>
                            </tbody>

                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Worker Modal -->
    <div class="modal fade" id="addworker" tabindex="-1" aria-labelledby="addworkerLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius: 8px;">
                <div class="modal-header">
                    <h5 class="modal-title" id="addworkerLabel">Add Worker</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form id="workers">
                    <div class="modal-body bg-light">
                        <div class="mb-3">
                            <input type="text" class="form-control" name="w_name"
                                placeholder="Enter Worker Name" required>
                        </div>

                        <div class="mb-3">
                            <select class="form-select" id="department" name="w_dept" required>
                                <option value="">Select department</option>
                                <option value="civil">Civil</option>
                                <option value="electrical">Electrical</option>
                                <option value="itkm">ITKM</option>
                                <option value="transport">Transport</option>
                                <option value="house">House Keeping</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <select class="form-select" id="role" name="w_role" required>
                                <option value="">Select Role</option>
                                <option value="head">Head</option>
                                <option value="worker">Worker</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <input type="text" class="form-control" name="w_phone"
                                placeholder="Enter Phone Number" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Reject Complaint Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalLabel">Reject Complaint</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="rejectForm">
                        <input type="hidden" name="id" id="complaint_id99">
                        <div class="mb-3">
                            <label for="rejectReason" class="form-label">Reason for rejection</label>
                            <textarea class="form-control" name="feedback" id="rejectReason" rows="3"
                                placeholder="Type the reason here..."></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-danger">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Manager Approval Modal -->
    <div class="modal fade" id="managerapproveModal" tabindex="-1"
        aria-labelledby="managerapproveModalLabel1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="managerapproveModalLabel1">Approval Modal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="managerapproveForm">
                        <input type="hidden" name="problem_id" id="complaint_id56">
                        <input type="hidden" name="worker_id" id="worker_id" value="">
                        <p id="assignedWorker">Assigned Worker: </p>

                        <!-- Deadline input -->
                        <label for="deadline" class="fw-bold mb-2 d-block">Set Deadline:</label>
                        <input type="date" id="deadline01" name="deadline" class="form-control mb-3">

                        <!-- Priority selection -->
                        <span class="fw-bold mb-2 d-block">Set Priority:</span>
                        <ul class="list-group">
                            <li class="list-group-item">
                                <input type="radio" class="form-check-input" name="priority" value="High"
                                    required>
                                <label class="form-check-label">High</label>
                            </li>
                            <li class="list-group-item">
                                <input type="radio" class="form-check-input" name="priority" value="Medium">
                                <label class="form-check-label">Medium</label>
                            </li>
                            <li class="list-group-item">
                                <input type="radio" class="form-check-input" name="priority" value="Low">
                                <label class="form-check-label">Low</label>
                            </li>
                        </ul>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" form="managerapproveForm"
                        id="submitButton">Submit</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Partially Completed Reason Modal -->
    <div class="modal fade" id="partially_reason" tabindex="-1" aria-labelledby="partially_reasonLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="partially_reasonLabel">Partially Completed Reason</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="partially_completed">
                        <input type="hidden" name="id" id="complaint_id119">
                        <div class="mb-3">
                            <label for="partiallyReason" class="form-label">Reason</label>
                            <textarea readonly class="form-control" name="reason" id="partiallyReason"
                                rows="3" placeholder="Type the reason here..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalLabel">Reject Complaint</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="rejectForm">
                        <input type="hidden" name="id" id="complaint_id99">
                        <div class="mb-3">
                            <label for="rejectReason" class="form-label">Reason for rejection</label>
                            <textarea class="form-control" name="feedback" id="rejectReason" rows="3"
                                placeholder="Type the reason here..."></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-danger">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Principal Approve Modal -->
    <div class="modal fade" id="principalModal" tabindex="-1" aria-labelledby="principalModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="principalModalLabel">Need Approval</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="principal_Form">
                        <input type="hidden" name="id" id="complaint_id89">
                        <div class="mb-3">
                            <label for="approvalReason" class="form-label">Reason for Approval</label>
                            <textarea class="form-control" name="reason" id="approvalReason"
                                rows="3" placeholder="Type the reason here..."></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-danger">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Complaint Details Modal -->
    <div class="modal fade" id="complaintDetailsModal" tabindex="-1" aria-labelledby="complaintDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title" id="complaintDetailsModalLabel">
                        📋 Complaint Details
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Complaint ID</label>
                            <div class="text-muted"><b id="id"></b></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Faculty Name</label>
                            <div class="text-muted"><b id="faculty_name"></b></div>
                        </div>

                        <!-- Right Column -->
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Mobile Number</label>
                            <div class="text-muted"><b id="faculty_contact"></b></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">E-mail</label>
                            <div class="text-muted"><b id="faculty_mail"></b></div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Faculty Infra Coordinator Name</label>
                            <div class="text-muted"><b id="fac_name"></b></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Faculty ID</label>
                            <div class="text-muted"><b id="fac_id"></b></div>
                        </div>

                        <!-- Venue and Type of Problem -->
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Venue Name</label>
                            <div class="text-muted"><b id="venue_name"></b></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Type of Problem</label>
                            <div class="text-muted"><b id="type_of_problem"></b></div>
                        </div>

                        <!-- Problem Description -->
                        <div class="col-md-12">
                            <label class="fw-bold">Problem Description</label>
                            <div class="alert alert-light" role="alert">
                                <span id="problem_description"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!--Before Image Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img id="modalImage" src="" alt="Image" class="img-fluid">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- After Image Modal -->
    <div class="modal fade" id="afterImageModal" tabindex="-1" aria-labelledby="afterImageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
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

    <!-- Principal Query Modal -->
    <div class="modal fade" id="principalQueryModal" tabindex="-1" aria-labelledby="principalQueryLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="principalQueryLabel">Principal's Query</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="commentQueryText"></p>
                    <div class="mb-3">
                        <label for="commentReply" class="form-label">Your Reply</label>
                        <input type="text" class="form-control" id="commentReply"
                            placeholder="Enter your reply">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="submitReply">Submit Reply</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Faculty Feedback Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Faculty Feedback</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5>Rating: </h5>
                    <div class="stars" id="star-rating1">
                        <span data-value="1">&#9733;</span>
                        <span data-value="2">&#9733;</span>
                        <span data-value="3">&#9733;</span>
                        <span data-value="4">&#9733;</span>
                        <span data-value="5">&#9733;</span>
                    </div>
                    <h5 class="mt-3">Feedback: </h5>
                    <textarea name="ffeed" id="ffeed" readonly class="form-control" rows="4"></textarea>
                    <input type="hidden" id="complaintfeed_id" value="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success mfeed">Done</button>
                    <button type="button" class="btn btn-danger reass">Reassign</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Reassign Deadline Modal -->
    <div class="modal fade" id="datePickerModal" tabindex="-1" aria-labelledby="datePickerModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="datePickerModalLabel">Set Reassign Deadline</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="reassign_deadline" class="form-label">Reassign Deadline Date:</label>
                    <input type="date" id="reassign_deadline" name="reassign_deadline" class="form-control"
                        required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveDeadline">Set Deadline</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Principal Reject Reason Modal -->
    <div class="modal fade" id="princerejectres" tabindex="-1" aria-labelledby="princerejectresLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="princerejectresLabel">Rejected Reason</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <textarea name="feedback" id="feedback" readonly class="form-control"
                        rows="3"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Worker Detail Modal -->
    <div class="modal fade" id="workerdetailmodal" tabindex="-1" aria-labelledby="workerdetailmodalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="workerdetailmodalLabel">Worker Mobile Number</h5>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div
                        class="d-flex justify-content-between align-items-center p-3 bg-light rounded shadow">
                        <div>
                            <span id="worker_mobile" class="fw-bold fs-5 text-dark">9629613708</span>
                        </div>
                        <div>
                            <a href="tel:9629613708" id="callWorkerBtn" class="btn btn-success px-4">Call
                                Worker</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--extend_deadline date Modal -->
    <!-- Extend Deadline Modal -->
    <div class="modal fade" id="extend_date" tabindex="-1" aria-labelledby="extend_dateLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header ">
                    <h5 class="modal-title" id="extend_dateLabel">Extend Deadline</h5>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="extenddead">
                        <input type="hidden" name="id" id="deadline_id">

                        <div class="mb-3">
                            <label for="extend_deadline" class="form-label">New Deadline Date:</label>
                            <input type="date" id="extend_deadline" name="extend_deadline"
                                class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="extendReason" class="form-label">Reason for Extension:</label>
                            <textarea class="form-control" name="reason" id="extendReason" rows="3"
                                placeholder="Type the reason here..." required></textarea>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Set Deadline</button>
                        </div>
                    </form>
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

    <!-- JavaScript Alertify-->
    <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build/alertify.min.js"></script>

    <!--Download as XL-Sheet-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>



    <script>
        //Tool Tip
        $(function() {
            // Initialize the tooltip
            $('[data-toggle="tooltip"]').tooltip();

            // You can also set options manually if needed
            $('.rejectcomplaint').tooltip({
                placement: 'top',
                title: 'Reject'
            });
        });

        $(function() {
            // Initialize the tooltip
            $('[data-toggle="tooltip"]').tooltip();

            // You can also set options manually if needed
            $('.managerapprove').tooltip({
                placement: 'top',
                title: 'Accept'
            });
        });

        $(function() {
            // Initialize the tooltip
            $('[data-toggle="tooltip"]').tooltip();

            // You can also set options manually if needed
            $('.principalcomplaint').tooltip({
                placement: 'top',
                title: 'Principal Approval'
            });
        });

        $(function() {
            // Initialize the tooltip
            $('[data-toggle="tooltip"]').tooltip();

            // You can also set options manually if needed
            $('.showImage').tooltip({
                placement: 'top',
                title: 'Before'
            });
        });

        $(function() {
            // Initialize the tooltip
            $('[data-toggle="tooltip"]').tooltip();

            // You can also set options manually if needed
            $('.imgafter').tooltip({
                placement: 'top',
                title: 'After'
            });
        });

        $(function() {
            // Initialize the tooltip
            $('[data-toggle="tooltip"]').tooltip();

            // You can also set options manually if needed
            $('.viewcomplaint').tooltip({
                placement: 'top',
                title: 'View Complaint'
            });
        });


        $(document).ready(function() {
            $("#principal_table").DataTable();
        });
        $(document).ready(function() {
            $("#complain_table").DataTable();
        });
        $(document).ready(function() {
            $("#worker_table").DataTable();
        });
        $(document).ready(function() {
            $("#finished_table").DataTable();
        });
        $(document).ready(function() {
            $("#reassigned_table").DataTable();
        });
        $(document).ready(function() {
            $("#completed_table").DataTable();
        });
        $(document).ready(function() {
            $("#record_table").DataTable();
        });
        $(document).ready(function() {
            $("#Rworkers").DataTable();
        });
        $(document).ready(function() {
            $("#user_display").DataTable();
        });
        $(document).ready(function() {
            $("#worker_display").DataTable({
                pageLength: 5,
            });
        });
    </script>
    <script>
        //reject complaint
        $(document).on("click", "#rejectbutton", function(e) {
            e.preventDefault();
            var user_id = $(this).val(); // Get the ID from the button's value
            console.log("User ID:", user_id);
            // Set the user_id in the hidden input field within the form
            $("#complaint_id99").val(user_id);
            $(document).data("user_id_reject", user_id);
        });
        $(document).on("submit", "#rejectForm", function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            var reject_id = $(document).data("user_id_reject");

            $.ajax({
                type: "POST",
                url: 'cms_backend.php?action=reject_complaint',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    var res = jQuery.parseJSON(response);

                    if (res.status == 200) {

                        confirm("Are you sure? you want to reject it!!");
                        alertify.set('notifier', 'position', 'top-right');
                        alertify.error('Rejected');
                        // Close modal
                        $("#navref1").load(location.href + " #navref1");
                        $("#navref2").load(location.href + " #navref2");



                        $("#rejectModal").modal("hide");

                        // Reset the form
                        $("#rejectForm")[0].reset();
                        // Force refresh the table body with cache bypass

                        // Before loading new content, destroy the existing DataTable instance
                        $('#complain_table').DataTable().destroy();

                        $("#complain_table").load(location.href + " #complain_table > *",
                            function() {
                                // Reinitialize the DataTable after the content is loaded
                                $('#complain_table').DataTable();
                            });

                        // Display success message
                    } else if (res.status == 500) {
                        $("#rejectModal").modal("hide");
                        $("#rejectForm")[0].reset();
                        alert("Something went wrong. Please try again.");
                    }
                },
                error: function(xhr, status, error) {
                    alert("An error occurred while processing your request.");
                },
            });

            sendRejectionMail(reject_id);
        });

        // Function to send mail
        function sendRejectionMail(id) {
            var user_type = "Manager";
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


        //pass worker department tp approve model
        //approve by manager
        $(document).on("click", ".managerapprove", function(e) {
            e.preventDefault();
            var user_id = $(this).val(); // Get the ID from the button's value
            console.log("User ID:", user_id);
            // pass id to model - form
            $("#complaint_id56").val(user_id);
            $(document).data("user_approve_id", user_id);

            // Reset the worker selection in modal for next selection
            $("#worker_id").val('');
            $("#assignedWorker").text('Assigned Worker: ');
        });

        $(document).on('click', ".worker", function(e) {
            e.preventDefault();
            var worker = $(this).data('value');

            console.log(worker);

            //pass values to model
            $("#worker_id").val(worker);
            $("#assignedWorker").text("Assigned Worker: " + worker);
        })

        //reassign for manager
        $(document).on("click", ".reassign", function(e) {
            e.preventDefault();
            var user_id = $(this).val(); // Get the ID from the button's value
            console.log("User ID:", user_id);

            $(document).data("user_id2", user_id);

        });

        $(document).on('click', ".reass1", function(e) {
            e.preventDefault();
            var worker = $(this).data('value');
            var user_id = $(document).data("user_id2");

            console.log(worker);
            console.log("User ID:", user_id);

            $.ajax({
                url: 'cms_backend.php?action=reassign_complaint',
                type: "POST",
                data: {
                    user_id: user_id,
                    worker: worker,
                },
                success: function(response) {
                    var res = jQuery.parseJSON(response);
                    console.log(res);
                    if (res.status == 200) {
                        swal({
                            title: "success!",
                            text: "Complaint accepted sucessfully!",
                            icon: "success",
                            button: "Ok",
                            timer: null
                        });

                        $("#managerapproveModal").modal("hide");

                        // Reset the form
                        $("#managerapproveForm")[0].reset();


                        $('#complain_table').DataTable().destroy();
                        $('#principal_table').DataTable().destroy();

                        $("#complain_table").load(location.href + " #complain_table > *",
                            function() {
                                // Reinitialize the DataTable after the content is loaded
                                $('#complain_table').DataTable();
                            });
                        $("#principal_table").load(location.href + " #principal_table > *",
                            function() {
                                // Reinitialize the DataTable after the content is loaded
                                $('#principal_table').DataTable();
                            });
                        $("#navref1").load(location.href + " #navref1");
                        $("#navref2").load(location.href + " #navref2");



                    } else {
                        alert("Failed to accept complaint");
                    }
                },
            });


        });

        $(document).on("submit", "#managerapproveForm", function(e) {
            e.preventDefault();
            var data = new FormData(this);
            console.log(data);
            var comp_id = $(document).data("user_approve_id");

            $.ajax({
                url: 'cms_backend.php?action=manager_approve',
                type: "POST",
                data: data,
                processData: false,
                contentType: false,
                success: function(response) {
                    var res = jQuery.parseJSON(response);
                    console.log(res);
                    if (res.status == 200) {
                        swal({
                            title: "success!",
                            text: "Complaint accepted sucessfully!",
                            icon: "success",
                            button: "Ok",
                            timer: null
                        });

                        $("#managerapproveModal").modal("hide");

                        // Reset the form
                        $("#managerapproveForm")[0].reset();


                        $('#complain_table').DataTable().destroy();
                        $('#principal_table').DataTable().destroy();

                        $("#complain_table").load(location.href + " #complain_table > *",
                            function() {
                                // Reinitialize the DataTable after the content is loaded
                                $('#complain_table').DataTable();
                            });
                        $("#principal_table").load(location.href + " #principal_table > *",
                            function() {
                                // Reinitialize the DataTable after the content is loaded
                                $('#principal_table').DataTable();
                            });
                        $("#navref1").load(location.href + " #navref1");
                        $("#navref2").load(location.href + " #navref2");



                    } else {
                        alert("Failed to accept complaint");
                    }
                },
            });

            // sendApproveMail(comp_id);
        });


        //Principal approval
        $(document).on("click", "#principalbutton", function(e) {
            e.preventDefault();
            var user_id = $(this).val(); // Get the ID from the button's value
            console.log("User ID:", user_id);
            // Set the user_id in the hidden input field within the form
            $("#complaint_id89").val(user_id);
        });
        $(document).on("submit", "#principal_Form", function(e) {
            e.preventDefault();
            var formData = new FormData(this);

            $.ajax({
                type: "POST",
                url: 'cms_backend.php?action=principal_complaint',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    var res = jQuery.parseJSON(response);

                    if (res.status == 200) {

                        swal({
                            title: "success!",
                            text: "Complaint sent to Principal sucessfully!",
                            icon: "success",
                            button: "Ok",
                            timer: null
                        });
                        // Close modal
                        $("#principalModal").modal("hide");
                        // Reset the form
                        $("#principal_Form")[0].reset();
                        // Force refresh the table body with cache bypass
                        $('#complain_table').DataTable().destroy();
                        $("#complain_table").load(location.href + " #complain_table > *",
                            function() {
                                // Reinitialize the DataTable after the content is loaded
                                $('#complain_table').DataTable();
                            });
                        $('#worker_table').DataTable().destroy();
                        $("#worker_table").load(location.href + " #worker_table > *",
                            function() {
                                // Reinitialize the DataTable after the content is loaded
                                $('#worker_table').DataTable();
                            });
                        $("#navref1").load(location.href + " #navref1");
                        $("#navref2").load(location.href + " #navref2");
                        $("#navref3").load(location.href + " #navref3");



                        // Display success message
                    } else if (res.status == 500) {
                        $("#principalModal").modal("hide");
                        $("#principal_Form")[0].reset();
                        alert("Something went wrong. Please try again.");
                    }
                },
                error: function(xhr, status, error) {
                    alert("An error occurred while processing your request.");
                },
            });
        });


        //jquerry for view complaint
        $(document).on("click", ".viewcomplaint", function(e) {
            e.preventDefault();
            var user_id = $(this).val();
            var fac_id = $(this).data("value");
            console.log(user_id);
            // Clear the previously entered modal
            $("#id").text("");
            $("#type_of_problem").text("");
            $("#problem_description").text("");
            $("#faculty_name").text("");
            $("#faculty_mail").text("");
            $("#faculty_contact").text("");
            $("#block_venue").text("");
            $("#venue_name").text("");
            $("#fac_name").text("N/A");
            $("#fac_id").text("N/A");
            $.ajax({
                type: "POST",
                url: 'cms_backend.php?action=view_complaint',
                data: {
                    user_id: user_id,
                    fac_id: fac_id,
                },
                success: function(response) {
                    var res = jQuery.parseJSON(response);
                    console.log(res);
                    if (res.status == 404) {
                        alert(res.message);
                    } else {
                        //$('#student_id2').val(res.data.uid);
                        $("#id").text(res.data.id);
                        $("#type_of_problem").text(res.data.type_of_problem);
                        $("#problem_description").text(res.data.problem_description);
                        $("#faculty_name").text(res.data.fname);
                        $("#faculty_mail").text(res.data.email);
                        $("#faculty_contact").text(res.data.mobile);
                        $("#block_venue").text(res.data.block_venue);
                        $("#venue_name").text(res.data.venue_name);
                        $("#fac_name").text(res.data1.name);
                        $("#fac_id").text(res.data1.id);

                        $("#complaintDetailsModal").modal("show");
                    }
                },
            });
        });

        //Before image
        $(document).on("click", ".showImage", function() {
            var problem_id = $(this).val(); // Get the problem_id from button value
            console.log(problem_id); // Ensure this logs correctly
            $.ajax({
                type: "POST",
                url: 'cms_backend.php?action=get_image',
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
                            response.message ||
                            "An error occurred while retrieving the image."
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

        //principal question 
        $(document).ready(function() {
            // When the button is clicked, populate the modal with the query
            $(".openQueryModal").on("click", function() {
                // Check if the button is disabled
                if ($(this).is(':disabled')) {
                    return; // Do nothing if the button is disabled
                }

                var commentQuery = $(this).data("comment-query");
                var taskId = $(this).data("task-id");
                // Set the comment query text in the modal
                $("#commentQueryText").text(commentQuery);
                // Store the task_id for later use when submitting the answer
                $("#submitReply").data("task-id", taskId);
            });

            // Handle form submission when 'Submit Reply' is clicked
            $("#submitReply").on("click", function() {
                var taskId = $(this).data("task-id");
                var commentReply = $("#commentReply").val();

                // AJAX request to send the reply to the backend
                $.ajax({
                    url: 'cms_backend.php?action=submit_comment_reply', // Your backend file
                    type: "POST",
                    data: {
                        task_id: taskId,
                        comment_reply: commentReply,
                    },
                    success: function(response) {
                        var res = jQuery.parseJSON(response);
                        if (res.status == 200) {
                            alert(res.message);
                            $("#principalQueryModal").modal("hide");
                            // Reload the table to reflect changes
                            $("#worker_table").load(location.href + " #worker_table");
                        } else {
                            alert("Something went wrong. Please try again.");
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error:", error);
                        alert("Something went wrong. Please try again.");
                    },
                });
            });
        });

        //verify once again


        $(document).on("click", ".facfeed", function(e) {
            e.preventDefault();
            var user_id = $(this).val();
            console.log(user_id);
            $(document).data("feedid", user_id);
            $.ajax({
                type: "POST",
                url: 'cms_backend.php?action=facfeedview',
                data: {
                    user_id: user_id,
                },
                success: function(response) {
                    var res = jQuery.parseJSON(response);
                    console.log(res);
                    if (res.status == 500) {
                        alert(res.message);
                    } else {
                        //$('#student_id2').val(res.data.uid);
                        $("#ffeed").val(res.data.feedback)
                        $("#exampleModal").modal("show");

                        var nu = res.data.rating;
                        console.log(nu);

                        if (!isNaN(nu) && nu > 0) {
                            const stars1 = document.querySelectorAll("#star-rating1 span");

                            stars1.forEach(s => s.classList.remove("highlighted"));

                            for (let i = 0; i < nu; i++) {
                                stars1[i].classList.add("highlighted");
                            }
                        }
                    }
                },
            });
        });

        $(document).ready(function() {
            var complaintfeedId = null; // Store complaintfeed_id globally

            // Open the feedback modal and set the complaintfeed ID (Event Delegation)
            $(document).on("click", ".facfeed", function() {
                var complaintfeedId = $(this).val();
                $("#complaintfeed_id").val(complaintfeedId)

                // Send the rating ID to the PHP script via AJAX

            });

            // When 'Reassign' is clicked (Event Delegation)
            $(document).on("click", ".reass", function() {
                $("#datePickerModal").modal("show"); // Show the modal to select deadline
            });

            // When 'Set Deadline' is clicked in the date picker modal
            $(document).on("click", "#saveDeadline", function() {
                var reassign_deadline = $("#reassign_deadline").val(); // Get the selected deadline

                if (!reassign_deadline) {
                    alert("Please select a deadline date.");
                    return;
                }

                var complaintfeedId = $("#complaintfeed_id").val();
                updateComplaintStatus(complaintfeedId, 15,
                    reassign_deadline); // Status '15' for Reassign with deadline
                swal({
                    title: "success!",
                    text: "Reassigned sucessfully!",
                    icon: "success",
                    button: "Ok",
                    timer: null
                });
                $("#datePickerModal").modal("hide"); // Close the date picker modal
                $("#exampleModal").modal("hide"); // Close the feedback modal

                $('#finished_table').DataTable().destroy();
                $('#reassigned_table').DataTable().destroy();

                $("#finished_table").load(location.href + " #finished_table > *", function() {
                    // Reinitialize the DataTable after the content is loaded
                    $('#finished_table').DataTable();
                });
                $("#reassigned_table").load(location.href + " #reassigned_table > *", function() {
                    // Reinitialize the DataTable after the content is loaded
                    $('#reassigned_table').DataTable();
                });
                $("#navref3").load(location.href + " #navref3");
                $("#navref4").load(location.href + " #navref4");
                $("#navref5").load(location.href + " #navref5");
            });

            // Function to update the complaint status
            function updateComplaintStatus(complaintfeedId, status, reassign_deadline = null) {
                $.ajax({
                    type: "POST",
                    url: 'cms_backend.php?action=reassign_work',
                    data: {
                        complaintfeed_id: complaintfeedId,
                        status: status,
                        reassign_deadline: reassign_deadline, // Only pass this when we give 'reassign'
                    },
                    success: function(response) {
                        var res = jQuery.parseJSON(response);
                        if (res.status == 500) {
                            alert(res.message);
                        }
                    },
                    error: function() {
                        alert("An error occurred while updating the status.");
                    }
                });
            }
        });


        //Reject Reason from principal
        $(document).on("click", ".rejectreasonbtn", function(e) {
            e.preventDefault();
            var id12 = $(this).val();
            console.log(id12);
            $.ajax({
                type: "POST",
                url: 'cms_backend.php?action=get_reject_reason',
                data: {
                    problem_id: id12,
                },
                success: function(response) {
                    var res = jQuery.parseJSON(response);
                    console.log(res);
                    if (res.status == 500) {
                        alert(res.message);
                    } else {
                        $("#feedback").text(res.data.feedback);
                    }
                },
            });
        });


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

        //to download as xlsheet record table
        document.getElementById('download').addEventListener('click', function() {
            var wb = XLSX.utils.book_new();
            var ws = XLSX.utils.table_to_sheet(document.getElementById('record_table'));
            XLSX.utils.book_append_sheet(wb, ws, "Complaints Data");

            // Create and trigger the download
            XLSX.writeFile(wb, 'complaints_data.xlsx');
        });

        //to download as xlsheet workers record table
        document.getElementById('download1').addEventListener('click', function() {
            var we = XLSX.utils.book_new();
            var wg = XLSX.utils.table_to_sheet(document.getElementById('Rworkers'));
            XLSX.utils.book_append_sheet(we, wg, "Workers Data");

            // Create and trigger the download
            XLSX.writeFile(we, 'workers_data.xlsx');
        });


        //exctend deadline
        $(document).on("click", ".deadline_extend", function(e) {
            e.preventDefault();
            var user = $(this).val();
            console.log(user);
            $("#deadline_id").val(user);
            // $(document).data("u_id",user);
        });
        $(document).on("submit", "#extenddead", function(e) {
            e.preventDefault();
            console.log("Haii!!");
            var data = new FormData(this);
            console.log(data);
            data.append("dealine_extend_mail", true);

            $.ajax({
                url: 'cms_backend.php?action=extend_deadlinedate',
                type: "POST",
                data: data,
                processData: false,
                contentType: false,
                success: function(response) {
                    var res = jQuery.parseJSON(response);
                    console.log(res);
                    if (res.status == 200) {
                        swal({
                            title: "success!",
                            text: "Complaint accepted sucessfully!",
                            icon: "success",
                            button: "Ok",
                            timer: null
                        });
                        $("#extend_date").modal("hide");
                        $("#extenddead")[0].reset();
                        $('#worker_table').DataTable().destroy();
                        $("#worker_table").load(location.href + " #worker_table > *",
                            function() {
                                // Reinitialize the DataTable after the content is loaded
                                $('#worker_table').DataTable();
                            });
                    }
                }
            });

            //another j Query for sending mail
            $.ajax({
                type: "POST",
                url: "cms_mail.php",
                data: data,
                processData: false,
                contentType: false,
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

        })

        //Add worker
        $(document).on("submit", "#workers", function(e) {
            e.preventDefault();
            var dt = new FormData(this);
            console.log(dt);

            $.ajax({
                url: 'cms_backend.php?action=addworker',
                type: "POST",
                data: dt,
                processData: false,
                contentType: false,
                success: function(response) {

                    var res = jQuery.parseJSON(response);

                    if (res.status == 200) {
                        alertify.set('notifier', 'position', 'top-right');
                        alertify.success('User Added');
                        $("#addworker").modal("hide");
                        $('#workers')[0].reset();
                        $('#worker_display').DataTable().destroy();
                        $("#worker_display").load(location.href + " #worker_display > *",
                            function() {
                                $('#worker_display').DataTable({
                                    pageLength: 5
                                });
                            });



                    } else {
                        alert("Error");
                    }
                },
                error: function(xhr, status, error) {
                    alert("An error occurred: " + error);
                }
            });
        })

        $(document).on("click", ".mfeed", function(e) {
            e.preventDefault();

            var manfeed = $(document).data("feedid")
            console.log(manfeed);

            $.ajax({
                type: "POST",
                url: 'cms_backend.php?action=manager_feedbacks',
                data: {
                    'id': manfeed,
                },
                success: function(response) {
                    console.log(response);
                    var res = jQuery.parseJSON(response);

                    if (res.status == 200) {
                        swal({
                            title: "success!",
                            text: "Completed sucessfully!",
                            icon: "success",
                            button: "Ok",
                            timer: null
                        });

                        $("#exampleModal").modal("hide");

                        // Reset the form
                        $('#finished_table').DataTable().destroy();
                        $('#completed_table').DataTable().destroy();
                        $('#record_table').DataTable().destroy();
                        $('#completed_table').DataTable().destroy();

                        $("#finished_table").load(location.href + " #finished_table > *",
                            function() {
                                // Reinitialize the DataTable after the content is loaded
                                $('#finished_table').DataTable();
                            });
                        $("#completed_table").load(location.href + " #completed_table > *",
                            function() {
                                // Reinitialize the DataTable after the content is loaded
                                $('#completed_table').DataTable();
                            });
                        $("#record_table").load(location.href + " #record_table > *",
                            function() {
                                // Reinitialize the DataTable after the content is loaded
                                $('#record_table').DataTable();
                            });
                        $("#Rworkers").load(location.href + " #Rworkers > *", function() {
                            // Reinitialize the DataTable after the content is loaded
                            $('#Rworkers').DataTable();
                        });
                        $("#navref3").load(location.href + " #navref3");
                        $("#navref4").load(location.href + " #navref4");
                        $("#navref5").load(location.href + " #navref5");



                        // Display success message
                    } else if (res.status == 500) {
                        $("#DoneModal").modal("hide");
                        alert(res.message);
                    }
                },
                error: function(xhr, status, error) {
                    alert("An error occurred while processing your request.");
                },
            });
        });
    </script>

    <script>
        // Get today's date in the format 'YYYY-MM-DD'
        var today = new Date().toISOString().split('T')[0];

        // Get the date input element
        var dateInput = document.getElementById('deadline01');

        // Set the minimum and maximum date for the input field to today's date
        dateInput.setAttribute('min', today);
    </script>

    <script>
        // Get today's date in the format 'YYYY-MM-DD'
        var today = new Date().toISOString().split('T')[0];

        // Get the date input element
        var dateInput = document.getElementById('reassign_deadline');

        // Set the minimum and maximum date for the input field to today's date
        dateInput.setAttribute('min', today);
    </script>

    <script>
        // Get today's date in the format 'YYYY-MM-DD'
        var today = new Date().toISOString().split('T')[0];

        // Get the date input element
        var dateInput = document.getElementById('extend_deadline');

        // Set the minimum and maximum date for the input field to today's date
        dateInput.setAttribute('min', today);
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




        //delete user
        $(document).on("click", ".deleteuser", function(e) {
            e.preventDefault();
            var id = $(this).val();
            console.log(id);
            $.ajax({
                type: "POST",
                url: 'cms_backend.php?action=delete_user',
                data: {
                    id: id,
                },
                success: function(response) {
                    var res = jQuery.parseJSON(response);
                    console.log(res);
                    if (res.status == 200) {

                        alertify.set('notifier', 'position', 'top-right');
                        alertify.error('deleted');
                        $('#user_display').DataTable().destroy();

                        $("#user_display").load(location.href + " #user_display > *",
                            function() {
                                $('#user_display').DataTable({
                                    pageLength: 5
                                });
                            });

                    }
                },
            });

        });

        //delete worker
        $(document).on("click", ".deleteworker", function(e) {
            e.preventDefault();
            var id = $(this).val();
            console.log(id);
            $.ajax({
                type: "POST",
                url: 'cms_backend.php?action=delete_worker',
                data: {
                    id: id,
                },
                success: function(response) {
                    var res = jQuery.parseJSON(response);
                    console.log(res);
                    if (res.status == 200) {

                        alertify.set('notifier', 'position', 'top-right');
                        alertify.error('Deleted');
                        $('#worker_display').DataTable().destroy();
                        $("#worker_display").load(location.href + " #worker_display > *",
                            function() {
                                $('#worker_display').DataTable({
                                    pageLength: 5
                                });
                            });

                    }
                },
            });

        });



        $(document).on("submit", "#user_data", function(e) {
            e.preventDefault();
            var form = new FormData(this);
            form.append("add_user", true);
            console.log(form);
            $.ajax({
                type: "POST",
                url: 'cms_backend.php?action=add_user',
                data: form,
                contentType: false,
                processData: false,
                success: function(response) {
                    console.log(response);
                    var res = jQuery.parseJSON(response);

                    if (res.status == 200) {
                        alertify.set('notifier', 'position', 'top-right');
                        alertify.success('User Added');

                        $("#adduser").modal("hide");
                        $("#user_data")[0].reset();
                        $('#user_display').DataTable().destroy();
                        $("#user_display").load(location.href + " #user_display > *",
                            function() {
                                $('#user_display').DataTable({
                                    pageLength: 5
                                });
                            });
                    } else {
                        swal({
                            title: "Warning!",
                            text: "Invalid user Id!",
                            icon: "warning",
                            button: "Ok",
                            timer: null
                        });
                    }
                }
            })
        })



        $(document).on("click", ".partially", function(e) {
            e.preventDefault();
            var id = $(this).val();
            console.log(id);

            $.ajax({
                type: "POST",
                url: "cms_backend.php?action=partially_reason",
                data: {
                    id: id,
                },
                success: function(response) {
                    var res = jQuery.parseJSON(response);
                    console.log(response);
                    if (res.status == 404) {
                        alert("something went wrong!!");
                    } else {
                        $("#partiallyReason").text(res.data.reason);
                        $("#partially_reason").modal("show");
                    }
                }
            })
        });

        $(document).on("click", ".fetchdept", function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "manager.php",
                data: {
                    "fdept": true,

                },
                success: function(response) {
                    $('#department').html(response);

                }
            })
        });

        $(document).on("submit", "#date-form", function(e) {
            e.preventDefault();
            var form = new FormData(this);


            $.ajax({
                type: "POST",
                url: "cms_backend.php?action=dateapply",
                data: form,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log(response);
                    var res = jQuery.parseJSON(response);

                    if (res.status == 200) {
                        console.log("Date applied");

                        // Clear the existing table rows
                        $("#Rworkers tbody").empty();

                        // Add new rows dynamically
                        res.data.forEach((row, index) => {
                            var avgFacultyRating = row.avg_faculty_rating !== "N/A" ? row.avg_faculty_rating : "N/A";

                            var totalpoints = row.totalpoints !== "N/A" ? row.totalpoints : "N/A";

                            $("#Rworkers tbody").append(`
                        <tr>
                            <td class="text-center">${index + 1}</td>
                            <td class="text-center">${row.worker_id}</td>
                            <td class="text-center">${row.worker_first_name}</td>
                            <td class="text-center">${row.worker_dept}</td>
                            <td class="text-center">${row.total_completed_works}</td>
                            <td class="text-center">${avgFacultyRating}</td>
                            <td class="text-center">${totalpoints}</td>
                        </tr>
                    `);
                        });
                    } else {
                        console.log("Error: " + res.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", error);
                },
            });
        });

        $(document).on("submit", "#date-filter-form", function(e) {
            e.preventDefault();

            var formData = new FormData(this);
            $.ajax({
                type: "POST",
                url: "cms_backend.php?action=workrecord",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    var res = jQuery.parseJSON(response);
                    if (res.status == 200) {
                        console.log("Data fetched successfully!");

                        // Clear the existing table rows
                        $("#record_table tbody").empty();

                        // Dynamically populate the table with new data
                        var data = res.data;
                        data.forEach((row, index) => {
                            var avgRating = row.average_rating !== "N/A" ? row.average_rating : "N/A";
                            $("#record_table tbody").append(`
                        <tr>
                            <td class="text-center">${index + 1}</td>
                            <td class="text-center">${row.id}</td>
                            <td class="text-center">Venue: ${row.block_venue} | <br>Problem: ${row.problem_description}</td>
                            <td class="text-center">Completed by: ${row.completed_by} | <br>Department: ${row.department}</td>
                            <td class="text-center">${row.itemno}</td>
                            <td class="text-center">${row.amount_spent}</td>
                            <td class="text-center">${row.feedback}<br>Ratings: ${row.rating}</td>
                            <td class="text-center">${row.point}</td>
                            <td class="text-center">${row.date_of_completion}</td>
                        </tr>
                    `);
                        });
                    } else {
                        console.log("Error fetching data: ", res.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", error);
                },
            });
        });
    </script>




</body>

</html>