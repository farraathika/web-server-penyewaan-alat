<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['login_user'])) {
    header('Location: login.php');
    exit;
}

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
    <title>Sewa Alat Camping - Transaksi</title>
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
                <a href="transaksi.php" class="text-lg text-gray-700 font-semibold tracking-wide">Manage Transaksi</a>
                <div>
                    <a href="logout.php">Logout</a>
                </div>
            </nav>
            <div class="container flex flex-col gap-10">
                <button onclick="openAddModal()"
                    class="w-48 py-3 px-12 rounded-lg text-white bg-green-600 text-sm font-medium hover:bg-green-800">
                    Add Transaksi
                </button>
                <div class="flex flex-col gap-4 justify-center items-center w-full">
                    <div
                        class="grid grid-cols-12 border mb-6 border-gray-700 border-opacity-60 gap-1 w-full min-h-16 rounded-lg shadow-md">
                        <div class="col-span-1 flex justify-center items-center font-semibold bg-gray-100 rounded-l-lg">ID</div>
                        <div class="col-span-1 flex justify-center items-center font-semibold">Pelanggan</div>
                        <div class="col-span-1 flex justify-center items-center font-semibold bg-gray-100">Alat</div>
                        <div class="col-span-1 flex justify-center items-center font-semibold">Admin</div>
                        <div class="col-span-1 flex justify-center items-center font-semibold bg-gray-100">Jumlah</div>
                        <div class="col-span-1 flex justify-center items-center font-semibold">Sewa</div>
                        <div class="col-span-1 flex justify-center items-center font-semibold bg-gray-100">Kembali</div>
                        <div class="col-span-2 flex justify-center items-center font-semibold">Harga</div>
                        <div class="col-span-1 flex justify-center items-center font-semibold bg-gray-100">Status</div>
                        <div class="col-span-2 flex justify-center items-center font-semibold">Actions</div>
                    </div>

                    <div class="flex flex-col gap-4 justify-center items-center w-full">
                        <?php while ($row = $transactionResult->fetch_assoc()) {?>
                        <div
                            class="grid grid-cols-12 border border-gray-700 border-opacity-60 gap-1 w-full min-h-16 rounded-lg shadow-md">
                            <div class="col-span-1 text-center flex justify-center items-center text-[14px] font-medium bg-gray-100 rounded-l-lg">
                                <?php echo $row['id_transaksi']; ?>
                            </div>
                            <div class="col-span-1 text-center flex justify-center items-center text-[14px] font-medium">
                                <?php echo $row['id_pelanggan']; ?>
                            </div>
                            <div class="col-span-1 text-center flex justify-center items-center text-[14px] font-medium bg-gray-100">
                                <?php echo $row['id_alat']; ?>
                            </div>
                            <div class="col-span-1 text-center flex justify-center items-center text-[14px] font-medium">
                                <?php echo $row['id_admin']; ?>
                            </div>
                            <div class="col-span-1 text-center flex justify-center items-center text-[14px] font-medium bg-gray-100">
                                <?php echo $row['jumlah']; ?>
                            </div>
                            <div class="col-span-1 text-center flex justify-center items-center text-[14px] font-medium">
                                <?php echo $row['tanggal_sewa']; ?>
                            </div>
                            <div class="col-span-1 text-center flex justify-center items-center text-[14px] font-medium bg-gray-100">
                                <?php echo $row['tanggal_kembali']; ?>
                            </div>
                            <div class="col-span-2 text-center flex justify-center items-center text-[14px] font-medium">
                                <?php echo $row['total_harga']; ?>
                            </div>
                            <div class="col-span-1 text-center flex justify-center items-center text-[14px] font-medium bg-gray-100">
                                <?php echo $row['status']; ?>
                            </div>
                            <div class="col-span-2 text-center flex flex-row justify-center items-center gap-4">
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
                            <input type="text" name="id_alat" placeholder="ID Alat" class="w-full px-4 py-2 border rounded-md">
                        </div>
                        <div class="mb-4">
                            <input type="text" name="id_admin" placeholder="ID Admin" class="w-full px-4 py-2 border rounded-md">
                        </div>
                        <div class="mb-4">
                            <input type="number" name="jumlah" placeholder="Jumlah" class="w-full px-4 py-2 border rounded-md">
                        </div>
                        <div class="mb-4">
                            <input type="date" name="tanggal_sewa" placeholder="Tanggal Sewa" class="w-full px-4 py-2 border rounded-md">
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
                            <input type="text" name="status" placeholder="Status" class="w-full px-4 py-2 border rounded-md">
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
                            <input type="date" name="tanggal_kembali" id="edit-tanggal_kembali" placeholder="Tanggal Kembali"
                                class="w-full px-4 py-2 border rounded-md">
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
