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

// Handle CRUD
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        $id_pelanggan = $_POST['id_pelanggan'] ?? '';
        $id_alat = $_POST['id_alat'] ?? '';
        $id_admin = $_POST['id_admin'] ?? '';
        $jumlah = $_POST['jumlah'] ?? '';
        $tanggal_sewa = $_POST['tanggal_sewa'] ?? '';
        $tanggal_kembali = $_POST['tanggal_kembali'] ?? '';
        $total_harga = $_POST['total_harga'] ?? '';
        $status = $_POST['status'] ?? '';

        if (!empty($id_pelanggan) && !empty($id_alat) && !empty($id_admin) && !empty($jumlah) && !empty($tanggal_sewa) && !empty($tanggal_kembali) && !empty($total_harga) && !empty($status)) {
            $sql = "INSERT INTO transaksi (id_pelanggan, id_alat, id_admin, jumlah, tanggal_sewa, tanggal_kembali, total_harga, status) VALUES ('$id_pelanggan', '$id_alat', '$id_admin', '$jumlah', '$tanggal_sewa', '$tanggal_kembali', '$total_harga', '$status')";

            if ($conn->query($sql) === true) {
                echo "New record created successfully";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "All fields are required.";
        }

    } elseif (isset($_POST['edit'])) {
        $id = $_POST['id'];
        $id_pelanggan = $_POST['id_pelanggan'];
        $id_alat = $_POST['id_alat'];
        $id_admin = $_POST['id_admin'];
        $jumlah = $_POST['jumlah'];
        $tanggal_sewa = $_POST['tanggal_sewa'];
        $tanggal_kembali = $_POST['tanggal_kembali'];
        $total_harga = $_POST['total_harga'];
        $status = $_POST['status'];

        $sql = "UPDATE transaksi SET id_pelanggan='$id_pelanggan', id_alat='$id_alat', id_admin='$id_admin', jumlah='$jumlah', tanggal_sewa='$tanggal_sewa', tanggal_kembali='$tanggal_kembali', total_harga='$total_harga', status='$status' WHERE id_transaksi='$id'";
        $conn->query($sql);
    } elseif (isset($_POST['delete'])) {
        $id = $_POST['id'];

        $sql = "DELETE FROM transaksi WHERE id_transaksi='$id'";
        $conn->query($sql);
    }
}

// Fetch transaction data
$transactionQuery = "SELECT * FROM transaksi";
$transactionResult = $conn->query($transactionQuery);
?>

<!DOCTYPE html>
<html lang="en">

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
                        class="w-48 py-3 px-12 rounded-lg text-white bg-green-600 text-sm font-medium hover:bg-green-800">
                        Add Transaksi
                    </button>
                    <div class="flex flex-col gap-4 justify-center items-center w-full">
                        <div
                            class="grid grid-cols-12 border mb-6 border-gray-700 border-opacity-60 gap-0 w-full min-h-16 rounded-lg shadow-md">
                            <div
                                class="col-span-1 flex justify-center items-center font-semibold bg-gray-100 rounded-l-lg">
                                ID</div>
                            <div class="col-span-1 flex justify-center items-center font-semibold bg-white">Pelanggan</div>
                            <div class="col-span-1 flex justify-center items-center font-semibold bg-gray-100">Alat
                            </div>
                            <div class="col-span-1 flex justify-center items-center font-semibold bg-white">Admin</div>
                            <div class="col-span-1 flex justify-center items-center font-semibold bg-gray-100">Jumlah
                            </div>
                            <div class="col-span-1 flex justify-center items-center font-semibold bg-white">Sewa</div>
                            <div class="col-span-1 flex justify-center items-center font-semibold bg-gray-100">Kembali
                            </div>
                            <div class="col-span-2 flex justify-center items-center font-semibold bg-white">Harga</div>
                            <div class="col-span-1 flex justify-center items-center font-semibold bg-gray-100">Status
                            </div>
                            <div class="col-span-2 flex justify-center items-center font-semibold rounded-r-lg bg-white">Actions</div>
                        </div>

                        <div class="flex flex-col gap-4 justify-center items-center w-full">
                            <?php while ($row = $transactionResult->fetch_assoc()) {?>
                            <div
                                class="grid grid-cols-12 border border-gray-700 border-opacity-60 gap-0 w-full min-h-16 rounded-lg shadow-md">
                                <div
                                    class="col-span-1 text-center flex justify-center items-center text-[14px] font-medium bg-gray-100 rounded-l-lg">
                                    <?php echo $row['id_transaksi']; ?>
                                </div>
                                <div
                                    class="col-span-1 text-center flex justify-center items-center text-[14px] font-medium bg-white">
                                    <?php echo $row['id_pelanggan']; ?>
                                </div>
                                <div
                                    class="col-span-1 text-center flex justify-center items-center text-[14px] font-medium bg-gray-100">
                                    <?php echo $row['id_alat']; ?>
                                </div>
                                <div
                                    class="col-span-1 text-center flex justify-center items-center text-[14px] font-medium bg-white">
                                    <?php echo $row['id_admin']; ?>
                                </div>
                                <div
                                    class="col-span-1 text-center flex justify-center items-center text-[14px] font-medium bg-gray-100">
                                    <?php echo $row['jumlah']; ?>
                                </div>
                                <div
                                    class="col-span-1 text-center flex justify-center items-center text-[14px] font-medium bg-white">
                                    <?php echo $row['tanggal_sewa']; ?>
                                </div>
                                <div
                                    class="col-span-1 text-center flex justify-center items-center text-[14px] font-medium bg-gray-100">
                                    <?php echo $row['tanggal_kembali']; ?>
                                </div>
                                <div
                                    class="col-span-2 text-center flex justify-center items-center text-[14px] font-medium bg-white">
                                    <?php echo $row['total_harga']; ?>
                                </div>
                                <div
                                    class="col-span-1 text-center flex justify-center items-center text-[14px] font-medium bg-gray-100">
                                    <?php echo $row['status']; ?>
                                </div>
                                <div class="col-span-2 text-center flex flex-row justify-center items-center gap-4 bg-white rounded-r-lg">
                                    <button
                                        onclick="openEditModal('<?php echo $row['id_transaksi']; ?>', '<?php echo $row['id_pelanggan']; ?>', '<?php echo $row['id_alat']; ?>', '<?php echo $row['id_admin']; ?>', '<?php echo $row['jumlah']; ?>', '<?php echo $row['tanggal_sewa']; ?>', '<?php echo $row['tanggal_kembali']; ?>', '<?php echo $row['total_harga']; ?>', '<?php echo $row['status']; ?>')"
                                        class="w-16 py-2 flex justify-center items-center rounded-md text-sm font-medium bg-green-600 text-white hover:bg-green-800">Edit</button>
                                    <form
                                        class="w-16 py-2 flex justify-center items-center rounded-md text-sm font-medium bg-red-600 text-white hover:bg-red-800"
                                        method="post" style="display:inline-block;">
                                        <input type="hidden" name="id" value="<?php echo $row['id_transaksi']; ?>">
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

        <!-- Add Transaksi Modal -->
        <div id="addModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Add New Transaksi</h3>
                    <div class="mt-2 px-7 py-3">
                        <form method="post">
                            <div class="mb-4">
                                <input type="text" name="id_pelanggan" placeholder="ID Pelanggan"
                                    class="w-full px-4 py-2 border rounded-md">
                            </div>
                            <div class="mb-4">
                                <input type="text" name="id_alat" placeholder="ID Alat"
                                    class="w-full px-4 py-2 border rounded-md">
                            </div>
                            <div class="mb-4">
                                <input type="text" name="id_admin" placeholder="ID Admin"
                                    class="w-full px-4 py-2 border rounded-md">
                            </div>
                            <div class="mb-4">
                                <input type="number" name="jumlah" placeholder="Jumlah"
                                    class="w-full px-4 py-2 border rounded-md">
                            </div>
                            <div class="mb-4">
                                <input type="date" name="tanggal_sewa" placeholder="Tanggal Sewa"
                                    class="w-full px-4 py-2 border rounded-md">
                            </div>
                            <div class="mb-4">
                                <input type="date" name="tanggal_kembali" placeholder="Tanggal Kembali"
                                    class="w-full px-4 py-2 border rounded-md">
                            </div>
                            <div class="mb-4">
                                <input type="number" name="total_harga" placeholder="Total Harga"
                                    class="w-full px-4 py-2 border rounded-md">
                            </div>
                            <div class="mb-4">
                                <input type="text" name="status" placeholder="Status"
                                    class="w-full px-4 py-2 border rounded-md">
                            </div>
                            <div class="flex justify-center gap-4">
                                <button type="button" onclick="closeAddModal()"
                                    class="px-4 py-2 bg-gray-600 text-white rounded-md">Cancel</button>
                                <button type="submit" name="add"
                                    class="px-4 py-2 bg-green-600 text-white rounded-md">Add</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Transaksi Modal -->
        <div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Edit Transaksi</h3>
                    <div class="mt-2 px-7 py-3">
                        <form method="post">
                            <input type="hidden" name="id" id="edit-id">
                            <div class="mb-4">
                                <input type="text" name="id_pelanggan" id="edit-id_pelanggan" placeholder="ID Pelanggan"
                                    class="w-full px-4 py-2 border rounded-md">
                            </div>
                            <div class="mb-4">
                                <input type="text" name="id_alat" id="edit-id_alat" placeholder="ID Alat"
                                    class="w-full px-4 py-2 border rounded-md">
                            </div>
                            <div class="mb-4">
                                <input type="text" name="id_admin" id="edit-id_admin" placeholder="ID Admin"
                                    class="w-full px-4 py-2 border rounded-md">
                            </div>
                            <div class="mb-4">
                                <input type="number" name="jumlah" id="edit-jumlah" placeholder="Jumlah"
                                    class="w-full px-4 py-2 border rounded-md">
                            </div>
                            <div class="mb-4">
                                <input type="date" name="tanggal_sewa" id="edit-tanggal_sewa" placeholder="Tanggal Sewa"
                                    class="w-full px-4 py-2 border rounded-md">
                            </div>
                            <div class="mb-4">
                                <input type="date" name="tanggal_kembali" id="edit-tanggal_kembali"
                                    placeholder="Tanggal Kembali" class="w-full px-4 py-2 border rounded-md">
                            </div>
                            <div class="mb-4">
                                <input type="number" name="total_harga" id="edit-total_harga" placeholder="Total Harga"
                                    class="w-full px-4 py-2 border rounded-md">
                            </div>
                            <div class="mb-4">
                                <input type="text" name="status" id="edit-status" placeholder="Status"
                                    class="w-full px-4 py-2 border rounded-md">
                            </div>
                            <div class="flex justify-center gap-4">
                                <button type="button" onclick="closeEditModal()"
                                    class="px-4 py-2 bg-gray-600 text-white rounded-md">Cancel</button>
                                <button type="submit" name="edit"
                                    class="px-4 py-2 bg-green-600 text-white rounded-md">Save</button>
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
            document.getElementById('addModal').classList.remove('hidden');
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.add('hidden');
        }

        function openEditModal(id, id_pelanggan, id_alat, id_admin, jumlah, tanggal_sewa, tanggal_kembali, total_harga, status) {
            document.getElementById('edit-id').value = id;
            document.getElementById('edit-id_pelanggan').value = id_pelanggan;
            document.getElementById('edit-id_alat').value = id_alat;
            document.getElementById('edit-id_admin').value = id_admin;
            document.getElementById('edit-jumlah').value = jumlah;
            document.getElementById('edit-tanggal_sewa').value = tanggal_sewa;
            document.getElementById('edit-tanggal_kembali').value = tanggal_kembali;
            document.getElementById('edit-total_harga').value = total_harga;
            document.getElementById('edit-status').value = status;
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
    </script>
</body>

</html>