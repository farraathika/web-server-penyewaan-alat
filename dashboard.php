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

// // Fetch data for dashboard
// $totalCustomersQuery = "SELECT COUNT(*) AS total_pelanggan FROM pelanggan";
// $totalCustomersResult = $conn->query($totalCustomersQuery);
// $totalCustomers = $totalCustomersResult->fetch_assoc()['total_pelanggan'];

// $totalStockQuery = "SELECT SUM(stok) AS total_stok FROM alat";
// $totalStockResult = $conn->query($totalStockQuery);
// $totalStock = $totalStockResult->fetch_assoc()['total_stok'];

// $totalRevenueQuery = "SELECT SUM(total_harga) AS total_pendapatan FROM transaksi WHERE status = 'Selesai'";
// $totalRevenueResult = $conn->query($totalRevenueQuery);
// $totalRevenue = $totalRevenueResult->fetch_assoc()['total_pendapatan'];

// $recentTransactionsQuery = "SELECT * FROM view_detail_transaksi ORDER BY tanggal_sewa DESC LIMIT 5";
// $recentTransactionsResult = $conn->query($recentTransactionsQuery);

// $stockDetailsQuery = "SELECT * FROM view_stok";
// $stockDetailsResult = $conn->query($stockDetailsQuery);
// ?>

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
                    class="navbar flex flex-row shadow-xl justify-between items-center w-full min-h-16 h-16 bg-white my-4 px-4 rounded-lg">
                    <a href="dashboard.php" class="text-lg text-gray-700 font-semibold tracking-wide">Dashboard</a>
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
                <div class="container-content mb-2 flex flex-col gap-4 overflow-y-auto shadow shadow-md">
                    <div class="grid grid-cols-3 gap-4">
                        <div class="bg-white shadow-lg rounded-lg p-6">
                            <h2 class="text-xl font-bold text-gray-700">Total Customers</h2>
                            <p class="text-3xl font-semibold text-gray-900">
                                <?php echo $totalCustomers; ?>
                            </p>
                        </div>
                        <div class="bg-white shadow-lg rounded-lg p-6">
                            <h2 class="text-xl font-bold text-gray-700">Total Stock</h2>
                            <p class="text-3xl font-semibold text-gray-900">
                                <?php echo $totalStock; ?>
                            </p>
                        </div>
                        <div class="bg-white shadow-lg rounded-lg p-6">
                            <h2 class="text-xl font-bold text-gray-700">Total Revenue</h2>
                            <p class="text-3xl font-semibold text-gray-900">
                                <?php echo number_format($totalRevenue, 2); ?>
                            </p>
                        </div>
                    </div>
                    <div class="bg-white shadow-lg rounded-lg p-6">
                        <h2 class="text-2xl font-bold text-gray-700 mb-4">Recent Transactions</h2>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead>
                                    <tr>
                                        <th class="py-2 px-4 border-b border-gray-200">Transaction ID</th>
                                        <th class="py-2 px-4 border-b border-gray-200">Customer Name</th>
                                        <th class="py-2 px-4 border-b border-gray-200">Product</th>
                                        <th class="py-2 px-4 border-b border-gray-200">Admin</th>
                                        <th class="py-2 px-4 border-b border-gray-200">Quantity</th>
                                        <th class="py-2 px-4 border-b border-gray-200">Rent Date</th>
                                        <th class="py-2 px-4 border-b border-gray-200">Return Date</th>
                                        <th class="py-2 px-4 border-b border-gray-200">Total Price</th>
                                        <th class="py-2 px-4 border-b border-gray-200">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $recentTransactionsResult->fetch_assoc()) {?>
                                    <tr>
                                        <td class="py-2 px-4 border-b border-gray-200 text-center">
                                            <?php echo $row['id_transaksi']; ?>
                                        </td>
                                        <td class="py-2 px-4 border-b border-gray-200 text-center">
                                            <?php echo $row['nama_pelanggan']; ?>
                                        </td>
                                        <td class="py-2 px-4 border-b border-gray-200 text-center">
                                            <?php echo $row['nama_produk']; ?>
                                        </td>
                                        <td class="py-2 px-4 border-b border-gray-200 text-center">
                                            <?php echo $row['nama_admin']; ?>
                                        </td>
                                        <td class="py-2 px-4 border-b border-gray-200 text-center">
                                            <?php echo $row['jumlah']; ?>
                                        </td>
                                        <td class="py-2 px-4 border-b border-gray-200 text-center">
                                            <?php echo $row['tanggal_sewa']; ?>
                                        </td>
                                        <td class="py-2 px-4 border-b border-gray-200 text-center">
                                            <?php echo $row['tanggal_kembali']; ?>
                                        </td>
                                        <td class="py-2 px-4 border-b border-gray-200 text-center">
                                            <?php echo number_format($row['total_harga'], 2); ?>
                                        </td>
                                        <td class="py-2 px-4 border-b border-gray-200 text-center">
                                            <?php echo $row['status']; ?>
                                        </td>
                                    </tr>
                                    <?php }?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="bg-white shadow-lg rounded-lg p-6">
                        <h2 class="text-2xl font-bold text-gray-700 mb-4">Summary of Stock Details</h2>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead>
                                    <tr>
                                        <th class="py-2 px-4 border-b border-gray-200">Product Name</th>
                                        <th class="py-2 px-4 border-b border-gray-200">Remaining Stock</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $stockDetailsResult->fetch_assoc()) {?>
                                    <tr>
                                        <td class="py-2 px-4 border-b border-gray-200">
                                            <?php echo $row['nama_produk']; ?>
                                        </td>
                                        <td class="py-2 px-4 border-b border-gray-200">
                                            <?php echo $row['stok_tersisa']; ?>
                                        </td>
                                    </tr>
                                    <?php }?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <footer class="h-[5vh] w-full px-4 pr-6 py-[0.4px]">
            <div class="rounded-lg h-full bottom-0 bg-white flex flex-row justify-center items-center">
                haloo
            </div>
        </footer>
    </section>

</body>

</html>