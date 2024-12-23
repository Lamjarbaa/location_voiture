
<?php
include 'db.php';

// Add new formulaire
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
    $nameform = htmlspecialchars($_POST['nameform']);
    $text_inclu = htmlspecialchars($_POST['text_inclu']);
    $text_non_inclu = htmlspecialchars($_POST['text_non_inclu']);
    $franchise = floatval($_POST['franchise']);
    $prix = floatval($_POST['prix']);
    $vehicule_id = intval($_POST['vehicule_id']);

    $stmt = $conn->prepare("INSERT INTO app_formulaire (nameform, text_inclu, text_non_inclu, franchise, prix, vehicule_id) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssdi", $nameform, $text_inclu, $text_non_inclu, $franchise, $prix, $vehicule_id);

    if ($stmt->execute()) {
        header("Location: {$_SERVER['PHP_SELF']}?success=added");
        exit;
    } else {
        header("Location: {$_SERVER['PHP_SELF']}?error=" . urlencode($stmt->error));
        exit;
    }
    $stmt->close();
}

// Delete formulaire
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM app_formulaire WHERE id = ?");
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

// Update formulaire
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'update') {
    $id = intval($_POST['formulaireId']);
    $nameform = htmlspecialchars($_POST['nameform']);
    $test_inclu = htmlspecialchars($_POST['text_inclu']);
    $test_non_inclu = htmlspecialchars($_POST['text_non_inclu']);
    $franchise = floatval($_POST['franchise']);
    $prix = floatval($_POST['prix']);
    $vehicule_id = intval($_POST['vehicule_id']);

    $stmt = $conn->prepare("UPDATE app_formulaire SET nameform = ?, text_inclu = ?, text_non_inclu = ?, franchise = ?, prix = ?, vehicule_id = ? WHERE id = ?");
    $stmt->bind_param("ssssdii", $nameform, $text_inclu, $text_non_inclu, $franchise, $prix, $vehicule_id, $id);

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
    <title>Gestion des Formulaires</title>
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
                                <h3 class="nk-block-title page-title">Gestion des Formulaires</h3>
                                <div class="nk-block-des text-soft">
                                    <p>Tous les formulaires disponibles</p>
                                </div>
                            </div>
                            <div class="nk-block-head-content">
                                <div class="toggle-wrap nk-block-tools-toggle">
                                    <div class="toggle-expand-content" data-content="pageMenu">
                                        <ul class="nk-block-tools g-3">
                                            <div class="dropdown">
                                                <a href="#" class="dropdown-toggle btn btn-icon btn-primary" data-bs-toggle="modal" data-bs-target="#addFormModal" title="Ajouter">
                                                    <em class="icon ni ni-plus"></em>
                                                </a>
                                            </div>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Add Form Modal -->
                        <div class="modal fade" id="addFormModal">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Ajouter un formulaire</h5>
                                        <a href="#" class="close" data-bs-dismiss="modal" aria-label="Close">
                                            <em class="icon ni ni-cross"></em>
                                        </a>
                                    </div>
                                    <div class="modal-body">
                                        <form id="formulaireForm">
                                            <input type="hidden" name="action" value="add">
                                            <div class="form-group">
                                                <label for="nameform">Nom du formulaire</label>
                                                <input type="text" class="form-control" id="nameform" name="nameform" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="text_inclu">Texts inclus</label>
                                                <textarea class="form-control" id="text_inclu" name="text_inclu" required></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="text_non_inclu">Texts non inclus</label>
                                                <textarea class="form-control" id="text_non_inclu" name="text_non_inclu" required></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="franchise">Franchise</label>
                                                <input type="number" step="0.01" class="form-control" id="franchise" name="franchise" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="prix">Prix</label>
                                                <input type="number" step="0.01" class="form-control" id="prix" name="prix" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="vehicule_id">Véhicule</label>
                                                <select class="form-control" id="vehicule_id" name="vehicule_id" required>
                                                    <?php
                                                    // Fetch vehicules from the database
                                                    $result = $conn->query("SELECT * FROM app_vehicule");
                                                    while ($row = $result->fetch_assoc()) {
                                                        echo "<option value='{$row['id']}'>{$row['nom_vehicule']}</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Ajouter</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Edit Form Modal -->
                        <div class="modal fade" id="editFormModal">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Modifier un formulaire</h5>
                                        <a href="#" class="close" data-bs-dismiss="modal" aria-label="Close">
                                            <em class="icon ni ni-cross"></em>
                                        </a>
                                    </div>
                                    <div class="modal-body">
                                        <form id="formulaireForm">
                                            <input type="hidden" name="action" value="add">
                                            <div class="form-group">
                                                <label for="nameform">Nom du formulaire</label>
                                                <input type="text" class="form-control" id="nameform" name="nameform" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="text_inclu">Texts inclus</label>
                                                <textarea class="form-control" id="text_inclu" name="text_inclu" required></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="text_non_inclu">Texts non inclus</label>
                                                <textarea class="form-control" id="text_non_inclu" name="text_non_inclu" required></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="franchise">Franchise</label>
                                                <input type="number" step="0.01" class="form-control" id="franchise" name="franchise" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="prix">Prix</label>
                                                <input type="number" step="0.01" class="form-control" id="prix" name="prix" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="vehicule_id">Véhicule</label>
                                                <select class="form-control" id="vehicule_id" name="vehicule_id" required>
                                                    <?php
                                                    // Fetch vehicules from the database
                                                    $result = $conn->query("SELECT * FROM app_vehicule");
                                                    while ($row = $result->fetch_assoc()) {
                                                        echo "<option value='{$row['id']}'>{$row['nom_vehicule']}</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Ajouter</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Formulaire Table -->
                        <div class="card card-bordered card-preview">
                            <div class="card-inner">
                                <div class="nk-block">
                                    <table class="datatable-init nowrap nk-tb-list nk-tb-ulist" data-auto-responsive="false" id="formulaireTable">
                                        <thead>
                                            <tr class="nk-tb-item nk-tb-head">
                                                <th class="nk-tb-col">Nom du formulaire</th>
                                                <th class="nk-tb-col">Texts inclus</th>
                                                <th class="nk-tb-col">Texts non inclus</th>
                                                <th class="nk-tb-col">Franchise</th>
                                                <th class="nk-tb-col">Prix</th>
                                                <th class="nk-tb-col">Véhicule</th>
                                                <th class="nk-tb-col">Créée le</th>
                                                <th class="nk-tb-col">Modifiée le</th>
                                                <th class="nk-tb-col nk-tb-col-tools text-end"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // Fetch form data from the database
                                            $result = $conn->query("SELECT * FROM app_formulaire");
                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    echo "<tr class='nk-tb-item'>
                                                            <td class='nk-tb-col'>{$row['nameform']}</td>
                                                            <td class='nk-tb-col'>{$row['text_inclu']}</td>
                                                            <td class='nk-tb-col'>{$row['text_non_inclu']}</td>
                                                            <td class='nk-tb-col'>{$row['franchise']}</td>
                                                            <td class='nk-tb-col'>{$row['prix']}</td>
                                                            <td class='nk-tb-col'>{$row['vehicule_id']}</td>
                                                            <td class='nk-tb-col'>" . date('d-m-Y H:i:s', strtotime($row['created_at'])) . "</td>
                                                            <td class='nk-tb-col'>" . date('d-m-Y H:i:s', strtotime($row['updated_at'])) . "</td>
                                                            <td class='nk-tb-col nk-tb-col-tools'>
                                                                <ul class='nk-tb-actions gx-2'>
                                                                    <li>
                                                                        <a href='#' class='btn btn-trigger btn-icon btn-edit' 
                                                                           data-id='" . $row['id'] . "' 
                                                                           data-nameform='" . htmlspecialchars($row['nameform'], ENT_QUOTES) . "' 
                                                                           data-text-inclu='" . htmlspecialchars($row['text_inclu'], ENT_QUOTES) . "' 
                                                                           data-text-non-inclu='" . htmlspecialchars($row['text_non_inclu'], ENT_QUOTES) . "' 
                                                                           data-franchise='" . $row['franchise'] . "' 
                                                                           data-prix='" . $row['prix'] . "' 
                                                                           data-vehicule-id='" . $row['vehicule_id'] . "' 
                                                                           data-bs-toggle='modal' data-bs-target='#editFormModal'>
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
                                                echo "<tr><td colspan='9'>Aucun formulaire trouvé</td></tr>";
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
        // Add new formulaire
        $('#formulaireForm').on('submit', function (e) {
            e.preventDefault();
            $.ajax({
                url: '', // Same PHP file
                type: 'POST',
                data: $(this).serialize(),
                success: function (response) {
                    alert('Formulaire ajouté avec succès!');
                    location.reload();
                },
                error: function () {
                    alert('Une erreur s\'est produite. Veuillez réessayer.');
                }
            });
        });

        // Update formulaire
        $('#editFormForm').on('submit', function (e) {
            e.preventDefault();
            $.ajax({
                url: '', // Same PHP file
                type: 'POST',
                data: $(this).serialize(),
                success: function (response) {
                    alert('Formulaire mis à jour avec succès!');
                    location.reload();
                },
                error: function () {
                    alert('Une erreur s\'est produite. Veuillez réessayer.');
                }
            });
        });

        // Prefill edit modal
        $('#editFormModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var formulaireId = button.data('id');
            var nameform = button.data('nameform');
            var textInclu = button.data('text-inclu');
            var textNonInclu = button.data('text-non-inclu');
            var franchise = button.data('franchise');
            var prix = button.data('prix');
            var vehiculeId = button.data('vehicule-id');
            var modal = $(this);
            
            modal.find('input[name="formulaireId"]').val(formulaireId);
            modal.find('input[name="nameform"]').val(nameform);
            modal.find('textarea[name="text_inclu"]').val(textInclu);
            modal.find('textarea[name="text_non_inclu"]').val(textNonInclu);
            modal.find('input[name="franchise"]').val(franchise);
            modal.find('input[name="prix"]').val(prix);
            modal.find('select[name="vehicule_id"]').val(vehiculeId);
        });
    });
    </script>

        <script src="./assets/js/bundle.js?ver=3.2.2"></script>
        <script src="./assets/js/scripts.js?ver=3.2.2"></script>
        <script src="./assets/js/charts/chart-crm.js?ver=3.2.2"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body> 
</html>