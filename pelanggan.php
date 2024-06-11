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
        $name = $_POST['name'];
        $address = $_POST['address'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];

        $sql = "INSERT INTO pelanggan (nama, alamat, telepon, email) VALUES ('$name', '$address', '$phone', '$email')";
        $conn->query($sql);
    } elseif (isset($_POST['edit'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $address = $_POST['address'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];

        $sql = "UPDATE pelanggan SET nama='$name', alamat='$address', telepon='$phone', email='$email' WHERE id_pelanggan='$id'";
        $conn->query($sql);
    } elseif (isset($_POST['delete'])) {
        $id = $_POST['id'];

        $sql = "DELETE FROM pelanggan WHERE id_pelanggan='$id'";
        $conn->query($sql);
    }
}

// Fetch customer data
$customerQuery = "SELECT * FROM pelanggan";
$customerResult = $conn->query($customerQuery);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sewa Alat Camping - Customers</title>
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
                <a href="customers.php" class="text-lg text-gray-700 font-semibold tracking-wide">Manage Customers</a>
                <div>
                    <a href="logout.php">Logout</a>
                </div>
            </nav>
            <div class="container flex flex-col gap-10">
                <button onclick="openAddModal()"
                    class="w-48 py-3 px-12 rounded-lg text-white bg-green-600 text-sm font-medium hover:bg-green-800">
                    Add Customer
                </button>
                <div class="flex flex-col gap-4 justify-center items-center w-full">
                    <div
                        class="grid grid-cols-12 border border-gray-700 border-opacity-60 gap-1 mb-6 w-full min-h-16 rounded-lg shadow-md">
                        <div class="col-span-1 flex justify-center items-center font-semibold bg-gray-100 rounded-l-lg">
                            ID</div>
                        <div class="col-span-2 flex justify-center items-center font-semibold">Name</div>
                        <div class="col-span-2 flex justify-center items-center font-semibold bg-gray-100">Address</div>
                        <div class="col-span-2 flex justify-center items-center font-semibold">Phone</div>
                        <div class="col-span-3 flex justify-center items-center font-semibold bg-gray-100">Email</div>
                        <div class="col-span-2 flex justify-center items-center font-semibold">Actions</div>
                    </div>

                    <div class="flex flex-col gap-4 justify-center items-center w-full">
                        <?php while ($row = $customerResult->fetch_assoc()) {?>
                        <div
                            class="grid grid-cols-12 border border-gray-700 border-opacity-60 gap-1 w-full min-h-16 rounded-lg shadow-md">
                            <div
                                class="col-span-1 text-center flex justify-center items-center text-[14px] font-medium bg-gray-100 rounded-l-lg">
                                <?php echo $row['id_pelanggan']; ?>
                            </div>
                            <div class="col-span-2 text-center flex justify-center items-center text-[14px] font-medium">
                                <?php echo $row['nama']; ?>
                            </div>
                            <div class="col-span-2 text-center flex justify-center items-center text-[14px] font-medium bg-gray-100">
                                <?php echo $row['alamat']; ?>
                            </div>
                            <div class="col-span-2 text-center flex justify-center items-center text-[14px] font-medium">
                                <?php echo $row['telepon']; ?>
                            </div>
                            <div class="col-span-3 text-center flex justify-center items-center text-[14px] font-medium bg-gray-100 px-2">
                                <?php echo $row['email']; ?>
                            </div>
                            <div class="col-span-2 text-center flex justify-center items-center text-[14px] font-medium gap-4">
                                <button
                                    onclick="openEditModal('<?php echo $row['id_pelanggan']; ?>', '<?php echo $row['nama']; ?>', '<?php echo $row['alamat']; ?>', '<?php echo $row['telepon']; ?>', '<?php echo $row['email']; ?>')"
                                    class="px-4 py-2 flex justify-center items-center rounded-md text-sm font-medium bg-green-600 text-white hover:bg-green-800">Edit</button>
                                <form
                                    class="px-4 py-2 flex justify-center items-center rounded-md text-sm font-medium bg-red-600 text-white hover:bg-red-800"
                                    method="post" style="display:inline-block;">
                                    <input type="hidden" name="id" value="<?php echo $row['id_pelanggan']; ?>">
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

    <!-- Add Customer Modal -->
    <div id="addModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Add New Customer</h3>
                <div class="mt-2 px-7 py-3">
                    <form method="post">
                        <div class="mb-4">
                            <input type="text" name="name" placeholder="Name"
                                class="w-full px-4 py-2 border rounded-md">
                        </div>
                        <div class="mb-4">
                            <textarea name="address" placeholder="Address"
                                class="w-full px-4 py-2 border rounded-md"></textarea>
                        </div>
                        <div class="mb-4">
                            <input type="text" name="phone" placeholder="Phone"
                                class="w-full px-4 py-2 border rounded-md">
                        </div>
                        <div class="mb-4">
                            <input type="email" name="email" placeholder="Email"
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

    <!-- Edit Customer Modal -->
    <div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Edit Customer</h3>
                <div class="mt-2 px-7 py-3">
                    <form method="post">
                        <input type="hidden" name="id" id="editId">
                        <div class="mb-4">
                            <input type="text" name="name" id="editName" placeholder="Name"
                                class="w-full px-4 py-2 border rounded-md">
                        </div>
                        <div class="mb-4">
                            <textarea name="address" id="editAddress" placeholder="Address"
                                class="w-full px-4 py-2 border rounded-md"></textarea>
                        </div>
                        <div class="mb-4">
                            <input type="text" name="phone" id="editPhone" placeholder="Phone"
                                class="w-full px-4 py-2 border rounded-md">
                        </div>
                        <div class="mb-4">
                            <input type="email" name="email" id="editEmail" placeholder="Email"
                                class="w-full px-4 py-2 border rounded-md">
                        </div>
                        <div class="flex gap-4 justify-center">
                            <button type="submit" name="edit"
                                class="px-4 py-2 text-white bg-green-600 rounded-md hover:bg-green-800">Save</button>
                            <button type="button" onclick="closeEditModal()"
                                class="px-4 py-2 text-white bg-gray-600 rounded-md hover:bg-gray-800">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openAddModal() {
            document.getElementById('addModal').classList.remove('hidden');
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.add('hidden');
        }

        function openEditModal(id, name, address, phone, email) {
            document.getElementById('editId').value = id;
            document.getElementById('editName').value = name;
            document.getElementById('editAddress').value = address;
            document.getElementById('editPhone').value = phone;
            document.getElementById('editEmail').value = email;
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
    </script>
</body>

</html>
