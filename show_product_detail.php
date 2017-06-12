<?php
include_once('baseclass.php');
$p = new Work_withDB();
$link = $p->ConnectDB();
$query = mysqli_query($link, "SELECT * FROM Products WHERE ProductID = $_GET[pid]");
$row = mysqli_fetch_array($query);
$query_grup = mysqli_query($link, "SELECT * FROM Grups WHERE GrupID = $row[GrupID]");
$row_grup = mysqli_fetch_array($query_grup);
$query_category = mysqli_query($link, "SELECT * FROM Categories WHERE CategoryID = $row_grup[CategoryID]");
$row_category = mysqli_fetch_array($query_category);
echo <<<OUT
    <table border='0' width='500'>
       <tr>
          <td align='center'>
             <div style='font-weight:bold;'><a href="categories.php" title='Продукция' style='color:#000000;'>Продукция</a> -> <a href="grups.php?cid={$row_grup['CategoryID']}" title='{$row_category['CategoryName']}' style='color:#000000;'>{$row_category['CategoryName']}</a> -> <a href="products.php?gid={$row['GrupID']}" title='{$row_grup["GrupName"]}' style='color:#000000;'>{$row_grup["GrupName"]}</a> -> {$row["ProductName"]}
             </div>
          </td>
       </tr>
OUT;
    $p->ShowProductDetail($_GET['pid']);
echo <<<OUT
    </table>
OUT;
?>