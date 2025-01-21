<?php
session_start();
include "template/header.php";
$_SESSION['pageTitle'] = "userMGT";
include "template/navbar.php";
include "template/sidebar.php";
include "config/db_con.php";

?>

		<!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
			<div class="container-fluid">
                <div class="row page-titles">
					<ol class="breadcrumb">
						<li class="breadcrumb-item active"><a href="javascript:void(0)">Accounts</a></li>
						<li class="breadcrumb-item"><a href="javascript:void(0)">Creation</a></li>
					</ol>
                </div>
                <div class="notifications"></div>
                <!-- row -->
                <div class="row">

                    <!-- Start of content -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">List of Accounts</h4>
                                <button type="button" class="btn btn-rounded btn-info" data-bs-toggle="modal" data-bs-target="#addModal"><span class="btn-icon-start text-info"><i class="fa fa-plus color-success"></i>
                                    </span>Add</button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="example3" class="display" style="min-width: 845px">
                                        <thead>
                                            <tr>                                           
                                                <th>#</th>
                                                <th>User Profile</th> <!-- Profile Picture -->
                                                <th>Account ID</th>
                                                <th>Account Name</th>
                                                <th>Username</th>
                                                <th>Password</th>
                                                <th>Role</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <?php
include "config/db_con.php";

// Fetch all users and their details
$userQuery = "SELECT * FROM `tbl_user_information` INNER JOIN tbl_user_account WHERE tbl_user_account.userInfo_Id = tbl_user_information.userInfo_ID";
$userResult = $conn->query($userQuery);
$counter = 1; // Initialize counter
while ($row = $userResult->fetch_assoc()) {
    ?>
    <tr>
        <td><?= $counter++ ?></td>
        <td><img class="rounded-circle" width="50" src="donation_tracker/uploads/<?= htmlspecialchars($row['profile']) ?>" alt=""></td>
        <td><?= $row['Account_Number'] ?></td>
        <td><?= $row['FirstName'] . ' ' . $row['LastName'] ?></td>
        <td><?= $row['username'] ?></td>
        <td>•••••</td>
        <td><?= $row['role'] ?></td>

        <td>
            <div class="d-flex">
            <button type="button" class="btn btn-rounded shadow btn-xxs btn-primary me-1" data-bs-toggle="modal" data-bs-target="#editModal" onclick="loadUserData(<?= $row['userInfo_ID'] ?>)">
    <i class="fa fa-pencil-alt"></i> <?= $row['userInfo_ID'] ?>
</button>
                <button type="button" class="btn btn-rounded shadow btn-xxs btn-danger" onclick="confirmDeleteUser(<?= $row['userInfo_ID'] ?>)"><i class="fa fa-trash"></i></button>
            </div>
        </td>
    </tr>
    <?php
}
?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>



                    <!-- End of content -->

                </div>
            </div>
        </div>
        <!--**********************************
            Content body end
        ***********************************-->

        <!--**********************************
            Modals
        ***********************************-->
        <!-- Add Modal -->
        <div class="modal fade" id="addModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="addModalLabel">Add User</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="basic-form">
                            <form action="config/superadmin/add_account.php" method="POST" enctype="multipart/form-data">
                                <div class="mb-3 row">
                                    <label class="col-sm-2 col-form-label">Profile</label>
                                    <div class="col-sm-10">
                                        <input type="file" name="userProfile" accept="image/*" class="form-file-input form-control" required>
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label class="col-sm-2 col-form-label">Account ID</label>
                                    <div class="col-sm-10">
                                        <input type="text" id="accId" name="accId" class="form-control form-control-lg" readonly>
                                    </div>
                                </div>
                                
                                <div class="mb-3 row">
                                    <label class="col-sm-2 col-form-label">First Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="FName" class="form-control form-control-lg" placeholder="Enter First Name" required>
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label class="col-sm-2 col-form-label">Middle Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="MName" class="form-control form-control-lg" placeholder="Enter Middle Name" required>
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label class="col-sm-2 col-form-label">Last Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="LName" class="form-control form-control-lg" placeholder="Enter Last Name" required>
                                    </div>
                                </div>
                               
                                <div class="mb-3 row">
                                    <label class="col-sm-2 col-form-label">Username</label>
                                    <div class="col-sm-10">
                                        <input type="email" name="username" class="form-control form-control-lg" placeholder="Enter Username" required>
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label class="col-sm-2 col-form-label">Password</label>
                                    <div class="col-sm-10">
                                        <input type="password" name="password" class="form-control form-control-lg" placeholder="Enter Password" required>
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label class="col-sm-2 col-form-label">Role</label>
                                    <div class="col-sm-10">
                                        <select name="role" id="roleSelect" class="form-control wide" required>
                                            <option selected="" disabled>Select A Role</option>
                                            <option selected="SuperAdmin">SuperAdmin</option>
                                            <option selected="Admin">admin</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger light" data-bs-dismiss="modal"><span class="me-2"><i class="fa fa-times"></i></span>Close</button>
                                    <button type="submit" class="btn btn-success"><span class="me-2"><i class="fa fa-paper-plane"></i></span>Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
        document.querySelector('#positionSelect').addEventListener('change', function() {
            var positionId = this.value;
            var counterSelect = document.querySelector('#counterSelect');
            
            // Clear previous options
            counterSelect.innerHTML = '<option disabled selected>Select A Counter</option>';

            // Fetch available counters for Add User
            fetch('config/fetchCounter.php?position_id=' + positionId)
            .then(response => response.json())
            .then(data => {
                var counterSelect = document.querySelector('#counterSelect');
                counterSelect.innerHTML = '<option disabled selected>Select A Counter</option>';

                if (data.status === 'no-counter-required') {
                    // Disable the counter dropdown if counters are not required
                    counterSelect.innerHTML = '<option disabled selected>No counter required for this role.</option>';
                    counterSelect.disabled = true; // Disable selection
                } else if (data.status === 'available') {
                    counterSelect.disabled = false; // Ensure dropdown is enabled for other roles
                    data.counters.forEach(counter => {
                        var option = document.createElement('option');
                        option.value = counter.counter_ID;
                        option.text = counter.counterName;
                        counterSelect.appendChild(option);
                    });
                } else if (data.status === 'taken') {
                    var option = document.createElement('option');
                    option.disabled = true;
                    option.text = data.message;
                    counterSelect.appendChild(option);
                }
            })
            .catch(error => console.error('Error fetching counters:', error));
        });
        </script>

        <!-- End of Add Modal -->

        <!-- Edit Modal -->
        <div class="modal fade" id="editModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="editModalLabel">Edit User</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="basic-form">
                            <form id="editUserForm" action="config/editUser.php" method="POST" enctype="multipart/form-data">
                                <!-- Hidden input to store User ID -->
                                <input type="number" name="ID" id="userID" />

                                <div class="mb-3 row">
                                    <label class="col-sm-2 col-form-label">Current Profile</label>
                                    <div class="col-sm-10">
                                        <img id="currentProfileImage" src="" alt="User Profile" class="img-thumbnail" width="100" height="100">
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label class="col-sm-2 col-form-label">New Profile</label>
                                    <div class="col-sm-10">
                                        <input type="file" name="userProfile" accept="image/*" class="form-file-input form-control">
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label class="col-sm-2 col-form-label">Role</label>
                                    <div class="col-sm-10">
                                        <select name="role" id="editRoleSelect" class="form-control wide" required disabled>
                                            <option selected="" disabled>Select A Role</option>
                                            <option selected="1">SuperAdmin</option>
                                            <option selected="2">Admin</option>
                                        </select>
                                        <!-- Hidden input to submit the role value -->
                                        <input type="hidden" name="role" id="hiddenRoleInput">
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label class="col-sm-2 col-form-label">First Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="FName" id="editFName" class="form-control form-control-lg" required>
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label class="col-sm-2 col-form-label">Middle Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="MName" id="editMName" class="form-control form-control-lg" required>
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label class="col-sm-2 col-form-label">Last Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="LName" id="editLName" class="form-control form-control-lg" required>
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label class="col-sm-2 col-form-label">Email</label>
                                    <div class="col-sm-10">
                                        <input type="email" name="email" id="editEmail" class="form-control form-control-lg" required>
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label class="col-sm-2 col-form-label">Password</label>
                                    <div class="col-sm-10">
                                        <div class="input-group transparent-append">
                                            <input type="password" name="password" id="editPassword" class="form-control form-control-lg" required>
                                                <span class="input-group-text show-pass" data-target="editPassword">
                                                    <i class="fa fa-eye-slash"></i>
                                                    <i class="fa fa-eye"></i>
                                                </span>
                                        </div>
                                    </div>
                                </div>       
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light" data-bs-dismiss="modal"><span class="me-2"><i class="fa fa-times"></i></span>Close</button>
                        <button type="submit" class="btn btn-success"><span class="me-2"><i class="fa fa-save"></i></span>Save</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            let isSwitching = false;  // Flag to prevent multiple switches at the same time

            // JavaScript to handle Edit User functionality
            function loadUserData(userInfo_ID) {
    if (isSwitching) return;  // Prevent loading during a switch
    isSwitching = true;  // Flag data loading in progress

    // Fetch user data from the server
    fetch('config/fetchUser.php?user_id=' + userInfo_ID)
        .then(response => response.json())
        .then(data => {
            // Check if data is returned correctly
            if (data && data.ID) {
                // Populate the form with user data
                document.getElementById('userInfo_ID').value = data.ID;
                document.getElementById('editFName').value = data.FName;
                document.getElementById('editMName').value = data.MName;
                document.getElementById('editLName').value = data.LName;
                document.getElementById('editEmail').value = data.email;
                document.getElementById('editPassword').value = data.password;
                document.getElementById('editPhone').value = data.phone;

                document.getElementById('originalPosition').value = data.position;
                document.getElementById('originalCounter').value = data.counter;

                // Set the current profile image
                document.getElementById('currentProfileImage').src = data.userProfile;

                // Load positions into the select
                const positionSelect = document.getElementById('editPositionSelect');
                positionSelect.innerHTML = '<option disabled>Select A Position</option>';
                data.positions.forEach(position => {
                    const option = document.createElement('option');
                    option.value = position.position_ID;
                    option.text = position.positionName;
                    if (position.position_ID == data.position) option.selected = true;
                    positionSelect.appendChild(option);
                });

                // Populate the role dropdown
                const roleSelect = document.getElementById('editRoleSelect');
                const hiddenRoleInput = document.getElementById('hiddenRoleInput');
                roleSelect.innerHTML = '<option selected disabled>Select A Role</option>'; // Reset options
                data.roles.forEach(role => {
                    const option = document.createElement('option');
                    option.value = role.role_ID;
                    option.text = role.roleName;
                    if (role.role_ID == data.role) {
                        option.selected = true; // Mark the user's current role as selected
                        hiddenRoleInput.value = role.role_ID;
                    }
                    roleSelect.appendChild(option);
                });

                // Disable the role dropdown
                roleSelect.disabled = true;

                // Handle roles without counters
                const counterSelect = document.getElementById('editCounterSelect');
                if (data.role == 1) { // Superadmin role
                    counterSelect.innerHTML = '<option selected disabled>Not Applicable</option>';
                    counterSelect.disabled = true;
                } else {
                    counterSelect.innerHTML = '<option disabled>Select A Counter</option>';
                    data.counters.forEach(counter => {
                        const option = document.createElement('option');
                        option.value = counter.counter_ID;
                        option.text = counter.counterName;
                        if (counter.counter_ID == data.counter) {
                            option.selected = true;
                        }
                        counterSelect.appendChild(option);
                    });
                    counterSelect.disabled = false;
                }

                // Ensure no duplicate event listeners for position change
                positionSelect.removeEventListener('change', positionChangeListener);
                positionSelect.addEventListener('change', function() {
                    positionChangeListener(userInfo_ID);
                });

                // Load counters for the current position (reset previous counters)
                loadCountersForPosition(data.position, data.counter, userInfo_ID);

                // Show the modal
                $('#editModal').modal('show');
            } else {
                console.error('Failed to fetch user data');
            }
        })
        .catch(error => {
            console.error('Error fetching user data:', error);
        })
        .finally(() => {
            // Release flag once data loading completes
            isSwitching = false;
        });
}
            //generate account id for every user
            function generateAccId() {
            const prefix = "XS-";
            const uniqueNumber = Math.floor(10000 + Math.random() * 90000); // Generate a random 5-digit number
            const accId = prefix + uniqueNumber;

            document.getElementById('accId').value = accId;
        }

        // Automatically generate ID when the page loads
       document.addEventListener("DOMContentLoaded", generateAccId);

         
        
            
            // Ensure that the modal gets fully reset on close
            $('#editModal').on('hidden.bs.modal', function () {
                isSwitching = false;  // Ensure the flag is reset when modal closes
                const userID = document.getElementById('userID').value;
            });
        </script>

        
        <!-- End of Edit Modal -->


        <!-- Delete Modal -->

        <!-- Preloader for delete operation -->
        <div id="preloader" style="display: none;">
            <div class="lds-ripple">
                <div></div>
                <div></div>
            </div>
        </div>

        <!-- SweetAlert and AJAX logic for delete operation -->
        <script>
            function showPreloader() {
                document.getElementById('preloader').style.display = 'block';
            }

            // Function to confirm and delete user
            function confirmDeleteUser(userInfo_ID) {
                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    cancelButtonColor: "#6e6e6e",
                    confirmButtonText: "Confirm",
                    cancelButtonText: "Cancel"
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show preloader after confirmation
                        showPreloader();

                        // Ajax request to delete the user
                        $.ajax({
                            url: 'config/superadmin/delete_acc.php', // Endpoint for deleting user
                            type: 'POST',
                            data: { id: userInfo_ID },
                            dataType: 'json',
                            success: function(response) {
                                if (response.status === 'success') {
                                    // Store the success state in sessionStorage
                                    sessionStorage.setItem('userDeleted', true);

                                    // Delay the page reload to allow preloader animation
                                    setTimeout(function() {
                                        location.reload();
                                    }, 300);
                                } else {
                                    Swal.fire(
                                        'Error!',
                                        response.message,
                                        'error'
                                    );
                                }
                            },
                            error: function() {
                                Swal.fire(
                                    'Error!',
                                    'There was an error deleting the user.',
                                    'error'
                                );
                            }
                        });
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        Swal.fire(
                            'Cancelled',
                            'The user is safe :)',
                            'error'
                        );
                    }
                });
            }

            // Show success message after page reload if user was deleted
            window.onload = function() {
                if (sessionStorage.getItem('userDeleted')) {
                    sessionStorage.removeItem('userDeleted');
                    setTimeout(function() {
                        Swal.fire(
                            'Deleted!',
                            'The user has been successfully deleted.',
                            'success'
                        );
                    }, 1100);
                }
            };
        </script>
        <!-- End of Delete Modal -->

        <!--**********************************
            Modals End
        ***********************************-->
		
		
        <?php 
        include "template/footer.php";
        ?>