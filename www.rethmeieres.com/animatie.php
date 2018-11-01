<?php
include('config/inc_config.php');
$product = mysqli_fetch_array(mysqli_query($con, 'SELECT * FROM teksten WHERE id = 1'));

if($_GET['language']=='en')
{
?>
<woorden>
<woord1><?php echo $product['EN1']?></woord1>
<woord2><?php echo $product['EN2']?></woord2>
<woord3><?php echo $product['EN3']?></woord3>
<woord4><?php echo $product['EN4']?></woord4>
<woord5><?php echo $product['EN5']?></woord5>
<woord6><?php echo $product['EN6']?></woord6>
<woord7><?php echo $product['EN7']?></woord7>
<woord8><?php echo $product['EN8']?></woord8>
<woord9><?php echo $product['EN9']?></woord9>
<woord10><?php echo $product['EN10']?></woord10>
</woorden>
<?php
}
else
{
?>
<woorden>
<woord1><?php echo $product['TEXT1']?></woord1>
<woord2><?php echo $product['TEXT2']?></woord2>
<woord3><?php echo $product['TEXT3']?></woord3>
<woord4><?php echo $product['TEXT4']?></woord4>
<woord5><?php echo $product['TEXT5']?></woord5>
<woord6><?php echo $product['TEXT6']?></woord6>
<woord7><?php echo $product['TEXT7']?></woord7>
<woord8><?php echo $product['TEXT8']?></woord8>
<woord9><?php echo $product['TEXT9']?></woord9>
<woord10><?php echo $product['TEXT10']?></woord10>
</woorden>
<?php
}
?>
