<?php
include 'connect.php';

if(isset($_POST['add'])){
    $product_name=$_POST['product_name'];
    $price=$_POST['price'];
    $quantity=$_POST['quantity'];
    $total_price=$price * $quantity;
    $date=$_POST['date'];
$sel=mysqli_query($conn, "SELECT * FROM stock_in WHERE product_name='$product_name'");
if(mysqli_num_rows($sel)>0){
    $row=mysqli_fetch_assoc($sel);
    $quantityd=$row['quantity'];

    $quantity_new=$quantity + $quantityd;
    $total_new=$quantity_new * $price;

    $upd=mysqli_query($conn,"update stock_in set price='$price',
    quantity='$quantity_new',date='$date',total_price='$total_new' where product_name='$product_name'");

    if(!$upd){
        die("Error: ".mysqli_error($conn));
    }

}else{
    $insert=mysqli_query($conn,"INSERT INTO stock_in(product_name,price,quantity,total_price,date) VALUES('$product_name','$price','$quantity','$total_price','$date')");
if(!$insert){
        die("Error: ".mysqli_error($conn));
    }

}
    
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Management</title>
</head>
<body>
    <form method="POST">
     <table>
        <tr>
            <th>
                Product Name: 
            </th>
            <td>
                <input type="text" name="product_name" required placeholder="----Eggs---">
            </td>
        </tr>

        <tr>
            <th>
                Price: 
            </th>
            <td>
                <input type="number" name="price" id="" required>
            </td>
        </tr>
        <tr>
            <th>
                Quantity: 
            </th>
            <td>
                <input type="number" name="quantity" required>
            </td>
        </tr>
        <tr>
            <th>Date</th>
            <td>
                <input type="date" name="date" required>
            </td>
        </tr>

        <tr>
            <th colspan="2">
                <button name="add" >Stock in</button>
            </th>
        </tr>
    </table>
    </form>

    <br>
    <table border="1" cellspacing="0" cellpadding="10">
        <tr>
            <th>Product ID</th>
            <th>Product Name</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Total Price</th>
            <th>date</th>
            <th>Action</th>
        </tr>
        


    

<?php
$sel=mysqli_query($conn, "SELECT * FROM stock_in");
if(mysqli_num_rows($sel)>0){
    while($row=mysqli_fetch_array($sel)){
        $product_id=$row['product_id'];
        $product_name=$row['product_name'];
        $price=$row['price'];
        $quantity=$row['quantity'];
        $total=$row['total_price'];
        $date=$row['date'];
        echo " <tr>";
        echo "<td> $product_id</td>  ";
        echo "<td> $product_name</td> ";
        echo "<td> $price</td> ";
        echo " <td>$quantity</td> ";
        echo "<td>$total</td> ";
        echo "<td> $date</td> ";
        echo " <td><a href='stock_out.php?product_id=$product_id'>StockOut</a> ||
        <a href='update.php?product_id=$product_id'>Update</a> </td>";
        echo " </tr> ";
    }

}else{
        echo " <tr> <th colspan='7'>Stock is Empty</th></tr>";
    }

?>
</table>
</body>
</html>
