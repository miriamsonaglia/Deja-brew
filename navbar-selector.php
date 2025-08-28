<?php
    $userRole = $_SESSION['UserRole'] ?? Role::GUEST->value;
    switch ($userRole) {
        case Role::GUEST->value:
            include('./reusables/navbars/empty-navbar.php');
            break;
        case Role::BUYER->value:
            include('./reusables/navbars/buyer-navbar.php');
            break;
        case Role::VENDOR->value:
            include('./reusables/navbars/vendor-navbar.php');
            break;
        default:
            include('./reusables/navbars/empty-navbar.php');
            break;
    }
?>