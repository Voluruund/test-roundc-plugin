<?php

class test extends rcube_plugin
{

    public $task = 'settings';

    function init()
    {
        $this->rc = rcmail::get_instance();

        //load configuration and localization
        $this->load_config();
        $this->add_texts('localization/', true);

        //add button above settings in the taskbar section
        // $this->add_button([
        //     'command' => 'plugin.simpleplugin',
        //     'type' => 'link',
        //     'class' => 'button simpleplugin-button',
        //     'label' => $this->gettext('simpleplugin_title'),
        //     'title' => 'simpleplugin_title',
        //     'id' => 'simpleplugin-button',
        // ], 'taskbar');

        $this->add_hook('settings_actions', [$this, 'settings_actions']);
        $this->register_action('plugin.test', array($this, 'display'));
        $this->register_action('plugin.test_submit', array($this, 'test_submit'));

        //includes
        $this->include_script('client.js');
        $this->include_stylesheet('test.css');

    }

    // Function to display the settings page
    function display()
    {
        $rcmail = rcmail::get_instance();
        $rcmail->output->set_pagetitle($this->gettext('threeinputs'));  // Set the page title
        $this->register_handler('plugin.body', array($this, 'render_form'));  // Register a handler to render the form
        $rcmail->output->send('plugin');  // Send the plugin's output
    }

    function render_form($args)
    {
        $rcmail = rcmail::get_instance();
        //element creation
        $submit_button = $rcmail->output->button([
            'command' => 'plugin.test_submit',
            'class'   => 'button mainaction submit btn btn-primary custom-btn',
            'label'   => $this->gettext('submit'),
        ]);
        $form_buttons = html::p(['class' => 'formbuttons footerleft'], $submit_button);

        //session checked or default values
        $input_value1 = isset($_SESSION['input1']) ? $_SESSION['input1'] : '';
        $input_value2 = isset($_SESSION['input2']) ? $_SESSION['input2'] : '';
        $input_value3 = isset($_SESSION['input3']) ? $_SESSION['input3'] : '';

        //html append
        $html = '';
        $html .= html::tag('h1', array('id' => 'main-title', 'class' => 'main-title'), $this->gettext('title'));
        $html .= html::tag('form', array(
            'id' => 'test_form',
            'method' => 'post',
            'action' => '?_task=settings&_action=plugin.test_submit',
            ),
                html::div(
                    array('class' => 'form-section form-test'),
                    html::label('input1', $this->gettext('input1')) .
                    html::tag('input', array('type' => 'text', 'name' => 'input1', 'id' => 'input1', 'class' => 'form-control', 'value' => $input_value1))
                ) .
                html::div(
                    array('class' => 'form-section form-test'),
                    html::label('input2', $this->gettext('input2')) .
                    html::tag('input', array('type' => 'text', 'name' => 'input2', 'id' => 'input2', 'class' => 'form-control', 'value' => $input_value2))
                ) . 
                html::div(
                    array('class' => 'form-section form-test'),
                    html::label('input3', $this->gettext('input3')) .
                    html::tag('input', array('type' => 'text', 'name' => 'input3', 'id' => 'input3', 'class' => 'form-control','value' => $input_value3))
                ) . 
                html::tag('input', array(
                    'type' => 'submit',
                    'value' => $this->gettext('submit'),
                    'class'   => 'button mainaction submit btn btn-primary custom-btn',
                    'id' => 'submit_button',
                    'disabled' => 'disabled'
                ), null, false)
            , null);

        return $html;
    }

    function settings_actions($args)
    {
        // register as settings action
        $args['actions'][] = [
            'action' => 'plugin.test',
            'class' => 'test',
            'label' => 'title_label',
            'domain' => 'test',
        ];

        return $args;
    }

    function test_submit()
    {
        $rcmail = rcmail::get_instance();

        // Get the database handle
        $db = $rcmail->get_dbh();
    
        // Get the current user ID (if needed)
        $user_id = $rcmail->user->ID;
    
        // Retrieve the submitted value
        $input_value1 = rcube_utils::get_input_value('input1', rcube_utils::INPUT_POST, true);
        $input_value2 = rcube_utils::get_input_value('input2', rcube_utils::INPUT_POST, true);
        $input_value3 = rcube_utils::get_input_value('input3', rcube_utils::INPUT_POST, true);
    
        if ($input_value1 && $input_value2 && $input_value3) {
            // Prepare the SQL query to insert the data
            $sql = "INSERT INTO plugin_data (user_id, input_first, input_second, input_third) VALUES (?, ?, ?, ?)";
    
            // Execute the query using Roundcube's database API
            $db->query($sql, $user_id, $input_value1, $input_value2, $input_value3);
    
            // Provide feedback to the user
            $rcmail->output->command('display_message', 'Data saved successfully', 'confirmation');
        } else {
            $rcmail->output->command('display_message', 'Input is required', 'error');
        }
    
        $rcmail->output->send('plugin');
    }

}

