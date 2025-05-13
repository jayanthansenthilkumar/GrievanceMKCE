<?php
require "config.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

// Function to send email
function sendMail($email, $subject, $body) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'mkceinfocorner@gmail.com';
        $mail->Password = 'npdllnbipximwvnq';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('mkceinfocorner@gmail.com', 'MKCE Info Corner');
        $mail->addAddress($email);

        $mail->Subject = $subject;
        $mail->isHTML(true);
        $mail->Body = $body;

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

// Complaint Rejection Mail
if (isset($_POST['reject_mail'])) {
    $id = $_POST['id'];
    $user = $_POST['user_type'];
    
    $query = "SELECT * FROM complaints_detail WHERE id='$id'";
    $query_run = mysqli_query($db, $query);
    $query_val = mysqli_fetch_array($query_run);
    
    $fac_id = $query_val['faculty_id'];
    $com_des = $query_val['problem_description'];
    
    $q1 = "SELECT * FROM basic WHERE id='$fac_id'";
    $run = mysqli_query($db, $q1);
    $val = mysqli_fetch_array($run);
    $email = $val['email'];

    $subject = "Complaint Rejection Notification";
    $body = "<p>Dear User,</p>
            <p>We regret to inform you that your complaint has been rejected.</p>
            <p><strong>Complaint ID:</strong> $id</p>
            <p><strong>Description:</strong> $com_des</p>
            <p><strong>Rejected by:</strong> $user</p>
            <p>To get more information vist Complaint Management System.</p>
            <p>Best Regards,<br>MKCE Info Corner</p>";

    if (sendMail($email, $subject, $body)) {
        echo json_encode(['status' => 200, 'message' => 'Rejection mail sent successfully!']);
    } else {
        echo 'Email could not be sent.';
    }
    return;
}

// Work Started Mail
if (isset($_POST['work_started'])) {
    $id = $_POST['id'];
    
    $query = "SELECT * FROM complaints_detail WHERE id='$id'";
    $query_run = mysqli_query($db, $query);
    $query_val = mysqli_fetch_array($query_run);
    
    $fac_id = $query_val['faculty_id'];
    $com_des = $query_val['problem_description'];
    $deadline = $query_val['days_to_complete'];
    
    $q1 = "SELECT * FROM basic WHERE id='$fac_id'";
    $run = mysqli_query($db, $q1);
    $val = mysqli_fetch_array($run);
    $email = $val['email'];

    $subject = "Work Started on Your Complaint";
    $body = "<p>Dear User,</p>
            <p>Your complaint has been accepted, and work has begun.</p>
            <p><strong>Complaint ID:</strong> $id</p>
            <p><strong>Description:</strong> $com_des</p>
            <p><strong>Estimated Completion Date:</strong> $deadline</p>
            <p>We will notify you once the work is completed.</p>
            <p>Best Regards,<br>MKCE Info Corner</p>";

    sendMail($email, $subject, $body);
    echo json_encode(['status' => 200]);
    return;
}

// Deadline Extension Mail
if (isset($_POST['dealine_extend_mail'])) {
    $id = $_POST['id'];
    $dead_date = $_POST['extend_deadline'];
    $reason = $_POST['reason'];

    $query = "SELECT * FROM complaints_detail WHERE id='$id'";
    $query_run = mysqli_query($db, $query);
    $query_val = mysqli_fetch_array($query_run);
    
    $fac_id = $query_val['faculty_id'];
    $com_des = $query_val['problem_description'];
    
    $q1 = "SELECT * FROM basic WHERE id='$fac_id'";
    $run = mysqli_query($db, $q1);
    $val = mysqli_fetch_array($run);
    $email = $val['email'];

    $subject = "Complaint Deadline Extension";
    $body = "<p>Dear User,</p>
            <p>The deadline for your complaint resolution has been extended.</p>
            <p><strong>Complaint ID:</strong> $id</p>
            <p><strong>Description:</strong> $com_des</p>
            <p><strong>New Deadline:</strong> $dead_date</p>
            <p><strong>Reason:</strong> $reason</p>
            <p>Thank you for your patience.</p>
            <p>Best Regards,<br>MKCE Info Corner</p>";

    sendMail($email, $subject, $body);
    echo json_encode(['status' => 200]);
    return;
}

// Work Completion Mail
if (isset($_POST['work_completed'])) {
    $id = $_POST['id'];

    $q4 = "SELECT * FROM manager WHERE task_id ='$id'";
    $q4run = mysqli_query($db, $q4);
    $q4val = mysqli_fetch_array($q4run);
    $cid = $q4val['problem_id'];

    $query = "SELECT * FROM complaints_detail WHERE id='$cid'";
    $query_run = mysqli_query($db, $query);
    $query_val = mysqli_fetch_array($query_run);
    
    $fac_id = $query_val['faculty_id'];
    $com_des = $query_val['problem_description'];
    
    $q1 = "SELECT * FROM basic WHERE id='$fac_id'";
    $run = mysqli_query($db, $q1);
    $val = mysqli_fetch_array($run);
    $email = $val['email'];

    $subject = "Work Completed - Feedback Requested";
    $body = "<p>Dear User,</p>
            <p>We are pleased to inform you that your complaint has been resolved.</p>
            <p><strong>Complaint ID:</strong> $cid</p>
            <p><strong>Description:</strong> $com_des</p>
            <p>Please provide your feedback within 24 hours.</p>
            <p>Best Regards,<br>MKCE Info Corner</p>";

    sendMail($email, $subject, $body);
    echo json_encode(['status' => 200]);
    return;
}
