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
        $id_transaksi = $_POST['id_transaksi'];
        $tanggal_pembayaran = $_POST['tanggal_pembayaran'];
        $jumlah_pembayaran = $_POST['jumlah_pembayaran'];
        $metode_pembayaran = $_POST['metode_pembayaran'];

        $sql = "INSERT INTO pembayaran (id_transaksi, tanggal_pembayaran, jumlah_pembayaran, metode_pembayaran) VALUES ('$id_transaksi', '$tanggal_pembayaran', '$jumlah_pembayaran', '$metode_pembayaran')";
        $conn->query($sql);
    } elseif (isset($_POST['edit'])) {
        $id = $_POST['id'];
        $id_transaksi = $_POST['id_transaksi'];
        $tanggal_pembayaran = $_POST['tanggal_pembayaran'];
        $jumlah_pembayaran = $_POST['jumlah_pembayaran'];
        $metode_pembayaran = $_POST['metode_pembayaran'];
        
        $sql = "UPDATE pembayaran SET id_transaksi='$id_transaksi', tanggal_pembayaran='$tanggal_pembayaran', jumlah_pembayaran='$jumlah_pembayaran', metode_pembayaran='$metode_pembayaran' WHERE id_pembayaran='$id'";
        $conn->query($sql);
    } elseif (isset($_POST['delete'])) {
        $id = $_POST['id'];

        $sql = "DELETE FROM pembayaran WHERE id_pembayaran='$id'";
        $conn->query($sql);
    }
}

// Fetch payment data
$paymentQuery = "SELECT * FROM pembayaran";
$paymentResult = $conn->query($paymentQuery);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sewa Alat Camping - Pembayaran</title>
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
                <a href="pembayaran.php" class="text-lg text-gray-700 font-semibold tracking-wide">Manage Payments</a>
                <div>
                    <a href="logout.php">Logout</a>
                </div>
            </nav>
            <div class="container flex flex-col gap-10">
                <button onclick="openAddModal()"
                    class="w-48 py-3 px-12 rounded-lg text-white bg-green-600 text-sm font-medium hover:bg-green-800">
                    Add Payment
                </button>
                <div class="flex flex-col gap-4 justify-center items-center w-full">
                    <div
                        class="grid grid-cols-12 border border-gray-700 border-opacity-60 gap-1 w-full min-h-16 rounded-lg shadow-md mb-6">
                        <div class="col-span-1 flex justify-center items-center font-semibold bg-gray-100 rounded-l-lg">
                            ID</div>
                        <div class="col-span-2 flex justify-center items-center font-semibold">Transaction ID</div>
                        <div class="col-span-2 flex justify-center items-center font-semibold bg-gray-100">Payment Date</div>
                        <div class="col-span-2 flex justify-center items-center font-semibold">Amount</div>
                        <div class="col-span-3 flex justify-center items-center font-semibold bg-gray-100">Method</div>
                        <div class="col-span-2 flex justify-center items-center font-semibold">Actions</div>
                    </div>

                    <div class="flex flex-col gap-4 justify-center items-center w-full">
                        <?php while ($row = $paymentResult->fetch_assoc()) {?>
                        <div
                            class="grid grid-cols-12 border border-gray-700 border-opacity-60 gap-1 w-full min-h-16 rounded-lg shadow-md">
                            <div
                                class="col-span-1 text-center flex justify-center items-center text-[14px] font-medium bg-gray-100 rounded-l-lg">
                                <?php echo $row['id_pembayaran']; ?>
                            </div>
                            <div class="col-span-2 text-center flex justify-center items-center text-[14px] font-medium">
                                <?php echo $row['id_transaksi']; ?>
                            </div>
                            <div class="col-span-2 text-center flex justify-center items-center text-[14px] font-medium bg-gray-100">
                                <?php echo $row['tanggal_pembayaran']; ?>
                            </div>
                            <div class="col-span-2 text-center flex justify-center items-center text-[14px] font-medium">
                                <?php echo $row['jumlah_pembayaran']; ?>
                            </div>
                            <div class="col-span-3 text-center flex justify-center items-center text-[14px] font-medium bg-gray-100">
                                <?php echo $row['metode_pembayaran']; ?>
                            </div>
                            <div class="col-span-2 text-center flex justify-center items-center text-[14px] font-medium gap-4">
                                <button
                                    onclick="openEditModal('<?php echo $row['id_pembayaran']; ?>', '<?php echo $row['id_transaksi']; ?>', '<?php echo $row['tanggal_pembayaran']; ?>', '<?php echo $row['jumlah_pembayaran']; ?>', '<?php echo $row['metode_pembayaran']; ?>')"
                                    class="px-4 py-2 flex justify-center items-center rounded-md text-sm font-medium bg-green-600 text-white hover:bg-green-800">Edit</button>
                                <form
                                    class="px-4 py-2 flex justify-center items-center rounded-md text-sm font-medium bg-red-600 text-white hover:bg-red-800"
                                    method="post" style="display:inline-block;">
                                    <input type="hidden" name="id" value="<?php echo $row['id_pembayaran']; ?>">
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

    <!-- Add Payment Modal -->
    <div id="addModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Add New Payment</h3>
                <div class="mt-2 px-7 py-3">
                    <form method="post">
                        <div class="mb-4">
                            <input type="text" name="id_transaksi" placeholder="Transaction ID"
                                class="w-full px-4 py-2 border rounded-md">
                        </div>
                        <div class="mb-4">
                            <input type="date" name="tanggal_pembayaran" placeholder="Payment Date"
                                class="w-full px-4 py-2 border rounded-md">
                        </div>
                        <div class="mb-4">
                            <input type="text" name="jumlah_pembayaran" placeholder="Amount"
                                class="w-full px-4 py-2 border rounded-md">
                        </div>
                        <div class="mb-4">
                            <input type="text" name="metode_pembayaran" placeholder="Payment Method"
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

    <!-- Edit Payment Modal -->
    <div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Edit Payment</h3>
                <div class="mt-2 px-7 py-3">
                    <form method="post">
                        <input type="hidden" id="edit-id" name="id">
                        <div class="mb-4">
                            <input type="text" id="edit-id_transaksi" name="id_transaksi" placeholder="Transaction ID"
                                class="w-full px-4 py-2 border rounded-md">
                        </div>
                        <div class="mb-4">
                            <input type="date" id="edit-tanggal_pembayaran" name="tanggal_pembayaran"
                                placeholder="Payment Date" class="w-full px-4 py-2 border rounded-md">
                        </div>
                        <div class="mb-4">
                            <input type="text" id="edit-jumlah_pembayaran" name="jumlah_pembayaran" placeholder="Amount"
                                class="w-full px-4 py-2 border rounded-md">
                        </div>
                        <div class="mb-4">
                            <input type="text" id="edit-metode_pembayaran" name="metode_pembayaran"
                                placeholder="Payment Method" class="w-full px-4 py-2 border rounded-md">
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

        function openEditModal(id, id_transaksi, tanggal_pembayaran, jumlah_pembayaran, metode_pembayaran) {
            document.getElementById('edit-id').value = id;
            document.getElementById('edit-id_transaksi').value = id_transaksi;
            document.getElementById('edit-tanggal_pembayaran').value = tanggal_pembayaran;
            document.getElementById('edit-jumlah_pembayaran').value = jumlah_pembayaran;
            document.getElementById('edit-metode_pembayaran').value = metode_pembayaran;
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
    </script>
</body>

</html>
