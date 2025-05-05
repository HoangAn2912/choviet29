<?php
// Initialize the session
// session_start();

// // Check if the user is logged in, if not redirect to login page
// if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
//     header("location: login.php");
//     exit;
// }

// Sample revenue data (in a real application, this would come from a database)
$revenues = [
    [
        'id' => 'REV001',
        'date' => '2023-04-25',
        'client' => 'Acme Corporation',
        'description' => 'Software License',
        'amount' => 12500.00,
        'status' => 'Paid'
    ],
    [
        'id' => 'REV002',
        'date' => '2023-04-23',
        'client' => 'TechSolutions Inc.',
        'description' => 'Consulting Services',
        'amount' => 8500.00,
        'status' => 'Pending'
    ],
    [
        'id' => 'REV003',
        'date' => '2023-04-20',
        'client' => 'Global Enterprises',
        'description' => 'Maintenance Contract',
        'amount' => 5000.00,
        'status' => 'Paid'
    ],
    [
        'id' => 'REV004',
        'date' => '2023-04-18',
        'client' => 'Innovative Systems',
        'description' => 'Product Sales',
        'amount' => 15750.00,
        'status' => 'Paid'
    ],
    [
        'id' => 'REV005',
        'date' => '2023-04-15',
        'client' => 'Digital Solutions',
        'description' => 'Web Development',
        'amount' => 9800.00,
        'status' => 'Pending'
    ],
    [
        'id' => 'REV006',
        'date' => '2023-04-12',
        'client' => 'Future Tech',
        'description' => 'Hardware Sales',
        'amount' => 22000.00,
        'status' => 'Paid'
    ],
    [
        'id' => 'REV007',
        'date' => '2023-04-10',
        'client' => 'Smart Systems',
        'description' => 'Cloud Services',
        'amount' => 7500.00,
        'status' => 'Paid'
    ]
];

// Process form submission for adding new revenue
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_revenue'])) {
    // In a real application, you would validate and save to a database
    // For this example, we'll just show a success message
    $success_message = "Revenue entry added successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revenue Management - Revenue Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .main-content {
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            
            <!-- Main content -->
            <div class="main-content">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Revenue Management</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRevenueModal">
                            <i class="bi bi-plus-circle me-1"></i> Add New Revenue
                        </button>
                    </div>
                </div>

                <?php if(isset($success_message)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo $success_message; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>

                <!-- Revenue Summary -->
                <div class="row mb-4">
                    <div class="col-md-4 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            Total Revenue (Monthly)</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">$81,050</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="bi bi-currency-dollar fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            Paid Revenue</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">$62,750</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="bi bi-check-circle fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-4">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            Pending Revenue</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">$18,300</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="bi bi-clock-history fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Revenue Filters -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Filter Revenue</h6>
                    </div>
                    <div class="card-body">
                        <form class="row g-3">
                            <div class="col-md-3">
                                <label for="dateFrom" class="form-label">Date From</label>
                                <input type="date" class="form-control" id="dateFrom">
                            </div>
                            <div class="col-md-3">
                                <label for="dateTo" class="form-label">Date To</label>
                                <input type="date" class="form-control" id="dateTo">
                            </div>
                            <div class="col-md-3">
                                <label for="clientFilter" class="form-label">Client</label>
                                <select class="form-select" id="clientFilter">
                                    <option value="">All Clients</option>
                                    <option>Acme Corporation</option>
                                    <option>TechSolutions Inc.</option>
                                    <option>Global Enterprises</option>
                                    <option>Innovative Systems</option>
                                    <option>Digital Solutions</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="statusFilter" class="form-label">Status</label>
                                <select class="form-select" id="statusFilter">
                                    <option value="">All Statuses</option>
                                    <option>Paid</option>
                                    <option>Pending</option>
                                </select>
                            </div>
                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-primary">Apply Filters</button>
                                <button type="reset" class="btn btn-secondary">Reset</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Revenue Table -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Revenue Entries</h6>
                        <div class="dropdown no-arrow">
                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="bi bi-three-dots-vertical text-gray-400"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                aria-labelledby="dropdownMenuLink">
                                <div class="dropdown-header">Export Options:</div>
                                <a class="dropdown-item" href="#"><i class="bi bi-file-earmark-excel me-2"></i>Export to Excel</a>
                                <a class="dropdown-item" href="#"><i class="bi bi-file-earmark-pdf me-2"></i>Export to PDF</a>
                                <a class="dropdown-item" href="#"><i class="bi bi-printer me-2"></i>Print</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="revenueTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Date</th>
                                        <th>Client</th>
                                        <th>Description</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($revenues as $revenue): ?>
                                    <tr>
                                        <td><?php echo $revenue['id']; ?></td>
                                        <td><?php echo $revenue['date']; ?></td>
                                        <td><?php echo $revenue['client']; ?></td>
                                        <td><?php echo $revenue['description']; ?></td>
                                        <td>$<?php echo number_format($revenue['amount'], 2); ?></td>
                                        <td>
                                            <?php if($revenue['status'] == 'Paid'): ?>
                                                <span class="badge bg-success">Paid</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning">Pending</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editRevenueModal"><i class="bi bi-pencil"></i></button>
                                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteRevenueModal"><i class="bi bi-trash"></i></button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center mt-4">
                                <li class="page-item disabled">
                                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                                </li>
                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item">
                                    <a class="page-link" href="#">Next</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Revenue Modal -->
    <div class="modal fade" id="addRevenueModal" tabindex="-1" aria-labelledby="addRevenueModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addRevenueModalLabel">Add New Revenue</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" action="">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="client" class="form-label">Client</label>
                                <input type="text" class="form-control" id="client" name="client" required>
                            </div>
                            <div class="col-md-6">
                                <label for="date" class="form-label">Date</label>
                                <input type="date" class="form-control" id="date" name="date" required>
                            </div>
                            <div class="col-md-6">
                                <label for="amount" class="form-label">Amount</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control" id="amount" name="amount" step="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="Paid">Paid</option>
                                    <option value="Pending">Pending</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                            </div>
                            <div class="col-md-6">
                                <label for="category" class="form-label">Category</label>
                                <select class="form-select" id="category" name="category">
                                    <option>Product Sales</option>
                                    <option>Services</option>
                                    <option>Subscription</option>
                                    <option>Consulting</option>
                                    <option>Other</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="paymentMethod" class="form-label">Payment Method</label>
                                <select class="form-select" id="paymentMethod" name="payment_method">
                                    <option>Bank Transfer</option>
                                    <option>Credit Card</option>
                                    <option>PayPal</option>
                                    <option>Cash</option>
                                    <option>Other</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="add_revenue" class="btn btn-primary">Save Revenue</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Revenue Modal (placeholder) -->
    <div class="modal fade" id="editRevenueModal" tabindex="-1" aria-labelledby="editRevenueModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editRevenueModalLabel">Edit Revenue</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Form would be pre-populated with the selected revenue entry -->
                    <p class="text-center">Edit form would appear here with pre-populated data</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Revenue Modal (placeholder) -->
    <div class="modal fade" id="deleteRevenueModal" tabindex="-1" aria-labelledby="deleteRevenueModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteRevenueModalLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this revenue entry? This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>