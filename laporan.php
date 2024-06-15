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
        $name = $_POST['name'];
        $price = $_POST['price'];
        $stock = $_POST['stock'];
        $description = $_POST['description'];

        $sql = "INSERT INTO alat (nama, harga_sewa_per_hari, stok, deskripsi) VALUES ('$name', '$price', '$stock', '$description')";
        $conn->query($sql);
    } elseif (isset($_POST['edit'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $price = $_POST['price'];
        $stock = $_POST['stock'];
        $description = $_POST['description'];

        $sql = "UPDATE alat SET nama='$name', harga_sewa_per_hari='$price', stok='$stock', deskripsi='$description' WHERE id_alat='$id'";
        $conn->query($sql);
    } elseif (isset($_POST['delete'])) {
        $id = $_POST['id'];

        $sql = "DELETE FROM alat WHERE id_alat='$id'";
        $conn->query($sql);
    }

}

// Fetch stock data
$stockQuery = "SELECT * FROM alat_dipinjam";
$stockResult = $conn->query($stockQuery);

$view2query = "SELECT * FROM detail_penyewaan";
$view2result = $conn->query($view2query);

$view3query = "SELECT * FROM stok_tersisa";
$view3result = $conn->query($view3query);

$view4query = "SELECT * FROM alat_dipinjam_perbulan";
$view4result = $conn->query($view4query);

$view5query = "SELECT * FROM detail_pesanan";
$view5result = $conn->query($view5query);
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
                
                <div class="container-content mb-2 flex flex-col gap-2 overflow-y-auto">
                <div
                    class="navbar flex flex-row shadow-xl justify-between items-center w-full h-16 max-h-16 bg-white py-3 my-4 px-4 rounded-lg">
                    <a href="stock.php" class="text-lg text-gray-700 font-semibold tracking-wide">Alat Yang Di Pinjam</a>
                    <div class="flex flex-row justify-center items-center gap-2">
                        <!-- <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                            class="bi bi-person-fill-check" viewBox="0 0 16 16">
                            <path
                                d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m1.679-4.493-1.335 2.226a.75.75 0 0 1-1.174.144l-.774-.773a.5.5 0 0 1 .708-.708l.547.548 1.17-1.951a.5.5 0 1 1 .858.514M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                            <path
                                d="M2 13c0 1 1 1 1 1h5.256A4.5 4.5 0 0 1 8 12.5a4.5 4.5 0 0 1 1.544-3.393Q8.844 9.002 8 9c-5 0-6 3-6 4" />
                        </svg> -->
                        <!-- <p class="text-[13px] font-medium text-gray-800">
                            <?php echo $_SESSION['admin_name']; ?>
                        </p> -->
                    </div>
                </div>
                    <div class="flex flex-col gap-2 justify-center items-center w-full">
                        <div
                            class="grid grid-cols-12 border border-gray-700 border-opacity-60 gap-6 w-full min-h-16 rounded-lg shadow-md bg-white">
                            <div class="col-span-1 flex justify-center items-center font-semibold bg-gray-100 rounded-l-lg">
                                ID</div>
                            <div class="col-span-3 flex justify-center items-center font-semibold">Nama</div>
                            <div class="col-span-3 flex justify-center items-center font-semibold bg-gray-100">ID Transaksi</div>
                            <div class="col-span-2 flex justify-center items-center font-semibold">Tanggal Sewa</div>
                            <div class="col-span-3 flex justify-center items-center font-semibold bg-gray-100">Tanggal Kembali
                            </div>
                        </div>

                        <div class="flex flex-col gap-2 justify-center items-center w-full">
                            <?php while ($row = $stockResult->fetch_assoc()) {?>
                            <div
                                class="grid grid-cols-12 border border-gray-700 border-opacity-60 gap-6 w-full min-h-16 rounded-lg shadow-md bg-white">
                                <div
                                    class="col-span-1 text-center flex justify-center items-center text-[14px] font-medium bg-gray-100 rounded-l-lg">
                                    <?php echo $row['id_alat']; ?>
                                </div>
                                <div class="col-span-3 text-center flex justify-center items-center text-[14px] font-medium">
                                    <?php echo $row['nama']; ?>
                                </div>
                                <div
                                    class="col-span-3 text-center flex justify-center items-center text-[14px] font-medium bg-gray-100">
                                    <?php echo $row['id_transaksi']; ?>
                                </div>
                                <div class="col-span-2 text-center flex justify-center items-center text-[14px] font-medium">
                                    <?php echo $row['tanggal_sewa']; ?>
                                </div>
                                <div
                                    class="col-span-3 text-center flex justify-center items-center text-[14px] font-medium bg-gray-100 px-2">
                                    <?php echo $row['tanggal_kembali']; ?>
                                </div>
                                
                            </div>
                            <?php }?>
                        </div>
                    <div
                        class="navbar flex flex-row shadow-xl justify-between items-center w-full h-14 max-h-14 bg-white my-4 px-4 rounded-lg">
                        <a href="stock.php" class="text-lg text-gray-700 font-semibold tracking-wide">Detail Penyewaan</a>
                        <div class="flex flex-row justify-center items-center gap-2">
                        <!-- <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                            class="bi bi-person-fill-check" viewBox="0 0 16 16">
                            <path
                                d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m1.679-4.493-1.335 2.226a.75.75 0 0 1-1.174.144l-.774-.773a.5.5 0 0 1 .708-.708l.547.548 1.17-1.951a.5.5 0 1 1 .858.514M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                            <path
                                d="M2 13c0 1 1 1 1 1h5.256A4.5 4.5 0 0 1 8 12.5a4.5 4.5 0 0 1 1.544-3.393Q8.844 9.002 8 9c-5 0-6 3-6 4" />
                        </svg> -->
                        <!-- <p class="text-[13px] font-medium text-gray-800">
                            <?php echo $_SESSION['admin_name']; ?>
                        </p> -->
                    </div>
                </div>

                <div class="flex flex-col gap-2 justify-center items-center w-full">
                        <div
                            class="grid grid-cols-12 border border-gray-700 border-opacity-60 gap-6 w-full min-h-16 rounded-lg shadow-md bg-white">
                            <div class="col-span-1 flex justify-center items-center font-semibold bg-gray-100 rounded-l-lg">
                                ID</div>
                            <div class="col-span-1 flex justify-center items-center font-semibold">Nama Pelanggan</div>
                            <div class="col-span-2 flex justify-center items-center font-semibold bg-gray-100">Nama Alat</div>
                            <div class="col-span-1 flex justify-center items-center font-semibold">Jumlah</div>
                            <div class="col-span-2 flex justify-center items-center font-semibold bg-gray-100">Tanggal Sewa</div>
                            <div class="col-span-2 flex justify-center items-center font-semibold">Tanggal Kembali</div>
                            <div class="col-span-1 flex justify-center items-center font-semibold bg-gray-100">Total Harga</div>
                            <div class="col-span-1 flex justify-center items-center font-semibold">Denda</div>
                            <div class="col-span-1 flex justify-center items-center font-semibold bg-gray-100">Status</div>
                            </div>
                        </div>

                        <div class="flex flex-col gap-2 justify-center items-center w-full">
                            <?php while ($row = $view2result->fetch_assoc()) {?>
                            <div
                                class="grid grid-cols-12 border border-gray-700 border-opacity-60 gap-6 w-full min-h-16 rounded-lg shadow-md bg-white">
                                <div
                                    class="col-span-1 text-center flex justify-center items-center text-[14px] font-medium bg-gray-100 rounded-l-lg">
                                    <?php echo $row['id_transaksi']; ?>
                                </div>
                                <div class="col-span-1 text-center flex justify-center items-center text-[14px] font-medium">
                                    <?php echo $row['nama_pelanggan']; ?>
                                </div>
                                <div
                                    class="col-span-2 text-center flex justify-center items-center text-[14px] font-medium bg-gray-100 rounded-l-lg">
                                    <?php echo $row['nama_alat']; ?>
                                </div>
                                <div class="col-span-1 text-center flex justify-center items-center text-[14px] font-medium">
                                    <?php echo $row['jumlah']; ?>
                                </div>
                                <div
                                    class="col-span-2 text-center flex justify-center items-center text-[14px] font-medium bg-gray-100 rounded-l-lg">
                                    <?php echo $row['tanggal_sewa']; ?>
                                </div>
                                <div
                                    class="col-span-2 text-center flex justify-center items-center text-[14px] font-medium">
                                    <?php echo $row['tanggal_kembali']; ?>
                                </div>
                                <div class="col-span-1 text-center flex justify-center items-center text-[14px] font-medium bg-gray-100 rounded-l-lg">
                                    <?php echo $row['total_harga']; ?>
                                </div>
                                <div
                                    class="col-span-1 text-center flex justify-center items-center text-[14px] font-medium">
                                    <?php echo $row['denda']; ?>
                                </div>
                                <div
                                    class="col-span-1 text-center flex justify-center items-center text-[14px] font-medium bg-gray-100 rounded-l-lg">
                                    <?php echo $row['status']; ?>
                                </div>
                                
                            </div>
                            <?php }?>
                        </div>

                        <div
                    class="navbar flex flex-row shadow-xl justify-between items-center w-full h-16 max-h-16 bg-white my-4 px-4 rounded-lg">
                    <a href="stock.php" class="text-lg text-gray-700 font-semibold tracking-wide">Stok Tersedia</a>

                    <div class="flex flex-row justify-center items-center gap-2">
                        <!-- <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                            class="bi bi-person-fill-check" viewBox="0 0 16 16">
                            <path
                                d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m1.679-4.493-1.335 2.226a.75.75 0 0 1-1.174.144l-.774-.773a.5.5 0 0 1 .708-.708l.547.548 1.17-1.951a.5.5 0 1 1 .858.514M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                            <path
                                d="M2 13c0 1 1 1 1 1h5.256A4.5 4.5 0 0 1 8 12.5a4.5 4.5 0 0 1 1.544-3.393Q8.844 9.002 8 9c-5 0-6 3-6 4" />
                        </svg> -->
                        <!-- <p class="text-[13px] font-medium text-gray-800">
                            <?php echo $_SESSION['admin_name']; ?>
                        </p> -->
                    </div>
                </div>

                <div class="flex flex-col gap-2 justify-center items-center w-full">
                        <div
                            class="grid grid-cols-12 border border-gray-700 border-opacity-60 gap-6 w-full min-h-16 rounded-lg shadow-md bg-white">
                            <div class="col-span-6 flex justify-center items-center font-semibold bg-gray-100 rounded-l-lg">
                                Nama Produk</div>
                            <div class="col-span-6 flex justify-center items-center font-semibold">Stok Tersisa</div>
                            </div>
                        </div>

                        <div class="flex flex-col gap-2 justify-center items-center w-full">
                            <?php while ($row = $view3result->fetch_assoc()) {?>
                            <div
                                class="grid grid-cols-12 border border-gray-700 border-opacity-60 gap-6 w-full min-h-16 rounded-lg shadow-md bg-white">
                                <div
                                    class="col-span-6 text-center flex justify-center items-center text-[14px] font-medium bg-gray-100 rounded-l-lg">
                                    <?php echo $row['nama_produk']; ?>
                                </div>
                                <div class="col-span-6 text-center flex justify-center items-center text-[14px] font-medium">
                                    <?php echo $row['stok_tersisa']; ?>
                                </div>
                            </div>
                            <?php }?>
                        </div>

                        <div
                    class="navbar flex flex-row shadow-xl justify-between items-center w-full h-16 max-h-16 bg-white my-4 px-4 rounded-lg">
                    <a href="stock.php" class="text-lg text-gray-700 font-semibold tracking-wide">Alat Di Pinjam Per Bulan</a>
                    <div class="flex flex-row justify-center items-center gap-2">
                        <!-- <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                            class="bi bi-person-fill-check" viewBox="0 0 16 16">
                            <path
                                d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m1.679-4.493-1.335 2.226a.75.75 0 0 1-1.174.144l-.774-.773a.5.5 0 0 1 .708-.708l.547.548 1.17-1.951a.5.5 0 1 1 .858.514M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                            <path
                                d="M2 13c0 1 1 1 1 1h5.256A4.5 4.5 0 0 1 8 12.5a4.5 4.5 0 0 1 1.544-3.393Q8.844 9.002 8 9c-5 0-6 3-6 4" />
                        </svg> -->
                        <!-- <p class="text-[13px] font-medium text-gray-800">
                            <?php echo $_SESSION['admin_name']; ?>
                        </p> -->
                    </div>
                </div>

                <div class="flex flex-col gap-2 justify-center items-center w-full">
                        <div
                            class="grid grid-cols-12 border border-gray-700 border-opacity-60 gap-6 w-full min-h-16 rounded-lg shadow-md bg-white">
                            <div class="col-span-4 flex justify-center items-center font-semibold bg-gray-100 rounded-l-lg">Bulan</div>
                            <div class="col-span-4 flex justify-center items-center font-semibold">Nama Alat</div>
                            <div class="col-span-4 flex justify-center items-center font-semibold bg-gray-100 rounded-l-lg">Jumlah Peminjaman</div>
                            </div>
                        </div>

                        <div class="flex flex-col gap-2 justify-center items-center w-full">
                            <?php while ($row = $view4result->fetch_assoc()) {?>
                            <div
                                class="grid grid-cols-12 border border-gray-700 border-opacity-60 gap-6 w-full min-h-16 rounded-lg shadow-md bg-white">
                                <div
                                    class="col-span-4 text-center flex justify-center items-center text-[14px] font-medium bg-gray-100 rounded-l-lg">
                                    <?php echo $row['bulan']; ?>
                                </div>
                                <div class="col-span-4 text-center flex justify-center items-center text-[14px] font-medium">
                                    <?php echo $row['nama_alat']; ?>
                                </div>
                                <div class="col-span-4 text-center flex justify-center items-center text-[14px] font-medium bg-gray-100 rounded-l-lg">
                                    <?php echo $row['jumlah_peminjaman']; ?>
                                </div>
                            </div>
                            <?php }?>
                        </div>

                        <div
                    class="navbar flex flex-row shadow-xl justify-between items-center w-full h-16 max-h-16 bg-white my-4 px-4 rounded-lg">
                    <a href="stock.php" class="text-lg text-gray-700 font-semibold tracking-wide">Detail Pesanan</a>

                    <div class="flex flex-row justify-center items-center gap-2">
                        <!-- <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                            class="bi bi-person-fill-check" viewBox="0 0 16 16">
                            <path
                                d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m1.679-4.493-1.335 2.226a.75.75 0 0 1-1.174.144l-.774-.773a.5.5 0 0 1 .708-.708l.547.548 1.17-1.951a.5.5 0 1 1 .858.514M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                            <path
                                d="M2 13c0 1 1 1 1 1h5.256A4.5 4.5 0 0 1 8 12.5a4.5 4.5 0 0 1 1.544-3.393Q8.844 9.002 8 9c-5 0-6 3-6 4" />
                        </svg> -->
                        <!-- <p class="text-[13px] font-medium text-gray-800">
                            <?php echo $_SESSION['admin_name']; ?>
                        </p> -->
                    </div>
                </div>

                <div class="flex flex-col gap-2 justify-center items-center w-full">
                        <div
                            class="grid grid-cols-12 border border-gray-700 border-opacity-60 gap-6 w-full min-h-16 rounded-lg shadow-md bg-white">
                            <div class="col-span-4 flex justify-center items-center font-semibold bg-gray-100 rounded-l-lg">Nama Pelanggan</div>
                            <div class="col-span-4 flex justify-center items-center font-semibold">Total</div>
                            <div class="col-span-4 flex justify-center items-center font-semibold bg-gray-100 rounded-l-lg">Tanggal Pesanan</div>
                            </div>
                        </div>

                        <div class="flex flex-col gap-2 justify-center items-center w-full">
                            <?php while ($row = $view5result->fetch_assoc()) {?>
                            <div
                                class="grid grid-cols-12 border border-gray-700 border-opacity-60 gap-6 w-full min-h-16 rounded-lg shadow-md bg-white">
                                <div
                                    class="col-span-4 text-center flex justify-center items-center text-[14px] font-medium bg-gray-100 rounded-l-lg">
                                    <?php echo $row['nama_pelanggan']; ?>
                                </div>
                                <div class="col-span-4 text-center flex justify-center items-center text-[14px] font-medium">
                                    <?php echo $row['total']; ?>
                                </div>
                                <div class="col-span-4 text-center flex justify-center items-center text-[14px] font-medium bg-gray-100 rounded-l-lg">
                                    <?php echo $row['tanggal_pesanan']; ?>
                                </div>
                            </div>
                            <?php }?>
                        </div>

                
                    </div>
                </div>
            </div>
        </main>

        <!-- Add Item Modal -->
        <div id="addModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Add New Item</h3>
                    <div class="mt-2 px-7 py-3">
                        <form method="post">
                            <div class="mb-4">
                                <input type="text" name="name" placeholder="Name" class="w-full px-4 py-2 border rounded-md">
                            </div>
                            <div class="mb-4">
                                <input type="text" name="price" placeholder="Price" class="w-full px-4 py-2 border rounded-md">
                            </div>
                            <div class="mb-4">
                                <input type="text" name="stock" placeholder="Stock" class="w-full px-4 py-2 border rounded-md">
                            </div>
                            <div class="mb-4">
                                <textarea name="description" placeholder="Description"
                                    class="w-full px-4 py-2 border rounded-md"></textarea>
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


        <footer class="h-[5vh] w-full px-4 pr-6 py-[0.4px]">
            <div class="rounded-lg h-full bottom-0 bg-white flex flex-row justify-center items-center">
            
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

            function openEditModal(id, name, price, stock, description) {
                document.getElementById('editId').value = id;
                document.getElementById('editName').value = name;
                document.getElementById('editPrice').value = price;
                document.getElementById('editStock').value = stock;
                document.getElementById('editDescription').value = description;
                document.getElementById('editModal').style.display = 'block';
            }

            function closeEditModal() {
                document.getElementById('editModal').style.display = 'none';
            }
        </script>

</body>

</html>