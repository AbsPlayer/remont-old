<?php
include_once('baseclass.php');
$p = new Work_withDB();
$link = $p->ConnectDB();
$query = mysqli_query($link, "SELECT * FROM Grups WHERE GrupID = $_GET[gid]");
$row = mysqli_fetch_array($query);
$query_category = mysqli_query($link, "SELECT * FROM Categories WHERE CategoryID = $row[CategoryID]");
$row_category = mysqli_fetch_array($query_category);
echo <<<OUT
    <table border='0' width='500'>
       <tr>
          <td colspan='2' align='center'>
             <div style='font-weight:bold;'><a href="categories.php" title='Продукция' style='color:#000000;'>Продукция</a> -> <a href="grups.php?cid={$row['CategoryID']}" title='{$row_category['CategoryName']}' style='color:#000000;'>{$row_category['CategoryName']}</a> -> {$row["GrupName"]}
             </div>
          </td>
       </tr>
OUT;
    $p->ShowProducts($_GET['gid']);
echo <<<OUT
    </table>
OUT;
?>