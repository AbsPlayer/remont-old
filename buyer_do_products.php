<?php
include_once('baseclass.php');
$p = new Work_withDB();
$link = $p->ConnectDB();
if (isset($_SESSION['user']))
{
    $author = FALSE;
    $query_admin = mysqli_query($link, "SELECT * FROM Users WHERE UserName = '{$_SESSION['user']}'");
    $row_admin = mysqli_fetch_array($query_admin);
    $user_id = $row_admin['UserID'];
    if ($user_id == 1)
    {
        $author = TRUE;
    }
echo <<<OUT
    <table border='1' width='500' style="border-collapse: collapse; border: 1px solid black;">
    <thead style="font-weight:bold; text-align:center;">
        <td width='50' align='left'>Категория</td>
        <td width='50' align='left'>Группа</td>
        <td width='50' align='left'>Наименование</td>
        <td width='30'>Закупка/<br>Наценка, %</td>
        <td width='135'>Изображение</td>
        <td width='50'>Операции</td>
    </thead>
    <tr>
        <td colspan='7' align='left'>
            <input type='submit' name='addproduct' value='Новый товар'>
        </td>
    </tr>
    <tr>
        <td colspan='6'>
            <img src="images/linemid.gif" vspace="10">
        </td>
    </tr>
OUT;
$query_category = mysqli_query($link, "SELECT * FROM Categories ORDER BY CategoryName");
while ($row_category = mysqli_fetch_array($query_category))
{
    $query_grup = mysqli_query($link, "SELECT * FROM Grups WHERE CategoryID={$row_category['CategoryID']} ORDER BY GrupName");
    while ($row_grup = mysqli_fetch_array($query_grup))
      {
         $query_product = mysqli_query($link, "SELECT * FROM Products WHERE GrupID={$row_grup['GrupID']} ORDER BY ProductName");
         while ($row_product = mysqli_fetch_array($query_product))
         {
            $query_user = mysqli_query($link, "SELECT * FROM Users WHERE UserID = '{$row_product['UserID']}'");
            $row_user = mysqli_fetch_array($query_user);
            if ($author)
            {
                $user = $row_user['UserName'];
            }
            else
            {
                $user = '';
            }
            if ($row_product['ProductInfo'] == '')
            {
                $info="&nbsp;";
            }
            else
            {
                $info = $row_product['ProductInfo'];
            }
            $img_product_image =  $row_product['ProductSmallImage'];
            if (strlen($row_product['ProductSmallImage'])<1)
            {
                $img_product_image = './images/no_image.jpg';
            }
            $product_marga = $row_product['ProductMarga']*100;
             echo <<<OUT
                <tr>
                    <td>{$row_category['CategoryName']}</td>
                    <td>{$row_grup['GrupName']}</td>
                    <td>{$row_product['ProductName']}</td>
                    <td>{$row_product['ProductPrice']}/<br>{$product_marga} %<br>{$user}</td>
                    <td align='center'><img src="$img_product_image" height=85 weight=85 alt='{$row_product['ProductName']}'></td>
                    <td rowspan='2'><input class='button_edit' type='submit' name='editproduct_{$row_product['ProductID']}' value='Изменить'></td>
                </tr>
                <tr>
                    <td colspan='5'>
                        {$row_product['ProductInfo']}
                    </td>
                </tr>
OUT;
         }
      }
}
              echo <<< OUT
                  <tr>
                    <td colspan='6'>
                        <img src="images/linemid.gif" vspace="10">
                    </td>
                  </tr>
                   <tr>
                        <td colspan='6' align='left'>
                            <input type='submit' name='addproduct' value='Новый товар'>
                        </td>
                   </tr>
    </table>
OUT;
}
?>