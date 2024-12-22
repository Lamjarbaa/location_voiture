<?php
include 'db.php';

// Ensure $conn is initialized properly
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Add new category
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
    $name_en = htmlspecialchars($_POST['categoryNameEn']);
    $description_en = htmlspecialchars($_POST['categoryDescriptionEn']);
    $name_fr = htmlspecialchars($_POST['categoryNameFr']);
    $description_fr = htmlspecialchars($_POST['categoryDescriptionFr']);

    $stmt = $conn->prepare("INSERT INTO app_categories_voiture (name_en, description_en, name_fr, description_fr) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name_en, $description_en, $name_fr, $description_fr);

    if ($stmt->execute()) {
        echo "Category added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
    exit;
}

// Delete category
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM app_categories_voiture WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header('Location: gestion-categories-voitures.php');
        exit;
    } else {
        echo "Error deleting category: " . $stmt->error;
    }
    $stmt->close();
    exit;
}

// Update category
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'update') {
    $id = intval($_POST['categoryId']);
    $name_en = htmlspecialchars($_POST['categoryNameEn']);
    $description_en = htmlspecialchars($_POST['categoryDescriptionEn']);
    $name_fr = htmlspecialchars($_POST['categoryNameFr']);
    $description_fr = htmlspecialchars($_POST['categoryDescriptionFr']);

    $stmt = $conn->prepare("UPDATE app_categories_voiture SET name_en = ?, description_en = ?, name_fr = ?, description_fr = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $name_en, $description_en, $name_fr, $description_fr, $id);

    if ($stmt->execute()) {
        echo "Category updated successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
    exit;
}
?>


<!DOCTYPE html>
<html lang="fr" class="js">

<head>
    <meta charset="utf-8">
    <meta name="author" content="Softnio">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Car category management dashboard">
    <link rel="shortcut icon" href="./images/favicon.png">
    <title>Gestion des catégories de voitures</title>
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
                                <h3 class="nk-block-title page-title">Catégories de Voitures</h3>
                                <div class="nk-block-des text-soft">
                                    <p>Toutes les catégories</p>
                                </div>
                            </div>
                            <div class="nk-block-head-content">
                                <div class="toggle-wrap nk-block-tools-toggle">
                                    <div class="toggle-expand-content" data-content="pageMenu">
                                        <ul class="nk-block-tools g-3">
                                            <div class="dropdown">
                                                <a href="#" class="dropdown-toggle btn btn-icon btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal" title="Ajouter">
                                                    <em class="icon ni ni-plus"></em>
                                                </a>
                                            </div>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Add Category Modal -->
                        <div class="modal fade" id="addCategoryModal">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Ajouter une catégorie</h5>
                                        <a href="#" class="close" data-bs-dismiss="modal" aria-label="Close">
                                            <em class="icon ni ni-cross"></em>
                                        </a>
                                    </div>
                                    <div class="modal-body">
                                        <form id="categoryForm">
                                        <input type="hidden" name="action" value="add">

                                            <div class="form-group">
                                                <label for="categoryNameEn">Nom de la catégorie (EN)</label>
                                                <input type="text" class="form-control" id="categoryNameEn" name="categoryNameEn" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="categoryDescriptionEn">Description (EN)</label>
                                                <textarea class="form-control" id="categoryDescriptionEn" name="categoryDescriptionEn" required></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="categoryNameFr">Nom de la catégorie (FR)</label>
                                                <input type="text" class="form-control" id="categoryNameFr" name="categoryNameFr">
                                            </div>
                                            <div class="form-group">
                                                <label for="categoryDescriptionFr">Description (FR)</label>
                                                <textarea class="form-control" id="categoryDescriptionFr" name="categoryDescriptionFr"></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Ajouter</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Categories Table -->
                        <div class="card card-bordered card-preview">
                            <div class="card-inner">
                                <div class="nk-block">
                                    <table class="datatable-init nowrap nk-tb-list nk-tb-ulist" data-auto-responsive="false" id="categoryTable">
                                        <thead>
                                            <tr class="nk-tb-item nk-tb-head">
                                                <th class="nk-tb-col">Nom de la catégorie (EN)</th>
                                                <th class="nk-tb-col">Description (EN)</th>
                                                <th class="nk-tb-col">Nom de la catégorie (FR)</th>
                                                <th class="nk-tb-col">Description (FR)</th>
                                                <th class="nk-tb-col">Créé à</th>
                                                <th class="nk-tb-col">Mis à jour à</th>
                                                <th class="nk-tb-col nk-tb-col-tools text-end"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                    // Fetch categories from the database
                                    include 'db.php';
                                    $result = $conn->query("SELECT * FROM app_categories_voiture");
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                              echo "<tr class='nk-tb-item'>
                                                    <td class='nk-tb-col'>{$row['name_en']}</td>
                                                    <td class='nk-tb-col'>{$row['description_en']}</td>
                                                    <td class='nk-tb-col'>{$row['name_fr']}</td>
                                                    <td class='nk-tb-col'>{$row['description_fr']}</td>
                                                    <td class='nk-tb-col'>{$row['created_at']}</td>
                                                    <td class='nk-tb-col'>{$row['updated_at']}</td>
                                                    <td class='nk-tb-col nk-tb-col-tools'>
                                                        <ul class='nk-tb-actions gx-2'>
                                                            <li>
                                                                <a href='#' class='btn btn-trigger btn-icon btn-edit' 
                                                                data-id='" . $row['id'] . "' 
                                                                data-name-en='" . htmlspecialchars($row['name_en'], ENT_QUOTES) . "' 
                                                                data-description-en='" . htmlspecialchars($row['description_en'], ENT_QUOTES) . "' 
                                                                data-name-fr='" . htmlspecialchars($row['name_fr'], ENT_QUOTES) . "' 
                                                                data-description-fr='" . htmlspecialchars($row['description_fr'], ENT_QUOTES) . "' 
                                                                data-bs-toggle='modal' data-bs-target='#editCategoryModal'>
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
                                                echo "<tr class='nk-tb-item'><td colspan='7'>Aucune catégorie trouvée</td></tr>";
                                            }
                                            ?>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- Edit Category Modal -->
                        <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form id="editCategoryForm">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Modifier une catégorie</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="action" value="update">
                                            <input type="hidden" name="categoryId" id="editCategoryId">
                                            <div class="form-group">
                                                <label>Nom de la catégorie (EN)</label>
                                                <input type="text" class="form-control" name="categoryNameEn" id="editCategoryNameEn" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Description (EN)</label>
                                                <textarea name="categoryDescriptionEn" class="form-control" id="editCategoryDescriptionEn" required></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label>Nom de la catégorie (FR)</label>
                                                <input type="text" name="categoryNameFr" class="form-control" id="editCategoryNameFr" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Description (FR)</label>
                                                <textarea name="categoryDescriptionFr" class="form-control" id="editCategoryDescriptionFr" required></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include 'footer.php'; ?>
    </div>

    <script src="./assets/js/bundle.js?ver=3.2.2"></script>
    <script src="./assets/js/scripts.js?ver=3.2.2"></script>
    <script src="./assets/js/charts/chart-crm.js?ver=3.2.2"></script>

    <script>
$(document).ready(function () {
    // Add Category Form
    $('#categoryForm').on('submit', function (event) {
        event.preventDefault();
        $.ajax({
            url: 'gestion-categories-voitures.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function (response) {
                alert(response);
                location.reload();
            },
            error: function () {
                alert('An error occurred. Please try again.');
            }
        });
    });

    // Populate Edit Modal
    $('.btn-edit').on('click', function () {
        const id = $(this).data('id');
        const nameEn = $(this).data('name-en');
        const descEn = $(this).data('description-en');
        const nameFr = $(this).data('name-fr');
        const descFr = $(this).data('description-fr');

        $('#editCategoryId').val(id);
        $('#editCategoryNameEn').val(nameEn);
        $('#editCategoryDescriptionEn').val(descEn);
        $('#editCategoryNameFr').val(nameFr);
        $('#editCategoryDescriptionFr').val(descFr);
    });

    // Edit Category Form
    $('#editCategoryForm').on('submit', function (event) {
        event.preventDefault();
        $.ajax({
            url: 'gestion-categories-voitures.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function (response) {
                alert(response);
                location.reload();
            },
            error: function () {
                alert('An error occurred. Please try again.');
            }
        });
    });
});
    </script>
</body>

</html>
