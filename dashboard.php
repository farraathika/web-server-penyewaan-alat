<?php
session_start();

if (!isset($_SESSION['login_user'])) {
    header('Location: login.php');
    exit;
}

include 'db/connection.php';
$admin_email = $_SESSION['login_user'];

// Fetch admin name
$sql = "SELECT nama FROM admin WHERE email = '$admin_email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $admin_name = $row['nama'];
} else {
    $admin_name = "Unknown";
}
$_SESSION['admin_name'] = $admin_name;

// Fetching data for cards
$sql_total_stock = "SELECT SUM(stok) AS total_stock FROM alat";
$result_total_stock = $conn->query($sql_total_stock);
$total_stock = $result_total_stock->fetch_assoc()['total_stock'];

$sql_total_profit = "SELECT SUM(total_harga) AS total_profit FROM transaksi WHERE status = 'Selesai'";
$result_total_profit = $conn->query($sql_total_profit);
$total_profit = $result_total_profit->fetch_assoc()['total_profit'];

$sql_total_customers = "SELECT COUNT(*) AS total_customers FROM pelanggan";
$result_total_customers = $conn->query($sql_total_customers);
$total_customers = $result_total_customers->fetch_assoc()['total_customers'];

$sql_total_transactions = "SELECT COUNT(*) AS total_transactions FROM transaksi";
$result_total_transactions = $conn->query($sql_total_transactions);
$total_transactions = $result_total_transactions->fetch_assoc()['total_transactions'];

// Fetching data for detailed tables
$sql_table_data = "SELECT t.id_transaksi, p.nama AS nama_pelanggan, a.nama AS nama_alat, t.jumlah, t.total_harga, t.tanggal_sewa, t.status
                   FROM transaksi t
                   JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan
                   JOIN alat a ON t.id_alat = a.id_alat";
$result_table_data = $conn->query($sql_table_data);
$table_data = [];
while ($row = $result_table_data->fetch_assoc()) {
    $table_data[] = $row;
}

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
    <section class="h-[100vh] w-full flex flex-col bg-gray-200">
        <main class="w-full h-[95vh] flex flex-row">
            <div class="container-sidebar w-[20%]">
                <?php include 'sidebar.php';?>
            </div>
            <div class="w-[80%] flex flex-col gap-0 pr-6 pl-10">
                <nav class="navbar flex flex-row shadow-xl justify-between items-center w-full h-16 max-h-16 bg-white my-4 py-4 px-4 rounded-lg">
                    <a href="dashboard.php" class="text-lg text-gray-700 font-semibold tracking-wide">Dashboard</a>
                    <div class="py-2 px-4 flex items-center rounded-lg min-w-[550px] duration-300 cursor-pointer text-gray-700 border border-gray-600 border-opacity-40">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                        </svg>
                        <input type="text" placeholder="Search" class="text-[15px] ml-4 w-full bg-transparent placeholder:text-semibold placeholder:tracking-wide focus:outline-none" />
                    </div>
                    <div class="flex flex-row justify-center items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-person-fill-check" viewBox="0 0 16 16">
                            <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m1.679-4.493-1.335 2.226a.75.75 0 0 1-1.174.144l-.774-.773a.5.5 0 0 1 .708-.708l.547.548 1.17-1.951a.5.5 0 1 1 .858.514M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                            <path d="M2 13c0 1 1 1 1 1h5.256A4.5 4.5 0 0 1 8 12.5a4.5 4.5 0 0 1 1.544-3.393Q8.844 9.002 8 9c-5 0-6 3-6 4" />
                        </svg>
                        <p class="text-[13px] font-medium text-gray-800">
                            <?php echo $_SESSION['admin_name']; ?>
                        </p>
                    </div>
                </nav>
                <div class="container-content mb-2 flex flex-col gap-4 overflow-y-auto">
                    <div class="flex flex-col gap-10 justify-center items-center w-full">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 w-full">
                            <div class="card bg-white p-6 rounded-lg shadow-lg">
                                <h3 class="text-xl font-semibold mb-4">Total Stock</h3>
                                <p class="text-3xl font-bold"><?php echo $total_stock; ?></p>
                            </div>
                            <div class="card bg-white p-6 rounded-lg shadow-lg">
                                <h3 class="text-xl font-semibold mb-4">Total Profit</h3>
                                <p class="text-2xl font-bold">Rp. <?php echo number_format($total_profit, 2); ?></p>
                            </div>
                            <div class="card bg-white p-6 rounded-lg shadow-lg">
                                <h3 class="text-xl font-semibold mb-4">Total Customers</h3>
                                <p class="text-3xl font-bold"><?php echo $total_customers; ?></p>
                            </div>
                            <div class="card bg-white p-6 rounded-lg shadow-lg">
                                <h3 class="text-xl font-semibold mb-4">Total Transactions</h3>
                                <p class="text-3xl font-bold"><?php echo $total_transactions; ?></p>
                            </div>
                        </div>

                        <div class="bg-white p-6 rounded-lg shadow-lg w-full">
                            <h2 class="text-2xl font-semibold mb-4">Detailed Transactions</h2>
                            <table class="table-auto w-full">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-2 border-b">ID</th>
                                        <th class="px-4 py-2 border-b">Customer</th>
                                        <th class="px-4 py-2 border-b">Equipment</th>
                                        <th class="px-4 py-2 border-b">Quantity</th>
                                        <th class="px-4 py-2 border-b">Total Price</th>
                                        <th class="px-4 py-2 border-b">Rental Date</th>
                                        <th class="px-4 py-2 border-b">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($table_data as $data): ?>
                                    <tr>
                                        <td class="px-4 py-2 border-b"><?php echo $data['id_transaksi']; ?></td>
                                        <td class="px-4 py-2 border-b"><?php echo $data['nama_pelanggan']; ?></td>
                                        <td class="px-4 py-2 border-b"><?php echo $data['nama_alat']; ?></td>
                                        <td class="px-4 py-2 border-b"><?php echo $data['jumlah']; ?></td>
                                        <td class="px-4 py-2 border-b">Rp. <?php echo number_format($data['total_harga'], 2); ?></td>
                                        <td class="px-4 py-2 border-b"><?php echo $data['tanggal_sewa']; ?></td>
                                        <td class="px-4 py-2 border-b"><?php echo $data['status']; ?></td>
                                    </tr>
                                    <?php endforeach;?>
                                </tbody>
                            </table>
                        </div>

                        <div class="bg-white p-6 rounded-lg shadow-lg w-full">
                            <h2 class="text-2xl font-semibold mb-4">Equipment Stock Details</h2>
                            <table class="table-auto w-full">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-2 border-b">ID</th>
                                        <th class="px-4 py-2 border-b">Equipment</th>
                                        <th class="px-4 py-2 border-b">Stock</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $sql_stock_data = "SELECT id_alat, nama, stok FROM alat";
                                        $result_stock_data = $conn->query($sql_stock_data);
                                        while ($row = $result_stock_data->fetch_assoc()): ?>
                                    <tr>
                                        <td class="px-4 py-2 border-b"><?php echo $row['id_alat']; ?></td>
                                        <td class="px-4 py-2 border-b"><?php echo $row['nama']; ?></td>
                                        <td class="px-4 py-2 border-b"><?php echo $row['stok']; ?></td>
                                    </tr>
                                    <?php endwhile;?>
                                </tbody>
                            </table>
                        </div>

                        <div class="bg-white p-6 rounded-lg shadow-lg w-full">
                            <h2 class="text-2xl font-semibold mb-4">Customer Details</h2>
                            <table class="table-auto w-full">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-2 border-b">ID</th>
                                        <th class="px-4 py-2 border-b">Customer Name</th>
                                        <th class="px-4 py-2 border-b">Email</th>
                                        <th class="px-4 py-2 border-b">Phone</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $sql_customer_data = "SELECT id_pelanggan, nama, email, telepon FROM pelanggan";
                                        $result_customer_data = $conn->query($sql_customer_data);
                                        while ($row = $result_customer_data->fetch_assoc()): ?>
                                    <tr>
                                        <td class="px-4 py-2 border-b"><?php echo $row['id_pelanggan']; ?></td>
                                        <td class="px-4 py-2 border-b"><?php echo $row['nama']; ?></td>
                                        <td class="px-4 py-2 border-b"><?php echo $row['email']; ?></td>
                                        <td class="px-4 py-2 border-b"><?php echo $row['telepon']; ?></td>
                                    </tr>
                                    <?php endwhile;?>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </main>
    </section>
</body>

</html>