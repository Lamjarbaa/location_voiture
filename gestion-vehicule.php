<?php
include 'db.php';

// Add new vehicle
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
    $nom = htmlspecialchars($_POST['nom_vehicule']);
    $modele = htmlspecialchars($_POST['modele']);
    $description = htmlspecialchars($_POST['description']);
    $clim = htmlspecialchars($_POST['clim']);
    $passager = intval($_POST['passager']);
    $transmission = htmlspecialchars($_POST['transmission']);
    $portes = intval($_POST['portes']);
    $bagages = htmlspecialchars($_POST['bagages']);
    
    // Handle image upload
    $images = [];
    if (!empty($_FILES['images']['name'][0])) {
        foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
            $image_name = $_FILES['images']['name'][$key];
            $image_tmp_name = $_FILES['images']['tmp_name'][$key];
            $image_path = 'uploads/' . basename($image_name);
            if (move_uploaded_file($image_tmp_name, $image_path)) {
                $images[] = $image_path;
            }
        }
    }
    $images_json = json_encode($images);

    $stmt = $conn->prepare("INSERT INTO app_vehicule (nom_vehicule, modele, description, clim, passager, transmission, portes, bagages, images) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssisiiss", $nom, $modele, $description, $clim, $passager, $transmission, $portes, $bagages, $images_json);

    if ($stmt->execute()) {
        header("Location: {$_SERVER['PHP_SELF']}?success=added");
        exit;
    } else {
        header("Location: {$_SERVER['PHP_SELF']}?error=" . urlencode($stmt->error));
        exit;
    }
    $stmt->close();
}

// Delete vehicle
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM app_vehicule WHERE id = ?");
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

// Update vehicle
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'update') {
    $id = intval($_POST['vehiculeId']);
    $nom = htmlspecialchars($_POST['nom_vehicule']);
    $modele = htmlspecialchars($_POST['modele']);
    $description = htmlspecialchars($_POST['description']);
    $clim = htmlspecialchars($_POST['clim']);
    $passager = intval($_POST['passager']);
    $transmission = htmlspecialchars($_POST['transmission']);
    $portes = intval($_POST['portes']);
    $bagages = htmlspecialchars($_POST['bagages']);
    
    // Handle image upload
    $images = [];
    if (!empty($_FILES['images']['name'][0])) {
        foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
            $image_name = $_FILES['images']['name'][$key];
            $image_tmp_name = $_FILES['images']['tmp_name'][$key];
            $image_path = 'uploads/' . basename($image_name);
            if (move_uploaded_file($image_tmp_name, $image_path)) {
                $images[] = $image_path;
            }
        }
    }
    $images_json = json_encode($images);

    $stmt = $conn->prepare("UPDATE app_vehicule SET nom_vehicule = ?, modele = ?, description = ?, clim = ?, passager = ?, transmission = ?, portes = ?, bagages = ?, images = ? WHERE id = ?");
    $stmt->bind_param("sssisiissi", $nom, $modele, $description, $clim, $passager, $transmission, $portes, $bagages, $images_json, $id);

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
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Gestion des véhicules</title>
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
                                <h3 class="nk-block-title page-title">Gestion des vehicules</h3>
                                <div class="nk-block-des text-soft">
                                    <p>Toutes les vehicules</p>
                                </div>
                            </div>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addVehicleModal"><em class="icon ni ni-plus"></em></button>
                        </div>

                        <!-- Add Vehicle Modal -->
                        <div class="modal fade" id="addVehicleModal">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Ajouter un véhicule</h5>
                                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                            <span>&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="vehicleForm" method="POST" enctype="multipart/form-data">
                                            <input type="hidden" name="action" value="add">
                                            <div class="form-group">
                                                <label for="nom_vehicule">Nom du véhicule</label>
                                                <input type="text" class="form-control" name="nom_vehicule" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="modele">Modèle</label>
                                                <input type="text" class="form-control" name="modele" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="description">Description</label>
                                                <textarea class="form-control" name="description"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="clim">Climatisation</label>
                                                <select class="form-control" name="clim" required>
                                                    <option value="Oui">Oui</option>
                                                    <option value="Non">Non</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="passager">Passagers</label>
                                                <input type="number" class="form-control" name="passager" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="transmission">Transmission</label>
                                                <select class="form-control" name="transmission" required>
                                                    <option value="Manuelle">Manuelle</option>
                                                    <option value="Automatique">Automatique</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="portes">Portes</label>
                                                <input type="number" class="form-control" name="portes" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="bagages">Bagages</label>
                                                <input type="text" class="form-control" name="bagages" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="images">Images (Upload multiple files)</label>
                                                <input type="file" class="form-control" name="images[]" multiple>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Ajouter</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Vehicles Table -->
                        <div class="card card-bordered card-preview">
                            <div class="card-inner">
                                <table class="datatable-init nowrap nk-tb-list" id="vehicleTable">
                                    <thead>
                                        <tr>
                                            <th>Nom du véhicule</th>
                                            <th>Modèle</th>
                                            <th>Climatisation</th>
                                            <th>Passagers</th>
                                            <th>Transmission</th>
                                            <th>Portes</th>
                                            <th>Bagages</th>
                                            <th>Créé le</th>
                                            <th>Mis à jour le</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $result = $conn->query("SELECT * FROM app_vehicule");
                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<tr>
                                                    <td>{$row['nom_vehicule']}</td>
                                                    <td>{$row['modele']}</td>
                                                    <td>{$row['clim']}</td>
                                                    <td>{$row['passager']}</td>
                                                    <td>{$row['transmission']}</td>
                                                    <td>{$row['portes']}</td>
                                                    <td>{$row['bagages']}</td>
                                                    <td>{$row['created_at']}</td>
                                                    <td>{$row['updated_at']}</td>
                                                    <td>
                                                        <a class='btn btn-trigger btn-icon btn-trash' href='?action=delete&id={$row['id']}' onclick='return confirm(\"Confirmer la suppression ?\")'><em class='icon ni ni-trash'></em></a>
                                                        <button class='btn btn-trigger btn-icon btn-trash' data-bs-toggle='modal' data-bs-target='#editVehicleModal{$row['id']}'><em class='icon ni ni-edit'></em></button>
                                                    </td>
                                                </tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='10'>Aucun véhicule trouvé</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Edit Vehicle Modal for each row -->
                        <?php
                        $result = $conn->query("SELECT * FROM app_vehicule");
                        while ($row = $result->fetch_assoc()) {
                            ?>
                            <div class="modal fade" id="editVehicleModal<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="editVehicleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editVehicleModalLabel">Modifier le véhicule</h5>
                                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                <span>&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="POST" enctype="multipart/form-data">
                                                <input type="hidden" name="action" value="update">
                                                <input type="hidden" name="vehiculeId" value="<?php echo $row['id']; ?>">
                                                <div class="form-group">
                                                    <label for="nom_vehicule">Nom du véhicule</label>
                                                    <input type="text" class="form-control" name="nom_vehicule" value="<?php echo $row['nom_vehicule']; ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="modele">Modèle</label>
                                                    <input type="text" class="form-control" name="modele" value="<?php echo $row['modele']; ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="description">Description</label>
                                                    <textarea class="form-control" name="description"><?php echo $row['description']; ?></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label for="clim">Climatisation</label>
                                                    <select class="form-control" name="clim" required>
                                                        <option value="Oui" <?php if ($row['clim'] == 'Oui') echo 'selected'; ?>>Oui</option>
                                                        <option value="Non" <?php if ($row['clim'] == 'Non') echo 'selected'; ?>>Non</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="passager">Passagers</label>
                                                    <input type="number" class="form-control" name="passager" value="<?php echo $row['passager']; ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="transmission">Transmission</label>
                                                    <select class="form-control" name="transmission" required>
                                                        <option value="Manuelle" <?php if ($row['transmission'] == 'Manuelle') echo 'selected'; ?>>Manuelle</option>
                                                        <option value="Automatique" <?php if ($row['transmission'] == 'Automatique') echo 'selected'; ?>>Automatique</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="portes">Portes</label>
                                                    <input type="number" class="form-control" name="portes" value="<?php echo $row['portes']; ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="bagages">Bagages</label>
                                                    <input type="text" class="form-control" name="bagages" value="<?php echo $row['bagages']; ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="images">Images (Upload multiple files)</label>
                                                    <input type="file" class="form-control" name="images[]" multiple>
                                                </div>
                                                <button type="submit" class="btn btn-info">Modifier</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
