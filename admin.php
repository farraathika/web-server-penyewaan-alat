<?php
session_start();

if (!isset($_SESSION['login_user'])) {
    header('Location: login.php');
    exit;
}

include 'db/connection.php';
$admin_email = $_SESSION['login_user'];

// Handle CRUD operations
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        $nama = $_POST['nama'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        $sql = "INSERT INTO admin (nama, email, password) VALUES ('$nama', '$email', '$password')";
        $conn->query($sql);
    } elseif (isset($_POST['edit'])) {
        $id = $_POST['id'];
        $nama = $_POST['nama'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        $sql = "UPDATE admin SET nama='$nama', email='$email', password='$password' WHERE id_admin='$id'";
        $conn->query($sql);
    } elseif (isset($_POST['delete'])) {
        $id = $_POST['id'];

        $sql = "DELETE FROM admin WHERE id_admin='$id'";
        $conn->query($sql);
    }
}

// Fetch admin data
$adminQuery = "SELECT * FROM admin";
$adminResult = $conn->query($adminQuery);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sewa Alat Camping - Admin Management</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <main class="w-full min-h-[100vh] pb-32 flex flex-row bg-white">
        <div class="container-sidebar w-[20%]">
            <?php include 'sidebar.php';?>
        </div>
        <div class="container-dashboard w-[80%] flex flex-col gap-10 px-10">
            <nav class="navbar flex flex-row justify-between items-center py-3">
                <a href="admin.php" class="text-lg text-gray-700 font-semibold tracking-wide">Manage Admins</a>
                <div>
                    <a href="logout.php">Logout</a>
                </div>
            </nav>
            <div class="container flex flex-col gap-10">
                <button onclick="openAddModal()"
                    class="w-44 py-3 px-12 rounded-lg text-white bg-green-600 text-sm font-medium hover:bg-green-800">
                    Add Admin
                </button>
                <div class="flex flex-col gap-4 justify-center items-center w-full">
                    <div
                        class="grid grid-cols-8 border mb-6 border-gray-700 border-opacity-60 gap-6 w-full min-h-16 rounded-lg shadow-md">
                        <div class="col-span-1 flex justify-center items-center font-semibold bg-gray-100 rounded-l-lg">ID
                        </div>
                        <div class="col-span-2 flex justify-center items-center font-semibold">Name</div>
                        <div class="col-span-3 flex justify-center items-center font-semibold bg-gray-100">Email</div>
                        <div class="col-span-2 flex justify-center items-center font-semibold">Actions</div>
                    </div>

                    <div class="flex flex-col gap-4 justify-center items-center w-full">
                        <?php while ($row = $adminResult->fetch_assoc()) {?>
                        <div
                            class="grid grid-cols-8 border border-gray-700 border-opacity-60 gap-6 w-full min-h-16 rounded-lg shadow-md">
                            <div class="col-span-1 text-center flex justify-center items-center bg-gray-100 rounded-l-lg">
                                <?php echo $row['id_admin']; ?>
                            </div>
                            <div class="col-span-2 text-center flex justify-center items-center">
                                <?php echo $row['nama']; ?>
                            </div>
                            <div class="col-span-3 text-center flex justify-center items-center bg-gray-100">
                                <?php echo $row['email']; ?>
                            </div>
                            <div class="col-span-2 text-center flex justify-center items-center gap-4">
                                <button
                                    onclick="openEditModal('<?php echo $row['id_admin']; ?>', '<?php echo $row['nama']; ?>', '<?php echo $row['email']; ?>', '<?php echo $row['password']; ?>')"
                                    class="px-4 py-2 flex justify-center items-center rounded-md text-sm font-medium bg-green-600 text-white hover:bg-green-800">Edit</button>
                                <form class="px-4 py-2 flex justify-center items-center rounded-md text-sm font-medium bg-red-600 text-white hover:bg-red-800"
                                    method="post" style="display:inline-block;">
                                    <input type="hidden" name="id" value="<?php echo $row['id_admin']; ?>">
                                    <button type="submit" name="delete">Delete</button>
                                </form>
                            </div>
                        </div>
                        <?php }?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Add Admin Modal -->
    <div id="addModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Add New Admin</h3>
                <div class="mt-2 px-7 py-3">
                    <form method="post">
                        <div class="mb-4">
                            <input type="text" name="nama" placeholder="Name" class="w-full px-4 py-2 border rounded-md">
                        </div>
                        <div class="mb-4">
                            <input type="email" name="email" placeholder="Email" class="w-full px-4 py-2 border rounded-md">
                        </div>
                        <div class="mb-4">
                            <input type="password" name="password" placeholder="Password" class="w-full px-4 py-2 border rounded-md">
                        </div>
                        <div class="flex gap-4 justify-center">
                            <button type="submit" name="add"
                                class="px-4 py-2 text-white bg-green-600 rounded-md hover:bg-green-800">Add</button>
                            <button type="button" onclick="closeAddModal()"
                                class="px-4 py-2 text-white bg-gray-600 rounded-md hover:bg-gray-800">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Admin Modal -->
    <div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Edit Admin</h3>
                <div class="mt-2 px-7 py-3">
                    <form method="post">
                        <input type="hidden" name="id" id="editId">
                        <div class="mb-4">
                            <input type="text" name="nama" id="editNama" placeholder="Name"
                                class="w-full px-4 py-2 border rounded-md">
                        </div>
                        <div class="mb-4">
                            <input type="email" name="email" id="editEmail" placeholder="Email"
                                class="w-full px-4 py-2 border rounded-md">
                        </div>
                        <div class="mb-4">
                            <input type="password" name="password" id="editPassword" placeholder="Password"
                                class="w-full px-4 py-2 border rounded-md">
                        </div>
                        <div class="flex gap-4 justify-center">
                            <button type="submit" name="edit"
                                class="px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-800">Update</button>
                            <button type="button" onclick="closeEditModal()"
                                class="px-4 py-2 text-white bg-gray-600 rounded-md hover:bg-gray-800">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php include 'footer.php';?>

    <script>
        function openAddModal() {
            document.getElementById('addModal').style.display = 'block';
        }

        function closeAddModal() {
            document.getElementById('addModal').style.display = 'none';
        }

        function openEditModal(id, nama, email, password) {
            document.getElementById('editId').value = id;
            document.getElementById('editNama').value = nama;
            document.getElementById('editEmail').value = email;
            document.getElementById('editPassword').value = password;
            document.getElementById('editModal').style.display = 'block';
        }

        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }
    </script>
</body>

</html>
