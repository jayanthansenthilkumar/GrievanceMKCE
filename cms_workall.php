<?php
require "config.php";


$worker_id = "civil";


//fetching worker head details using session v ar
$qry = "SELECT * FROM worker_details WHERE worker_id='$worker_id'";
$qry_run = mysqli_query($db, $qry);
$srow  = mysqli_fetch_array($qry_run);
$dept = $srow['worker_dept'];


//inprogress query
$sql1 = "
    SELECT 
        cd.id,
        cd.faculty_id,
        faculty.name,
        faculty.dept,
        cd.block_venue,
        cd.venue_name,
        cd.type_of_problem,
        cd.problem_description,
        cd.images,
        cd.date_of_reg,
        cd.days_to_complete,
        cd.task_completion,
        cd.status,
        cd.feedback,
        cd.extend_date,
        m.task_id,
        m.priority
    FROM 
        complaints_detail AS cd
    JOIN 
        manager AS m ON cd.id = m.problem_id
    JOIN 
        faculty ON cd.faculty_id = faculty.id
    WHERE 
        (m.worker_dept='$dept')
    AND 
        cd.status = '10'
";

$stmt = $db->prepare($sql1);
$stmt->execute();
$result1 = $stmt->get_result();
$progcount = mysqli_num_rows($result1);


$sql2 = "
    SELECT 
        cd.id,
        cd.faculty_id,
        faculty.name,
        faculty.dept,
        cd.block_venue,
        cd.venue_name,
        cd.type_of_problem,
        cd.problem_description,
        cd.images,
        cd.date_of_reg,
        cd.days_to_complete,
        cd.task_completion,
        cd.status,
        cd.feedback,
        cd.reason,
        m.task_id,
        m.priority
    FROM 
        complaints_detail AS cd
    JOIN 
        manager AS m ON cd.id = m.problem_id
    JOIN 
        faculty ON cd.faculty_id = faculty.id
    WHERE 
        (m.worker_dept='$dept')
    AND 
        (cd.status = '11' OR cd.status = '18')
";

$stmt = $db->prepare($sql2);
$stmt->execute();
$result2 = $stmt->get_result();
$waitcount = mysqli_num_rows($result2);


//completed query
$sql3 = "
    SELECT 
        cd.id,
        cd.faculty_id,
        faculty.name,
        faculty.dept,
        cd.block_venue,
        cd.venue_name,
        cd.type_of_problem,
        cd.problem_description,
        cd.images,
        cd.date_of_reg,
        cd.days_to_complete,
        cd.date_of_completion,
        cd.task_completion,
        cd.status,
        cd.feedback,
        m.task_id,
        m.priority
    FROM 
        complaints_detail AS cd
    JOIN 
        manager AS m ON cd.id = m.problem_id
    JOIN 
        faculty ON cd.faculty_id = faculty.id
    WHERE 
        (m.worker_dept='$dept')
    AND 
        cd.status IN('14','16')
";

$stmt = $db->prepare($sql3);
$stmt->execute();
$result3 = $stmt->get_result();
$compcount = mysqli_num_rows($result3);


//not approved query
$sql4 = "
    SELECT 
        cd.id,
        cd.faculty_id,
        faculty.name,
        faculty.dept,
        cd.block_venue,
        cd.venue_name,
        cd.type_of_problem,
        cd.problem_description,
        cd.images,
        cd.date_of_reg,
        cd.days_to_complete,
        cd.task_completion,
        cd.status,
        cd.feedback,
        m.task_id,
        m.priority
    FROM 
        complaints_detail AS cd
    JOIN 
        manager AS m ON cd.id = m.problem_id
    JOIN 
        faculty ON cd.faculty_id = faculty.id
    WHERE 
        (m.worker_dept='$dept')
    AND 
        cd.status = '15'
";


$stmt = $db->prepare($sql4);
$stmt->execute();
$result4 = $stmt->get_result();
$notcount = mysqli_num_rows($result4);


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MIC</title>
    <link rel="icon" type="image/png" sizes="32x32" href="image/icons/mkce_s.png">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-5/bootstrap-5.css" rel="stylesheet">

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
    <?php include 'wsidebar.php'; ?>

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
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $worker_id; ?></li>
                </ol>
            </nav>
        </div>

        <!-- Content Area -->
        <div class="container-fluid">
            <div class="custom-tabs">
                <ul class="nav nav-tabs" role="tablist">
                    <!-- Center the main tabs -->
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" data-bs-toggle="tab" id="edit-bus-tab" href="#progress" role="tab"
                            aria-selected="true">
                            <span class="hidden-xs-down" id="ref4" style="font-size: 0.9em;"><i class="fas fa-book tab-icon"></i>
                                In Progess (<?php echo $progcount ?>)</span>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" data-bs-toggle="tab" id="edit-bus-tab" href="#waiting" role="tab"
                            aria-selected="true">
                            <span class="hidden-xs-down" id="ref5" style="font-size: 0.9em;"><i
                                    class="fas fa-book tab-icon"></i>Waiting for approval (<?php echo $waitcount ?>)</span>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" data-bs-toggle="tab" id="edit-bus-tab" href="#not_approved" role="tab"
                            aria-selected="true">
                            <span class="hidden-xs-down" ref="ref3" style="font-size: 0.9em;"><i
                                    class="fas fa-book tab-icon"></i>Not approved (<?php echo $notcount ?>)</span>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" data-bs-toggle="tab" id="edit-bus-tab" href="#completed" role="tab"
                            aria-selected="true">
                            <span class="hidden-xs-down" id="ref1" style="font-size: 0.9em;"><i
                                    class="fas fa-book tab-icon"></i>Completed (<?php echo $compcount ?>)</span>
                        </a>
                    </li>
                </ul>
                <div class="tab-content">

                    <div class="tab-content">
                        <div class="tab-pane p-20 active" id="progress" role="tabpanel">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card-header mb-3 " style="text-align: right;">

                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="statusinprogress" class="table table-striped table-bordered">
                                                <thead class="gradient-header">
                                                    <tr>

                                                        <th class="text-center"><b>
                                                                <h5>S.No</h5>
                                                            </b></th>
                                                        <th class="col-md-2 text-center"><b>
                                                                <h5>Complaint Date</h5>
                                                            </b></th>
                                                        <th class="text-center"><b>
                                                                <h5>Task ID</h5>
                                                            </b></th>
                                                        <th class="text-center col-md-2"><b>
                                                                <h5>Dept</h5>
                                                            </b></th>
                                                        <th class="col-md-2 text-center"><b>
                                                                <h5>Complaint</h5>
                                                            </b></th>
                                                        <th class="text-center"><b>
                                                                <h5>Priority</h5>
                                                            </b></th>
                                                        <th class="text-center">
                                                            <b>
                                                                <h5>Photos</h5>
                                                            </b>
                                                        </th>
                                                        <th class="text-center"><b>
                                                                <h5>Deadline</h5>
                                                            </b></th>
                                                        <th class="text-center"><b>
                                                                <h5>Status</h5>
                                                            </b></th>
                                                        <th class="text-center"><b>
                                                                <h5>Action</h5>
                                                            </b></th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                                        $count = 1;
                                                                        while ($row = $result1->fetch_assoc()) {
                                                                            if($row['extend_date']==1){
                                                                                echo "<tr style='background-color:      #c2f0c2
'>";

                                                                            }
                                                                            else{
                                                                            echo "<tr>";
                                                                            }
                                                                            
                                                                            echo "<td class='text-center'>" . $count++ . "</td>";
                                                                            echo "<td class='text-center'>" . htmlspecialchars($row['date_of_reg']) . "</td>";
                                                                            echo "<td class='text-center'>" . htmlspecialchars($row['task_id']) . "</td>";
                                                                            echo "<td class='text-center'>" . htmlspecialchars($row['dept']) . "</td>";
                                                                        ?>
                                                    <td class='text-center'>
                                                        <button type='button' class='btn btn margin-5 view-complaint
                                                            '
                                                            data-task-id='<?php echo htmlspecialchars($row['task_id']); ?>'>
                                                            <i class="fas fa-eye" style="font-size: 25px;"></i>

                                                        </button>
                                                    </td>
                                                    <?php
                                                                            echo "<td class='text-center'>" . htmlspecialchars($row['priority']) . "</td>";
                                                                            ?>
                                                    <td class='text-center'>
                                                        <button type='button' class='btn margin-5 showbeforeimg'
                                                            data-task-id='<?php echo htmlspecialchars($row['task_id']); ?>'>
                                                            <i class="fas fa-image" style="font-size: 25px;"></i>
                                                        </button>
                                                    </td>
                                                    <?php
                                                                            echo "<td class='text-center'>" . htmlspecialchars($row['days_to_complete']) . "</td>";
                                                                            echo "<td class='text-center'>In Progress</td>";
                                                                            ?>
                                                    <td class='text-center'>
                                                        <button type='button' class='work-comp btn btn-primary margin-5'
                                                            data-value="<?php echo $srow['worker_dept'] ?>"
                                                            data-task-id='<?php echo htmlspecialchars($row['task_id']); ?>'>
                                                            Work Completion
                                                        </button>
                                                    </td>
                                                    <?php echo "</tr>";
                                                                        }
                                                                        ?>
                                                </tbody>


                                            </table>
                                        </div>
                                    </div>
                                    <!-- </div> -->
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane p-20" id="waiting" role="tabpanel">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card-header mb-3 " style="text-align: right;">

                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="approval" class="table table-striped table-bordered">
                                                <thead class="gradient-header">
                                                    <tr>

                                                        <th class="text-center"><b>
                                                                <h5>S.No</h5>
                                                            </b></th>
                                                        <th class="col-md-2 text-center"><b>
                                                                <h5>Complaint Date</h5>
                                                            </b></th>
                                                        <th class="text-center"><b>
                                                                <h5>Task ID</h5>
                                                            </b></th>
                                                        <th class="text-center"><b>
                                                                <h5>Dept</h5>
                                                            </b></th>
                                                        <th class="col-md-2 text-center"><b>
                                                                <h5>Complaint</h5>
                                                            </b></th>
                                                        <th class="text-center"><b>
                                                                <h5>Priority</h5>
                                                            </b></th>
                                                        <th class="text-center col-md-2">
                                                            <b>
                                                                <h5>Photos</h5>
                                                            </b>
                                                        </th>
                                                        <th class=" col-md-2 text-center"><b>
                                                                <h5>Deadline</h5>
                                                            </b></th>
                                                        <th class=" col-md-2 text-center"><b>
                                                                <h5>Reason</h5>
                                                            </b></th>
                                                        <th class="text-center"><b>
                                                                <h5>Task Completion</h5>
                                                            </b></th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                            $count = 1;
                                                            while ($row = $result2->fetch_assoc()) {
                                                                echo "<tr>";
                                                                echo "<td class='text-center'>" . $count++ . "</td>";
                                                                echo "<td class='text-center'>" . htmlspecialchars($row['date_of_reg']) . "</td>";
                                                                echo "<td class='text-center'>" . htmlspecialchars($row['task_id']) . "</td>";
                                                                echo "<td class='text-center'>" . htmlspecialchars($row['dept']) . "</td>";
                                                            ?>
                                                    <td class='text-center'>
                                                        <button type='button' class='btn btn margin-5 view-complaint
                                                            '
                                                            data-task-id='<?php echo htmlspecialchars($row['task_id']); ?>'>
                                                            <i class="fas fa-eye" style="font-size: 25px;"></i>

                                                        </button>
                                                    </td>
                                                    <?php
                                                                echo "<td class='text-center'>" . htmlspecialchars($row['priority']) . "</td>";
                                                                ?>
                                                    <td>
                                                        <div class="d-flex justify-content-between">
                                                            <!-- Align the first button to the left -->

                                                            <button type='button' class='btn margin-5 showbeforeimg'
                                                                data-task-id='<?php echo htmlspecialchars($row['task_id']); ?>'>
                                                                <i class="fas fa-image" style="font-size: 25px;"></i>
                                                            </button>

                                                            <!-- Align the second button to the right -->
                                                            <button type="button" class="btn I"
                                                                style="margin-left:-12px;" data-toggle="modal"
                                                                data-target="#Modal4"
                                                                data-task-id='<?php echo htmlspecialchars($row['task_id']); ?>'>
                                                                <i class="fas fa-image" style="font-size: 25px;"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                    <?php
                                                                echo "<td class='text-center'>" . htmlspecialchars($row['days_to_complete']) . "</td>";
                                                                echo "<td class='text-center'>" . htmlspecialchars($row['reason']) . "</td>";
                                                                echo "<td class='text-center'>" . htmlspecialchars($row['task_completion']) . "</td>";
                                                                echo "</tr>";
                                                            }
                                                            ?>
                                                </tbody>


                                            </table>
                                        </div>
                                    </div>
                                    <!-- </div> -->
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane p-20" id="not_approved" role="tabpanel">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card-header mb-3 " style="text-align: right;">

                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="statusnotapproved" class="table table-striped table-bordered">
                                                <thead class="gradient-header">
                                                    <tr>

                                                        <th class="text-center"><b>
                                                                <h5>S.No</h5>
                                                            </b></th>
                                                        <th class="col-md-2 text-center"><b>
                                                                <h5>Complaint Date</h5>
                                                            </b></th>
                                                        <th class="text-center"><b>
                                                                <h5>Task ID</h5>
                                                            </b></th>
                                                        <th class="text-center col-md-2"><b>
                                                                <h5>Dept</h5>
                                                            </b></th>
                                                        <th class="col-md-2 text-center"><b>
                                                                <h5>Complaint</h5>
                                                            </b></th>
                                                        <th class="text-center"><b>
                                                                <h5>Priority</h5>
                                                            </b></th>
                                                        <th class="text-center">
                                                            <b>
                                                                <h5>Photos</h5>
                                                            </b>
                                                        </th>
                                                        <th class="text-center"><b>
                                                                <h5>Deadline</h5>
                                                            </b></th>
                                                        <th class="text-center"><b>
                                                                <h5>Comments</h5>
                                                            </b></th>
                                                        <th class="text-center"><b>
                                                                <h5>Status</h5>
                                                            </b></th>
                                                        <th class="text-center"><b>
                                                                <h5>Action</h5>
                                                            </b></th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                                        $count = 1;
                                                                        while ($row = $result4->fetch_assoc()) {
                                                                            echo "<tr>";
                                                                            echo "<td class='text-center'>" . $count++ . "</td>";
                                                                            echo "<td class='text-center'>" . htmlspecialchars($row['date_of_reg']) . "</td>";
                                                                            echo "<td class='text-center'>" . htmlspecialchars($row['task_id']) . "</td>";
                                                                            echo "<td class='text-center'>" . htmlspecialchars($row['dept']) . "</td>";
                                                                        ?>
                                                    <td class='text-center'>
                                                        <button type='button' class='btn btn margin-5 view-complaint
                                                            '
                                                            data-task-id='<?php echo htmlspecialchars($row['task_id']); ?>'>
                                                            <i class="fas fa-eye" style="font-size: 25px;"></i>
                                                        </button>
                                                    </td>
                                                    <?php
                                                                            echo "<td class='text-center'>" . htmlspecialchars($row['priority']) . "</td>";
                                                                            ?>
                                                    <td class='text-center'>
                                                        <button type='button' class='btn  margin-5 showbeforeimg'
                                                            data-task-id='<?php echo htmlspecialchars($row['task_id']); ?>'>
                                                            <i class="fas fa-image" style="font-size: 25px;"></i>
                                                        </button>
                                                    </td>
                                                    <?php
                                                                            echo "<td class='text-center'>" . htmlspecialchars($row['days_to_complete']) . "</td>";
                                                                            echo "<td class='text-center'>" . htmlspecialchars($row['feedback']) . "</td>";
                                                                            echo "<td class='text-center'>Pending</td>";
                                                                            ?>
                                                    <td class='text-center'>
                                                        <button type='button'
                                                            class='btn btn-primary margin-5 start-work-btn '
                                                            data-task-id='<?php echo htmlspecialchars($row['task_id']); ?>'>
                                                            Start to work
                                                        </button>
                                                    </td>
                                                    <?php echo "</tr>";
                                                                        }
                                                                        ?>
                                                </tbody>


                                            </table>
                                        </div>
                                    </div>
                                    <!-- </div> -->
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane p-20" id="completed" role="tabpanel">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card-header mb-3 " style="text-align: right;">

                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="addnewtaskcompleted" class="table table-striped table-bordered">
                                                <thead class="gradient-header">
                                                    <tr>

                                                        <th class="text-center"><b>
                                                                <h5>S.No</h5>
                                                            </b></th>
                                                        <th class="col-md-2 text-center"><b>
                                                                <h5>Complaint Date</h5>
                                                            </b></th>
                                                        <th class="text-center"><b>
                                                                <h5>Task ID</h5>
                                                            </b></th>
                                                        <th class="text-center"><b>
                                                                <h5>Dept</h5>
                                                            </b></th>
                                                        <th class="col-md-2 text-center"><b>
                                                                <h5>Complaint</h5>
                                                            </b></th>
                                                        <th class="text-center">
                                                            <b>
                                                                <h5>Photos</h5>
                                                            </b>
                                                        </th>
                                                        <th class=" col-md-2 text-center"><b>
                                                                <h5>Deadline</h5>
                                                            </b></th>
                                                        <th class=" col-md-2 text-center"><b>
                                                                <h5>Date of completion</h5>
                                                            </b></th>
                                                        <th class="text-center"><b>
                                                                <h5>Status</h5>
                                                            </b></th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                            $count = 1;
                                                            while ($row = $result3->fetch_assoc()) {
                                                                echo "<tr>";
                                                                echo "<td class='text-center'>" . $count++ . "</td>";
                                                                echo "<td class='text-center'>" . htmlspecialchars($row['date_of_reg']) . "</td>";
                                                                echo "<td class='text-center'>" . htmlspecialchars($row['task_id']) . "</td>";
                                                                echo "<td class='text-center'>" . htmlspecialchars($row['dept']) . "</td>";
                                                            ?>
                                                    <td class='text-center'>
                                                        <button type='button' class='btn btn margin-5 view-complaint
                                                            '
                                                            data-task-id='<?php echo htmlspecialchars($row['task_id']); ?>'>
                                                            <i class="fas fa-eye" style="font-size: 25px;"></i>

                                                        </button>
                                                    </td>

                                                    <td>
                                                        <div class="d-flex justify-content-between">
                                                            <!-- Align the first button to the left -->

                                                            <button type='button' class='btn margin-5 showbeforeimg'
                                                                data-task-id='<?php echo htmlspecialchars($row['task_id']); ?>'>
                                                                <i class="fas fa-image" style="font-size: 25px;"></i>
                                                            </button>

                                                            <!-- Align the second button to the right -->
                                                            <button type="button" class="btn I"
                                                                data-task-id='<?php echo htmlspecialchars($row['task_id']); ?>'
                                                                style="margin-left:2px;" data-toggle="modal"
                                                                data-target="#Modal4">
                                                                <i class="fas fa-image" style="font-size: 25px;"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                    <?php
                                                                echo "<td class='text-center'>" . htmlspecialchars($row['days_to_complete']) . "</td>";
                                                                echo "<td class='text-center'>" . htmlspecialchars($row['date_of_completion']) . "</td>";

                                                                ?>
                                                    <td class="text-center"><button type="button" class="btn btn-info "
                                                            data-toggle="modal">
                                                            Completed
                                                        </button></td>
                                                    <?php

                                                                echo "</tr>";
                                                            }
                                                            ?>
                                                </tbody>

                                            </table>
                                        </div>
                                    </div>
                                    <!-- </div> -->
                                </div>
                            </div>
                        </div>

                    </div>
                </div>





            </div>
        </div>
    </div>

     <!-- View Complaint Modal Starts -->
     <div class="modal fade" id="complaint" tabindex="-1" role="dialog"
        aria-labelledby="complaintDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
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

                    <!-- Complaint Info Section arranged in two-column layout -->
                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label class="fw-bold">Faculty ID</label>
                                <div class="text-muted"><b id="id"></b></div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label class="fw-bold">Faculty Name</label>
                                <div class="text-muted"><b id="faculty_name"></b></div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label class="fw-bold">Mobile Number

                                </label>
                                <div class="text-muted"><b id="faculty_contact"></b></div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label class="fw-bold">E-mail</label>
                                <div class="text-muted"><b id="faculty_mail"></b></div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label class="fw-bold">Type of Problem</label>
                                <div class="text-muted"><b id="fac_name"></b></div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label class="fw-bold">Problem Description</label>
                                <div class="text-muted"><b id="fac_id"></b></div>
                            </div>
                        </div>

                        <!-- New row for Venue and Type of Problem -->


                        <!-- Full width for Problem Description -->

                    </div>

                </div>

                <!-- Modal Footer with Save Button -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Problem Description Modal -->

    <div class="modal fade" id="Modal1" tabindex="-1" role="dialog"
        aria-labelledby="complaintDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
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
                    <ul class="list-group">
                        
                        <li class="list-group-item">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold text-primary">Faculty Name</div>
                                <b><span id="vfaculty_name" class="text-secondary"></span></b>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold text-primary">Mobile Number</div>
                                <b><span id="vcontact" class="text-secondary"></span></b>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold text-primary">Block name</div>
                                <b><span id="vblock-content" class="text-secondary"></span></b>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold text-primary">Venue name</div>
                                <b><span id="vvenue-content" class="text-secondary"></span></b>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold text-primary">Problem Description</div>
                                <div class="alert alert-light border rounded">
                                    <b><span id="vproblem-description-content" class="text-secondary"></span></b>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-primary btn-lg rounded-pill" data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
              


            </div>
        </div>
    </div>


    
    <div class="modal fade" id="Modal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Task Completion</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <!-- form -->
                                <form id="taskCompletionForm">
                                    <div class="mb-3">
                                        <label class="form-label">Task ID</label>
                                        <input type="text" class="form-control" id="taskid" value="{{ $d1->task_id ?? ''}}" disabled readonly>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="worker" class="font-weight-bold">Assign Worker:</label>
                                        <select class="form-control" name="worker" id="worker">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" id="oth" name="oth" onclick="checkIfOthers()">
                                        Others
                                    </div>
                                    <div id="othersInput"style="display: none;">
                                        <label class="form-label" for="otherValue">Please specify:</label>
                                        <input placeholder="Enter worker details" type="text" id="otherValue" name="otherworkername">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Add Image-Proof</label>
                                        <input onchange="validateSize(this)" class="form-control" type="file" id="imgafter" name="after_photo">
                                    </div>
                                    <label class="form-label">Task Completion</label><br>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="completionStatus" id="inlineRadio1" value="Fully Completed">
                                        <label class="form-check-label" for="inlineRadio1">Fully Completed</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="completionStatus" id="inlineRadio2" value="Partially Completed">
                                        <label class="form-check-label" for="inlineRadio2">Partially Completed</label>
                                    </div>
                                    <!-- Hidden input field for reason -->
                                    <div class="mb-3 mt-3" id="reason-container" style="display: none">
                                        <label class="form-label">Reason</label>
                                        <input type="text" class="form-control" id="reason" name="reason" placeholder="Enter reason for partial completion">
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" id="save-btn" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!--Before Image Modal -->
                <div class="modal fade" id="Modal3" tabindex="-1" aria-labelledby="imageModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="imageModalLabel">Image</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <img id="modalImage1" src="" alt="Image" class="img-fluid">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                 <!--Before Image Modal -->
                 <div class="modal fade" id="Modal4" tabindex="-1" aria-labelledby="imageModalLabel"
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

    <!-- Footer -->
    <?php include 'footer.php'; ?>
    </div>




    <script src="script.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>


    <script>
            $(function() {
                // Initialize the tooltip
                $('[data-toggle="tooltip"]').tooltip();

                // You can also set options manually if needed
                $('.view-complaint').tooltip({
                    placement: 'top',
                    title: 'View Complaint'
                });
            });


            $(function() {
                // Initialize the tooltip
                $('[data-toggle="tooltip"]').tooltip();

                // You can also set options manually if needed
                $('.showbeforeimg').tooltip({
                    placement: 'top',
                    title: 'Before'
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
            $(document).ready(function() {
                // Initialize DataTables
                var addTable = $('#addnewtask').DataTable({
                });
                var inProgTable = $('#statusinprogress').DataTable({
                });
                var apprTable = $('#approval').DataTable({
                });
                var compTable = $('#addnewtaskcompleted').DataTable({
                });
                var notApprTable = $('#statusnotapproved').DataTable({
                });


            });
        </script>
        <script>
            //viewing complaint details in modal
            $(document).on('click', '.view-complaint', function(e) {
                e.preventDefault();
                var taskId = $(this).data('task-id');

                $.ajax({
                    url: 'cms_backend.php?action=wviewcomp',
                    type: 'POST',
                    data: {
                        task_id: taskId
                    },
                    success: function(response) {
                        console.log("Raw response:", response);

                        // If response is a JSON string, parse it
                        var data = typeof response === "string" ? JSON.parse(response) : response;

                        if (data.error) {
                            alert(data.error);
                        } else {


                            // Update modal content with data
                            $('#vfaculty_name').text(data.faculty_name);
                            $('#vcontact').text(data.faculty_contact);
                            $('#vblock-content').text(data.block_venue);
                            $('#vvenue-content').text(data.venue_name);
                            $('#vproblem-description-content').text(data.problem_description);
                            $('#vdays-remaining-content').text(data.days_to_complete);

                            // Show modal
                            $('#Modal1').modal('show');
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log("AJAX error:", textStatus, errorThrown);
                        alert('Failed to fetch details');
                    }
                });
            });
        </script>
        <script>
            //work completed status in inprogress table
            $(document).on('click', '.work-comp', function(e) {
                e.preventDefault();
                var taskId = $(this).data('task-id');
                console.log(taskId);
                $('#Modal2').modal('show');

                $('#taskid').val(taskId);

            });
        </script>
        <script>
            $('input[id="inlineRadio1"]').on('change', function() {
                if ($(this).val() === 'Fully Completed') {
                    $('#reason-container').hide();
                }
            });

            $('input[id="inlineRadio2"]').on('change', function() {
                if ($(this).val() === 'Partially Completed') {
                    $('#reason-container').show();
                }
            });



            // Handle save button click for work completion
            $(document).on('click', '#save-btn', function(e) {
                var taskId = $('#taskid').val();
                console.log("this id:",taskId);
                var completionStatus = $('input[name="completionStatus"]:checked').val();
                var imgAfter = $('#imgafter')[0].files[0];
                var reason = $('#reason').val(); // Capture reason from the input field
                var w_name = $('#worker').val();
                var o_name = $('#otherValue').val();
                var amt = $('#amtspent').val();
                var p_id = $('#complaint_id77').val();
                console.log(w_name);
                console.log(o_name);

                if (!taskId || !completionStatus) {
                    Swal.fire({
                        title: "Invalid!",
                        text: "Please provide all required information.",
                        icon: "error"
                    });
                    return;
                }

                // Prepare form data for submission
                var formData = new FormData();
                formData.append("update", true);
                formData.append('task_id', taskId);
                formData.append('completion_status', completionStatus);
                formData.append('reason', reason); // Append reason to form data
                formData.append('w_name', w_name);
                formData.append('o_name', o_name);
                formData.append('p_id', p_id);
                formData.append('amt', amt);

                if (imgAfter) {
                    formData.append('img_after', imgAfter);
                }
                for (const [key, value] of formData.entries()) {
                    console.log(`${key}: ${value}`);
                }

                // AJAX request to submit the form data
                $.ajax({
                    url: 'cms_backend.php?action=workcompletion',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        Swal.fire({
                            title: "Updated!",
                            text: "Work is Completed",
                            icon: "success"
                        });
                        $('#Modal2').modal('hide');

                        // Refresh specific sections dynamically
                        setTimeout(function() {
                            $('#ref1').load(location.href + " #ref1");
                            $('#ref2').load(location.href + " #ref2");

                            $('#ref3').load(location.href + " #ref3");

                            $('#ref4').load(location.href + " #ref4");

                            $('#ref5').load(location.href + " #ref5");
                            $('#addnewtask').DataTable().destroy();

                            $("#addnewtask").load(location.href + " #addnewtask > *", function() {
                                // Reinitialize the DataTable after the content is loaded
                                $('#addnewtask').DataTable();
                            });
                            $('#statusinprogress').DataTable().destroy();

                            $("#statusinprogress").load(location.href + " #statusinprogress > *", function() {
                                // Reinitialize the DataTable after the content is loaded
                                $('#statusinprogress').DataTable();
                            });

                            $('#approval').DataTable().destroy();

                            $("#approval").load(location.href + " #approval > *", function() {
                                // Reinitialize the DataTable after the content is loaded
                                $('#approval').DataTable();
                            });

                            $('#addnewtaskcompleted').DataTable().destroy();

                            $("#addnewtaskcompleted").load(location.href + " #addnewtaskcompleted > *", function() {
                                // Reinitialize the DataTable after the content is loaded
                                $('#addnewtaskcompleted').DataTable();
                            });

                            $('#statusnotapproved').DataTable().destroy();

                            $("#statusnotapproved").load(location.href + " #statusnotapproved > *", function() {
                                // Reinitialize the DataTable after the content is loaded
                                $('#statusnotapproved').DataTable();
                            });

                        }, 500); // Adding a delay to ensure the sections are reloaded after the update
                    },
                    error: function() {
                        Swal.fire({
                            title: "Invalid!",
                            text: "An error occurred. Please try again.",
                            icon: "error"
                        });
                    }
                });
                sendmailCompletion(taskId);
            });

            function sendmailCompletion(id){
                $.ajax({
        type: "POST",
        url: "cms_mail.php",
        data: {
            'work_completed': true,
            'id': id,
        },
        success: function (response) {
            var res = jQuery.parseJSON(response);
            if (res.status == 200) {
                console.log("Mail sent successfully!!");
            }
        },
        error: function (xhr, status, error) {
            console.error("Mail AJAX error:", error);
        }
    });                
            }

            function checkIfOthers() {
                const dropdown = document.getElementById('oth');
                const othersInput = document.getElementById('othersInput');
                const sel = document.getElementById('worker');

                // Show the input field if "Others" is selected
                if (dropdown.checked) {
                    othersInput.style.display = 'block';
                    sel.value = "";
                } else {
                    othersInput.style.display = 'none';

                }
            }


            // Show the reason input field only when 'Partially Completed' is selected
        </script>
        <script>
            //after image showing
            // Show image
            // Show image
            $(document).on('click', '.I', function(e) {
                e.preventDefault(); // Prevent form submission
                var task_id = $(this).data('task-id');
                console.log(task_id);

                $.ajax({
                    type: "POST",
                    url: 'cms_backend.php?action=wafterimage',
                    data: {
                        'task_id': task_id
                    },
                    dataType: "json",
                    success: function(response) {
                        console.log(response);

                        if (response.status == 200) {
                            console.log('Image Path:', response.data.after_photo);

                            if (response.data.after_photo) {
                                $('#modalImage').attr('src', response.data.after_photo);
                            } else {
                                alert('No image found.');
                            }

                            $('#Modal4').modal('show');
                        } else {
                            alert(response.message || 'An error occurred while retrieving the image.');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error: ", xhr.responseText);
                        alert('An error occurred: ' + error + "\nStatus: " + status + "\nDetails: " + xhr.responseText);
                    }
                });
            });
        </script>
        <script>
            //before image showing
            // Show image
            // Show image
            $(document).on('click', '.showbeforeimg', function(e) {
                e.preventDefault();
                var task_id = $(this).data('task-id');
                console.log(task_id);

                $.ajax({
                    type: "POST",
                    url: 'cms_backend.php?action=wbeforeimg',
                    data: {
                        'task_id': task_id
                    },
                    dataType: "json",
                    success: function(response) {
                        console.log(response);

                        if (response.status == 200) {
                            console.log('Image Path:', response.data.after_photo);

                            if (response.data.after_photo) {
                                $('#modalImage1').attr('src', response.data.after_photo);
                            } else {
                                alert('No image found.');
                            }

                            $('#Modal3').modal('show');
                        } else {
                            alert(response.message || 'An error occurred while retrieving the image.');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error: ", xhr.responseText);
                        alert('An error occurred: ' + error + "\nStatus: " + status + "\nDetails: " + xhr.responseText);
                    }
                });
            });
        </script>
        <script>
            //file validation after image
            function validateSize(input) {
                const fileSize = input.files[0].size / 1024;
                var ext = input.value.split(".");
                ext = ext[ext.length - 1].toLowerCase();
                var arrayExtensions = ["jpg", "jpeg", "png"];
                if (arrayExtensions.lastIndexOf(ext) == -1) {
                    alert("Invalid file type");
                    $(input).val('');

                } else if (fileSize > 2048057) {
                    alert("file is too large");
                    $(input).val('');
                }

            }


            $(document).on("click", ".work-comp", function(e) {
                e.preventDefault();

                var user_id = $(this).val(); // Get the ID from the button's value
                console.log("User ID:", user_id);

                // Set the complaint ID in the hidden input field within the form
                $("#complaint_id77").val(user_id);

                // Reset the worker selection and the text in the modal
                $("#worker_id").val(''); // Reset the worker ID
                $("#assignedWorker").text('Assigned Worker: '); // Reset the assigned worker text
            });

            $(document).on("click", ".work-comp", function(e) {
                e.preventDefault();
                var worker_dept = $(this).data("value");
                console.log(worker_dept);

                $.ajax({
                    url: 'cms_backend.php?action=wworkerassign',
                    type: "POST",
                    data: {
                        "worker_dept": worker_dept
                    },
                    success: function(response) {
                        // Inject the received HTML options into the <select> element
                        $('#worker').html(response);
                    }
                });
            });



            $(document).ready(function() {
                $('.start-work-btn').click(function(e) {
                    e.preventDefault();
                    var taskId = $(this).data('task-id');
                    console.log(taskId);

                    $.ajax({
                        url: 'cms_backend.php?action=wrestart',
                        type: 'POST',
                        data: {
                            start_work: true,
                            task_id: taskId
                        },
                        success: function(response) {
                            var res = jQuery.parseJSON(response);
                            if (res.status == 200) {
                                $('#addnewtask').DataTable().destroy();

                                $("#addnewtask").load(location.href + " #addnewtask > *", function() {
                                    // Reinitialize the DataTable after the content is loaded
                                    $('#addnewtask').DataTable();
                                });
                                $('#statusinprogress').DataTable().destroy();

                                $("#statusinprogress").load(location.href + " #statusinprogress > *", function() {
                                    // Reinitialize the DataTable after the content is loaded
                                    $('#statusinprogress').DataTable();
                                });

                                $('#approval').DataTable().destroy();

                                $("#approval").load(location.href + " #approval > *", function() {
                                    // Reinitialize the DataTable after the content is loaded
                                    $('#approval').DataTable();
                                });

                                $('#addnewtaskcompleted').DataTable().destroy();

                                $("#addnewtaskcompleted").load(location.href + " #addnewtaskcompleted > *", function() {
                                    // Reinitialize the DataTable after the content is loaded
                                    $('#addnewtaskcompleted').DataTable();
                                });

                                $('#statusnotapproved').DataTable().destroy();

                                $("#statusnotapproved").load(location.href + " #statusnotapproved > *", function() {
                                    // Reinitialize the DataTable after the content is loaded
                                    $('#statusnotapproved').DataTable();
                                });

                                $('#ref1').load(location.href + " #ref1");
                                $('#ref2').load(location.href + " #ref2");

                                $('#ref3').load(location.href + " #ref3");

                                $('#ref4').load(location.href + " #ref4");

                                $('#ref5').load(location.href + " #ref5");




                            } else {
                                alert('Something went wrong')
                            }
                        }
                    });
                });
            });
        </script>
</body>

</html>