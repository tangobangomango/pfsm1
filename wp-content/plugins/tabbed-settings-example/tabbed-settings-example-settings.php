<?php

/*
 * Building default settings page

 * Some sources: 
 * https://wordpress.stackexchange.com/questions/91764/save-and-retrieve-custom-plugin-options-value
 * https://samelh.com/blog/2015/12/13/how-to-create-a-wordpress-settings-page-for-your-wordpress-plugin/
 * https://wordpress.stackexchange.com/questions/100023/settings-api-with-arrays-example REALLY EXCELLENT
 * https://hugh.blog/2014/02/26/complete-versatile-options-page-class-wordpress-plugin/
 * 
 * https://wordpress.stackexchange.com/questions/272177/plugin-settings-page-checkbox-not-saving-one-options-array-with-sub-array/272532#272532
 * 
 */

/* ----------------------------------------------------------------------------------------------------------------------
 * MAIN PAGE SETUP
 *-----------------------------------------------------------------------------------------------------------------------
 */

// Hook to add page
add_action('admin_menu', 'tse_add_plugin_options_page');

if ( !function_exists( 'tse_add_plugin_options_page' ) ) {
    //function to add page under setting options in wordpress admin section
    function tse_add_plugin_options_page() {
        add_options_page(
        	'Tabbed Settings Example Settings', // Shows on page in title tag
        	'Tabbed Settings Example',                  // Shows in tab
        	'manage_options',                    // Permission needed to access page
        	'tse-default-settings', 	         // page slug - used only in <h2 class="nav-tab-wrapper"> when making tabs
        	'tse_plugin_options_page');          // callback function name
    }   
}

// Callback function from add_options_page to render input page
function tse_plugin_options_page() {
    
    // Check that user has permission
    if (!current_user_can('manage_options'))
    	return;

    ?>
    <div class="wrap">
        <h2>Tabbed Settings Example Settings</h2>
        <form action="options.php" method="post"> <?php // options.php a fixed name- don't change ?>

            <?php
            $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'text_options';
            ?>

        <h2 class="nav-tab-wrapper">
            <a href="?page=tse-default-settings&tab=text_options" class="nav-tab <?php echo $active_tab == 'text_options' ? 'nav-tab-active' : ''; ?>">Text Options</a>
            <a href="?page=tse-default-settings&tab=select_options" class="nav-tab <?php echo $active_tab == 'select_options' ? 'nav-tab-active' : ''; ?>">Select Options</a>
        </h2>

        <?php if( $active_tab == 'text_options' ) { ?>

            <?php settings_fields('tse_plugin_text_options_group'); // needs to match group name in register_settings ?>
            
            <?php do_settings_sections('tse-default-text-settings'); /* slug of page for add_settings_section(). This will output the section titles wrapped in h3 tags
             and the settings fields wrapped in tables. */?>

             <?php } else { ?> 

             <?php settings_fields('tse_plugin_select_options_group'); // needs to match group name in register_settings ?>
            
            <?php do_settings_sections('tse-default-select-settings'); /* slug of page for add_settings_section(). This will output the section titles wrapped in h3 tags
             and the settings fields wrapped in tables. */?> 

             <?php } ?>
         
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
} 

/* ----------------------------------------------------------------------------------------------------------------------
 * REGISTER SETTINGS, SECTIONS, AND FIELDS
 * ----------------------------------------------------------------------------------------------------------------------
 */

// We hook into admin_init when we need it and call a registration function.
// The important part here is: $GLOBALS['pagenow'] must be either options-general.php (for the output) 
// or options.php (for the validation). Do not call all of the following code on each request. 

if ( ! empty ( $GLOBALS['pagenow'] )
    and ( 'options-general.php' === $GLOBALS['pagenow']
        or 'options.php' === $GLOBALS['pagenow']
    )
)
{
    add_action( 'admin_init', 'tse_plugin_register_settings' );
} 

function tse_plugin_register_settings()
{
    $text_option_name   = 'tse_plugin_text_option_name'; // free to choose this name
    $select_option_name   = 'tse_plugin_select_option_name'; // free to choose this name

    /* Not sure this section is needed even for new options 

           // If the theme options don't exist, create them.
    if( false == get_option( $text_option_name ) ) {  
        add_option( $text_option_name );
    } // end if

          // If the theme options don't exist, create them.
    if( false == get_option( $select_option_name ) ) {  
        add_option( $select_option_name );
    } // end if

    */
    


    // Fetch existing options.
    $text_option_values = get_option( $text_option_name );
    $select_option_values = get_option( $select_option_name );



    $text_default_values = array (
        'number' => 500,
        
        'long'   => '',
    );

    $select_default_values = array(
        'color'  => 'blue',
        'activity' => array(), // arrays for checkboxes and multi-select will hold keys into options array, not keys and values
        'vposition' => 'above',
        'country' => array()
    );

    /* Temporary reset - used when adding variables or when testing to reset options values array 
    update_option( 'tse_plugin_text_option_name', $text_default_values);
    $option_values = get_option( $text_option_name );
    update_option( 'tse_plugin_select_option_name', $select_default_values);
    $option_values = get_option( $select_option_name );
    */
     

    // Parse option values into predefined keys, throw the rest away.

    /*          Interesting use of shortcode_atts(): Combines user shortcode attributes with known attributes and fills in defaults when needed. 
     *          The result will contain every key from the known attributes, merged with values from shortcode attributes. 
     */
    $text_data = shortcode_atts( $text_default_values, $text_option_values );
    $select_data = shortcode_atts( $select_default_values, $select_option_values );
    //$text_data = $text_option_values;
    //$select_data = $select_option_values; 
    //tse_plugin_debug_var($data, 'Data: ');

    /* These two if statements needed if a checkbox array or multi select array are saved empty, which sets variable to NULL. 
     * Needs to be an empty array for array functions to work when sanitizing values or calculating checked() or selected()
     */

    if (is_null($select_data['activity'])) {
        $select_data['activity'] = array();
    }

    if (is_null($select_data['country'])) {
        $select_data['country'] = array();
    }

    register_setting(
        'tse_plugin_text_options_group', // group, set above in tse_plugin_options_page() for settings_fields()
        $text_option_name,  // option name, used as key in database. Set above
        ''/*'tse_plugin_validate_option'*/      // validation callback function below
    );

    register_setting(
        'tse_plugin_select_options_group', // group, set above in tse_plugin_options_page() for settings_fields()
        $select_option_name,  // option name, used as key in database. Set above
        ''/*'tse_plugin_validate_option'*/      // validation callback function below
    );

       

    add_settings_section(
        'section_1', // section ID
        'Some text fields', // section title
        'tse_plugin_render_section_1', // print output
        'tse-default-text-settings' // menu slug from do_settings_sections() 
    );

    add_settings_field(
        'section_1_field_1', // field ID
        'A Number', // field title
        'tse_plugin_render_section_1_field_1', // callback to render appropriate html
        'tse-default-text-settings',  // menu slug
        'section_1', // section ID of field placement
        array (
            'label_for'   => 'label1', // makes the field name clickable,
            'name'        => 'number', // value for 'name' attribute
            'value'       => esc_attr( $text_data['number'] ), // use esc_attr() when you are outputting something intended to be in an HTML attribute.
            'option_name' => $text_option_name  // To be used for form ids and names
        )
    );

    add_settings_field(
        'section_2_field_1',
        'Notes',
        'tse_plugin_render_section_2_field_1',
        'tse-default-text-settings',  // menu slug
        'section_1',
        array (
            'label_for'   => 'label3', // makes the field name clickable,
            'name'        => 'long', // value for 'name' attribute
            'value'       => esc_textarea( $text_data['long'] ), // a string cleaned and escaped for output in a textarea element.
            'option_name' => $text_option_name
        )
    );




    add_settings_section(
        'section_2', // ID
        'Some Selections', // Title
        'tse_plugin_render_section_2', // print output
        'tse-default-select-settings' // menu slug
    );

     add_settings_field(
        'section_1_field_2',
        'Select',
        'tse_plugin_render_section_1_field_2', // callback to render html
        'tse-default-select-settings',  // menu slug
        'section_2',
        array (
            'label_for'   => 'label2', // makes the field name clickable,
            'name'        => 'color', // value for 'name' attribute
            'value'       => esc_attr( $select_data['color'] ),
            'options'     => array (
                'blue'  => 'Blue',
                'red'   => 'Red',
                'black' => 'Black'
            ),
            'option_name' => $select_option_name
        )
    );   


/*    add_settings_section(
        'section_3', // ID
        'Checkboxes and Radio Buttons', // Title
        'tse_plugin_render_section_3', // print output
        'tse-default-select-settings' // menu slug
    );
*/

    add_settings_field(
        'section_3_field_1',
        'Checkboxes',
        'tse_plugin_render_section_3_field_1', // callback to render html
        'tse-default-select-settings',  // menu slug
        'section_2',
        array (
            'label_for'   => 'label4', // makes the field name clickable,
            'name'        => 'activity', // value for 'name' attribute
            'value'       => array_map( 'esc_attr', $select_data['activity'] ) ,
            'options'     => array (
                'baseball'  => 'Baseball',
                'golf'      => 'Golf',
                'hockey'    => 'Hockey'
            ),
            'option_name' => $select_option_name
        )
    );

    add_settings_field(
        'section_3_field_2',
        'Radio Buttons',
        'tse_plugin_render_section_3_field_2', // callback to render html
        'tse-default-select-settings',  // menu slug
        'section_2',
        array (
            'label_for'   => 'label5', // makes the field name clickable,
            'name'        => 'vposition', // value for 'name' attribute
            'value'       => esc_attr( $select_data['vposition'] ) ,
            'options'     => array (
                'above'  => 'Above',
                'side'      => 'Side ',
                'below'    => 'Below'
            ),
            'description' => array (
                'above' => __('- Quote appears next to paragraph above where it is in the text (Default)', 'tse-plugin-text-domain'),
                'side' => __('- Quote appears next to same paragraph where it is in the text', 'tse-plugin-text-domain'),
                'below' => __('- Quote appears next to paragraph below where it is in the text', 'tse-plugin-text-domain')
            ),
            'option_name' => $select_option_name
        )
    );

/*    add_settings_section(
        'section_4', // ID
        'Multi Select', // Title
        'tse_plugin_render_section_4', // print output
        'tse-default-select-settings' // menu slug
    );
*/


    add_settings_field(
        'section_4_field_1',
        'Multi-Select',
        'tse_plugin_render_section_4_field_1', // callback to render html
        'tse-default-select-settings',  // menu slug
        'section_2',
        array (
            'label_for'   => 'label6', // makes the field name clickable,
            'name'        => 'country', // value for 'name' attribute
            'value'       => array_map( 'esc_attr', $select_data['country'] ) ,
            'options'     => array (
                'usa'  => 'United States',
                'italy'      => 'Italy',
                'france'    => 'France'
            ),
            'option_name' => $select_option_name
        )
    );

}

/* ----------------------------------------------------------------------------------------------------------------------
 * RENDER THE HTML
 * ----------------------------------------------------------------------------------------------------------------------
 */

function tse_plugin_render_section_1()
{
    print '<p>Pick a number between 1 and 1000, and choose a color.</p>';
}
function tse_plugin_render_section_1_field_1( $args )
{
    /* Creates this markup:
    /* <input name="tse_plugin_option_name[number]"

                <tr>
                <th scope="row"><label for="label1">A Number</label></th>
                <td><input name="tse_plugin_option_name[number]" id="label1" value="500" class="regular-text"></td>
            </tr>

     */ 
    printf(
        '<input name="%1$s[%2$s]" id="%3$s" value="%4$s" class="regular-text">',
        $args['option_name'],
        $args['name'],
        $args['label_for'],
        $args['value']
    );
    // tse_plugin_debug_var( func_get_args(), __FUNCTION__ );
}

/* Renders
            <tr>
                <th scope="row"><label for="label2">Select</label></th>
                <td><select name="tse_plugin_option_name[color]" id="label2">
                    <option value="blue"  selected='selected'>Blue</option>
                    <option value="red" >Red</option>
                    <option value="black" >Black</option>
                </select></td>
            </tr>

*/

function tse_plugin_render_section_1_field_2( $args )
{
    printf(
        '<select name="%1$s[%2$s]" id="%3$s">',
        $args['option_name'],
        $args['name'],
        $args['label_for']
    );

    foreach ( $args['options'] as $val => $title )
        printf(
            '<option value="%1$s" %2$s>%3$s</option>',
            $val,
            selected( $val, $args['value'], FALSE ),
            $title
        );

    print '</select>';

    //tse_plugin_debug_var( func_get_args(), __FUNCTION__ );
}

function tse_plugin_render_section_2()
{
    print '<p>Makes some notes.</p>';
}

/* Below renders
 *     
 *        <table class="form-table">
 *            <tr>
 *           <th scope="row"><label for="label3">Notes</label></th>
 *            <td><textarea name="tse_plugin_option_name[long]" id="label3" rows="10" cols="30" class="code">hello</textarea></td>
 *            </tr>
 *        </table> 
  */ 

function tse_plugin_render_section_2_field_1( $args )
{
    printf(
        '<textarea name="%1$s[%2$s]" id="%3$s" rows="10" cols="30" class="code">%4$s</textarea>',
        $args['option_name'],
        $args['name'],
        $args['label_for'],
        $args['value']
    );
}

function tse_plugin_render_section_3()
{
    print '<p>Very tricky to sort out.</p>';
}

/* Below renders 
        <fieldset>
            <label for="activity_1"><input id="activity_1" name="tse_plugin_option_name[activity][]" type="checkbox" value="baseball"  /> Baseball</label><br/>
            <label for="activity_2"><input id="activity_2" name="tse_plugin_option_name[activity][]" type="checkbox" value="golf"  /> Golf</label><br/>
            <label for="activity_3"><input id="activity_3" name="tse_plugin_option_name[activity][]" type="checkbox" value="hockey"  /> Hockey</label><br/>
        </fieldset>
*/

function tse_plugin_render_section_3_field_1( $args )
{   

    $options_markup = '';
    $iterator = 0;
    $values = $args['value'];
    $options = $args['options'];
    //tse_plugin_debug_var( $options, 'Options: ' );
    //tse_plugin_debug_var( $values, 'Values: ' );

   
    
    foreach ( $options as $key => $title ) {
        //tse_plugin_debug_var( $key, 'Key: ' );
        //tse_plugin_debug_var( $title, 'Title: ' );
        $checked = checked((in_array($key, $values)), true, false);  // $values arrya contains only keys from $options array
        //$checked = checked((in_array($key, array_keys($values))), true, false);
        //$checked = checked (($title == $values[$key]), true, false);

        //tse_plugin_debug_var($checked, 'Checked: ');

        // CRITICAL in line below that the input name be built like this with option name, name, and [] to get firlds to save
        $iterator ++;
        $options_markup .= sprintf(
            '<label for="%2$s_%6$s"><input id="%2$s_%6$s" name="%1$s[%2$s][]" type="checkbox" value="%3$s" %4$s /> %5$s</label><br/>', 
           
            $args['option_name'],
            $args['name'],
            $key,
            $checked,
            $title,
            $iterator
        );
            
           
    }
            printf( '<fieldset>%s</fieldset>', $options_markup );

    

    //tse_plugin_debug_var( $values, 'Values: ');
}

/* Below renders 
       <fieldset>
            <label for="vposition_1"><input id="vposition_1" name="tse_plugin_option_name[vposition]" type="radio" value="above"  /> Above  - Quote appears next to paragraph above where it is in the text (Default)</label><br/>
            <label for="vposition_2"><input id="vposition_2" name="tse_plugin_option_name[vposition]" type="radio" value="side"  /> Side   - Quote appears next to same paragraph where it is in the text</label><br/>
            <label for="vposition_3"><input id="vposition_3" name="tse_plugin_option_name[vposition]" type="radio" value="below"  checked='checked' /> Below  - Quote appears next to paragraph below where it is in the text</label><br/>
        </fieldset>
*/

function tse_plugin_render_section_3_field_2( $args )
{   

    $options_markup = '';
    $iterator = 0;
    $value = $args['value'];
    $options = $args['options'];
    //tse_plugin_debug_var( $options, 'Options: ' );
    //tse_plugin_debug_var( $value, 'Value: ' );

   
    
    foreach ( $options as $key => $position ) {
        //tse_plugin_debug_var( $key, 'Key: ' );
        //tse_plugin_debug_var( $position, 'Position: ' );
        $checked = checked(($key == $value), true, false);
        //$checked = checked((in_array($key, array_keys($values))), true, false);
        //$checked = checked (($title == $values[$key]), true, false);

        //tse_plugin_debug_var($checked, 'Checked: ');


        $iterator ++;
        $options_markup .= sprintf(
            '<label for="%2$s_%6$s"><input id="%2$s_%6$s" name="%1$s[%2$s]" type="radio" value="%3$s" %4$s /> %5$s  %7$s</label><br/>', 
           
            $args['option_name'],
            $args['name'],
            $key,
            $checked,
            $position,
            $iterator,
            $args['description'][$key]
        );
            
           
    }
            printf( '<fieldset>%s</fieldset>', $options_markup );

    

    //tse_plugin_debug_var( $value, 'Value: ');
}

function tse_plugin_render_section_4()
{
    print '<p>Trying to extend learning</p>';
}


function tse_plugin_render_section_4_field_1( $args )
{   

    $options_markup = '';
    $attributes = '';
    $values = $args['value'];
    $options = $args['options'];
    tse_plugin_debug_var( $options, 'Options: ' );
    tse_plugin_debug_var( $values, 'Values: ' );

   
    
    foreach ( $options as $key => $title ) {
        //tse_plugin_debug_var( $key, 'Key: ' );
        //tse_plugin_debug_var( $title, 'Title: ' );
        $selected = selected((in_array($key, $values)),  true, false);
        //$checked = checked((in_array($key, array_keys($values))), true, false);
        //$checked = checked (($title == $values[$key]), true, false);

        //tse_plugin_debug_var($selected, 'Selected: ');


        $iterator ++;
        $options_markup .= sprintf(
            '<option value="%1$s" %2$s>%3$s</option>',  
           
            $key,
            $selected,
            $title
            
    );

        $attributes = ' multiple="multiple" ';
            
           
    }
            //printf( '<fieldset>%s</fieldset>', $options_markup );
            printf( '<select name="%1$s[%2$s][]" id="%1$s" %3$s>%4$s</select>', $args['option_name'], $args['name'], $attributes, $options_markup );
    

    //tse_plugin_debug_var( $values, 'Values: ');
}

// For debugging rendering

function tse_plugin_debug_var( $var, $before = '' )
{
    $export = esc_html( var_export( $var, TRUE ) );
    print "<pre>$before = $export</pre>";
}

/* ----------------------------------------------------------------------------------------------------------------------
 * VALIDATE THE DATA
 * ----------------------------------------------------------------------------------------------------------------------
 */

function tse_plugin_validate_option( $values )
{
    $default_values = array (
        'number' => 500,
        'color'  => 'blue',
        'long'   => ''
    );

    if ( ! is_array( $values ) ) // some bogus data
        return $default_values;

    $out = array ();

    foreach ( $default_values as $key => $value )
    {
        if ( empty ( $values[ $key ] ) )
        {
            $out[ $key ] = $value;
        }
        else
        {
            if ( 'number' === $key )
            {
                if ( 0 > $values[ $key ] )
                    add_settings_error(
                        'tse_plugin_option_group',
                        'number-too-low',
                        'Number must be between 1 and 1000.'
                    );
                elseif ( 1000 < $values[ $key ] )
                    add_settings_error(
                        'tse_plugin_option_group',
                        'number-too-high',
                        'Number must be between 1 and 1000.'
                    );
                else
                    $out[ $key ] = $values[ $key ];
            }
            elseif ( 'long' === $key )
            {
                $out[ $key ] = trim( $values[ $key ] );
            }
            else
            {
                $out[ $key ] = $values[ $key ];
            }
        }
    }

    return $out;
}