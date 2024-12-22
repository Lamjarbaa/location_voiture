<?php
include 'db.php'; 

$sql = "SELECT * FROM app_users ORDER BY role = 'admin' DESC"; 
$result = $conn->query($sql);

// delete
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']); // Sécuriser l'ID
    $sql = "DELETE FROM app_users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param('i', $id); // Liaison de l'ID à la requête
        $stmt->execute();
        
        if ($stmt->affected_rows > 0) {
            echo "Employee deleted successfully.";
        } else {
            echo "Error deleting employee.";
        }
        
        $stmt->close();
    } else {
        echo "Error preparing statement.";
    }
}
// update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $role = isset($_POST['role']) ? (int)$_POST['role'] : 0; 
    $etat = isset($_POST['etat']) ? (int)$_POST['etat'] : 0;  
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $sql = "UPDATE app_users SET name = ?, email = ?, role = ?, etat = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param('ssiii', $name, $email, $role, $etat, $id); 
        $stmt->execute();
        
        if ($stmt->affected_rows > 0) {
            echo "Employee updated successfully.";
        } else {
            echo "No changes made or error in updating.";
        }
        $stmt->close();
    } else {
        echo "Error preparing statement.";
    }
}
// add 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        header("Location: gestion-utilisateurs.php");  
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $etat = $_POST['etat'];

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO employees (name, email, password, role, etat) VALUES (?, ?, ?, ?, ?)");
    if ($stmt === false) {
        die("Failed to prepare statement: " . $conn->error);
    }

    $stmt->bind_param("sssii", $name, $email, $hashed_password, $role, $etat);
    if ($stmt->execute()) {
        echo json_encode([
            'name' => $name,
            'email' => $email,
            'role' => $role,
            'etat' => $etat
        ]);
    } else {
        echo json_encode(['error' => 'Error: ' . $stmt->error]);
    }
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="fr" class="js">

<head>
    <meta charset="utf-8">
    <meta name="author" content="Softnio">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="A powerful and conceptual apps base dashboard template that especially build for developers and programmers.">
    <link rel="shortcut icon" href="./images/favicon.png">
    <title>Gestion utilisateurs - Location voiture</title>
    <link rel="stylesheet" href="src/assets/css/dashlite.css">
    <link id="skin-default" rel="stylesheet" href="src/assets/css/theme.css">
</head>

<body class="nk-body bg-lighter npc-general has-sidebar ">
<?php include 'sidebare.php'; ?>
            <div class="nk-wrap">

            <?php include 'head.php'; ?>
    <div class="nk-content ">
                    <div class="container-fluid ">
                        <div class="nk-content-inner  ">
                          <div class="nk-content-body ">
                            <div class="nk-block-between">
                             <div class="nk-block-head-content ">
                                <h3 class="nk-block-title page-title">Utilisateurs</h3>
                                <div class="nk-block-des text-soft">
                                    <p>Tous les utilisateurs</p>
                                </div>
                             </div>

<div class="nk-block-head-content">
  <div class="toggle-wrap nk-block-tools-toggle"> 
   <div class="toggle-expand-content" data-content="pageMenu">
    <ul class="nk-block-tools g-3">
    <div class="dropdown">
        <a href="#" class="dropdown-toggle btn btn-icon btn-primary" data-bs-toggle="modal" data-bs-target="#addEmployeeModal"  title="add">
            <em class="icon ni ni-plus"></em>
        </a>
        <div class="modal fade" id="addEmployeeModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Ajouter un utilisateur  </h5>
                        <a href="#" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <em class="icon ni ni-cross"></em>
                        </a>
                    </div>
                    <div class="modal-body">
                    <form method="POST">
                    <div class="form-group">
                        <label for="nom">Nom</label>
                        <input type="text" class="form-control" id="nom" name="nom" required>
                    </div>
                    <div class="form-group">
                        <label for="prenom">Prénom</label>
                        <input type="text" class="form-control" id="prenom" name="prenom" required>
                    </div>
                    <div class="form-group">
                        <label for="tel">Téléphone</label>
                        <input type="text" class="form-control" id="tel" name="tel" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="genre">Genre</label>
                        <select class="form-control" id="genre" name="genre" required>
                            <option value="Homme">Homme</option>
                            <option value="Femme">Femme</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary" name="add_user">Ajouter</button>
                </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</ul>


                                    </div>
                                </div><!-- .toggle-wrap -->
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
 <div class="card card-bordered card-preview">
   <div class="card-inner">
      <div class="nk-block">
         <table class="datatable-init nowrap nk-tb-list nk-tb-ulist" data-auto-responsive="false" id="employeeTable">
             <thead>
                 <tr class="nk-tb-item nk-tb-head">
                     <th class="nk-tb-col"><span class="sub-text">Employee</span></th>
                     <th class="nk-tb-col"><span class="sub-text">Tel</span></th>
                     <th class="nk-tb-col"><span class="sub-text">Genre</span></th> 
                     <th class="nk-tb-col"><span class="sub-text">créé à</span></th>
                     <th class="nk-tb-col"><span class="sub-text">mis à jour à</span></th>
                     <th class="nk-tb-col tb-col-md"><span class="sub-text">Role</span></th>
                     <th class="nk-tb-col tb-col-lg"><span class="sub-text">Etat</span></th>
                     <th class="nk-tb-col nk-tb-col-tools text-end"></th>
                 </tr>
             </thead>
             <tbody>
                 <?php if ($result->num_rows > 0): ?>
                     <?php while($row = $result->fetch_assoc()): ?>
                         <tr class="nk-tb-item">
                             <td class="nk-tb-col">
                                 <div class="user-card">
                                     <div class="user-avatar bg-primary">
                                         <span><?php echo strtoupper(substr($row['name'], 0, 2)); ?></span>
                                     </div>
                                     <div class="user-info">
                                         <span class="tb-lead"><?php echo htmlspecialchars($row['name']); ?></span>
                                         <span><?php echo htmlspecialchars($row['email']); ?></span>
                                     </div>
                                 </div>
                             </td>
                             <td class="nk-tb-col">
                    <span><?php echo htmlspecialchars($row['tel']); ?></span>
                </td>
                <td class="nk-tb-col">
                <span><?php echo htmlspecialchars($row['genre']); ?></span>

                             </td>
                <td class="nk-tb-col">
                    <span><?php echo htmlspecialchars($row['created_at']); ?></span>
                </td>
                <td class="nk-tb-col">
                    <span><?php echo htmlspecialchars($row['updated_at']); ?></span>
                </td>
                             <td class="nk-tb-col tb-col-md">
                             <span><?php echo htmlspecialchars($row['role']); ?></span>

                             </td>
                             <td class="nk-tb-col tb-col-lg">
                                 <span>
                                     <?= $row['etat'] == 1 ? 'Active' : 'Inactive' ?>
                                 </span>
                             </td>
                             <td class="nk-tb-col nk-tb-col-tools">
                                 <ul class="nk-tb-actions gx-2">
                                     <li>
                                         <a href="#" class="btn btn-trigger btn-icon btn-view" 
                                            data-id="<?php echo $row['id']; ?>" 
                                            data-name="<?php echo htmlspecialchars($row['name']); ?>" 
                                            data-email="<?php echo htmlspecialchars($row['email']); ?>" 

                                            data-role="<?php echo htmlspecialchars($row['role']); ?>" 
                                            data-etat="<?php echo htmlspecialchars($row['etat']); ?>"
                                            data-bs-toggle="modal" data-bs-target="#viewProfileModal" 
                                            title="View Profile">
                                             <em class="icon ni ni-user"></em>
                                         </a>
                                     </li>
                                     <li>
                                         <a href="#" class="btn btn-trigger btn-icon btn-edit" 
                                            data-id="<?php echo $row['id']; ?>" 
                                            data-name="<?php echo htmlspecialchars($row['name']); ?>" 
                                            data-email="<?php echo htmlspecialchars($row['email']); ?>" 

                                            data-role="<?php echo $row['role'] == 1 ? '1' : '0'; ?>" 
                                            data-etat="<?php echo $row['etat'] == 1 ? '1' : '0'; ?>" 
                                            data-bs-toggle="modal" data-bs-target="#modalForm" 
                                            title="Edit">
                                             <em class="icon ni ni-edit"></em>
                                         </a>
                                     </li>
                                     <li>
                                         <a href="gestion-utilisateurs.php?action=delete&id=<?php echo $row['id']; ?>" class="btn btn-trigger btn-icon" title="Suspend" 
                                            onclick="return confirm('Are you sure you want to delete this employee?');">
                                             <em class="icon ni ni-user-cross-fill"></em>
                                         </a>
                                     </li>
                                 </ul>
                             </td>
                         </tr>
                     <?php endwhile; ?>
                 <?php else: ?>
                     <tr class="nk-tb-item">
                         <td class="nk-tb-col" colspan="4">
                             <span>No employees found</span>
                         </td>
                     </tr>
                 <?php endif; ?>
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
                    </div>
                <?php include 'footer.php'; ?>

            </div>
        </div>
    </div> 
    
    <script src="./assets/js/bundle.js?ver=3.2.2"></script>
    <script src="./assets/js/scripts.js?ver=3.2.2"></script>
    <script src="./assets/js/charts/chart-crm.js?ver=3.2.2"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
<script>
$(document).ready(function() {
    $('#modalForm').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); 
        var id = button.data('id');
        var name = button.data('name');
        var email = button.data('email');
        var role = button.data('role');  
        var etat = button.data('etat');  

        var modal = $(this);
        modal.find('#full-name').val(name);
        modal.find('#email-address').val(email);
        
        if (role == '1') {
            modal.find('#com-admin').prop('checked', true);
        } else {
            modal.find('#com-emp').prop('checked', true);
        }
        
        if (etat == '1') {
            modal.find('#com-active').prop('checked', true);
        } else {
            modal.find('#com-inactive').prop('checked', true);
        }

        modal.find('#employee-id').val(id);
    });
});

$('#employeeForm').on('submit', function(event) {
    event.preventDefault(); 

    $.ajax({
        url: 'gestion-utilisateurs.php',
        type: 'POST',
        data: $(this).serialize(),
        success: function(response) {
            let employee = JSON.parse(response);

            if (employee.error) {
                alert('Error: ' + employee.error);
            } else {
                let newRow = '<tr>' +
                    '<td>' + employee.name + '</td>' +
                    '<td>' + (employee.role == 1 ? 'Admin' : 'Employee') + '</td>' +
                    '<td>' + (employee.etat == 1 ? 'Active' : 'Inactive') + '</td>' +
                    '</tr>';
                
                $('#employeeTable tbody').append(newRow);

                $('#addEmployeeModal').modal('hide');
                $('#employeeForm')[0].reset();
            }
        },
        error: function() {
            alert('Error adding employee.');
        }
    });
});

</script>



</body>

</html>