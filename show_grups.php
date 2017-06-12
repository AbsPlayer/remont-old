<?php
include_once('baseclass.php');
$p = new Work_withDB();
$link = $p->ConnectDB();
$query = mysqli_query($link, "SELECT * FROM Categories WHERE CategoryID = $_GET[cid]");
$row = mysqli_fetch_array($query);
echo <<<OUT
    <table border='0' width='500'>
       <tr>
          <td colspan='2' align='center'>
             <div style='font-weight:bold;'><a href="categories.php" title='Продукция' style='color:#000000;'>Продукция</a> -> {$row["CategoryName"]}
             </div>
          </td>
       </tr>
OUT;
    $p->ShowGrups($_GET['cid']);
echo <<<OUT
    </table>
OUT;
?>