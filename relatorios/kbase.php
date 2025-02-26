<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
echo "<img src='kbase-item.png' width='99' height='55' alt='MISOPHONIA LIBRARY'/>";
$item = $_GET['kb'];
if (!empty($item)) {
    echo "<iframe src='https://www.zotero.org/groups/4579467/misophonia_library/items/$item/item-details'>";
}
//header("Location: https://www.zotero.org/groups/4579467/misophonia_library/items/$item/item-details");