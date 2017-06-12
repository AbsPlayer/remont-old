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
if (isset($_POST) and $_POST!=null)
{
    $command = array_keys($_POST);
    $cid = explode("_",$command[0]);
    $cid2 = explode("_",$command[2]);
    if($cid[0]==='editcategory')
    {
        $category = $cid[1];
    }
    if($cid[0]==='deletecategory')
    {
         mysqli_query($link, "DELETE FROM Categories WHERE CategoryID={$cid[1]}");
    }
    if($cid2[0]==='savecategory')
    {
         mysqli_query($link, "UPDATE Categories SET CategoryName='{$_POST['name']}', CategoryInfo='{$_POST['info']}' WHERE CategoryID={$cid2[1]}");
    }
}
if (isset($_POST['new_category']))
{
    $name = htmlspecialchars($_POST['new_categoryname']);
    $info = htmlspecialchars($_POST['new_categoryinfo']);
    mysqli_query($link, "INSERT INTO Categories (CategoryName, CategoryInfo) VALUES ('$name','$info')");
}
echo <<<OUT
    <table border='0' width='500'>
       <thead style="font-weight:bold; text-align:center;">
        <td width=100 align='left'>Категория</td>
        <td width=300 align='left'>Доп. информация</td>
        <td width=100>Операции</td>
       </thead>

OUT;
    $query = mysqli_query($link, "SELECT * FROM Categories ORDER BY CategoryName");
    while ($row = mysqli_fetch_array($query))
              {
                  $query_user = mysqli_query($link, "SELECT * FROM Users WHERE UserID = '{$row['UserID']}'");
                  $row_user = mysqli_fetch_array($query_user);
                  if ($author)
                  {
                      $user = $row_user['UserName'];
                  }
                  else
                  {
                      $user = '';
                  }
                  if ($row['CategoryInfo'] == '')
                    {
                        $info="&nbsp;";
                    }
                    else
                    {
                        $info = $row['CategoryInfo'];
                    }
                    if (isset($category) and $row['CategoryID'] == $category)
                    {
                        echo <<<OUT
                                <td><input name='name' type='textbox' size='25' value='{$row['CategoryName']}'></td>
                                <td><input name='info' type='textbox' size='30' value='{$row['CategoryInfo']}'></td>
                                <td><input class='button_edit' type='submit' name='savecategory_{$row['CategoryID']}' value='Сохранить' ><br>
                                <input class='button_edit' type='submit' name='cancelcategory' value='Отмена' ></td>
                                </tr>
OUT;
                    }
                    else
                    {
                     echo <<<OUT
                                <tr>
                                    <td>{$row['CategoryName']}</td>
                                    <td>{$info} {$user}</td>
                                    <td><input class='button_edit' type='submit' name='editcategory_{$row['CategoryID']}' value='Изменить'>
                                    </td>
                                </tr>
OUT;
                     }
              }
              echo <<< OUT
                  <tr>
                    <td colspan='3'>
                        <img src="images/linemid.gif" vspace="10">
                    </td>
                  </tr>
                  <tr>
                    <td colspan='3'><input type='text' size='50' name='new_categoryname' placeholder='Новая категория' onfocus="this.placeholder=''" onblur="this.placeholder='Новая категория'"></td>
                  </tr>
                  <tr>
                    <td colspan='3'><textarea rows=3 cols=50 name='new_categoryinfo' placeholder='Дополнительная информация' onfocus="this.placeholder=''" onblur="this.placeholder='Дополнительная информация'"></textarea></td>
                  </tr>
                  <tr>
                    <td colspan='3'><input class='button_add' type='submit' name='new_category' value='Добавить категорию'></td>
                  </tr>
    </table>
OUT;
}
?>