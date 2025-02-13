<?php
echo "Hello";


/*

include("config.php");
if(isset($_POST['input'])){
    $input = $_POST['input'];
    $query = "SELECT * FROM products WHERE name LIKE '{$input}%'";

    $result = mysqli_query($con, $query);

if(mysqli_num_rows($result) > 0){?>
<table class = "table table -bordered table -striped mt-4">
    <thead>
    <tr>
        <th>Id</th>
        <th>Name</th>
        <th>Occupation</th>
        <th>Hobbies</th>

    </tr>
    </thead>
    <tbody>
        <?php
    while($row = mysqli_fetch_assoc($result)){

        $id=$row['id'];
        $name=$row['name'];
        $price=$row['price'];


        ?>

    <tr>
        <td><?php echo $id;?></td>
        <td><?php echo $name;?></td>
        <td><?php echo $price;?></td>
    </tr>
        <?php
    }

        ?>
    </tbody>
</table>
    <?php
} else {
    echo "<h6 class='text-danger text-center mt-3'>No results found</h6>";
}
}
*/
?>
