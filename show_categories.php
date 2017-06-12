<?php
include_once('baseclass.php');
$p = new Work_withDB();
echo <<<OUT
    <table border='0' width='500'>
       <tr>
          <td colspan='2' align='center'>
             <div style='font-weight:bold;'>Продукция
             </div>
          </td>
       </tr>
OUT;
    $p->ShowCategories();
echo <<<OUT
    </table>
OUT;
?>