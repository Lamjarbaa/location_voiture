<?php
session_start(); 
?>
<div class="nk-app-root">
        <div class="nk-main ">
            <div class="nk-sidebar nk-sidebar-fixed is-dark " data-content="sidebarMenu">
                <div class="nk-sidebar-element nk-sidebar-head">
                    <div class="nk-menu-trigger">
                        <a href="#" class="nk-nav-toggle nk-quick-nav-icon d-xl-none" data-target="sidebarMenu"><em class="icon ni ni-arrow-left"></em></a>
                        <a href="#" class="nk-nav-compact nk-quick-nav-icon d-none d-xl-inline-flex" data-target="sidebarMenu"><em class="icon ni ni-menu"></em></a>
                    </div>
                    <!-- <div class="nk-sidebar-brand">
                        <a href="html/index.html" class="logo-link nk-sidebar-logo">
                            <img class="logo-light logo-img" src="./images/logo.png" srcset="./images/logo2x.png 2x" alt="logo">
                            <img class="logo-dark logo-img" src="./images/logo-dark.png" srcset="./images/logo-dark2x.png 2x" alt="logo-dark">
                        </a>
                    </div> -->
                </div>
                <div class="nk-sidebar-element nk-sidebar-body">
                    <div class="nk-sidebar-content">
                        <div class="nk-sidebar-menu" data-simplebar>
                            <ul class="nk-menu">
                                <li class="nk-menu-item">
                                    <a href="dashbord.php" class="nk-menu-link">
                                        <span class="nk-menu-icon"><em class="icon ni ni-dashboard-fill"></em></span>
                                        <span class="nk-menu-text">Tableau de bord</span>
                                    </a>
                                </li>
                                <li class="nk-menu-item">
                                    <a href="gestion-utilisateurs.php" class="nk-menu-link">
                                        <span class="nk-menu-icon"><em class="icon ni ni-layers-fill"></em></span>
                                        <span class="nk-menu-text">Gestion des utilisateurs</span>
                                    </a>
                                </li>
                                <li class="nk-menu-item">
                                    <a href="gestion-categories-voitures.php" class="nk-menu-link">
                                        <span class="nk-menu-icon"><em class="icon ni ni-layers-fill"></em></span>
                                        <span class="nk-menu-text">Gestion des categories <br>des voitures</span>
                                    </a>
                                </li>   
                                <li class="nk-menu-item">
                                    <a href="gestion-saison.php" class="nk-menu-link">
                                        <span class="nk-menu-icon"><em class="icon ni ni-layers-fill"></em></span>
                                        <span class="nk-menu-text">Gestion des saisons</span>
                                    </a>
                                </li>
                                <li class="nk-menu-item">
                                    <a href="gestion-vehicule.php" class="nk-menu-link">
                                        <span class="nk-menu-icon"><em class="icon ni ni-layers-fill"></em></span>
                                        <span class="nk-menu-text">Gestion des Véhicules </span>
                                    </a>
                                </li>
                                <li class="nk-menu-item">
                                    <a href="gestion-ville.php" class="nk-menu-link">
                                        <span class="nk-menu-icon"><em class="icon ni ni-layers-fill"></em></span>
                                        <span class="nk-menu-text">Gestion des ville</span>
                                    </a>
                                </li>
                                <li class="nk-menu-item">
                                    <a href="gestion-qarties.php" class="nk-menu-link">
                                        <span class="nk-menu-icon"><em class="icon ni ni-layers-fill"></em></span>
                                        <span class="nk-menu-text">Gestion des Qarties</span>
                                    </a>
                                </li>
                                <li class="nk-menu-item">
                                    <a href="gestion-formulaire.php" class="nk-menu-link">
                                        <span class="nk-menu-icon"><em class="icon ni ni-layers-fill"></em></span>
                                        <span class="nk-menu-text">Gestion des formulaires</span>
                                    </a>
                                </li>
                                <li class="nk-menu-item">
                                    <a href="gestion-reservation.php" class="nk-menu-link">
                                        <span class="nk-menu-icon"><em class="icon ni ni-layers-fill"></em></span>
                                        <span class="nk-menu-text">Gestion des reservations</span>
                                    </a>
                                </li>
                                <li class="nk-menu-item">
                                    <a href="gestion-plage-prix.php" class="nk-menu-link">
                                        <span class="nk-menu-icon"><em class="icon ni ni-layers-fill"></em></span>
                                        <span class="nk-menu-text">Gestion de plage de prix</span>
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            