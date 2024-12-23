<?php
include 'db.php'; 

// Fetch all users, prioritizing admins
$sql = "SELECT * FROM app_users ORDER BY role = 'admin' DESC"; 
$result = $conn->query($sql);

// Delete user
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "DELETE FROM app_users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->close();
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
}

// Update user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
    $id = intval($_POST['id']);
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $tel = trim($_POST['tel']);
    $genre = trim($_POST['genre']);
    $role = intval($_POST['role']);
    $etat = intval($_POST['etat']);

    $sql = "UPDATE app_users SET name = ?, email = ?, tel = ?, genre = ?, role = ?, etat = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param('sssiiii', $name, $email, $tel, $genre, $role, $etat, $id);
        $stmt->execute();
        $stmt->close();
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
}

// Add user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $tel = trim($_POST['tel']);
    $genre = trim($_POST['genre']);
    $role = intval($_POST['role']);
    $etat = intval($_POST['etat']);
    $password = trim($_POST['password']);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO app_users (name, email, tel, genre, role, etat, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param('sssisss', $name, $email, $tel, $genre, $role, $etat, $hashed_password);
        $stmt->execute();
        $stmt->close();
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Gestion des utilisateurs</title>
    <link rel="stylesheet" href="src/assets/css/dashlite.css">
    <link rel="stylesheet" href="src/assets/css/theme.css">
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
                                <h3 class="nk-block-title page-title">Gestion des utilisateurs</h3>
                                <div class="nk-block-des text-soft">
                                    <p>Toutes les utilisateurs</p>
                                </div>
                            </div>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                                <em class="icon ni ni-plus"></em>
                            </button>
                        </div>
                        <div class="card card-bordered card-preview">
                            <div class="card-inner">
                                <table class="datatable-init nowrap nk-tb-list nk-tb-ulist">
                                    <thead>
                                        <tr>
                                            <th>Nom complet</th>
                                            <th>E-mail</th>
                                            <th>Tel</th>
                                            <th>Genre</th>
                                            <th>Role</th>
                                            <th>Etat</th>
                                            <th>Créé à</th>
                                            <th>Mis à jour à</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($result->num_rows > 0): ?>
                                            <?php while ($row = $result->fetch_assoc()): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['tel']); ?></td>
                                                    <td><?php echo $row['genre'] == 1 ? 'Femme' : 'Homme'; ?></td>
                                                    <td><?php echo $row['role'] == 1 ? 'User' : 'Admin'; ?></td>
                                                    <td><?php echo $row['etat'] == 1 ? 'Inactive' : 'Active'; ?></td>
                                                    <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['updated_at']); ?></td>
                                                    <td>
                                                        <button class="btn btn-trigger btn-icon btn-trash edit-user-btn"
                                                                data-id="<?php echo $row['id']; ?>"
                                                                data-name="<?php echo htmlspecialchars($row['name']); ?>"
                                                                data-email="<?php echo htmlspecialchars($row['email']); ?>"
                                                                data-tel="<?php echo htmlspecialchars($row['tel']); ?>"
                                                                data-genre="<?php echo htmlspecialchars($row['genre']); ?>"
                                                                data-role="<?php echo $row['role']; ?>"
                                                                data-etat="<?php echo $row['etat']; ?>">
                                                            <em class="icon ni ni-edit"></em>
                                                        </button>
                                                        <a href="?action=delete&id=<?php echo $row['id']; ?>" class='btn btn-trigger btn-icon btn-trash' onclick="return confirm('Êtes-vous sûr ?')">
                                                            <em class="icon ni ni-trash"></em>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add User Modal -->
        <div class="modal fade" id="addUserModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST">
                        <div class="modal-header">
                            <h5>Ajouter utilisateur</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Nom complet</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                            <div class="form-group">
                                <label>E-mail</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                            <div class="form-group">
                                <label>Téléphone</label>
                                <input type="tel" class="form-control" name="tel" required>
                            </div>
                            <div class="form-group">
                                <label>Genre</label>
                                <select class="form-control" name="genre" required>
                                    <option value="femme">Femme</option>
                                    <option value="homme">Homme</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Mot de passe</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>
                            <div class="form-group">
                                <label>Role</label>
                                <select class="form-control" name="role" required>
                                    <option value="0">Admin</option>
                                    <option value="1">Utilisateur</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Etat</label>
                                <select class="form-control" name="etat" required>
                                    <option value="0">Active</option>
                                    <option value="1">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="add_user" class="btn btn-primary">Ajouter</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit User Modal -->
        <div class="modal fade" id="editUserModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST">
                        <div class="modal-header">
                            <h5>Modifier utilisateur</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="editUserId" name="id">
                            <div class="form-group">
                                <label>Nom complet</label>
                                <input type="text" id="editUserName" class="form-control" name="name" required>
                            </div>
                            <div class="form-group">
                                <label>E-mail</label>
                                <input type="email" id="editUserEmail" class="form-control" name="email" required>
                            </div>
                            <div class="form-group">
                                <label>Téléphone</label>
                                <input type="tel" id="editUserTel" class="form-control" name="tel" required>
                            </div>
                            <div class="form-group">
                                <label>Genre</label>
                                <select id="editUserGenre" class="form-control" name="genre" required>
                                    <option value="1">Femme</option>
                                    <option value="0">Homme</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Role</label>
                                <select id="editUserRole" class="form-control" name="role" required>
                                    <option value="0">Admin</option>
                                    <option value="1">Utilisateur</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Etat</label>
                                <select id="editUserEtat" class="form-control" name="etat" required>
                                    <option value="0">Active</option>
                                    <option value="1">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="update_user" class="btn btn-info">Modifier</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.edit-user-btn').forEach(button => {
            button.addEventListener('click', function () {
                const id = this.dataset.id;
                const name = this.dataset.name;
                const email = this.dataset.email;
                const tel = this.dataset.tel;
                const genre = this.dataset.genre;
                const role = this.dataset.role;
                const etat = this.dataset.etat;

                document.getElementById('editUserId').value = id;
                document.getElementById('editUserName').value = name;
                document.getElementById('editUserEmail').value = email;
                document.getElementById('editUserTel').value = tel;
                document.getElementById('editUserGenre').value = genre;
                document.getElementById('editUserRole').value = role;
                document.getElementById('editUserEtat').value = etat;

                new bootstrap.Modal(document.getElementById('editUserModal')).show();
            });
        });
    </script>
    <script src="src/assets/js/dashlite.js"></script>
    <script src="src/assets/js/bundle.js"></script>
</body>
</html>
