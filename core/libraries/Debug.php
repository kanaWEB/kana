<?php
//Display debug data
//@todo Make it less messy

function debug($type=false,$text=false,$variable=false,$link=false){
	?>
	<h3>
		<code>
			<?php echo $type ?>
		</code> 
		<?php echo $text ?>
		<? if($variable): ?>
		<code>
			<?php if($link){ ?>
			<a href="<?php echo $variable ?>"><?php echo $variable ?></a>
			<?php }
			else { 
				echo $variable;
			} ?>
		</code>
	    <? endif ?>
	</h3>
	<?php
}
?>