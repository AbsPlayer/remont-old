<?php
include_once('baseclass.php');
$p = new Work_withDB();
$link = $p->ConnectDB();
if (isset($_SESSION['user']) and $_SESSION['permission']==1)
{
if (isset($_POST['new_unit']))
{
    $name = htmlspecialchars($_POST['new_unitname']);
    mysqli_query($link, "INSERT INTO Units (UnitName) VALUES ('$name')");
}
echo <<<OUT
    <table border='0' width='500'>
       <thead style="font-weight:bold; text-align:center;">
        <td width=100>Единицы измерения</td>
       </thead>
OUT;
    $query = mysqli_query($link, "SELECT * FROM Units ORDER BY UnitName");
          while ($row = mysqli_fetch_array($query))
              {
                     echo <<<OUT
                                <tr>
                                    <td>{$row['UnitName']}</td>
                                </tr>
OUT;

              }
              echo <<< OUT
                  <tr>
                    <td>
                        <img src="images/linemid.gif" vspace="10">
                    </td>
                  </tr>
                  <tr>
                    <td><input type='text' size='50' name='new_unitname' placeholder='Новая едиица измерения' onfocus="this.placeholder=''" onblur="this.placeholder='Новая едиица измерения'"></td>
                  </tr>
                  <tr>
                    <td><input class='button_add' type='submit' name='new_unit' value='Добавить ед. изм.'></td>
                  </tr>
    </table>
OUT;
}
?>