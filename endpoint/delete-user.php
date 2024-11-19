<?php
include('../conn/conn.php');

if (isset($_GET['user'])) {
    $userID = $_GET['user'];

    $env_hostname= 'https://sturdy-funicular-jjqgrrwp69gw3546p-80.app.github.dev/Cloud-Boyz/home.php';
    $env_hostname_error= 'https://sturdy-funicular-jjqgrrwp69gw3546p-80.app.github.dev/Cloud-Boyz/index.php';

    try {
        $stmt = $conn->prepare("SELECT `tbl_user_id` FROM `tbl_user` WHERE `tbl_user_id` = :userID");
        $stmt->execute([
            'userID' => $userID,
        ]);
        $accountExists = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!empty($accountExists)) {
            $conn->beginTransaction();

            $deleteUserStmt = $conn->prepare("DELETE FROM `tbl_user` WHERE `tbl_user_id` = :userID ");
            $deleteUserStmt->bindParam(':userID', $userID, PDO::PARAM_INT);
            $deleteUserStmt->execute();

            $deleteAccountsStmt = $conn->prepare("DELETE FROM `tbl_accounts` WHERE `tbl_user_id` = :userID");
            $deleteAccountsStmt->bindParam(':userID', $userID, PDO::PARAM_INT);
            $deleteAccountsStmt->execute();

            $conn->commit();
            echo "
            <script>
                alert('User Deleted Successfully, back to Login');
                window.location.href = '$env_hostname_error';
            </script>
            ";
        } else {
            echo "
            <script>
                alert('User account not found');
                window.location.href = '$env_hostname';
            </script>
            ";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "
    <script>
        alert('Invalid request. Please select an user to delete.');
        window.location.href = '$env_hostname';
    </script>
    ";
}

?>
