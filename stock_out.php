
<?php
include 'connect.php';

if (!isset($_GET['product_id']) || !is_numeric($_GET['product_id'])) {
    die("Invalid Product ID");
}

$product_id = (int) $_GET['product_id'];

// Get product details
$sel = mysqli_query($conn, "SELECT * FROM stock_in WHERE product_id='$product_id'");

if (!$sel) {
    die("Error: " . mysqli_error($conn));
}

if (mysqli_num_rows($sel) == 0) {
    die("Product not found in stock.");
}

$row = mysqli_fetch_assoc($sel);

$product_name_dis = $row['product_name'];
$price_dis = $row['price'];
$available_quantity = $row['quantity'];

if (isset($_POST['add'])) {

    $product_name = $product_name_dis;
    $price = (float) $_POST['price'];
    $quantity = (int) $_POST['quantity'];
    $date = $_POST['date'];

    if ($quantity <= 0) {
        die("Quantity must be greater than zero.");
    }

    // Check available stock
    if ($quantity > $available_quantity) {
        die("Error: Insufficient stock available.");
    }

    $total_price = $price * $quantity;

    // Check whether product already exists in stock_out
    $sel_out = mysqli_query($conn, "SELECT * FROM stock_out WHERE product_id='$product_id'");

    if (!$sel_out) {
        die("Error: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($sel_out) > 0) {

        $row_out = mysqli_fetch_assoc($sel_out);

        $quantity_old = (int) $row_out['quantity'];

        $quantity_new = $quantity_old + $quantity;

        $total_new = $quantity_new * $price;

        $upd = mysqli_query($conn, "UPDATE stock_out SET
            price='$price',
            quantity='$quantity_new',
            total_price='$total_new',
            date='$date'
            WHERE product_id='$product_id'");

        if (!$upd) {
            die("Error: " . mysqli_error($conn));
        }

    } else {

        $insert = mysqli_query($conn, "INSERT INTO stock_out
        (product_id, product_name, price, quantity, total_price, date)
        VALUES
        ('$product_id', '$product_name', '$price', '$quantity', '$total_price', '$date')");

        if (!$insert) {
            die("Error: " . mysqli_error($conn));
        }
    }

    // Reduce available stock in stock_in
    $quantity_remaining = $available_quantity - $quantity;

    $total_remaining = $quantity_remaining * $price;

    $upd_in = mysqli_query($conn, "UPDATE stock_in SET
        quantity='$quantity_remaining',
        total_price='$total_remaining'
        WHERE product_id='$product_id'");

    if (!$upd_in) {
        die("Error: " . mysqli_error($conn));
    }

    header("Location: stock_in.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Out</title>
</head>
<body>

    <h2><u>Stock Out</u></h2>

    <form method="POST">

        <table>

            <tr>
                <th>Product Name:</th>
                <td>
                    <input type="text"
                    name="product_name"
                    required
                    readonly
                    value="<?php echo htmlspecialchars($product_name_dis); ?>">
                </td>
            </tr>

            <tr>
                <th>Price:</th>
                <td>
                    <input type="number"
                    name="price"
                    step="0.01"
                    required readonly
                    value="<?php echo htmlspecialchars($price_dis); ?>">
                </td>
            </tr>

            <tr>
                <th>Available Quantity:</th>
                <td>
                    <input type="number"
                    readonly
                    value="<?php echo $available_quantity; ?>">
                </td>
            </tr>

            <tr>
                <th>Quantity:</th>
                <td>
                    <input type="number"
                    name="quantity"
                    min="1"
                    max="<?php echo $available_quantity; ?>"
                    required>
                </td>
            </tr>

            <tr>
                <th>Date:</th>
                <td>
                    <input type="date"
                    name="date"
                    required>
                </td>
            </tr>

            <tr>
                <th colspan="2">
                    <button type="submit" name="add">
                        Stock Out
                    </button>
                </th>
            </tr>

        </table>

    </form>

</body>
</html>
