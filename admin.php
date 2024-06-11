<?php
session_start();

if (!isset($_SESSION['login_user'])) {
    header('Location: login.php');
    exit;
}

include 'db/connection.php';
$admin_email = $_SESSION['login_user'];

$sql = "SELECT nama FROM admin WHERE email = '$admin_email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $admin_name = $row['nama'];
} else {
    $admin_name = "Unknown";
}
$_SESSION['admin_name'] = $admin_name;

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

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sewa Alat Camping - Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <section class=" h-[100vh] w-full flex flex-col bg-gray-200">
        <main class="w-full h-[95vh] flex flex-row">
            <div class="container-sidebar w-[20%]">
                <?php include 'sidebar.php';?>
            </div>
            <div class="w-[80%] flex flex-col gap-0 pr-6 pl-10">
                <nav
                    class="navbar flex flex-row shadow-xl justify-between items-center w-full h-16 max-h-16 bg-white my-4 px-4 rounded-lg">
                    <a href="transaction.php" class="text-lg text-gray-700 font-semibold tracking-wide">Transaction</a>
                    <div
                        class="py-2 px-4 flex items-center rounded-lg min-w-[550px] duration-300 cursor-pointer text-gray-700 border border-gray-600 border-opacity-40">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-search" viewBox="0 0 16 16">
                            <path
                                d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                        </svg>
                        <input type="text" placeholder="Search"
                            class="text-[15px] ml-4 w-full bg-transparent placeholder:text-semibold placeholder:tracking-wide focus:outline-none" />
                    </div>
                    <div class="flex flex-row justify-center items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                            class="bi bi-person-fill-check" viewBox="0 0 16 16">
                            <path
                                d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m1.679-4.493-1.335 2.226a.75.75 0 0 1-1.174.144l-.774-.773a.5.5 0 0 1 .708-.708l.547.548 1.17-1.951a.5.5 0 1 1 .858.514M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                            <path
                                d="M2 13c0 1 1 1 1 1h5.256A4.5 4.5 0 0 1 8 12.5a4.5 4.5 0 0 1 1.544-3.393Q8.844 9.002 8 9c-5 0-6 3-6 4" />
                        </svg>
                        <p class="text-[13px] font-medium text-gray-800">
                            <?php echo $_SESSION['admin_name']; ?>
                        </p>
                    </div>
                </nav>
                <div class="container-content mb-2 flex flex-col gap-4 overflow-y-auto">
                    <button onclick="openAddModal()"
                        class="w-44 py-3 px-12 rounded-lg text-white bg-green-600 text-sm font-medium hover:bg-green-800">
                        Add Admin
                    </button>
                    <div class="flex flex-col gap-4 justify-center items-center w-full">
                        <div
                            class="grid grid-cols-12 border mb-6 border-gray-700 border-opacity-60 gap-0 w-full min-h-16 rounded-lg shadow-md">
                            <div
                                class="col-span-2 flex justify-center items-center font-semibold bg-gray-100 rounded-l-lg">
                                ID
                            </div>
                            <div class="col-span-3 flex justify-center items-center font-semibold bg-white">Name</div>
                            <div class="col-span-4 flex justify-center items-center font-semibold bg-gray-100">Email
                            </div>
                            <div
                                class="col-span-3 flex justify-center items-center font-semibold bg-white rounded-r-lg">
                                Actions</div>
                        </div>

                        <div class="flex flex-col gap-4 justify-center items-center w-full">
                            <?php while ($row = $adminResult->fetch_assoc()) {?>
                            <div
                                class="grid grid-cols-12 border border-gray-700 border-opacity-60 gap-0 w-full min-h-16 rounded-lg shadow-md">
                                <div
                                    class="col-span-2 text-center flex justify-center items-center bg-gray-100 rounded-l-lg">
                                    <?php echo $row['id_admin']; ?>
                                </div>
                                <div class="col-span-3 text-center flex justify-center items-center bg-white">
                                    <?php echo $row['nama']; ?>
                                </div>
                                <div class="col-span-4 text-center flex justify-center items-center bg-gray-100">
                                    <?php echo $row['email']; ?>
                                </div>
                                <div
                                    class="col-span-3 text-center flex justify-center items-center text-[14px] font-medium gap-4 rounded-r-lg bg-white">
                                    <button
                                        onclick="openEditModal('<?php echo $row['id_admin']; ?>', '<?php echo $row['nama']; ?>', '<?php echo $row['email']; ?>', '<?php echo $row['password']; ?>')"
                                        class="px-4 py-2 flex justify-center items-center rounded-md text-sm font-medium bg-green-600 text-white hover:bg-green-800">Edit</button>
                                    
                                    <form
                                        class="px-4 py-2 flex justify-center items-center rounded-md text-sm font-medium bg-red-600 text-white hover:bg-red-800"
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
                                <input type="text" name="nama" placeholder="Name"
                                    class="w-full px-4 py-2 border rounded-md">
                            </div>
                            <div class="mb-4">
                                <input type="email" name="email" placeholder="Email"
                                    class="w-full px-4 py-2 border rounded-md">
                            </div>
                            <div class="mb-4">
                                <input type="password" name="password" placeholder="Password"
                                    class="w-full px-4 py-2 border rounded-md">
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
        <footer class="h-[5vh] w-full px-4 pr-6 py-[0.4px]">
            <div class="rounded-lg h-full bottom-0 bg-white flex flex-row justify-center items-center">
                haloo
            </div>
        </footer>
    </section>

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