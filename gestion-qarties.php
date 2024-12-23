<?php
include 'db.php';

// Add new qartie
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
    $name_depart = htmlspecialchars($_POST['name_depart']);
    $name_retour = htmlspecialchars($_POST['name_retour']);

    $stmt = $conn->prepare("INSERT INTO app_qarties (name_depart, name_retour) VALUES (?, ?)");
    $stmt->bind_param("ss", $name_depart, $name_retour);

    if ($stmt->execute()) {
        header("Location: {$_SERVER['PHP_SELF']}?success=added");
        exit;
    } else {
        header("Location: {$_SERVER['PHP_SELF']}?error=" . urlencode($stmt->error));
        exit;
    }
    $stmt->close();
}

// Delete qartie
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM app_qarties WHERE id = ?");
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

// Update qartie
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'update') {
    $id = intval($_POST['qartieId']);
    $name_depart = htmlspecialchars($_POST['name_depart']);
    $name_retour = htmlspecialchars($_POST['name_retour']);

    $stmt = $conn->prepare("UPDATE app_qarties SET name_depart = ?, name_retour = ? WHERE id = ?");
    $stmt->bind_param("ssi", $name_depart, $name_retour, $id);

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
    <link rel="shortcut icon" href="./images/favicon.png">
    <title>Gestion des qarties</title>
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
                                <h3 class="nk-block-title page-title">Gestion des qarties</h3>
                                <div class="nk-block-des text-soft">
                                    <p>Toutes les qarties disponibles</p>
                                </div>
                            </div>
                            <div class="nk-block-head-content">
                                <div class="toggle-wrap nk-block-tools-toggle">
                                    <div class="toggle-expand-content" data-content="pageMenu">
                                        <ul class="nk-block-tools g-3">
                                            <div class="dropdown">
                                                <a href="#" class="dropdown-toggle btn btn-icon btn-primary" data-bs-toggle="modal" data-bs-target="#addQartieModal" title="Ajouter">
                                                    <em class="icon ni ni-plus"></em>
                                                </a>
                                            </div>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Add qartie Modal -->
                        <div class="modal fade" id="addQartieModal">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Ajouter une qartie</h5>
                                        <a href="#" class="close" data-bs-dismiss="modal" aria-label="Close">
                                            <em class="icon ni ni-cross"></em>
                                        </a>
                                    </div>
                                    <div class="modal-body">
                                        <form id="qartieForm">
                                            <input type="hidden" name="action" value="add">
                                            <div class="form-group">
                                                <label for="name_depart">Point de départ</label>
                                                <input type="text" class="form-control" id="name_depart" name="name_depart" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="name_retour">Point de retour</label>
                                                <input type="text" class="form-control" id="name_retour" name="name_retour" required>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Ajouter</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Edit City Modal -->
                        <div class="modal fade" id="editQartieModal">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Modifier une qartie</h5>
                                        <a href="#" class="close" data-bs-dismiss="modal" aria-label="Close">
                                            <em class="icon ni ni-cross"></em>
                                        </a>
                                    </div>
                                    <div class="modal-body">
                                        <form id="editQartieForm" method="POST" action="">
                                            <input type="hidden" name="action" value="update">
                                            <input type="hidden" name="qartieId">
                                            <div class="form-group">
                                                <label for="edit_name_depart">point de la ville</label>
                                                <input type="text" class="form-control" id="edit_name_depart" name="name_depart" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="edit_name_retour">point de la ville</label>
                                                <input type="text" class="form-control" id="edit_name_retour" name="name_retour" required>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Cities Table -->
                        <div class="card card-bordered card-preview">
                            <div class="card-inner">
                                <div class="nk-block">
                                <table class="datatable-init nowrap nk-tb-list nk-tb-ulist" data-auto-responsive="false" id="qartieTable">
                                        <thead>
                                            <tr class="nk-tb-item nk-tb-head">
                                                <th class="nk-tb-col">Point départ</th>
                                                <th class="nk-tb-col">Point retour</th>
                                                <th class="nk-tb-col">Créée le</th>  <!-- New column for created_at -->
                                                <th class="nk-tb-col">Modifiée le</th>  <!-- New column for updated_at -->
                                                <th class="nk-tb-col nk-tb-col-tools text-end"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // Fetch cities from the database
                                            $result = $conn->query("SELECT * FROM app_qarties");
                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    echo "<tr class='nk-tb-item'>
                                                            <td class='nk-tb-col'>{$row['name_depart']}</td>
                                                            <td class='nk-tb-col'>{$row['name_retour']}</td>
                                                            <td class='nk-tb-col'>" . date('d-m-Y H:i:s', strtotime($row['created_at'])) . "</td>
                                                            <td class='nk-tb-col'>" . date('d-m-Y H:i:s', strtotime($row['updated_at'])) . "</td>
                                                            <td class='nk-tb-col nk-tb-col-tools'>
                                                                <ul class='nk-tb-actions gx-2'>
                                                                    <li>
                                                                    <a href='#' class='btn btn-trigger btn-icon btn-edit' 
                                                                        data-id='" . $row['id'] . "' 
                                                                        data-name-depart='" . htmlspecialchars($row['name_depart'], ENT_QUOTES) . "' 
                                                                        data-name-retour='" . htmlspecialchars($row['name_retour'], ENT_QUOTES) . "' 
                                                                        data-bs-toggle='modal' data-bs-target='#editQartieModal'>
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
                                                echo "<tr><td colspan='4'>Aucune ville trouvée</td></tr>";
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
    // Add new qartie
    $('#qartieForm').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            url: '', // Same PHP file
            type: 'POST',
            data: $(this).serialize(),
            success: function (response) {
                alert('Quartier ajouté avec succès!');
                location.reload();
            },
            error: function () {
                alert('Une erreur s\'est produite. Veuillez réessayer.');
            }
        });
    });

    // Update qartie
    $('#editQartieForm').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            url: '', // Same PHP file
            type: 'POST',
            data: $(this).serialize(),
            success: function (response) {
                alert('Quartier mis à jour avec succès!');
                location.reload();
            },
            error: function () {
                alert('Une erreur s\'est produite. Veuillez réessayer.');
            }
        });
    });

    // Prefill edit modal
    $('#editQartieModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var qartieId = button.data('id');
    var nameDepart = button.data('name-depart');
    var nameRetour = button.data('name-retour');

    var modal = $(this);
    modal.find('input[name="qartieId"]').val(qartieId);
    modal.find('input[name="name_depart"]').val(nameDepart);
    modal.find('input[name="name_retour"]').val(nameRetour);
});

});

    </script>
        <script src="./assets/js/bundle.js?ver=3.2.2"></script>
        <script src="./assets/js/scripts.js?ver=3.2.2"></script>
        <script src="./assets/js/charts/chart-crm.js?ver=3.2.2"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
