<?php

class WebPage // класс для обработки веб-страниц
    {

    var $menu = array('Главная', 'Новости', 'Корзина', 'Контакты', 'Админчасть');
    var $contentFileName;

    function WebPage($_contentFileName = 'main.php')
        {
        $this->contentFileName = $_contentFileName;
        session_start();
        $db = new Work_withDB();
        $link = $db->ConnectDB();
        $mistake_message = "";
        $menu = "ShowLogIn";
        $left = "Актуальное предложение";
        $admin_menu = "ShowActualOffer";
        if (isset($_POST) and $_POST != null)    // надо ли редактировать товар в админчасти?
            {
            $pid = explode("_", key($_POST));
            if ($pid[0] === 'editproduct')
                {
                header("Location: buyer_product_detail.php?pid={$pid[1]}");
                exit();
                }
            if ($pid[0] === 'addproduct')
                {
                header("Location: buyer_product_detail.php");
                exit();
                }
            }
        if (isset($_POST['cancelproduct']))    //отмена редактирования товара
            {
            header('Location: buyer_products.php');
            exit();
            }
        if (isset($_SESSION['user']))
            {
            $menu = "ShowLogOut";
            $admin_menu = "ShowBuyerMenu";
            }
        if (isset($_POST['enter']))  //нажата ли кнопка входа
            {
            $query = mysqli_query($link, "SELECT * FROM Users WHERE UserName = '{$_POST['user']}'");
            $pw = mysqli_fetch_array($query);
            if ($pw['UserPassword'] === sha1(md5($_POST['password']))
                                        AND $pw['UserName'] == ($_POST['user'])
                    )
                {
                $_SESSION['user'] = $_POST['user'];
                $_SESSION['permission'] = (int) $pw['PermissionID'];
                $menu = "ShowLogOut";
                $admin_menu = "ShowBuyerMenu";
                $left = 'Меню';
                } else
                {
                $mistake_message = "<font color='red' size='+1'>*Ошибка!</font>";
                }
            }
        if (isset($_POST['logout']))
            {
            $_SESSION = array();
            if (isset($_COOKIE[session_name()]))
                {
                setcookie(session_name(), '', time() - 42000, '/');
                }
            session_destroy();
            header('Location: index.php');
            exit();
            }
        //вывод шаблона на экран
        echo <<<OUT
<!doctype html>
<html>
<head>
    <title>Материалы для ремонта</title>
    <meta http-equiv="Content-Type" content="text/html" charset="UTF-8">
    <link rel="STYLESHEET" type="text/css" href="style.css">
</head>

<body topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0">
<form ID='f' name='main' method='POST' enctype='multipart/form-data'>
<table cellpadding="0" cellspacing="0" border="0" height="100%">
<tr>
    <td rowspan="10" width="50%" height="100%" background="images/bg1222.jpg" style="background-position:right top; background-repeat:repeat-y"></td>
    <td rowspan="10" width="1" bgcolor="#000000"></td>
    <td colspan="2" valign="top">
    <table cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td rowspan="2"><img src="images/p01.jpg"></td>
            <td><img src="images/p02.gif"></td>
        </tr>
        <tr>
            <td rowspan="2"></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"><img src="images/name1.jpg"></td>
        </tr>
        <tr>
            <td colspan="2" width="780" height="31" background="images/menuback.gif" align="center" class="menu"><a href='index.php' title='На главную страницу сайта'><img src="images/m01_1.jpg" border="0"></a><a href="categories.php" title='Продукция'><img src="images/m02_1.jpg" border="0"></a><a href="contacts.php" title='Контакты'><img src="images/m03_1.jpg" border="0"></a></td>
        </tr>
    </table>
    </td>
    <td rowspan="10" width="1" bgcolor="#000000"></td>
    <td rowspan="10" width="50%" height="100%" background="images/bg1223.jpg" style="background-position:left top; background-repeat:repeat-y"></td>
</tr>
<tr>
    <td valign="top"  height="100%" width="245" background="images/bgleft.gif" style="background-repeat:repeat-y;">
    <table cellpadding="0" cellspacing="0" border="0" height="100%">
        <tr>
            <td background="images/cap01.gif" width="245" height="57" align="center" class="cap1">{$left}</td>
        </tr>
        <tr>
            <td height="342" valign="top">
                <table border='0'>
OUT;
        $db->$admin_menu();
        echo <<<OUT
                </table>
            </td>
        </tr>
        <tr>
            <td><img src="images/lineleft.gif" vspace="10"></td>
        </tr>
        <tr>
            <td height="60" valign="center" align="left">
                <table border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td colspan='2' align='center'>
                            {$mistake_message}
                        </td>
                    </tr>
                    <tr>
OUT;
        $db->$menu();
        echo <<<OUT
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td><img src="images/lineleft.gif" vspace="10"></td>
        </tr>
        <tr>
            <td height="60" valign="center" align="center">
                <table>
                    <tr>
                        <td>
                            <script type="text/javascript" language="javascript">
                            hotlog_js="1.0";
                            hotlog_r=""+Math.random()+"&s=619087&im=116&r="+escape(document.referrer)+"&pg="+
                            escape(window.location.href);
                            document.cookie="hotlog=1; path=/"; hotlog_r+="&c="+(document.cookie?"Y":"N");
                            </script>
                            <script type="text/javascript" language="javascript1.1">
                            hotlog_js="1.1";hotlog_r+="&j="+(navigator.javaEnabled()?"Y":"N")
                            </script>
                            <script type="text/javascript" language="javascript1.2">
                            hotlog_js="1.2";
                            hotlog_r+="&wh="+screen.width+'x'+screen.height+"&px="+
                            (((navigator.appName.substring(0,3)=="Mic"))?
                            screen.colorDepth:screen.pixelDepth)</script>
                            <script type="text/javascript" language="javascript1.3">hotlog_js="1.3"</script>
                            <script type="text/javascript" language="javascript">hotlog_r+="&js="+hotlog_js;
                            document.write("<a href='http://click.hotlog.ru/?619087' target='_top'><img "+
                            " src='http://hit30.hotlog.ru/cgi-bin/hotlog/count?"+
                            hotlog_r+"&' border=0 width=88 height=31 alt=HotLog><\/a>")
                            </script>
                            <noscript>
                            <a href="http://click.hotlog.ru/?619087" target="_top">
                            <img src="http://hit30.hotlog.ru/cgi-bin/hotlog/count?s=619087&amp;im=116" border="0"
                             width="88" height="31" alt="HotLog"></a>
                            </noscript>
                        </td>
                        <td>
                            <!-- SpyLOG -->
                            <script src="http://tools.spylog.ru/counter_cv.js" id="spylog_code" type="text/javascript" counter="1165997" part="" track_links="ext" page_level="0">
                            </script>
                            <noscript>
                            <a href="http://u11659.97.spylog.com/cnt?cid=1165997&f=3&p=0" target="_blank">
                            <img src="http://u11659.97.spylog.com/cnt?cid=1165997&p=0" alt="SpyLOG" border="0" width="88" height="31"></a>
                            </noscript>
                            <!--/ SpyLOG -->
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td height="100%"></td>
        </tr>
    </table>
    </td>
    <td valign="top" height="100%">
    <table cellpadding="0" cellspacing="0" border="0" height="100%">
        <tr>
            <td width="320" background="images/capwelcome1.gif" style="background-repeat:no-repeat">
                <div style="padding-left:90px;padding-top:3px;font-size:12px;" valign="center" class="cap1">
                    Материалы для ремонта
                </div>
            </td>
            <td width="216" height="42" background="images/bgsearch.gif" style="background-repeat:no-repeat">
            &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="2" width="535" height="100%" background="images/bgmid.gif" valign="top">
            <table cellspacing="0" cellspacing="0" border="0">
                <tr>
                    <td>
                        <table border='0'>
                            <tr><td width='10'><img src="images/spacer.gif"></td>
                            <td>
OUT;
        //Вывод контента
        include_once ($_contentFileName);
        echo <<<OUT
                            </td></tr>
                        </table>
                    </td>
                </tr>
                <tr><td align="center"><img src="images/linemid.gif" vspace="10"></td></tr>
                <tr>
                    <td colspan="2" valign="top">
                    <table cellpadding="0" cellspacing="0">
                        <tr>
                            <td valign="top">
                            <table cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td rowspan="4" width="18"></td>
                                </tr>
                                <tr>
                                    <td colspan="3" width="158" height="1" bgcolor="#BBBBBB"></td>
                                </tr>
                                <tr>
                                    <td width="1" height="185" bgcolor="#BBBBBB"></td>
                                    <td width="156" valign="top">
                                    <img src="images/pic05.jpg" hspace="8" vspace="10">
                                    <div class="mid2" style="text-indent:5px;"><img src="images/w.gif" align="left">Сухие смеси<br>Этот товар на сайте появится в перспективе</div>
                                    <!--<div align="right" style=""><a href=""><img src="images/learnmore.gif" hspace="15" vspace="10" border="0"></a></div>-->
                                    </td>
                                    <td width="1" height="185" bgcolor="#BBBBBB"></td>
                                </tr>
                                <tr>
                                    <td colspan="3" width="158" height="1" bgcolor="#BBBBBB"></td>
                                </tr>
                            </table>
                            </td>
                            <td valign="top">
                            <table cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td valign="top">
                                    <table cellpadding="0" cellspacing="0" border="0">
                                        <tr>
                                            <td rowspan="4" width="10"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" width="158" height="1" bgcolor="#BBBBBB"></td>
                                        </tr>
                                        <tr>
                                            <td width="1" height="185" bgcolor="#BBBBBB"></td>
                                            <td width="156" valign="top">
                                            <img src="images/pic06.jpg" hspace="8" vspace="10">
                                            <div class="mid2" style="text-indent:5px;"><img src="images/w.gif" align="left">Краски<br>Этот товар на сайте появится в перспективе</div>
                                            <!--<div align="right" style=""><a href=""><img src="images/learnmore.gif" hspace="15" vspace="10" border="0"></a></div>-->
                                            </td>
                                            <td width="1" height="185" bgcolor="#BBBBBB"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" width="158" height="1" bgcolor="#BBBBBB"></td>
                                        </tr>
                                    </table>
                                    </td>
                                </tr>
                            </table>
                            </td>
                            <td valign="top">
                            <table cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td valign="top">
                                    <table cellpadding="0" cellspacing="0" border="0">
                                        <tr>
                                            <td rowspan="4" width="10"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" width="158" height="1" bgcolor="#BBBBBB"></td>
                                        </tr>
                                        <tr>
                                            <td width="1" height="185" bgcolor="#BBBBBB"></td>
                                            <td width="156" valign="top">
                                            <img src="images/pic07.jpg" hspace="8" vspace="10">
                                            <div class="mid2" style="text-indent:5px;"><img src="images/w.gif" align="left">Консультации<br>В телефонном режиме мы проконсультируем Вас по интересующим вопросам</div>
                                            <div align="right" style=""><a href="contacts.php" title='Контакты'><img src="images/learnmore.gif" hspace="15" vspace="10" border="0"></a></div>
                                            </td>
                                            <td width="1" height="185" bgcolor="#BBBBBB"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" width="158" height="1" bgcolor="#BBBBBB"></td>
                                        </tr>
                                    </table>
                                    </td>
                                </tr>
                            </table>
                            </td>
                        </tr>
                    </table>
                    </td>
                </tr>
                <tr>
                    <td height="100%"></td>
                </tr>
            </table>
            </td>
        </tr>
        <tr>
            <td colspan="2"><img src="images/bottom.gif"></td>
        </tr>
    </table>
    </td>
</tr>
<tr>
    <td colspan="2" width="780" height="28" background="images/bgmenu.gif" align="center" class="menu"><a href='index.php' title='На главную страницу сайта' class="menu">Главная</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="categories.php" title='Продукция' class="menu">Продукция</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="contacts.php" title='Контакты' class="menu">Контакты</a></td>
</tr>
<tr>
    <td colspan="2" width="780" height="52" background="images/footer.gif" align="center">Copyright &copy; 2009 <strong>me.</strong></td>
</tr>
</table>
</body>
</html>
OUT;
        }

    }

class Work_withDB   //класс для работы с базой данных
    {

    function ConnectDB()  //функция подключения кбазе данных
        {
        include_once("logindata.php");
        $link = mysqli_connect(SQLSERVER, SQLLOGIN, SQLPASSWORD, SQLDB);
        return $link;
        }

    function ShowCategories() //показ категорий
        {
        $link = $this->ConnectDB();
        $query = mysqli_query($link, "SELECT * FROM Categories ORDER BY CategoryName ASC");
        while ($row = mysqli_fetch_array($query)) {
            $img_product_image = $row['CategorySmallImage'];
            if (strlen($img_product_image) < 1)
                {
                $img_product_image = './images/no_image.jpg';
                }
            echo <<<OUT
                  <tr>
                    <td width='140' height='65' background="images/baget140_65.jpg" align="center" style="background-repeat:no-repeat">
                        <a href='grups.php?cid={$row['CategoryID']}' title='{$row["CategoryName"]}'><img src="{$img_product_image}" width='62' height='62' border='0' align='center'></a>
                    </td>
                    <td>
                        <img src='images/w.gif' align='left'><a href='grups.php?cid={$row['CategoryID']}' title='{$row["CategoryName"]}' class="menu">{$row["CategoryName"]}</a>
                    </td>
                  </tr>
OUT;
        }
        }

    function ShowGrups($cid) //показ групп в категории
        {
        $link = $this->ConnectDB();
        $query = mysqli_query($link, "SELECT * FROM Grups WHERE CategoryID = $cid ORDER BY GrupName");
        while ($row = mysqli_fetch_array($query)) {
            $img_product_image = $row['GrupSmallImage'];
            if (strlen($img_product_image) < 1)
                {
                $img_product_image = './images/no_image.jpg';
                }
            echo <<<OUT
                  <tr>
                    <td width='65' height='65' background="images/baget65_65.jpg" align="center" style="background-repeat:no-repeat">
                        <a href='products.php?gid={$row['GrupID']}' title='{$row["GrupName"]}'><img src="{$img_product_image}" width='62' height='62' border='0' align='center'></a>
                    </td>
                    <td>
                        <img src='images/w.gif' align='left' hspace='10'><a href='products.php?gid={$row['GrupID']}' title='{$row["GrupName"]}' class="menu">{$row["GrupName"]}</a>
                    </td>
                  </tr>
OUT;
        }
        }

    function ShowProducts($gid) //показ товаров в группе
        {
        $link = $this->ConnectDB();
        $query = mysqli_query($link, "SELECT * FROM Products WHERE GrupID = $gid");
        while ($row = mysqli_fetch_array($query)) {
            $img_product_image = $row['ProductSmallImage'];
            if (strlen($img_product_image) < 1)
                {
                $img_product_image = './images/no_image.jpg';
                }
            echo <<<OUT
                  <tr>
                    <td width='65' height='65' background="images/baget65_65.jpg" align="center" style="background-repeat:no-repeat">
                        <a href='product_detail.php?pid={$row['ProductID']}' title='{$row["ProductName"]}'><img src="{$img_product_image}" border='0' align='left' width=61 height=61></a>
                    </td>
                    <td width='360'>
                        <img src='images/w.gif' align='left'><a href='product_detail.php?pid={$row['ProductID']}' title='{$row["ProductName"]}' class="menu">{$row["ProductName"]}</a>
                    </td>
                  </tr>
OUT;
        }
        }

    function ShowProductDetail($pid) //детализация товара
        {
        $link = $this->ConnectDB();
        $query = mysqli_query($link, "SELECT * FROM Products WHERE ProductID = $pid");
        while ($row = mysqli_fetch_array($query)) {
            $ral = '';
            if ($row['GrupID'] == 1 or $row['GrupID'] == 2)
                {
                $ral = "<a href='ral.php' style='color:#000000'>  Таблица возможных цветов</a>";
                }
            $img_product_image = $row['ProductBigImage'];
            if (strlen($img_product_image) < 1)
                {
                $img_product_image = './images/no_image.jpg';
                }
            echo <<<OUT
                  <tr>
                    <td width='500' align='center'>
                        <table border='0'>
                            <tr>
                                <td width='300' height='300' background="images/baget300_300.jpg" align="center" valign='center' style="background-repeat:no-repeat">
                                    <img src="{$img_product_image}" border='0'>
                                </td>
                            </tr>
                        </table>
                    </td>
                  </tr>
                  <tr>
                    <td>
                        {$row["ProductInfo"]} {$ral}
                    </td>
                  </tr>
OUT;
            $price = round(($row[ProductPrice] * (1 + $row[ProductMarga])), 1);
            $price = sprintf("%01.2f", $price);
            $query_unit = mysqli_query($link, "SELECT * FROM Units WHERE UnitID = $row[UnitID]");
            $row_unit = mysqli_fetch_array($query_unit);
            echo <<<OUT
                  <tr>
                    <td>
                        <div style='font-weight:bold;'>Цена: <font style='font-style: italic; font-size:12px'> {$price} грн. за {$row_unit["UnitName"]}</font>
                    </td>
                  </tr>
OUT;
        }
        }

    function ShowActualOffer() //показ случайных предложений
        {
        $link = $this->ConnectDB();
        $query = mysqli_query($link, "SELECT ProductID FROM Products");
        $index = 0;
        while ($row = mysqli_fetch_array($query)) {
            $array[$index] = $row[0];
            $index++;
        }
        $rand_keys = array_rand(array_flip($array), COUNT_OFFER);
        for ($i = 0; $i < COUNT_OFFER; $i++)
            {
            $query = mysqli_query($link, "SELECT * FROM Products WHERE ProductID = $rand_keys[$i]");
            $row = mysqli_fetch_array($query);
            $query_grup = mysqli_query($link, "SELECT * FROM Grups WHERE GrupID = $row[GrupID]");
            $row_grup = mysqli_fetch_array($query_grup);
            $img_product_image = $row['ProductBigImage'];
            if (strlen($img_product_image) < 1)
                {
                $img_product_image = './images/no_image.jpg';
                }
            echo <<<OUT
              <tr>
                    <td width='138' height='138' align='center' background="images/baget138_138.jpg" style="background-repeat:no-repeat;">
                        <img src="{$img_product_image}" width='132' height='132' border='0'>
                    </td>
                    <td>
                        <div class="cap">{$row_grup['GrupName']}</div>
                        <div class="left">{$row['ProductName']}</div>
                        <a href="product_detail.php?pid={$row['ProductID']}"><img src="images/learnmore.gif" border="0" vspace="10"></a>
                    </td>
                </tr>
OUT;
            }
        }

    function ShowBuyerMenu() //показ меню ввода товаров
        {
        echo <<<OUT
              <tr>
                <td>
                    <div style='font-size:12px;' >Операции с:</div>
                </td>
              </tr>
              <tr>
                <td>
                    <a href='buyer_categories.php' style='color:#000000'>Категориями товаров</a>
                </td>
              </tr>
              <tr>
                <td>
                    <a href='buyer_grups.php' style='color:#000000'>Группами товаров</a>
                </td>
              </tr>
              <tr>
                <td>
                    <a href='buyer_products.php' style='color:#000000'>Наименованиями товаров</a>
                </td>
              </tr>
              <tr>
                <td>-----------------------------------------------</td>
              </tr>
OUT;
        if (isset($_SESSION['user']) and $_SESSION['permission']  === 1)
            {
            echo <<<OUT
              <tr>
                <td>
                    <a href='buyer_units.php' style='color:#000000'>Единицы измерения</a>
                </td>
              </tr>

OUT;
            }
        }

    function ShowLogIn() //показ меню входа пользователя
        {
        echo <<<OUT
                        <td style="padding-left:10">
                            Имя:&nbsp;
                        </td>
                        <td style="padding-left:10">
                            <input type='text' name='user' size='15' maxlength='40'>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-left:10">
                            Пароль:&nbsp;
                        </td>
                        <td style="padding-left:10;">
                            <input type='password' name='password' size='15' maxlength='40'>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" align='right' style="padding-left:60; padding-top:5">
                            <input type="submit" name='enter' value='Вход' style='font-size:11px;'>
                        </td>

OUT;
        }

    function ShowLogOut() //показ меню выхода пользователя
        {

        echo <<<OUT
              <table border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="padding-left:10">
                           Добро пожаловать, {$_SESSION['user']}!
                        </td>
                    </tr>
                    <tr>
                        <td align='right' style="padding-left:60; padding-top:5">
                            <input type="submit" name='logout' value='Выход' style='font-size:11px;'>
                        </td>
OUT;
        }

    function ResizeImage($inputFilename, $new_side)   //изменение размера изображений
        {
        $imagedata = getimagesize($inputFilename);
        $w = $imagedata[0];
        $h = $imagedata[1];
        if ($h > $w)
            {
            $new_w = ($new_side / $h) * $w;
            $new_h = $new_side;
            } else
            {
            $new_h = ($new_side / $w) * $h;
            $new_w = $new_side;
            }
        $im2 = ImageCreateTrueColor($new_w, $new_h);
        $image = ImageCreateFromJpeg($inputFilename);
        imagecopyResampled($im2, $image, 0, 0, 0, 0, $new_w, $new_h, $imagedata[0], $imagedata[1]);
        imagejpeg($im2, $inputFilename);
        }

    }

?>