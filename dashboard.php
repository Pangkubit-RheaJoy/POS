<?php
session_start();
require_once "config/db.php";
include('include/header.php');

    // Check if username and password are set
    if (!isset($_POST['username']) || !isset($_POST['password'])) {
        $_SESSION['error'] = "Please provide both username and password.";
        header("Location: ../index.php");
        exit();
    }

// Helper function to execute a query and return the sum
function getSalesSum($conn, $sql) {
    $result = $conn->query($sql);
    if ($result && $row = $result->fetch_assoc()) {
        return $row['total_sum'];
    }
    return 0;
}

// Get sums for different time periods
$yesterdaySql = "SELECT SUM(total) AS total_sum FROM sales_tbl WHERE DATE(date) = CURDATE() - INTERVAL 1 DAY";
$todaySql = "SELECT SUM(total) AS total_sum FROM sales_tbl WHERE DATE(date) = CURDATE()";
$weekSql = "SELECT SUM(total) AS total_sum FROM sales_tbl WHERE YEARWEEK(date, 1) = YEARWEEK(CURDATE(), 1)";
$monthSql = "SELECT SUM(total) AS total_sum FROM sales_tbl WHERE YEAR(date) = YEAR(CURDATE()) AND MONTH(date) = MONTH(CURDATE())";
$yearSql = "SELECT SUM(total) AS total_sum FROM sales_tbl WHERE YEAR(date) = YEAR(CURDATE())";

$yesterdaySales = getSalesSum($conn, $yesterdaySql);
$todaySales = getSalesSum($conn, $todaySql);
$weeklySales = getSalesSum($conn, $weekSql);
$monthlySales = getSalesSum($conn, $monthSql);
$yearlySales = getSalesSum($conn, $yearSql);
?>

<style>
/* General Styling for Cards */
.card {
    border-radius: 10px; /* Rounded corners */
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1); /* Subtle shadow */
    margin-bottom: 20px; /* Spacing between cards */
}

/* Header Styling */
.card-header {
    padding: 10px;
    border-top-left-radius: 10px; /* Rounded corners for top */
    border-top-right-radius: 10px; /* Rounded corners for top */
    color: #ffffff; /* White text */
    font-weight: bold;
    text-align: center;
}

/* Sales Card Header Color */
.sales-header {
    background-color: #023629; /* Green background */
}

/* Attendance Card Header Color */
.attendance-header {
    background-color: #e8e605; /* Yellow background */
}

/* Products Card Header Color */
.products-header {
    background-color: #023629; /* Green background */
}

/* Chart Card Header Color */
.chart-header {
    background-color: #023629; /* Green background */
}

/* Body Styling */
.card-body {
    background-color: #ffffff; /* White background for the body */
    padding: 15px; /* Padding inside the card */
}

/* Layout adjustments */
.row-flex {
    display: flex;
    flex-wrap: wrap;
}
.column-flex {
    flex: 1;
    padding: 10px;
}

#chartdiv {
  width: 100%;
  height: 295px;
}

/* Popup styling */
.popup-message {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: #1d5b33; /* Darker green background */
    color: white;
    padding: 30px 50px; /* Increased padding for a larger size */
    font-size: 20px; /* Larger font size */
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    opacity: 0; /* Initially hidden */
    transition: opacity 0.5s ease-in-out; /* Fade effect */
    z-index: 1000; /* Ensure it's on top */
    width: 100%; /* Span the entire width */
    max-width: 800px; /* Limit maximum width */
    text-align: center; /* Center text */
}
</style>

</head> 
<body class="cbp-spmenu-push">

<!-- Popup Notification -->
<div id="popup-message" class="popup-message">
    Welcome to admin dashboard
</div>

<div class="main-content">
    <div class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-left" id="cbp-spmenu-s1">
        <!--left-fixed -navigation-->
        <?php include('include/sidebar.php'); ?>
    </div>
    <!--left-fixed -navigation-->

    <?php include('include/navbar.php'); ?>
    <!-- main content start-->
    <div id="page-wrapper">
        <div class="container">
            <!-- Card Section: Sales Summary at the top -->
            <div class="row row-flex">
                <div class="col-md-2 column-flex">
                    <div class="card">
                        <div class="card-header sales-header"></div>
                        <div class="card-body">
                            <h2 class="card-text-price">₱<?php echo number_format($yesterdaySales, 2); ?></h2>
                            <p class="card-text">Yesterday's Sales</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 column-flex">
                    <div class="card">
                        <div class="card-header sales-header"></div>
                        <div class="card-body">
                            <h2 class="card-text-price">₱<?php echo number_format($todaySales, 2); ?></h2>
                            <p class="card-text">Today's Sales</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 column-flex">
                    <div class="card">
                        <div class="card-header sales-header"></div>
                        <div class="card-body">
                            <h2 class="card-text-price">₱<?php echo number_format($weeklySales, 2); ?></h2>
                            <p class="card-text">Weekly's Sales</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 column-flex">
                    <div class="card">
                        <div class="card-header sales-header"></div>
                        <div class="card-body">
                            <h2 class="card-text-price">₱<?php echo number_format($monthlySales, 2); ?></h2>
                            <p class="card-text">Monthly's Sales</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 column-flex">
                    <div class="card">
                        <div class="card-header sales-header"></div>
                        <div class="card-body">
                            <h2 class="card-text-price">₱<?php echo number_format($yearlySales, 2); ?></h2>
                            <p class="card-text">Yearly's Sales</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Section: Chart, Attendance, and Products -->
            <div class="row">
                <!-- Chart Section on the left -->
                <div class="col-md-6">
                    <div class="card">
                    <div class="card-header chart-header">  Sales Chart

        <select id="periodSelect" class="form-control" style="width: 200px; display: inline-block;">
            <option value="day">Daily</option>
            <option value="week">Weekly</option>
            <option value="month">Monthly</option>
            <option value="year">Yearly</option>
        </select>
      
    </div>
                        <div class="card-body">
                            <canvas id="chart1"></canvas>
                        </div>
                    </div>
                    <!-- Time-In/Time-Out Section -->
					<div class="card">
    <?php
    include('config/db.php');

    // Fetch attendance data for today
    $sql = "SELECT tbl_staff.FN, tbl_staff.LN, tbl_time_in.time AS time_in, tbl_time_in.time_out AS time_out
            FROM tbl_time_in
            INNER JOIN tbl_user_account ON tbl_time_in.staff_id = tbl_user_account.employee_number
            INNER JOIN tbl_staff ON tbl_user_account.staff_id = tbl_staff.staff_ID
            WHERE DATE(tbl_time_in.time) = CURDATE();";

    $result = $conn->query($sql);

    // Check if the query executed successfully
    if (!$result) {
        die("Error executing query: " . $conn->error);
    }
    ?>

    <div class="card-header attendance-header">
        Attendance Monitoring
    </div>

    <div class="card-body">
        <h3>Date: <?php echo date('F j, Y'); ?></h3>

        <?php if ($result->num_rows > 0): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Staff Name</th>
                        <th scope="col">Time In</th>
                        <th scope="col">Time Out</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['FN'] . ' ' . $row['LN']); ?></td>
                            <td><?php echo htmlspecialchars(date('g:i A', strtotime($row['time_in']))); ?></td>
                            <td>
                                <?php 
                                if (!empty($row['time_out'])) {
                                    echo htmlspecialchars(date('g:i A', strtotime($row['time_out'])));
                                } else {
                                    echo "Not recorded yet";
                                }
                                ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No attendance data for today.</p>
        <?php endif; ?>
    </div>
</div>
</div>
<?php
include('config/db.php'); // Make sure to include your database connection file

// SQL query to select products with a stock less than 5
$sql = "SELECT products.P_name, products.P_stock 
        FROM products 
        INNER JOIN plain ON plain.plain_id = products.style_id 
        WHERE products.P_stock < 5";

$result = $conn->query($sql);

// Check if the query executed successfully
if (!$result) {
    die("Error executing query: " . $conn->error);
}
?>

<!-- Products Section on the right -->
<div class="col-md-6">
    <div class="card">
        <div class="card-header products-header">
            Products
        </div>
        <div class="card-body">
    <?php if ($result->num_rows > 0): ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Current Quantity</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['P_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['P_stock']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <!-- Red Alert Notification for No Products with Stock Less Than 5 -->
        <div class="alert alert-danger" role="alert">
            <strong>Notice:</strong> No products with stock less than 5.
        </div>
    <?php endif; ?>
</div>





        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Sample chart data
            const data1 = {
                labels: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
                datasets: [{
                    label: 'Sales',
                    data: [5000, 10000, 7500, 2000, 15000, 8000, 9000],
                    backgroundColor: '#00c4b3'
                }]
            };

            // Chart initialization
            new Chart(document.getElementById('chart1'), { type: 'bar', data: data1 });

            // JavaScript to show the popup and then hide it after 3 seconds
            window.onload = function() {
                var popup = document.getElementById('popup-message');
                popup.style.opacity = '1'; // Show popup

                // Hide popup after 3 seconds
                setTimeout(function() {
                    popup.style.opacity = '0'; // Fade out
                }, 3000);
            };
        </script>

        <div class="clearfix"></div>
    </div>
</div>

<?php
include('include/footer.php');
?>
</body>
</html>
