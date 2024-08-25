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
    text-align: left; /* Changed from center to left align */
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
    flex-basis: 40%; /* Adjust width of label */
    text-align: right; /* Align label text to the right */
    margin-right: 10px;
}

.input-group input {
    flex-basis: 55%; /* Adjust width of input */
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
        <div id="page-inner">
    <div class="row">
    <div class="col-md-4 col-sm-5">
    <div class="panel panel-primary">
        <div class="panel-heading">
            ADD NEW PRODUCTS
            <button type="button" class="btn btn-success pull-right" style="margin-top: -10px;" id="toggleForm">
                <i class="fas fa-plus"></i>
            </button>
        </div>
        <div class="panel-body" style="display: none;" id="formContainer">
        <form id="requestForm" method="post" action="config/addproduct.php">
    <div class="form-group">
        <label>Barcode</label>
        <input type="text" class="form-control" name="product_tik" id="OrderNo" placeholder="Automatically generated" readonly>
    </div>   

    <div class="form-group">
        <label>Product Name</label>
        <input type="text" class="form-control" name="P_name" required>
    </div>

    <div class="form-group">
        <label>Category</label>
        <select class="form-control" name="P_category" id="P_category" required>
            <option value=""></option>
            <?php
                include 'config/db.php';
                $query = "SELECT * FROM category";
                $result = mysqli_query($conn, $query);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<option value="' . $row['category_id'] . '">' . $row['category'] . '</option>';
                    }
                } else {
                    echo '<option value="">No categories available</option>';
                }

                mysqli_close($conn);
            ?>
        </select>
    </div>

    <div class="form-group">
        <label>Style</label>
        <select class="form-control" name="P_style" id="P_style" required>
            <option value=""></option>
        </select>
    </div>

    <div class="form-group row">
        <div class="col">
            <label for="P_stock">Stock</label>
            <input type="text" class="form-control" id="P_stock" name="P_stock" required>
        </div>
        <div class="col">
            <label for="P_price">Price</label>
            <input type="text" class="form-control" id="P_price" name="P_price" required>
        </div>
    </div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.getElementById('P_category');
    const styleSelect = document.getElementById('P_style');

    function generateReferenceNumber() {
        var random = Math.floor(100000 + Math.random() * 900000); 
        var referenceNumber = 'KAI888-' + random;
        document.getElementById('OrderNo').value = referenceNumber;
    }
                                                            
    generateReferenceNumber();

    categorySelect.addEventListener('change', function() {
        const categoryId = categorySelect.value;

        if (categoryId) {
            fetch(`config/get_styles.php?category_id=${categoryId}`)
                .then(response => response.json())
                .then(data => {
                    styleSelect.innerHTML = '<option value=""></option>';
                    data.forEach(style => {
                        const option = document.createElement('option');
                        option.value = style.plain_id;
                        option.textContent = style.style;
                        styleSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching styles:', error));
        } else {
            styleSelect.innerHTML = '<option value=""></option>';
        }
    });
});
</script>




         
                <button type="submit" name="added" class="btn btn-primary btn-block">Add Material</button>
            </form>
        </div>
    </div>
</div>


    <div class="col-md-8 col-sm-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                MATERIAL INFORMATION
            </div>
            <div class="panel-body">
                <!-- Advanced Tables -->
                <div class="panel panel-default">
    <div class='table-responsive' style='max-height: 600px; overflow-y: auto; overflow-x: auto;'>
        <table class='table table-striped table-bordered table-hover table-fixed' id='drinkTable' width='100%' cellspacing='0'>
            <thead>
                <tr>
                    <th style='width: 200px;'>Product Barcode</th>        
                    <th style='width: 200px;'>Product Name</th>
                    <th style='width: 200px;'>Category</th>
                    <th style='width: 200px;'>Style</th>
                    <th style='width: 100px;'>Price</th>
                    <th style='width: 100px;'>Stocks</th>
                    <th style='width: 200px;' class='text-center'>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
// Include database connection file
include('config/db.php');

// SQL query to fetch data from database
$sql = "SELECT * FROM products INNER JOIN plain ON plain.plain_id = products.style_id";
$result = mysqli_query($conn, $sql);

// Check if there are rows returned
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $id = $row['id'];
        $productTik = $row['product_tik'];
        $productName = $row['P_name'];
        $productPrice = $row['P_price'];
        $plain = $row['plain'];
        $stock = $row['P_stock'];
        $category = $row['P_category_id']; // Corrected from $row['category'] to $row['P_category_id']

        echo "<tr>";
        echo "<td>{$productTik}</td>";
        echo "<td>{$productName}</td>";
        echo "<td>{$productPrice}</td>";
        echo "<td>{$plain}</td>";
        echo "<td>{$productPrice}</td>";
        echo "<td>{$stock}</td>";
        echo "<td class='text-center action-buttons'>";
        echo "<button type='button' class='btn btn-primary btn-sm' data-toggle='modal' data-target='#editModal{$id}'><i class='fas fa-edit'></i></button>";
        echo "<button type='button' class='btn btn-danger btn-sm' data-toggle='modal' data-target='#confirmationModal'><i class='fas fa-trash-alt'></i></button>";
        echo "<button type='button' class='btn btn-success btn-sm' data-toggle='modal' data-target='#printmodal{$id}'><i class='fas fa-print'></i></button>";
        echo "</td>";
        echo "</tr>";

        echo "<div class='modal fade' id='editModal{$id}' tabindex='-1' role='dialog' aria-labelledby='editModalLabel{$id}' aria-hidden='true'>
            <div class='modal-dialog' role='document'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <h5 class='modal-title' id='editModalLabel{$id}'>Edit Product</h5>
                        <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                            <span aria-hidden='true'>&times;</span>
                        </button>
                    </div>
                    <div class='modal-body'>
                        <form action='config/updateprod.php' method='post'>
                            <input type='hidden' name='id' value='{$id}'>
                            <div class='form-group'>
                                <label for='editProductName{$id}'>Product Name</label>
                                <input type='text' id='editProductName{$id}' name='product_name' class='form-control' value='{$productName}' required />
                            </div>
                            <div class='form-group'>
                                <label for='editProductPrice{$id}'>Price</label>
                                <input type='number' id='editProductPrice{$id}' name='product_price' class='form-control' value='{$productPrice}' step='0.01' required />
                            </div>
                            <div class='form-group'>
                                <label for='editProductStock{$id}'>Stocks</label>
                                <input type='number' id='editProductStock{$id}' name='product_stock' class='form-control' value='{$stock}' required />
                            </div>
                            <div class='form-group'>
                                <label for='editProductStyle{$id}'>Style</label>
                                <select id='editProductStyle{$id}' name='style_id' class='form-control' required>";
                                
                                // Fetch styles for dropdown based on category
                                if ($category == 1) {
                                    // Fetch styles with the same category
                                    $styles = mysqli_query($conn, "SELECT * FROM plain WHERE category_id = {$category}");
                                } elseif ($category == 2) {
                                    // Fetch all styles, limit to 5
                                    $styles = mysqli_query($conn, "SELECT * FROM plain LIMIT 5");
                                } else {
                                    // Fetch all styles
                                    $styles = mysqli_query($conn, "SELECT * FROM plain");
                                }
                                
                                while ($style = mysqli_fetch_assoc($styles)) {
                                    $selected = ($style['plain_id'] == $row['style_id']) ? 'selected' : '';
                                    echo "<option value='{$style['plain_id']}' {$selected}>{$style['plain']}</option>";
                                }
                                
        echo "            </select>
                            </div>
                            <button type='submit' class='btn btn-primary'>Update</button>
                        </form>
                    </div>
                    <div class='modal-footer'>
                        <button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>
                    </div>
                </div>
            </div>
        </div>";

        
          // Modal for printing barcode
          echo "<div class='modal fade' id='printmodal{$id}' tabindex='-1' role='dialog' aria-labelledby='printmodalLabel{$id}' aria-hidden='true'>
          <div class='modal-dialog' role='document'>
              <div class='modal-content'>
                  <div class='modal-header'>
                      <h5 class='modal-title' id='printmodalLabel{$id}'>Print Barcode</h5>
                      <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                          <span aria-hidden='true'>&times;</span>
                      </button>
                  </div>
                  <div class='modal-body'>
                      <div id='barcodeContainer'>
                          <h1 style='text-align: center;'>{$productName}</h1>
                          <div class='input-group mb-3'>
                              <label for='barcodeData{$id}'>Product Barcode:</label>
                              <input type='text' id='barcodeData{$id}' class='form-control' value='{$productTik}' />
                          </div>
                          <div class='input-group mb-3'>
                              <label for='barcodeWidth{$id}'>Width (in mm):</label>
                              <input type='number' id='barcodeWidth{$id}' class='form-control' placeholder='Width' value='30' step='0.1' />
                          </div>
                          <div class='input-group mb-3'>
                              <label for='barcodeHeight{$id}'>Height (in mm):</label>
                              <input type='number' id='barcodeHeight{$id}' class='form-control' placeholder='Height' value='15' step='0.1' />
                          </div>
                          <div class='input-group mb-3'>
                              <label for='barcodeCopies{$id}'>Number of Copies:</label>
                              <input type='number' id='barcodeCopies{$id}' class='form-control' placeholder='Copies' value='1' />
                          </div>
                          <div class='d-flex justify-content-end mb-3'>
                              <button class='btn btn-primary' onclick='generateBarcode({$id})'>Generate Barcode</button>
                          </div>
                          <div id='downloadAlert{$id}' class='alert alert-info' style='display: none;'>
                          Downloading PDF... <span id='countdown{$id}'>5</span>
                      </div>
                          <div id='barcode{$id}' style='margin-top: 20px;'>
                              <!-- Barcodes will be displayed here -->
                          </div>
                      </div>
                  </div>
                  <div class='modal-footer'>
                      <button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>
                      <button class='btn btn-success' id='printBarcodes{$id}' onclick='downloadPDF({$id})'>Print</button>

                  </div>
              </div>
          </div>
      </div>";

  }
} else {
  // No records found
  echo "<tr><td colspan='7' class='text-center'>No products found.</td></tr>";
}

// End table
echo "</tbody>";
echo "</table>";

// Close connection
mysqli_close($conn);
?>

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

            <!-- Include jQuery and Bootstrap JS -->
            <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>


            <!-- Include jQuery library -->
            <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

            <!-- Add the following script at the end of your HTML file -->
            <script>
$(document).ready(function () {
    setTimeout(function () {
        $("#alertMsg").fadeOut(1000); 
    }, 2000); 
});

async function generateBarcode(id) {
    const data = document.getElementById(`barcodeData${id}`).value;
    const width = document.getElementById(`barcodeWidth${id}`).value;
    const height = document.getElementById(`barcodeHeight${id}`).value;
    const barcodeDiv = document.getElementById(`barcode${id}`);

    if (!data || !width || !height || !barcodeDiv) {
        console.error('Error: One or more elements not found.');
        return;
    }

    barcodeDiv.innerHTML = ''; // Clear previous barcodes

    // Generate a single barcode
    const barcodeUrl = `https://barcode.orcascan.com/?type=code128&data=${encodeURIComponent(data)}&width=${width}&height=${height}`;
    const barcodeItem = document.createElement('div');
    barcodeItem.classList.add('barcode-item');
    barcodeItem.style.marginBottom = '10px';

    const img = document.createElement('img');
    img.src = barcodeUrl;
    img.alt = 'Generated Barcode';
    img.style.marginTop = '10px';
    img.style.maxWidth = '100%';

    const text = document.createElement('div');
    text.innerText = data;
    text.classList.add('barcode-text');
    text.style.textAlign = 'center';

    barcodeItem.appendChild(img);
    barcodeItem.appendChild(text);

    barcodeDiv.appendChild(barcodeItem);

    document.getElementById(`downloadButton${id}`).disabled = false;
}

async function downloadPDF(id) {
    const { jsPDF } = window.jspdf;

    // Disable the download button to prevent multiple clicks
    const downloadButton = document.getElementById(`printBarcodes${id}`);
    downloadButton.disabled = true;

    // Start the countdown
    startCountdown(id);

    try {
        const data = document.getElementById(`barcodeData${id}`).value;
        const copies = parseInt(document.getElementById(`barcodeCopies${id}`).value, 10);
        const barcodeWidth = parseFloat(document.getElementById(`barcodeWidth${id}`).value);
        const barcodeHeight = parseFloat(document.getElementById(`barcodeHeight${id}`).value);
        const margin = 5;
        const pageWidth = 210;  // A4 width in mm
        const pageHeight = 297; // A4 height in mm

        const colsPerPage = 6; // Number of columns per row
        const rowsPerPage = 10; // Number of rows per page
        const barcodeSpacingX = 5; // Horizontal space between barcodes
        const barcodeSpacingY = 1; // Vertical space between rows (adjusted)
        const textFontSize = 8; // Font size for barcode text
        const textMargin = 2; // Margin to position text closer

        const doc = new jsPDF({
            orientation: 'p',
            unit: 'mm',
            format: [pageWidth, pageHeight]
        });

        let xOffset = margin;
        let yOffset = margin;

        for (let i = 0; i < copies; i++) {
            const barcodeUrl = `https://barcode.orcascan.com/?type=code128&data=${encodeURIComponent(data)}&width=${barcodeWidth}&height=${barcodeHeight}`;
            console.log('Fetching barcode URL:', barcodeUrl);

            const imageBlob = await fetch(barcodeUrl).then(res => {
                if (!res.ok) throw new Error('Failed to fetch image');
                return res.blob();
            });

            const imageDataUrl = await convertToPng(imageBlob);
            console.log('Barcode image data URL:', imageDataUrl);

            // Check if xOffset or yOffset need to be adjusted
            if (xOffset + barcodeWidth > pageWidth - margin) {
                xOffset = margin;
                yOffset += barcodeHeight + textFontSize + textMargin + barcodeSpacingY;
            }
            if (yOffset + barcodeHeight + textFontSize > pageHeight - margin) {
                doc.addPage();
                xOffset = margin;
                yOffset = margin;
            }

            // Add the barcode image
            doc.addImage(imageDataUrl, 'PNG', xOffset, yOffset, barcodeWidth, barcodeHeight);

            // Add text below the barcode
            doc.setFontSize(textFontSize);
            doc.text(data, xOffset + (barcodeWidth / 2), yOffset + barcodeHeight + textMargin, {
                align: 'center'
            });

            // Move to the next column
            xOffset += barcodeWidth + barcodeSpacingX;

            // Add a new page if necessary
            if ((i + 1) % (colsPerPage * rowsPerPage) === 0) {
                doc.addPage();
                xOffset = margin;
                yOffset = margin;
            }
        }

        doc.save('barcodes.pdf');

        // Remove the downloading message and show the success message
        removeCountdownAndShowSuccess(id);

    } catch (error) {
        console.error('Error generating PDF document:', error);

        // Re-enable the download button if an error occurs
        downloadButton.disabled = false;
    } finally {
        // Re-enable the download button after everything is complete
        downloadButton.disabled = false;
    }
}

// Function to convert Blob to PNG Data URL
function convertToPng(blob) {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.onload = () => resolve(reader.result);
        reader.onerror = reject;
        reader.readAsDataURL(blob);
    });
}

// Countdown function
function startCountdown(id) {
    const modalBody = document.querySelector(`#printmodal${id} .modal-body`);
    const countdownElement = document.createElement('div');
    countdownElement.classList.add('alert', 'alert-info');
    countdownElement.style.textAlign = 'center';
    countdownElement.id = `countdownElement${id}`;
    modalBody.appendChild(countdownElement);

    let countdown = 3;
    countdownElement.innerHTML = `Your download will start in ${countdown} seconds...`;

    const countdownInterval = setInterval(() => {
        countdown--;
        if (countdown > 0) {
            countdownElement.innerHTML = `Your download will start in ${countdown} seconds...`;
        } else {
            countdownElement.innerHTML = `Downloading...`;
            clearInterval(countdownInterval);
        }
    }, 1000);
}

// Remove countdown and show success message
function removeCountdownAndShowSuccess(id) {
    const countdownElement = document.getElementById(`countdownElement${id}`);
    if (countdownElement) {
        setTimeout(() => {
            countdownElement.remove(); // Remove the downloading message

            // Show the success message
            const modalBody = document.querySelector(`#printmodal${id} .modal-body`);
            const successMessageElement = document.createElement('div');
            successMessageElement.classList.add('alert', 'alert-success');
            successMessageElement.style.textAlign = 'center';
            successMessageElement.innerHTML = `Your PDF has been successfully downloaded.`;
            modalBody.appendChild(successMessageElement);

            setTimeout(() => {
                successMessageElement.remove();
            }, 3000); // 3 seconds
        }, 500); // Short delay to ensure smooth transition
    }
}

// Function to convert Blob to PNG Data URL
function convertToPng(blob) {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.onload = () => resolve(reader.result);
        reader.onerror = reject;
        reader.readAsDataURL(blob);
    });
}





async function convertToPng(imageBlob) {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.onloadend = () => {
            const img = new Image();
            img.onload = () => {
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');
                canvas.width = img.width;
                canvas.height = img.height;
                ctx.drawImage(img, 0, 0);
                resolve(canvas.toDataURL('image/png'));
            };
            img.onerror = reject;
            img.src = reader.result;
        };
        reader.onerror = reject;
        reader.readAsDataURL(imageBlob);
    });
}


</script>


        </div>

    </div>
				

       
    </div>

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