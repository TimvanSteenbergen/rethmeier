<?
include('config/inc_config.php');
$product = mysql_fetch_array(mysql_query('SELECT * FROM teksten WHERE id = 1'));

if($_GET['language']=='en')
{
?>
<woorden>
<woord1><?=$product['EN1']?></woord1>
<woord2><?=$product['EN2']?></woord2>
<woord3><?=$product['EN3']?></woord3>
<woord4><?=$product['EN4']?></woord4>
<woord5><?=$product['EN5']?></woord5>
<woord6><?=$product['EN6']?></woord6>
<woord7><?=$product['EN7']?></woord7>
<woord8><?=$product['EN8']?></woord8>
<woord9><?=$product['EN9']?></woord9>
<woord10><?=$product['EN10']?></woord10>
</woorden>
<?php
}
else
{
?>
<woorden>
<woord1><?=$product['TEXT1']?></woord1>
<woord2><?=$product['TEXT2']?></woord2>
<woord3><?=$product['TEXT3']?></woord3>
<woord4><?=$product['TEXT4']?></woord4>
<woord5><?=$product['TEXT5']?></woord5>
<woord6><?=$product['TEXT6']?></woord6>
<woord7><?=$product['TEXT7']?></woord7>
<woord8><?=$product['TEXT8']?></woord8>
<woord9><?=$product['TEXT9']?></woord9>
<woord10><?=$product['TEXT10']?></woord10>
</woorden>
<?php
}
?>