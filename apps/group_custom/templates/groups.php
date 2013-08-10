<div  id="controls">
	<button class="button" id="create_group"><?php echo $l->t('Create Group');?></button>
	<button class="button" id="modify_group_size"><?php echo $l->t('Modify Group Size');?></button>
</div>
	
<ul id="leftcontent">

    <?php
        echo $this->inc('part.group');
    ?>

</ul>

<div id="rightcontent">

</div>

<div id="group_custom_holder">

</div>


<div id="group_size_holder"></div> <!-- Jawinton -->
