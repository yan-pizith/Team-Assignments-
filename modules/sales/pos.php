<?php
// modules/sales/pos.php
require_once "../../includes/auth_check.php";
require_once "../../config/database.php";

$customers = $pdo->query("SELECT * FROM customers")->fetchAll();
include "../../includes/header.php";
?>

<div class="d-flex">
    <?php include "../../includes/sidebar.php"; ?>
    <div class="w-100">
        <?php include "../../includes/navbar.php"; ?>
        
        <div class="p-3">
            <h4 class="mb-3"><i class="fa-solid fa-cart-shopping"></i> POS System (លក់ថ្នាំ)</h4>
            
            <div class="row g-3">
                <!-- ផ្នែកខាងឆ្វេង: ស្វែងរកថ្នាំ -->
                <div class="col-md-6">
                    <div class="card shadow-sm mb-3">
                        <div class="card-body">
                            <label class="form-label fw-bold">ស្វែងរកថ្នាំ (កូដ ឬ ឈ្មោះថ្នាំ)</label>
                            <input type="text" id="search_input" class="form-control form-control-lg" placeholder="វាយបញ្ចូលឈ្មោះ ឬ ស្កែន Barcode..." autofocus autocomplete="off">
                            <div id="search_results" class="list-group position-absolute w-100 shadow-sm mt-1" style="z-index: 1000; max-height: 250px; overflow-y: auto;"></div>
                        </div>
                    </div>
                </div>

                <!-- ផ្នែកខាងស្តាំ: កន្ត្រកទំនិញ & គិតលុយ -->
                <div class="col-md-6">
                    <form action="cart_process.php" method="POST">
                        <div class="card shadow-sm">
                            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">បញ្ជីទំនិញក្នុងកន្ត្រក</h6>
                                <select name="customer_id" class="form-select form-select-sm w-50">
                                    <option value="">-- អតិថិជនទូទៅ (General) --</option>
                                    <?php foreach ($customers as $cust): ?>
                                        <option value="<?= $cust['id']; ?>"><?= htmlspecialchars($cust['name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-sm align-middle mb-0" id="cart_table">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ឈ្មោះថ្នាំ</th>
                                            <th width="15%">តម្លៃ</th>
                                            <th width="20%">ចំនួន</th>
                                            <th width="20%">សរុប</th>
                                            <th width="10%"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="cart_body">
                                        <!-- Items នឹងបង្ហាញតាម JS -->
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer bg-light p-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>សរុប (Subtotal):</span>
                                    <strong id="subtotal_val">$0.00</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-2 align-items-center">
                                    <span>បញ្ចុះតម្លៃ ($):</span>
                                    <input type="number" step="0.01" name="discount" id="discount_input" class="form-control form-control-sm text-end w-25" value="0.00">
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between mb-3">
                                    <h5 class="fw-bold">ប្រាក់ត្រូវទូទាត់:</h5>
                                    <h4 class="text-danger fw-bold" id="grand_total_val">$0.00</h4>
                                </div>
                                <button type="submit" name="btn_checkout" class="btn btn-success btn-lg w-100"><i class="fa-solid fa-print"></i> គិតលុយ & បោះពុម្ពវិក្កយបត្រ</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="../../assets/js/sales.js"></script>
<?php include "../../includes/footer.php"; ?>