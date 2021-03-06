<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<?php

require_once '/var/www/app/Mage.php';
umask ( 0 );

Mage::app ( 'default' );
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
Mage::log( "Web Service for SAP start");


function logfile($info){
	Mage::log($info, null,"stock2magento-".date("Y-m-d").'.log');
}




set_time_limit(0);

//error_reporting(E_ERROR );
$run = 0;
//create a mysql link
$dbname = (string)Mage::getConfig()->getNode('global/resources/default_setup/connection/dbname');
$username = (string)Mage::getConfig()->getNode('global/resources/default_setup/connection/username');
$password = (string)Mage::getConfig()->getNode('global/resources/default_setup/connection/password');

mysql_connect("localhost", $username, $password) or  die("Could not connect: " . mysql_error());
mysql_select_db($dbname);

// connect to DSN MSSQL with a user and password
$connect = odbc_connect("mssql", "sa", "Password01") or die ("couldn't connect");

odbc_exec($connect, "use IMPOS");

$result = odbc_exec($connect, "select * from sisleystock_magento order by sku");

while($row = odbc_fetch_array($result)){
	$StockArr[] = $row;
}
//var_dump($StockArr);
logfile( "库存数据获取成功.等待同步到Magento.<br/>");
echo "库存数据获取成功.等待同步到Magento.<br/>";
if ($run == 0){
	foreach($StockArr as $v){
		$inventory_Data['sku'] = $v['sku'];
		$inventory_Data['qty'] = $v['qty'];

		update_Inventory($inventory_Data);
	}
	$run = 1;
}


if ($run == 1){
	 //数据后处理流程
	$query = "delete from SISLEY_STOCK_Magento";
	//echo $query;
	odbc_exec($connect, $query);
}

odbc_free_result($result);
odbc_close($connect);
mysql_close($link); 
?>
<?php
//更新已有的客户信息
function update_Inventory($inventory_Data) {

$sku = "";
$qty = "";
$sku = $inventory_Data['sku'];
$qty = $inventory_Data['qty'];
$query = sprintf("update catalog_product_entity cpe,cataloginventory_stock_item csi set csi.qty= '%s' where cpe.entity_id = csi.item_id and cpe.sku = '%s'",mysql_real_escape_string($qty),mysql_real_escape_string($sku));
logfile($query."<br/>");
echo $query."<br/>";
mysql_query($query);
}
?>
</html>