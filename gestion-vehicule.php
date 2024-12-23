<?php
include 'db.php'; // Include database connection

$action = $_POST['action'] ?? null;

if ($action === 'update') {
    // Update Vehicle Logic
    $id = $_POST['id'];
    $nom_vehicule = $_POST['nom_vehicule'];
    $modele = $_POST['modele'];
    $description = $_POST['description'];
    $clim = $_POST['clim'];
    $passager = intval($_POST['passager']);
    $transmission = $_POST['transmission'];
    $portes = intval($_POST['portes']);
    $bagages = $_POST['bagages'];

    // Retrieve existing images
    $stmt = $conn->prepare("SELECT images FROM app_vehicule WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $existingImages = $result->fetch_assoc()['images'] ?? '';
    $stmt->close();
    $existingImagesArray = $existingImages ? json_decode($existingImages, true) : [];

    // Handle new image uploads
    $uploadedImages = [];
    if (!empty($_FILES['images']['name'][0])) {
        // Process the new image upload
        $filename = uniqid() . '-' . basename($_FILES['images']['name'][0]);
        $destination = 'uploads/' . $filename;

        if (move_uploaded_file($_FILES['images']['tmp_name'][0], $destination)) {
            // Only add the new uploaded image to the list
            $uploadedImages[] = $filename;
        } else {
            echo "Error uploading image.";
        }
    }

    // If no new image was uploaded, keep the existing ones
    $finalImages = $uploadedImages ? $uploadedImages : $existingImagesArray;

    // Convert array to JSON
    $images = json_encode($finalImages);

    // Update database
    $stmt = $conn->prepare("UPDATE app_vehicule SET 
        nom_vehicule = ?, 
        modele = ?, 
        description = ?, 
        clim = ?, 
        passager = ?, 
        transmission = ?, 
        portes = ?, 
        bagages = ?, 
        images = ?, 
        updated_at = NOW() 
        WHERE id = ?");
    $stmt->bind_param(
        "ssssissisi",
        $nom_vehicule,
        $modele,
        $description,
        $clim,
        $passager,
        $transmission,
        $portes,
        $bagages,
        $images,
        $id
    );

    if ($stmt->execute()) {
        echo "Vehicle updated successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    // Delete Vehicle Logic
    $id = $_GET['id'];

    // Delete the vehicle from the database
    $stmt = $conn->prepare("DELETE FROM app_vehicule WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "Vehicle deleted successfully.";
    } else {
        echo "Error: " . $stmt->error;
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
                                <h3 class="nk-block-title page-title">Gestion des véhicules</h3>
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
                                                <div id="imagePreviewContainer" class="mt-2"></div> 
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
                                            <th>Images</th>
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
                                                echo "<tr>";
                                                echo "<td>";
                                                if (!empty($row['images'])) {
                                                    $images = explode(',', $row['images']);
                                                    foreach ($images as $image) {
                                                        echo "<img src='uploads/$image' alt='Vehicle Image' style='width: 50px; height: 50px; margin: 5px;'>";
                                                    }
                                                } else {
                                                    echo "No Images";
                                                }
                                                echo "</td>";
                                                echo "<td>{$row['nom_vehicule']}</td>";
                                                echo "<td>{$row['modele']}</td>";
                                                echo "<td>{$row['clim']}</td>";
                                                echo "<td>{$row['passager']}</td>";
                                                echo "<td>{$row['transmission']}</td>";
                                                echo "<td>{$row['portes']}</td>";
                                                echo "<td>{$row['bagages']}</td>";
                                                echo "<td>{$row['created_at']}</td>";
                                                echo "<td>{$row['updated_at']}</td>";
                                                echo "<td>
                                                        <a class='btn btn-trigger btn-icon btn-trash' href='?action=delete&id={$row['id']}' onclick='return confirm(\"Confirmer la suppression ?\")'><em class='icon ni ni-trash'></em></a>
                                                        <button class='btn btn-trigger btn-icon btn-edit' data-bs-toggle='modal' data-bs-target='#editVehicleModal{$row['id']}'><em class='icon ni ni-edit'></em></button>
                                                    </td>";
                                                echo "</tr>";
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
                                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
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
                                                    <label for="images">Images</label>
                                                    <input type="file" class="form-control" name="images[]" accept="image/*">
                                                </div>
                                                <button type="submit" class="btn btn-primary">Mettre à jour</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="./assets/js/bundle.js?ver=3.2.2"></script>
    <script src="./assets/js/scripts.js?ver=3.2.2"></script>
</body>
</html>
