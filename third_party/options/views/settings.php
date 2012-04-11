<?php

// Data source type.
$this->table->add_row(
  form_label(lang('lbl_options_source_type'), 'options_source_type') .'<br />'
    .lang('hint_options_source_type'),
  form_dropdown('options_source_type', $options_source_types, $current_settings['options_source_type'])
);

// Direct input.
$this->table->add_row(
  form_label(lang('lbl_options_manual_source'), 'options_manual_source') .'<br />'
    .lang('hint_options_manual_source'),
  form_textarea('options_manual_source', $current_settings['options_manual_source'])
);

// Use data source.
$this->table->add_row(
  form_label(lang('lbl_options_global_source'), 'options_global_source') .'<br />'
    .lang('hint_options_global_source'),
  form_dropdown('options_global_source', $global_data_sources, $current_settings['options_global_source'])
);

// Control type.
$this->table->add_row(
  form_label(lang('lbl_options_control_type'), 'options_control_type') .'<br />'
    .lang('hint_options_control_type'),
  form_dropdown('options_control_type', $options_control_types, $current_settings['options_control_type'])
);

/* End of file    : settings.php */
/* File location  : system/third_party/options/views/settings.php */
