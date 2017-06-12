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
    $gid = explode("_",$command[0]);
    $gid2 = explode("_",$command[2]);
    if($gid[0]==='editgrup')
    {
        $grup = $gid[1];
    }
    if($cid[0]==='deletecategory')
    {
         mysqli_query($link, "DELETE FROM Categories WHERE CategoryID={$cid[1]}");
    }
    if($gid2[0]==='savegrup')
    {
         mysqli_query($link, "UPDATE Grups SET GrupName='{$_POST['grupname']}', GrupInfo='{$_POST['grupinfo']}' WHERE GrupID={$gid2[1]}");
    }
}
if (isset($_POST['new_grup']))
{
    $name = htmlspecialchars($_POST['new_grupname']);
    $info = htmlspecialchars($_POST['new_grupinfo']);
    mysqli_query($link, "INSERT INTO Grups (CategoryID, GrupName, GrupInfo) VALUES ({$_POST['category_name']},'$name','$info')");
}
echo <<<OUT
    <table border='0' width='500'>
       <thead style="font-weight:bold; text-align:center;">
        <td width=80 align='left'>Категория</td>
        <td width=80 align='left'>Группа товаров</td>
        <td width=260 align='left'>Доп. информация</td>
        <td width=80>Операции</td>
       </thead>
OUT;
$query_category = mysqli_query($link, "SELECT * FROM Categories ORDER BY CategoryName");
while ($row_category = mysqli_fetch_array($query_category))
{
    $query = mysqli_query($link, "SELECT * FROM Grups WHERE CategoryID={$row_category['CategoryID']} ORDER BY GrupName");
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
                  if ($row['GrupInfo'] == '')
                    {
                        $info="&nbsp;";
                    }
                    else
                    {
                        $info = $row['GrupInfo'];
                    }
                    if (isset($grup) and $row['GrupID'] == $grup)
                    {
                        echo <<<OUT
                                <td>{$row_category['CategoryName']}</td>
                                <td><input name='grupname' type='textbox' size='15' value='{$row['GrupName']}'></td>
                                <td><textarea rows=3 cols=25 name='grupinfo'>{$row['GrupInfo']}</textarea></td>
                                <td><input class='button_edit' type='submit' name='savegrup_{$row['GrupID']}' value='Сохранить' ><br>
                                <input class='button_edit' type='submit' name='cancelgrup' value='Отмена' ></td>
                                </tr>
OUT;
                    }
                    else
                    {
                     echo <<<OUT
                                <tr>
                                    <td>{$row_category['CategoryName']}</td>
                                    <td>{$row['GrupName']}</td>
                                    <td>{$info} {$user}</td>
                                    <td><input class='button_edit' type='submit' name='editgrup_{$row['GrupID']}' value='Изменить'>
                                    </td>
                                </tr>
OUT;
                     }
              }
}
              echo <<< OUT
                  <tr>
                    <td colspan='4'>
                        <img src="images/linemid.gif" vspace="10">
                    </td>
                  </tr>
                  <tr>
                    <td colspan='2'>
                        <select ID='c' name='category_name'>
OUT;
                    $query_category = mysqli_query($link, "SELECT * FROM Categories ORDER BY CategoryName");
                    while ($row_category = mysqli_fetch_array($query_category))
                    {
                        echo("<OPTION VALUE={$row_category['CategoryID']}>{$row_category['CategoryName']}");
                    }
                    echo<<<OUT
                        </select>
                    </td>
                    <td colspan='2'><input type='text' size='30' name='new_grupname' placeholder='Новая группа' onfocus="this.placeholder=''" onblur="this.placeholder='Новая группа'"></td>
                  </tr>
                  <tr>
                    <td colspan='4'><textarea rows=3 cols=50 name='new_grupinfo' placeholder='Дополнительная информация' onfocus="this.placeholder=''" onblur="this.placeholder='Дополнительная информация'"></textarea></td>
                  </tr>
                  <tr>
                    <td colspan='4'><input class='button_add' type='submit' name='new_grup' value='Добавить группу'></td>
                  </tr>
    </table>
OUT;
}
?>