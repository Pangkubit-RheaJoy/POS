<?php
session_start();
include('include/header.php');

    // Check if username and password are set
    if (!isset($_POST['username']) || !isset($_POST['password'])) {
        $_SESSION['error'] = "Please provide both username and password.";
        header("Location: ../index.php");
        exit();
    }
?>
<style>
#chartdiv {
  width: 100%;
  height: 295px;
}

#barcodeContainer {
    text-align: left;
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.input-group {
    display: flex;
    justify-content: space-between;
    margin-bottom: 15px;
}

.input-group label {
    flex-basis: 40%; 
    text-align: right; 
    margin-right: 10px;
}

.input-group input {
    flex-basis: 55%; 
}

#barcode {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    margin-top: 20px;
}

#barcode button {
    margin-top: 10px;
}
</style>

</head> 
<body class="cbp-spmenu-push">
    <div class="main-content">
    <div class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-left" id="cbp-spmenu-s1">
        <!--left-fixed -navigation-->
    <?php
    include('include/sidebar.php');
    ?>
    </div>
        <!--left-fixed -navigation-->
        
    <?php
    include('include/navbar.php');
    ?>
        <!-- main content start-->
        <div id="page-wrapper">
            <!-- Sales Table Section -->
            <div class="col-md-12 col-sm-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                SALES INFORMATION
            </div>
            <div class="panel-body">
                <!-- RTW Table -->
                <div class="panel panel-default">
                    <div class="panel-heading">RTW Sales</div>
                    <div class='table-responsive' style='max-height: 600px; overflow-y: auto; overflow-x: auto;'>
                        <table class='table table-striped table-bordered table-hover table-fixed' id='RTWTable' width='100%' cellspacing='0'>
                            <thead>
                                <tr>
                                    <th style='width: 200px;'>#</th>        
                                    <th style='width: 200px;'>Date</th>
                                    <th style='width: 200px;'>Sold RTW</th>
                                    <th style='width: 200px;'>Total Sales</th>               
                                    <th style='width: 200px;' class='text-center'>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Include database connection file
                                include('config/db.php');

                                // Query for RTW category
                                $sql = "
                                    SELECT 
                                        DATE(sales_tbl.Date) AS sales_date, 
                                        SUM(sales_tbl.Total) AS total_sales,  
                                        COUNT(tbl_report.report_id) AS report_count,  
                                        SUM(sales_tbl.quantity) AS total_quantity  
                                    FROM 
                                        sales_tbl
                                    INNER JOIN 
                                        tbl_report 
                                    ON 
                                        DATE(sales_tbl.Date) = DATE(tbl_report.sales_date)
                                    WHERE 
                                        sales_tbl.Category = 1
                                    GROUP BY 
                                        DATE(sales_tbl.Date)
                                    LIMIT 5;
                                ";

                                $result = mysqli_query($conn, $sql);
                                $counter = 1;

                                // Check if there are rows returned
                                if (mysqli_num_rows($result) > 0) {
                                    // Iterate through rows and display data
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>";
                                        echo "<td>" . $counter . "</td>"; 
                                        echo "<td>" . $row['sales_date'] . " </td>";
                                        echo "<td>" . $row['total_quantity'] . "</td>";
                                        echo "<td>" . $row['total_sales'] . "</td>";
                                        echo "<td class='text-center action-buttons'>";
                                        echo '<a href="report_sales.php?location_id=' . $row['branch_id'] . '&location=' . urlencode($row['location']) . '" class="btn btn-success">View Report</a>';
                                        echo "</td>";
                                        echo "</tr>";
                                        $counter++;
                                    }
                                } else {
                                    // No records found
                                    echo "<tr><td colspan='5' class='text-center'>No RTW products found.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Plain Table -->
                <div class="panel panel-default">
                    <div class="panel-heading">Plain Sales</div>
                    <div class='table-responsive' style='max-height: 600px; overflow-y: auto; overflow-x: auto;'>
                        <table class='table table-striped table-bordered table-hover table-fixed' id='PlainTable' width='100%' cellspacing='0'>
                            <thead>
                                <tr>
                                    <th style='width: 200px;'>#</th>        
                                    <th style='width: 200px;'>Date</th>
                                    <th style='width: 200px;'>Sold Plain</th>
                                    <th style='width: 200px;'>Total Sales</th>               
                                    <th style='width: 200px;' class='text-center'>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
// Query for Plain category
$sql = "
    SELECT 
        DATE(sales_tbl.Date) AS sales_date, 
        SUM(sales_tbl.Total) AS total_sales,  
        COUNT(tbl_report.report_id) AS report_count,  
        SUM(sales_tbl.quantity) AS total_quantity  
    FROM 
        sales_tbl
    INNER JOIN 
        tbl_report 
    ON 
        DATE(sales_tbl.Date) = DATE(tbl_report.sales_date)
    WHERE 
        sales_tbl.Category = 2
    GROUP BY 
        DATE(sales_tbl.Date)
    LIMIT 5;
";

$result = mysqli_query($conn, $sql);
$counter = 1;

// Check if there are rows returned
if (mysqli_num_rows($result) > 0) {
    // Iterate through rows and display data
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $counter . "</td>"; 
        echo "<td>" . htmlspecialchars($row['sales_date']) . "</td>";
        echo "<td>" . htmlspecialchars($row['total_quantity']) . "</td>";
        echo "<td>" . htmlspecialchars($row['total_sales']) . "</td>";
        echo "<td class='text-center action-buttons'>";
        echo '<a href="product_sales.php?sales_date=' . htmlspecialchars($row['sales_date']) . '&category=2" class="btn btn-success">View Report</a>';
        echo "</td>";
        echo "</tr>";
        $counter++;
    }
} else {
    // No records found
    echo "<tr><td colspan='5' class='text-center'>No Plain products found.</td></tr>";
}

// Close connection
mysqli_close($conn);
?>

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
        </div>

        <!-- Include jQuery and Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

        <!-- Include jQuery library -->
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

        <!-- Alert Message Script -->
        <script>
            $(document).ready(function () {
                setTimeout(function () {
                    $("#alertMsg").fadeOut(1000); 
                }, 2000); 
            });
        </script>

    </div>
    </div>
    </div>
    </div>
    <?php
    include('include/footer.php');
    ?>
</body>
</html>
