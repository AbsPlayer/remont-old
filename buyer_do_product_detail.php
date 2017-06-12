<?php

include_once('baseclass.php');
$p = new Work_withDB();
$link = $p->ConnectDB();
if (isset($_SESSION['user']))
    {
    $query_admin = mysqli_query($link, "SELECT * FROM Users WHERE UserName = '{$_SESSION['user']}'");
    $row_admin = mysqli_fetch_array($query_admin);
    $user_id = $row_admin['UserID'];
    if (isset($_GET['pid']))
        {
        $pid = $_GET['pid'];
        $query_product = mysqli_query($link, "SELECT * FROM Products WHERE ProductID=$pid");
        $row_product = mysqli_fetch_array($query_product);
        $tb_product_name = $row_product['ProductName'];
        $tb_product_price = $row_product['ProductPrice'];
        $tb_product_info = $row_product['ProductInfo'];
        $tb_product_marga = $row_product['ProductMarga'] * 100;
        $tb_product_unit = $row_product['UnitID'];
        $img_product_big_image = $row_product['ProductBigImage'];
        $img_product_small_image = $row_product['ProductSmallImage'];
        if (strlen($row_product['ProductBigImage']) < 1)
            {
            $img_product_big_image = './images/no_image.jpg';
            $img_product_small_image = './images/no_image.jpg';
            }
        $command = "saveproduct_$pid";
        $grup_select = $row_product['GrupID'];
        $query_category = mysqli_query($link, "SELECT CategoryID FROM Grups WHERE GrupID={$row_product['GrupID']}");
        $row_category = mysqli_fetch_array($query_category);
        $_POST['CategoryID'] = $row_category[0];
        $command_query = "";
        } else
        {
        $tb_product_name = '';
        $tb_product_price = '';
        $tb_product_marga = '';
        $tb_product_unit = '3';
        $tb_product_info = '';
        $img_product_big_image = './images/no_image.jpg';
        $img_product_small_image = './images/no_image.jpg';
        $command = 'saveproduct';
        }
    if (isset($_POST['CategoryID']) and $_POST['CategoryID'] != '')
        {
        $cid = $_POST['CategoryID'];
        } else
        {
        $cid = 1;
        }
    if (isset($_POST) and $_POST != null)
        {
        $post = array_keys($_POST);
        if (isset($post[8]))
            {
            $post_pid = explode("_", $post[8]);
            if ($post_pid[0] == 'saveproduct')
                {
                $name = htmlspecialchars($_POST['new_product_name']);
                $info = htmlspecialchars($_POST['new_product_info']);
                if (is_numeric($_POST['new_product_price']))
                    {
                    $price = $_POST['new_product_price'];
                    } else
                    {
                    $price = 0;
                    }
                if (is_numeric($_POST['new_product_marga']))
                    {
                    $marga = $_POST['new_product_marga'] / 100;
                    } else
                    {
                    $marga = 0;
                    }
                if (isset($_FILES['product_image']))
                    {
                    $ft = pathinfo($_FILES['product_image']['tmp_name']);
                    } else
                    {
                    $ft = '';
                    }
                if (isset($_FILES['product_image']))
                    {
                    $up_file = $_FILES['product_image'];
                    if (is_uploaded_file($_FILES['product_image']['tmp_name']))
                        {
                        move_uploaded_file($_FILES['product_image']['tmp_name'], "./images/big/" . $_FILES['product_image']['name']);
                        $p->ResizeImage("./images/big/" . $_FILES['product_image']['name'], 292);
                        $file = file_get_contents("./images/big/" . $_FILES['product_image']['name']);
                        $handle = fopen("./images/small/" . $up_file['name'], 'a');
                        fwrite($handle, $file);
                        fclose($handle); //move_uploaded_file("./images/big/".$_FILES['product_image']['name'],"./images/small/".$_FILES['product_image']['name']);
                        $p->ResizeImage("./images/small/" . $_FILES['product_image']['name'], 61);
                        $img_product_big_image = '../images/big/' . $_FILES['product_image']['name'];
                        $img_product_small_image = '../images/small/' . $_FILES['product_image']['name'];
                        } else
                        {
                        if (strlen($img_product_big_image) < 1 or $img_product_big_image == './images/no_image.jpg')
                            {
                            $img_product_big_image = '';
                            }
                        if (strlen($img_product_small_image) < 1 or $img_product_small_image == './images/no_image.jpg')
                            {
                            $img_product_small_image = '';
                            }
                        }
                    }
                if (!isset($_GET['pid']))
                    {
                    $q = "INSERT INTO Products (GrupID, UnitID, ProductName, ProductPrice, ProductMarga, ProductInfo, ProductBigImage, ProductSmallImage) VALUES ({$_POST['grup_name']},{$_POST['unit_name']}, '$name', $price, $marga, '$info', '$img_product_big_image', '$img_product_small_image')";
                    mysqli_query($link, $q);
                    echo <<<OUT
                    <script type="text/javascript">
                    window.location = "buyer_products.php"
                    </script>
OUT;
                    } else
                    {
                    mysqli_query($link, "UPDATE Products SET GrupID={$_POST['grup_name']}, UnitId={$_POST['unit_name']}, ProductName='$name', ProductPrice=$price, ProductMarga=$marga, ProductInfo='$info', ProductBigImage='$img_product_big_image', ProductSmallImage='$img_product_small_image' WHERE ProductID={$pid}");
                    echo <<<OUT
                    <script type="text/javascript">
                    window.location = "buyer_products.php"
                    </script>
OUT;
                    }
                }
            }
        }
    echo <<<OUT
    <script type = "text/javascript" src = "https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
        <script type="text/javascript">
    function Choice()
    {
        f.h.value = f.c.options[f.c.selectedIndex].value;
        debugger;
        f.submit();
    }
    function ShowPreview(objFile, objImageName)
      {
          if (objFile.value.search(/\.jpe?g$|\.gif$/i)!=-1)
                document.getElementById(objImageName).src = objFile.value;
      }

    </script>

<script>
$(document).ready(function() {
    $('#ProductImage').change(function () {
        var input = $(this)[0];
        if (input.files && input.files[0]) {
            if (input.files[0].type.match('image.*')) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#img-preview').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                console.log('ошибка, не изображение');
            }
        } else {
            console.log('хьюстон у нас проблема');
        }
    });
});
</script>
    <table border="1" width='500' style="border-collapse: collapse; border: 1px solid black;">
    <tr>
        <td rowspan=5>
            <span id="output"><img id='img-preview' src='$img_product_big_image' name='productimage' width=200></span>
        </td>
        <td width=100>
            Категория
        </td>
        <td width=150>
            <input ID='h' type='hidden' name='CategoryID' value=''/>
            <select ID='c' class='width' name='category_name' onChange='Choice()'>
OUT;
    $query_category = mysqli_query($link, "SELECT * FROM Categories ORDER BY CategoryName");
    while ($row_category = mysqli_fetch_array($query_category)) {
        if (!isset($_POST['CategoryID']))
            {
            $_POST['CategoryID'] = 1;
            }
        if ($row_category['CategoryID'] == $_POST['CategoryID'])
            {
            $selected = 'selected';
            } else
            {
            $selected = '';
            }
        echo("<OPTION VALUE={$row_category['CategoryID']} $selected>{$row_category['CategoryName']}");
    }
    echo<<<OUT
            </select>
        </td>
    </tr>
    <tr>
        <td width=100>
            Группа товара
        </td>
        <td width=150>
            <select ID='g' class='width' name='grup_name'>
OUT;
    $query_grup = mysqli_query($link, "SELECT * FROM Grups WHERE CategoryID=$cid ORDER BY GrupName");
    while ($row_grup = mysqli_fetch_array($query_grup)) {
        if (isset($grup_select) and $row_grup['GrupID'] == $grup_select)
            {
            $grup_selected = 'selected';
            } else
            {
            $grup_selected = '';
            }
        echo("<OPTION VALUE={$row_grup['GrupID']} $grup_selected>{$row_grup['GrupName']}");
    }
    echo<<<OUT
            </select>
        </td>
    </tr>
    <tr>
        <td width=100>
            Наименование
        </td>
        <td width=150>
            <input type='textbox' size='25' name='new_product_name' value='$tb_product_name' placeholder='Новый товар' onfocus="this.placeholder=''" onblur="this.placeholder='Новый товар'">
        </td>
    </tr>
    <tr>
        <td width=100>
            Закупка / Наценка / Ед.изм.
        </td>
        <td width=150>
            <input type='textbox' size='6' name='new_product_price' value='$tb_product_price' placeholder='0' onfocus="this.placeholder=''" onblur="this.placeholder='0'"><br>
            <input type='textbox' size='4' name='new_product_marga' value='$tb_product_marga' placeholder='0' onfocus="this.placeholder=''" onblur="this.placeholder='0'"> %<br>
            <select ID='u' class='width_unit' name='unit_name'>
OUT;
    $query_unit = mysqli_query($link, "SELECT * FROM Units ORDER BY UnitName");
    while ($row_unit = mysqli_fetch_array($query_unit)) {
        if (isset($tb_product_unit) and $row_unit['UnitID'] == $tb_product_unit)
            {
            $unit_selected = 'selected';
            } else
            {
            $unit_selected = '';
            }
        echo("<OPTION VALUE={$row_unit['UnitID']} $unit_selected>{$row_unit['UnitName']}");
    }
    echo<<<OUT
            </select>
        </td>
    </tr>
    <tr>
        <td width=100>
            Доп. информация
        </td>
        <td width=150>
            <textarea rows=3 cols=25 name='new_product_info' placeholder='Дополнительная информация' onfocus="this.placeholder=''" onblur="this.placeholder='Дополнительная информация'">$tb_product_info</textarea>
        </td>
    </tr>
    <tr>
        <td colspan=3>
            <input type="file" id="ProductImage" accept="image/*" name="product_image"/>
        </td>
    </tr>
    <tr>
        <td colspan=3 align='center' width=540>
            <input type='submit' class='button_edit' name='$command' value='Сохранить'>&nbsp;<input type='submit' class='button_edit' name='cancelproduct' value='Отмена'>
        </td>
    </tr>
</table>
OUT;
    }
?>