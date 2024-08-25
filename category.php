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
        <div id="page-inner">
    <div class="row">
    <div class="col-md-4 col-sm-5">
    <div class="panel panel-primary">
        <div class="panel-heading">
            ADD NEW CATEGORY
            <button type="button" class="btn btn-success pull-right" style="margin-top: -10px;" id="toggleForm">
                <i class="fas fa-plus"></i>
            </button>
        </div>
        <div class="panel-body" style="display: none;" id="formContainer">
        <form id="requestForm" method="post" action="config/addcategory.php">
         
                <div class="form-group">
                    <label>CATEGORY</label>
                    <input type="text" class="form-control" name="category" required>
                </div>
                <button type="submit" name="added" class="btn btn-primary btn-block">Add Material</button>
            </form>
        </div>
    </div>
</div>


    <div class="col-md-8 col-sm-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                CATEGORY INFORMATION
            </div>
            <div class="panel-body">
                <!-- Advanced Tables -->
                <div class="panel panel-default">
    <div class='table-responsive' style='max-height: 600px; overflow-y: auto; overflow-x: auto;'>
        <table class='table table-striped table-bordered table-hover table-fixed' id='drinkTable' width='100%' cellspacing='0'>
            <thead>
                <tr>
                    <th style='width: 200px;'>#</th>
                    <th style='width: 200px;'>COLOR</th>
                    <th style='width: 200px;' class='text-center'>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Include database connection file
                include('config/db.php');

                // SQL query to fetch data from database
                $sql = "SELECT * FROM category";
                $result = mysqli_query($conn, $sql);

                // Check if there are rows returned
                if (mysqli_num_rows($result) > 0) {
                    // Iterate through rows and display data
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['category_id'] . "</td>";
                        echo "<td>" . $row['category'] . "</td>";
                
                        echo "<td class='text-center action-buttons'>";
                        echo "<button type='button' class='btn btn-primary btn-sm' data-toggle='modal' data-target='#editModal" . $row['category_id'] . "'><i class='fas fa-edit'></i></button>";
                        echo "<button type='button' class='btn btn-danger btn-sm' data-toggle='modal' data-target='#confirmationModal'><i class='fas fa-trash-alt'></i></button>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    // No records found
                    echo "<tr><td colspan='5' class='text-center'>No Category found.</td></tr>";
                }

                // Close connection
                mysqli_close($conn);
                ?>
            </tbody>
        </table>
    </div>
</div>

                    

                        <div class='modal fade' id='editModal$id' tabindex='-1' role='dialog' aria-labelledby='editModalLabel$id' aria-hidden='true'>
                                <div class='modal-dialog' role='document'>
                                    <div class='modal-content'>
                                        <div class='modal-header'>
                                            <h5 class='modal-title' id='editModalLabel$id'>Edit Product</h5>
                                            <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                <span aria-hidden='true'>&times;</span>
                                            </button>
                                        </div>
                                        <div class='modal-body'>
                                            <form action='config/editprodductcash.php' method='post' enctype='multipart/form-data'>
                                                <input type='hidden' name='id' value='$id'>
                                                <input type='hidden' name='type' value=' $type'>
                                                <!-- Add your form fields for editing -->
                                                <div class='form-group'>
                                                    <label for='editName'>Product Name:</label>
                                                    <input type='text' class='form-control' id='editName' name='P_name' value='$productName'>
                                                </div>
                                                <div class='form-group'>
                                                    <label>Small Size</label>
                                                    <div class='row'>
                                                        <div class='col-md-6'>
                                                            
                                                        </div>
                                                        
                                                </div>
                                                
                                                </div>
                                                <button type='submit' class='btn btn-primary' name='edit'>Save Changes</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
            
                    <!-- Delete Modal -->
                    <div class='modal fade' id='confirmationModal' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                        <div class='modal-dialog' role='document'>
                            <div class='modal-content'>
                                <div class='modal-header'>
                                    <h5 class='modal-title' id='exampleModalLabel'>Confirm Deletion</h5>
                                    <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                        <span aria-hidden='true'>&times;</span>
                                    </button>
                                </div>
                                <div class='modal-body'>
                                    Are you sure you want to delete this product?
                                </div>
                                <div class='modal-footer'>
                                    <button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>
                                    <form action="config/deleteprod.php" method="get" style="display: inline;">
                                        <input type="hidden" name="delete_id" value="<?php echo $ticketNumber; ?>">
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


            <!-- Include jQuery and Bootstrap JS -->
            <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


            <!-- Include jQuery library -->
            <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

            <!-- Add the following script at the end of your HTML file -->
            <script>
                $(document).ready(function () {
                    // Delay hiding the alert for 3 seconds
                    setTimeout(function () {
                        $("#alertMsg").fadeOut(1000); // Fade out over 1 second
                    }, 2000); // 3000 milliseconds = 3 seconds
                });
            </script>






        </div>

    </div>
				

       
    </div>

   
   
		
				
			
				<div class="clearfix"> </div>
			</div>
				
	
	<!-- for amcharts js -->
			<script src="js/amcharts.js"></script>
			<script src="js/serial.js"></script>
			<script src="js/export.min.js"></script>
			<link rel="stylesheet" href="css/export.css" type="text/css" media="all" />
			<script src="js/light.js"></script>
	<!-- for amcharts js -->

    <script  src="js/index1.js"></script>
	

		
		
				
			</div>
		</div>
    <?php
  include('include/footer.php');
  ?>