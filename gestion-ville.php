<?php
include 'db.php';

// Add new city
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
    $name = htmlspecialchars($_POST['name_ville']);

    $stmt = $conn->prepare("INSERT INTO app_ville (name_ville) VALUES (?)");
    $stmt->bind_param("s", $name);

    if ($stmt->execute()) {
        header("Location: {$_SERVER['PHP_SELF']}?success=added");
        exit;
    } else {
        header("Location: {$_SERVER['PHP_SELF']}?error=" . urlencode($stmt->error));
        exit;
    }
    $stmt->close();
}

// Delete city
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM app_ville WHERE id = ?");
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

// Update city
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'update') {
    $id = intval($_POST['cityId']);
    $name = htmlspecialchars($_POST['name_ville']);

    $stmt = $conn->prepare("UPDATE app_ville SET name_ville = ? WHERE id = ?");
    $stmt->bind_param("si", $name, $id);

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
    <meta name="description" content="City management dashboard">
    <link rel="shortcut icon" href="./images/favicon.png">
    <title>Gestion des villes</title>
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
                                <h3 class="nk-block-title page-title">Villes</h3>
                                <div class="nk-block-des text-soft">
                                    <p>Toutes les villes disponibles</p>
                                </div>
                            </div>
                            <div class="nk-block-head-content">
                                <div class="toggle-wrap nk-block-tools-toggle">
                                    <div class="toggle-expand-content" data-content="pageMenu">
                                        <ul class="nk-block-tools g-3">
                                            <div class="dropdown">
                                                <a href="#" class="dropdown-toggle btn btn-icon btn-primary" data-bs-toggle="modal" data-bs-target="#addCityModal" title="Ajouter">
                                                    <em class="icon ni ni-plus"></em>
                                                </a>
                                            </div>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Add City Modal -->
                        <div class="modal fade" id="addCityModal">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Ajouter une ville</h5>
                                        <a href="#" class="close" data-bs-dismiss="modal" aria-label="Close">
                                            <em class="icon ni ni-cross"></em>
                                        </a>
                                    </div>
                                    <div class="modal-body">
                                        <form id="cityForm">
                                            <input type="hidden" name="action" value="add">
                                            <div class="form-group">
                                                <label for="name_ville">Nom de la ville</label>
                                                <input type="text" class="form-control" id="name_ville" name="name_ville" required>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Ajouter</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Edit City Modal -->
                        <div class="modal fade" id="editCityModal">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Modifier une ville</h5>
                                        <a href="#" class="close" data-bs-dismiss="modal" aria-label="Close">
                                            <em class="icon ni ni-cross"></em>
                                        </a>
                                    </div>
                                    <div class="modal-body">
                                        <form id="editCityForm" method="POST" action="">
                                            <input type="hidden" name="action" value="update">
                                            <input type="hidden" name="cityId">
                                            <div class="form-group">
                                                <label for="edit_name_ville">Nom de la ville</label>
                                                <input type="text" class="form-control" id="edit_name_ville" name="name_ville" required>
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
                                <table class="datatable-init nowrap nk-tb-list nk-tb-ulist" data-auto-responsive="false" id="cityTable">
                                        <thead>
                                            <tr class="nk-tb-item nk-tb-head">
                                                <th class="nk-tb-col">Nom de la ville</th>
                                                <th class="nk-tb-col">Créée le</th>  <!-- New column for created_at -->
                                                <th class="nk-tb-col">Modifiée le</th>  <!-- New column for updated_at -->
                                                <th class="nk-tb-col nk-tb-col-tools text-end"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // Fetch cities from the database
                                            $result = $conn->query("SELECT * FROM app_ville");
                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    echo "<tr class='nk-tb-item'>
                                                            <td class='nk-tb-col'>{$row['name_ville']}</td>
                                                            <td class='nk-tb-col'>" . date('d-m-Y H:i:s', strtotime($row['created_at'])) . "</td>
                                                            <td class='nk-tb-col'>" . date('d-m-Y H:i:s', strtotime($row['updated_at'])) . "</td>
                                                            <td class='nk-tb-col nk-tb-col-tools'>
                                                                <ul class='nk-tb-actions gx-2'>
                                                                    <li>
                                                                        <a href='#' class='btn btn-trigger btn-icon btn-edit' 
                                                                        data-id='" . $row['id'] . "' 
                                                                        data-name='" . htmlspecialchars($row['name_ville'], ENT_QUOTES) . "' 
                                                                        data-bs-toggle='modal' data-bs-target='#editCityModal'>
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
        // Add new city
        $('#cityForm').on('submit', function (e) {
            e.preventDefault();
            $.ajax({
                url: '', // Same PHP file
                type: 'POST',
                data: $(this).serialize(),
                success: function () {
                    alert('Ville ajoutée avec succès!');
                    location.reload();
                },
                error: function () {
                    alert('Une erreur s\'est produite.');
                }
            });
        });

        // Update city
        $('#editCityForm').on('submit', function (e) {
            e.preventDefault();
            $.ajax({
                url: '', // Same PHP file
                type: 'POST',
                data: $(this).serialize(),
                success: function () {
                    alert('Ville mise à jour avec succès!');
                    location.reload();
                },
                error: function () {
                    alert('Une erreur s\'est produite.');
                }
            });
        });

        // Prefill edit modal
        $('#editCityModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var cityId = button.data('id');
            var cityName = button.data('name');
            var modal = $(this);
            modal.find('input[name="cityId"]').val(cityId);
            modal.find('input[name="name_ville"]').val(cityName);
        });
    });
    </script>
</body>
</html>
