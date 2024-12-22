<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}

?>
<div class="nk-header nk-header-fixed is-light">
        <div class="container-fluid">
            <div class="nk-header-wrap">
                <div class="nk-menu-trigger d-xl-none ms-n1">
                    <a href="#" class="nk-nav-toggle nk-quick-nav-icon" data-target="sidebarMenu"><em class="icon ni ni-menu"></em></a>
                </div>
                <div class="nk-header-brand d-xl-none">
                    <a href="html/index.php" class="logo-link">
                        <img class="logo-light logo-img" src="./images/logo.png" srcset="./images/logo2x.png 2x" alt="logo">
                        <img class="logo-dark logo-img" src="./images/logo-dark.png" srcset="./images/logo-dark2x.png 2x" alt="logo-dark">
                    </a>
                </div>
                
<?php
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}
$email = $_SESSION['email'];
?>

<div class="nk-header-tools">
    <ul class="nk-quick-nav">
        <li class="dropdown user-dropdown">
            <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown">
                <div class="user-toggle">
                    <div class="user-avatar sm">
                        <em class="icon ni ni-user-alt"></em>
                    </div>
                    <div class="user-info d-none d-md-block">
                    <div class="user-name dropdown-indicator"><?php echo $email; ?></div>
                    </div>
                </div>
            </a>
            <div class="dropdown-menu dropdown-menu-md dropdown-menu-end dropdown-menu-s1">
                <div class="dropdown-inner user-card-wrap bg-lighter d-none d-md-block">
                    <div class="user-card">
                        <div class="user-avatar">
                            <span><?php echo strtoupper(substr($email, 0, 2)); ?></span>
                        </div>
                        <div class="user-info">
                            <span class="lead-text"><?php echo $email; ?></span>
                        </div>
                    </div>
                </div>
                <div class="dropdown-inner">
                    <ul class="link-list">
                        <li><a href="user-profile-regular.html"><em class="icon ni ni-user-alt"></em><span>Voir le profil</span></a></li>
                    </ul>
                </div>
                <div class="dropdown-inner">
                    <ul class="link-list">
                        <li>
                            <form action="logout.php" method="post">
                                <button type="submit" class="btn btn-link" style="border: none; padding: 0; background: none;">
                                    <em class="icon ni ni-signout"></em><span>Se déconnecter</span>
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </li>
    </ul>
</div>
                                   
                  
                </div>
            </div>
        </div>
   
