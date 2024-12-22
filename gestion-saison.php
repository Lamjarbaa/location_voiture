<?php
include 'db.php';

// Add new season
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
    $name_fr = htmlspecialchars($_POST['nom_saison_fr']);
    $name_en = htmlspecialchars($_POST['nom_saison_en']);
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    $stmt = $conn->prepare("INSERT INTO app_saison (nom_saison_fr, nom_saison_en, start_time, end_time) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name_fr, $name_en, $start_time, $end_time);

    if ($stmt->execute()) {
        header("Location: {$_SERVER['PHP_SELF']}?success=added");
        exit;
    } else {
        header("Location: {$_SERVER['PHP_SELF']}?error=" . urlencode($stmt->error));
        exit;
    }
    $stmt->close();
}

// Delete season
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM app_saison WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: {$_SERVER['PHP_SELF']}?success=deleted");
        exit;
    } else {
        header("Location: {$_SERVER['PHP_SELF']}?error=" . urlencode($stmt->error));
        exit;
    }
    $stmt->close();
}

// Update season
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'update') {
    $id = intval($_POST['seasonId']);
    $name_fr = htmlspecialchars($_POST['nom_saison_fr']);
    $name_en = htmlspecialchars($_POST['nom_saison_en']);
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    $stmt = $conn->prepare("UPDATE app_saison SET nom_saison_fr = ?, nom_saison_en = ?, start_time = ?, end_time = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $name_fr, $name_en, $start_time, $end_time, $id);

    if ($stmt->execute()) {
        header("Location: {$_SERVER['PHP_SELF']}?success=updated");
        exit;
    } else {
        header("Location: {$_SERVER['PHP_SELF']}?error=" . urlencode($stmt->error));
        exit;
    }
    $stmt->close();
}
?>


    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="author" content="Softnio">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="Car category management dashboard">
        <link rel="shortcut icon" href="./images/favicon.png">
        <title>Gestion des saisons</title>
        <link rel="stylesheet" href="src/assets/css/dashlite.css">
        <link id="skin-default" rel="stylesheet" href="src/assets/css/theme.css">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    </head>
    <body class="nk-body bg-lighter npc-general has-sidebar">
        <?php include 'sidebare.php'; ?>
        <div class="nk-wrap">
            <?php include 'head.php'; ?>
            <div class="nk-content">
                <div class="container-fluid">
                    <div class="nk-content-inner">
                        <div class="nk-content-body">
                            <div class="nk-block-between">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title page-title">Saisons</h3>
                                    <div class="nk-block-des text-soft">
                                        <p>Toutes les saisons disponibles</p>
                                    </div>
                                </div>
                                <div class="nk-block-head-content">
                                    <div class="toggle-wrap nk-block-tools-toggle">
                                        <div class="toggle-expand-content" data-content="pageMenu">
                                            <ul class="nk-block-tools g-3">
                                                <div class="dropdown">
                                                    <a href="#" class="dropdown-toggle btn btn-icon btn-primary" data-bs-toggle="modal" data-bs-target="#addSeasonModal" title="Ajouter">
                                                        <em class="icon ni ni-plus"></em>
                                                    </a>
                                                </div>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Add Season Modal -->
                            <div class="modal fade" id="addSeasonModal">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Ajouter une saison</h5>
                                            <a href="#" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                <em class="icon ni ni-cross"></em>
                                            </a>
                                        </div>
                                        <div class="modal-body">
                                            <form id="seasonForm">
                                                <input type="hidden" name="action" value="add">
                                                <div class="form-group">
                                                    <label for="nom_saison_fr">Nom de la saison (FR)</label>
                                                    <input type="text" class="form-control" id="nom_saison_fr" name="nom_saison_fr">
                                                </div>
                                                <div class="form-group">
                                                    <label for="nom_saison_en">Nom de la saison (EN)</label>
                                                    <input type="text" class="form-control" id="nom_saison_en" name="nom_saison_en" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="start_time">Date de début</label>
                                                    <input type="date" class="form-control" id="start_time" name="start_time" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="end_time">Date de fin</label>
                                                    <input type="date" class="form-control" id="end_time" name="end_time" required>
                                                </div>

                                                <button type="submit" class="btn btn-primary">Ajouter</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Edit Season Modal -->
                            <div class="modal fade" id="editSeasonModal">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Modifier une saison</h5>
                                            <a href="#" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                <em class="icon ni ni-cross"></em>
                                            </a>
                                        </div>
                                        <div class="modal-body">
                                            <form id="editSeasonForm" method="POST" action="">
                                                <input type="hidden" name="action" value="update">
                                                <input type="hidden" name="seasonId">
                                                <div class="form-group">
                                                    <label for="edit_nom_saison_fr">Nom de la saison (FR)</label>
                                                    <input type="text" class="form-control" id="edit_nom_saison_fr" name="nom_saison_fr">
                                                </div>
                                                <div class="form-group">
                                                    <label for="edit_nom_saison_en">Nom de la saison (EN)</label>
                                                    <input type="text" class="form-control" id="edit_nom_saison_en" name="nom_saison_en" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="edit_start_time">Date de début</label>
                                                    <input type="date" class="form-control" id="edit_start_time" name="start_time" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="edit_end_time">Date de fin</label>
                                                    <input type="date" class="form-control" id="edit_end_time" name="end_time" required>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Enregistrer</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Seasons Table -->
                            <div class="card card-bordered card-preview">
                                <div class="card-inner">
                                    <div class="nk-block">
                                        <table class="datatable-init nowrap nk-tb-list nk-tb-ulist" data-auto-responsive="false" id="seasonTable">
                                            <thead>
                                                <tr class="nk-tb-item nk-tb-head">
                                                    <th class="nk-tb-col">Nom de la saison (FR)</th>
                                                    <th class="nk-tb-col">Nom de la saison (EN)</th>
                                                    <th class="nk-tb-col">Date de début</th>
                                                    <th class="nk-tb-col">Date de fin</th>
                                                    <th class="nk-tb-col nk-tb-col-tools text-end"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                // Fetch seasons from the database
                                                $result = $conn->query("SELECT * FROM app_saison");
                                                if ($result->num_rows > 0) {
                                                    while ($row = $result->fetch_assoc()) {
                                                        echo "<tr class='nk-tb-item'>
                                                                <td class='nk-tb-col'>{$row['nom_saison_en']}</td>
                                                                <td class='nk-tb-col'>{$row['nom_saison_fr']}</td>
                                                                <td class='nk-tb-col'>{$row['start_time']}</td>
                                                                <td class='nk-tb-col'>{$row['end_time']}</td>
                                                                <td class='nk-tb-col nk-tb-col-tools'>
                                                                    <ul class='nk-tb-actions gx-2'>
                                                                        <li>
                                                                            <a href='#' class='btn btn-trigger btn-icon btn-edit' 
                                                                            data-id='" . $row['id'] . "' 
                                                                            data-name-en='" . htmlspecialchars($row['nom_saison_en'], ENT_QUOTES) . "' 
                                                                            data-name-fr='" . htmlspecialchars($row['nom_saison_fr'], ENT_QUOTES) . "' 
                                                                            data-start-time='" . $row['start_time'] . "' 
                                                                            data-end-time='" . $row['end_time'] . "' 
                                                                            data-bs-toggle='modal' data-bs-target='#editSeasonModal'>
                                                                            <em class='icon ni ni-edit'></em>
                                                                            </a>
                                                                        </li>
                                                                        <li>
                                                                        <a class='btn btn-trigger btn-icon btn-trash' href='?action=delete&id=" . $row['id'] . "' onclick='return confirm(\"Confirmer la suppression ?\")'>
                                                                            <em class='icon ni ni-trash'></em>
                                                                        </a>
                                                                        </li>
                                                                    </ul>
                                                                </td>
                                                            </tr>";
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='7'>Aucune saison trouvée</td></tr>";
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

        <script>
$(document).ready(function () {
    // Add new season
    $('#seasonForm').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            url: '', // Same PHP file
            type: 'POST',
            data: $(this).serialize(),
            success: function () {
                alert('Saison ajoutée avec succès!');
                location.reload();
            },
            error: function () {
                alert('Une erreur s\'est produite.');
            }
        });
    });

    // Update season
    $('#editSeasonForm').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            url: '', // Same PHP file
            type: 'POST',
            data: $(this).serialize(),
            success: function () {
                alert('Saison mise à jour avec succès!');
                location.reload();
            },
            error: function () {
                alert('Une erreur s\'est produite lors de la mise à jour.');
            }
        });
    });

    // Populate edit form
    $(document).on('click', '.btn-edit', function() {
        var id = $(this).data('id');
        var name_en = $(this).data('name-en');
        var name_fr = $(this).data('name-fr');
        var start_time = $(this).data('start-time');
        var end_time = $(this).data('end-time');
        $('#editSeasonModal').find('input[name="seasonId"]').val(id);
        $('#editSeasonModal').find('input[name="nom_saison_en"]').val(name_en);
        $('#editSeasonModal').find('input[name="nom_saison_fr"]').val(name_fr);
        $('#editSeasonModal').find('input[name="start_time"]').val(start_time);
        $('#editSeasonModal').find('input[name="end_time"]').val(end_time);
    });
});
    </script>

    </body>
    </html>
