<?php
session_start();

if (!isset($_SESSION['login_user'])) {
    header('Location: login.php');
    exit;
}

include "proses/get_alat_peminjaman.php";
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
$stockQuery = "SELECT * FROM alat";
$stockResult = $conn->query($stockQuery);
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
                    <a href="stock.php" class="text-lg text-gray-700 font-semibold tracking-wide">Manage Stock</a>
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
                    
                    <div class="flex flex-col gap-10 justify-center items-center w-full">
                        <div
                            class="grid grid-cols-12 border border-gray-700 border-opacity-60 gap-6 w-full min-h-16 rounded-lg shadow-md bg-white">
                            <div class="col-span-1 flex justify-center items-center font-semibold bg-gray-100 rounded-l-lg">
                                ID</div>
                            <div class="col-span-3 flex justify-center items-center font-semibold">Name</div>
                            <div class="col-span-3 flex justify-center items-center font-semibold bg-gray-100">Price</div>
                            <div class="col-span-2 flex justify-center items-center font-semibold">Stock</div>
                            <div class="col-span-3 flex justify-center items-center font-semibold bg-gray-100">Description
                            </div>
                        </div>

                        <div class="flex flex-col gap-4 justify-center items-center w-full">
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
                                    <?php echo $row['harga_sewa_per_hari']; ?>
                                </div>
                                <div class="col-span-2 text-center flex justify-center items-center text-[14px] font-medium">
                                    <?php echo $row['stok']; ?>
                                </div>
                                <div
                                    class="col-span-3 text-center flex justify-center items-center text-[14px] font-medium bg-gray-100 px-2">
                                    <?php echo $row['deskripsi']; ?>
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

       

        <div>
    <h3 style="text-align: center; font-family: Arial;">Ringkasan Pemesanan</h3>
    <table border="0" class="fontdt">
        <thead>
            <tr>
                <th>No</th>
                <th>ID Alat</th>
                <th>Nama Alat</th>
                <th>ID Transaksi</th>
                <th>Tanggal Sewa</th>
                <th>Tanggal Kembali</th>
            </tr>
        </thead>
        <tbody>
            <?php
include 'proses/get_alat_peminjaman.php';

$no = 1;
if (mysqli_num_rows($get_alat_peminjaman) > 0) {
    while ($data = mysqli_fetch_assoc($get_alat_peminjaman)) {
        echo "<tr>";
        echo "<td>" . $no++ . "</td>";
        echo "<td>" . $data['id_alat'] . "</td>";
        echo "<td>" . $data['nama'] . "</td>";
        echo "<td>" . $data['id_transaksi'] . "</td>";
        echo "<td>" . $data['tanggal_sewa'] . "</td>";
        echo "<td>" . $data['tanggal_kembali'] . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='6'>No data found</td></tr>";
}
?>
        </tbody>
    </table>
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