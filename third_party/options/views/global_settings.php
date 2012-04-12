<div id="ei_options">

<table class="mainTable padTable" cellpadding="0" cellspacing="0">
  <thead>
    <tr>
      <th><?=lang('thd_data_source_title'); ?></th>
      <th><?=lang('thd_data_source_type'); ?></th>
      <!-- <th><?=lang('thd_data_source_format'); ?></th> -->
      <th><?=lang('thd_data_source_location'); ?></th>
      <th>&nbsp;</th>
    </tr>
  </thead>

  <tbody class="roland">
  <?php if ( ! $data_sources): ?>
    <tr class="row">
      <td><?php echo form_input('data_source[0][title]'); ?></td>
      <td><?php echo form_dropdown('data_source[0][type]', $types); ?></td>
      <!-- <td><?php echo form_dropdown('data_source[0][format]', $formats); ?></td> -->
      <td><?php echo form_input('data_source[0][location]'); ?></td>

      <td class="act">
        <a class="remove_row btn" href="#"><img height="17" src="<?php echo $theme_url; ?>img/minus.png" width="16"></a>
        <a class="add_row btn" href="#"><img height="17" src="<?php echo $theme_url; ?>img/plus.png" width="16"></a>

        <!-- Hidden data -->
        <?php echo form_hidden('data_source[0][id]', ''); ?>
      </td>
    </tr>

  <?php else: foreach ($data_sources AS $data_source): ?>
    <tr class="row">
      <td><?php echo form_input('data_source[0][title]', $data_source->title); ?></td>
      <td><?php echo form_dropdown('data_source[0][type]', $types, $data_source->type); ?></td>
      <!-- <td><?php echo form_dropdown('data_source[0][format]', $formats, $data_source->format); ?></td> -->
      <td><?php echo form_input('data_source[0][location]', $data_source->location); ?></td>

      <td class="act">
        <a class="remove_row btn" href="#"><img height="17" src="<?php echo $theme_url; ?>img/minus.png" width="16"></a>
        <a class="add_row btn" href="#"><img height="17" src="<?php echo $theme_url; ?>img/plus.png" width="16"></a>

        <!-- Hidden data -->
        <?php echo form_hidden('data_source[0][id]', $data_source->id); ?>
      </td>
    </tr>

  <?php endforeach; endif; ?>
  </tbody>
</table>

</div><!-- /#ei_options -->
