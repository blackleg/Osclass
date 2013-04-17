<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

    /*
     *      Osclass – software for creating and publishing online classified
     *                           advertising platforms
     *
     *                        Copyright (C) 2012 OSCLASS
     *
     *       This program is free software: you can redistribute it and/or
     *     modify it under the terms of the GNU Affero General Public License
     *     as published by the Free Software Foundation, either version 3 of
     *            the License, or (at your option) any later version.
     *
     *     This program is distributed in the hope that it will be useful, but
     *         WITHOUT ANY WARRANTY; without even the implied warranty of
     *        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
     *             GNU Affero General Public License for more details.
     *
     *      You should have received a copy of the GNU Affero General Public
     * License along with this program.  If not, see <http://www.gnu.org/licenses/>.
     */

    class FieldForm extends Form {

        static public function i18n_datePicker() {
            ?>
        <script text="text/javascript">

            $.datepicker.regional['custom'] = { // Default regional settings
		closeText: '<?php _e('Done'); ?>', // Display text for close link
		prevText: '<?php _e('Prev'); ?>', // Display text for previous month link
		nextText: '<?php _e('Next'); ?>', // Display text for next month link
		currentText: '<?php _e('Today'); ?>', // Display text for current month link
		monthNames: ['<?php _e('January'); ?>','<?php _e('February'); ?>','<?php _e('March'); ?>','<?php _e('April'); ?>','<?php _e('May'); ?>','<?php _e('June'); ?>',
			'<?php _e('July'); ?>','<?php _e('August'); ?>','<?php _e('September'); ?>','<?php _e('October'); ?>','<?php _e('November'); ?>','<?php _e('December'); ?>'], // Names of months for drop-down and formatting
		monthNamesShort: ['<?php _e('Jan'); ?>', '<?php _e('Feb'); ?>', '<?php _e('Mar'); ?>', '<?php _e('Apr'); ?>', '<?php _e('May'); ?>', '<?php _e('Jun'); ?>', '<?php _e('Jul'); ?>', '<?php _e('Aug'); ?>', '<?php _e('Sep'); ?>', '<?php _e('Oct'); ?>', '<?php _e('Nov'); ?>', '<?php _e('Dec'); ?>'], // For formatting
		dayNames: ['<?php _e('Sunday'); ?>', '<?php _e('Monday'); ?>', '<?php _e('Tuesday'); ?>', '<?php _e('Wednesday'); ?>', '<?php _e('Thursday'); ?>', '<?php _e('Friday'); ?>', '<?php _e('Saturday'); ?>'], // For formatting
		dayNamesShort: ['<?php _e('Sun'); ?>', '<?php _e('Mon'); ?>', '<?php _e('Tue'); ?>', '<?php _e('Wed'); ?>', '<?php _e('Thu'); ?>', '<?php _e('Fri'); ?>', '<?php _e('Sat'); ?>'], // For formatting
		dayNamesMin: ['<?php _e('Su'); ?>','<?php _e('Mo'); ?>','<?php _e('Tu'); ?>','<?php _e('We'); ?>','<?php _e('Th'); ?>','<?php _e('Fr'); ?>','<?php _e('Sa'); ?>'], // Column headings for days starting at Sunday
		weekHeader: '<?php _e('Wk'); ?>', // Column header for week of the year
		dateFormat: 'dd/mm/yy', // See format options on parseDate
		firstDay: 0, // The first day of the week, Sun = 0, Mon = 1, ...
		isRTL: false, // True if right-to-left language, false if left-to-right
		showMonthAfterYear: false, // True if the year select precedes month, false for month then year
		yearSuffix: '' // Additional text to append to the year in the month headers
            };
        </script>
            <?php
        }

        static public function initDatePicker($id_field, $dateFormat, $value, $type='none') {

            if($value=='') $value = 0;
            $aux = <<<FB
            <script type="text/javascript">
                $('.$id_field').datepicker({
                    onSelect: function() {
                        // format to unix timestamp
                        var fecha = $(this).datepicker('getDate');
                        if('$type'=='from') {
                            fecha.setHours('0');
                            fecha.setMinutes('0');
                            fecha.setSeconds('0');
                        } else if('$type'=='to') {
                            fecha.setHours('23');
                            fecha.setMinutes('59');
                            fecha.setSeconds('59');
                        }

                        // new date format
                        var new_date = date('$dateFormat', fecha.getTime()/1000 );
                        // hack - same dateformat as php date function
                        $('.$id_field').prop('value', new_date);
                        $('#$id_field').prop('value', fecha.getTime()/1000);
                    },
                    inline: true,
                    navigationAsDateFormat: true,
                    dateFormat: '@' // javascript timestamp
                });
                $.datepicker.setDefaults($.datepicker.regional['custom']);

                if($value>0 && $value!='') {
                    // hack - same dateformat as php date function
                    $('.$id_field').prop('value', date('$dateFormat', $value));
                    $('#$id_field').prop('value', '$value');
                }

                $(".$id_field").change( function () {
                    if($(".$id_field").prop('value') == '') {
                        $('#$id_field').prop('value', '');
                    }
                });
            </script>
FB;
            echo  $aux;
        }

        static public function primary_input_hidden($field = null) {
            if(isset($field['pk_i_id'])) {
                parent::generic_input_hidden("id", $field["pk_i_id"]);
            }
        }

        static public function name_input_text($field = null) {
            parent::generic_input_text("s_name", (isset($field) && isset($field["s_name"])) ? $field["s_name"] : "", null, false);
            return true;
        }

        static public function options_input_text($field = null) {
            parent::generic_input_text("s_options", (isset($field) && isset($field["s_options"])) ? $field["s_options"] : "", null, false);
            return true;
        }

        static public function required_checkbox($field = null) {
            parent::generic_input_checkbox('field_required', 1, ($field!=null && isset($field['b_required']) && $field['b_required']==1)?true:false);
        }

        static public function searchable_checkbox($field = null) {
            parent::generic_input_checkbox('field_searchable', 1, ($field!=null && isset($field['b_searchable']) && $field['b_searchable']==1)?true:false);
        }

        static public function type_select($field = null) {
            ?>
            <select name="field_type" id="field_type">
                <option value="TEXT" <?php if($field['e_type']=="TEXT") { echo 'selected="selected"';};?>>TEXT</option>
                <option value="TEXTAREA" <?php if($field['e_type']=="TEXTAREA") { echo 'selected="selected"';};?>>TEXTAREA</option>
                <option value="DROPDOWN" <?php if($field['e_type']=="DROPDOWN") { echo 'selected="selected"';};?>>DROPDOWN</option>
                <option value="RADIO" <?php if($field['e_type']=="RADIO") { echo 'selected="selected"';};?>>RADIO</option>
                <option value="CHECKBOX" <?php if($field['e_type']=="CHECKBOX") { echo 'selected="selected"';};?>>CHECKBOX</option>
                <option value="URL" <?php if($field['e_type']=="URL") { echo 'selected="selected"';};?>>URL</option>
                <option value="DATE" <?php if($field['e_type']=="DATE") { echo 'selected="selected"';};?>>DATE</option>
                <option value="DATEINTERVAL" <?php if($field['e_type']=="DATEINTERVAL") { echo 'selected="selected"';};?>>DATE INTERVAL</option>
            </select>
            <?php
            return true;
        }

        static public function meta($field = null, $search = false) {

            if($field!=null) {
                // date interval
                if($field['e_type']=='DATEINTERVAL') {
                    $field['s_value']['from']   = '';
                    $field['s_value']['to']     = '';

                    if(!$search) {
                        $aInterval = Field::newInstance()->getDateIntervalByPrimaryKey($field['fk_i_item_id'], $field['pk_i_id']);

                        if(is_array($aInterval) && !empty($aInterval) ) {
                            $temp['from']       = @$aInterval['from'];
                            $temp['to']         = @$aInterval['to'];
                            $field['s_value']   = $temp;
                        }
                    } else {
                        $temp['from']   = Params::getParam('meta['.$field['pk_i_id'].'][from]');
                        $temp['to']     = Params::getParam('meta['.$field['pk_i_id'].'][to]');
                        $field['s_value'] = $temp;
                    }
                }


                // end date interval
                if(Session::newInstance()->_getForm('meta_'.$field['pk_i_id']) != ""){
                    $field['s_value'] = Session::newInstance()->_getForm('meta_'.$field['pk_i_id']);
                } else if(!isset($field['s_value']) || $field['s_value']=='') {
                    $s_value = Params::getParam('meta');
                    $field['s_value'] = '';
                    if(isset($s_value[$field['pk_i_id']])) {
                        $field['s_value'] = $s_value[$field['pk_i_id']];
                    }
                }

                if($field['e_type']=="TEXTAREA") {
                    if($search) {
                        echo '<h6>'.$field['s_name'].'</h6>';
                        echo '<input id="meta_'.$field['s_slug'].'" type="text" name="meta['.$field['pk_i_id'].']" value="' . osc_esc_html((isset($field) && isset($field["s_value"])) ? $field["s_value"] : "") . '" />';
                    } else {
                        echo '<label for="meta_'.$field['s_slug'].'">'.$field['s_name'].': </label>';
                        echo '<textarea id="meta_' . $field['s_slug'] . '" name="meta['.$field['pk_i_id'].']" rows="10">' . ((isset($field) && isset($field["s_value"])) ? $field["s_value"] : "") . '</textarea>';
                    }
                } else if($field['e_type']=="DROPDOWN") {
                    echo '<label for="meta_'.$field['s_slug'].'">'.$field['s_name'].': </label>';
                    if(isset($field) && isset($field['s_options'])) {
                        $options = explode(",", $field['s_options']);
                        if(count($options)>0) {
                            echo '<select name="meta['.$field['pk_i_id'].']" id="meta_' . $field['s_slug'] . '">';
                            foreach($options as $option) {
                                echo '<option value="'.osc_esc_html($option).'" '.($field['s_value']==$option?'selected="selected"':'').'>'.$option.'</option>';
                            }
                            echo '</select>';
                        }
                    }
                } else if($field['e_type']=="RADIO") {
                    // radio at search page, becomes dropdown with radio options
                    if($search) {
                        echo '<h6>'.$field['s_name'].'</h6>';
                        if(isset($field) && isset($field['s_options'])) {
                            $options = explode(",", $field['s_options']);
                            if(count($options)>0) {
                                echo '<select name="meta['.$field['pk_i_id'].']" id="meta_' . $field['s_slug'] . '">';
                                echo '<option value=""></option>';
                                foreach($options as $option) {
                                    echo '<option value="'.osc_esc_html($option).'" '.($field['s_value']==$option?'selected="selected"':'').'>'.$option.'</option>';
                                }
                                echo '</select>';
                            }
                        }
                    } else {
                        echo '<label for="meta_'.$field['s_slug'].'">'.$field['s_name'].': </label>';
                        if(isset($field) && isset($field['s_options'])) {
                            $options = explode(",", $field['s_options']);
                            if(count($options)>0) {
                                echo '<ul>';
                                foreach($options as $key => $option) {
                                    echo '<li><input type="radio" name="meta['.$field['pk_i_id'].']" id="meta_' . $field['s_slug'] . '_'.$key.'" value="'.osc_esc_html($option).'" '.($field['s_value']==$option?'checked="checked"':'').' /><label for="meta_' . $field['s_slug'] . '_'.$key.'">'.$option.'</label></li>';
                                }
                                echo '</ul>';
                            }
                        }
                    }
                } else if($field['e_type']=="CHECKBOX") {
                    if(isset($field) && isset($field['s_options'])) {
                        echo '<input type="checkbox" name="meta['.$field['pk_i_id'].']" id="meta_' . $field['s_slug'] . '_'.$field['s_name'].'" value="1" '.((isset($field) && isset($field['s_value']) && $field['s_value']==1)?'checked="checked"':'').' />';
                        echo '<label for="meta_'.$field['s_slug'] . '_'.$field['s_name'].'">'. $field['s_name'].' </label>';
                    }
                } else if($field['e_type']=="DATE") {
                    if($search) {
                        echo '<h6>'.$field['s_name'].'</h6>';
                    } else {
                        echo '<label for="meta_'.$field['s_slug'].'">'.$field['s_name'].': </label>';
                    }
                    // timestamp/1000 (javascript timestamp)
                    echo '<input type="hidden" id="date_'.$field['pk_i_id'].'" name="meta['.$field['pk_i_id'].']" value=""/>';
                    echo '<input type="text" id="" class="date_'.$field['pk_i_id'].'" value=""/>';
                    FieldForm::initDatePicker('date_'.$field['pk_i_id'], osc_date_format(), $field['s_value']);

                } else if($field['e_type']=="DATEINTERVAL") {
                    if($search) {
                        echo '<h6>'.$field['s_name'].'</h6>';
                    } else {
                        echo '<label for="meta_'.$field['s_slug'].'">'.$field['s_name'].': </label>';
                    }
                    // timestamp/1000 (javascript timestamp)
                    echo '<input type="hidden" id="date_'.$field['pk_i_id'].'_from" name="meta['.$field['pk_i_id'].'][from]" value="'.$field['s_value']['from'].'"/>';
                    echo '<input type="text" id="" class="date_'.$field['pk_i_id'].'_from" value=""/>';
                    FieldForm::initDatePicker('date_'.$field['pk_i_id'].'_from', osc_date_format(), $field['s_value']['from'], 'from');

                    echo '<input type="hidden" id="date_'.$field['pk_i_id'].'_to" name="meta['.$field['pk_i_id'].'][to]" value="'.$field['s_value']['to'].'"/>';
                    echo '<input type="text" id="" class="date_'.$field['pk_i_id'].'_to" value=""/>';
                    FieldForm::initDatePicker('date_'.$field['pk_i_id'].'_to', osc_date_format(), $field['s_value']['to'], 'to');

                } else {
                    if($search) {
                        echo '<h6>'.$field['s_name'].'</h6>';
                    } else {
                        echo '<label for="meta_'.$field['s_slug'].'">'.$field['s_name'].': </label>';
                    }
                    echo '<input id="meta_'.$field['s_slug'].'" type="text" name="meta['.$field['pk_i_id'].']" value="' . osc_esc_html((isset($field) && isset($field["s_value"])) ? $field["s_value"] : "") . '" ';
                    echo '/>';
                }
            }
        }

        static function meta_fields_search($catId = null) {
            // we received the categoryID
            if($catId == null) {
                return false;
            }

            $aCustomFields = array();
            // we check if the category is the same as our plugin
            foreach($catId as $id) {
                $aTemp = Field::newInstance()->findByCategory($id);
                foreach ($aTemp as $field) {
                    if($field['b_searchable']==1) {
                        $aCustomFields[$field['pk_i_id']] = $field;
                    }

                }
            }

            if(count($aCustomFields)>0) {
                echo '<filedset>';
                echo '<h3>'.__('custom fields').'</h3>';
                foreach($aCustomFields as $field) {
                    if($field['e_type']=='CHECKBOX') {
                        echo '<div class="row checkbox">';
                    } else {
                        echo '<div class="row one_input">';
                    }
                    FieldForm::meta($field, true);
                    echo '</div>';
                }
                echo '</filedset>';
            }
        }

        static public function meta_fields_input($catId = null, $itemId = null) {
            $fields = Field::newInstance()->findByCategoryItem($catId, $itemId);
            if(count($fields)>0) {
                echo '<div class="meta_list">';
                foreach($fields as $field) {
                    echo '<div class="meta">';
                        FieldForm::meta($field);
                    echo '</div>';
                }
                echo '</div>';
            }
        }

    }

?>