<?php
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
              PLAIN SALES INFORMATION 
            </div>
            <div class="panel-body">
                <!-- Advanced Tables -->
                <div class="panel panel-default">
    <div class='table-responsive' style='max-height: 600px; overflow-y: auto; overflow-x: auto;'>
        <table class='table table-striped table-bordered table-hover table-fixed' id='drinkTable' width='100%' cellspacing='0'>
            <thead>
                <tr>
                    <th style='width: 200px;'>#</th>        
                    <th style='width: 200px;'>Product Name</th>
                    <th style='width: 200px;'>Quantity</th>
                    <th style='width: 200px;'>Price</th>   
                    <th style='width: 200px;'>Total</th> 
                       <th style='width: 200px;'>Time</th>
                   
                </tr>
            </thead>
            <tbody>
                <?php
                // Include database connection file
                include('config/db.php');


                if (isset($_GET['sales_date']) && isset($_GET['category'])) {
                    $sales_date = $_GET['sales_date'];
                    $category = $_GET['category'];
                
                    // Validate and sanitize input
                    $sales_date = mysqli_real_escape_string($conn, $sales_date);
                    $category = (int)$category;  // Convert to integer for security
                    
                    // SQL query to fetch report details based on the selected date and category
                    $sql = "
                        SELECT 
                           *
                        FROM 
                            sales_tbl
                    
                        WHERE 
                            DATE(sales_tbl.Date) = '$sales_date' 
                            AND sales_tbl.Category = $category
                        ORDER BY 
                            sales_tbl.Date DESC;
                    ";
                
                $result = mysqli_query($conn, $sql);
                $counter = 1;
                
            
                
                // Check if there are rows returned
                if (mysqli_num_rows($result) > 0) {
                    // Iterate through rows and display data
                    while ($row = mysqli_fetch_assoc($result)) {
                        $date = strtotime($row['Date']);
                        $formattedDate = date('h:i:s A', $date);
                       
                     
                        echo "<tr>";
                        echo "<td>" . $counter . "</td>"; 
                        echo "<td>" . $row['ProductName'] . " </td>";
                        echo "<td>" . $row['Quantity'] . "</td>";
                        echo "<td>" . $row['Price'] . "</td>";
                        echo "<td>" . $row['Total'] . "</td>";
                        echo "<td>" . $formattedDate . "</td>";
                        echo "</td>";
                        echo "</tr>";
                        $counter++;
                    }
                
                } else {
                    // No records found
                    echo "<tr><td colspan='5' class='text-center'>No products found.</td></tr>";
                }
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
	<!-- for amcharts js -->
    <script src="js/amcharts.js"></script>
    <script src="js/serial.js"></script>
    <script src="js/export.min.js"></script>
    <link rel="stylesheet" href="css/export.css" type="text/css" media="all" />
    <script src="js/light.js"></script>
    <!-- for amcharts js -->

    <script src="js/index1.js"></script>
	</div>
	</div>
    </div>
	</div>
    <?php
    include('include/footer.php');
    ?>
</body>
</html>
