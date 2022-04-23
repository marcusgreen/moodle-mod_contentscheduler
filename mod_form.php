<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * The main mod_contentscheduler configuration form.
 *
 * @package     mod_contentscheduler
 * @copyright   2022 Marcus Green
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/course/moodleform_mod.php');

/**
 * Module instance settings form.
 *
 * @package     mod_contentscheduler
 * @copyright   2022 Marcus Greebn
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_contentscheduler_mod_form extends moodleform_mod {
    /** @var array options to be used with date_time_selector fields in the quiz. */
    public static $datefieldoptions = array('optional' => false);

    /**
     * Defines forms elements
     */
    public function definition() {
        global $CFG,$DB,$OUTPUT;
        $courseid = required_param('course',PARAM_INT);
        require_once($CFG->dirroot . '/course/externallib.php');

        $options = array(array("name" => "modname", "value" => "quiz"));

        $contents = \core_course_external::get_course_contents($courseid, $options);

        $mform = $this->_form;

        // Adding the "general" fieldset, where all the common settings are shown.
        $mform->addElement('header', 'general', get_string('general', 'form'));

        // Adding the standard "name" field.
        $mform->addElement('text', 'name', get_string('name', 'mod_contentscheduler'), array('size' => '64'));

        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addRule('name', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');
        $mform->addHelpButton('name', 'contentschedulername', 'mod_contentscheduler');

        $mform->addElement('header', 'timing', get_string('timing', 'quiz'));
        $mform->setExpanded('timing');

        // Start dates.
        $mform->addElement('date_time_selector', 'start', get_string('start', 'contentscheduler'),
            self::$datefieldoptions);
         $mform->addHelpButton('start', 'start', 'contentscheduler');

       $selectnumbers = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20];
        $group[] = $mform->createElement('select', 'repeat', get_string('repeat','contentscheduler'), $selectnumbers);
        $group[] = $mform->createElement('html',get_string('weeks','contentscheduler'));
        $group[] = $mform->createElement('checkbox', 'repeatenable',get_string('repeatenable','contentscheduler'));
        $mform->addGroup($group,'repeat',get_string('repeat','contentscheduler').'&nbsp;&nbsp;');
        $mform->addHelpButton('repeat', 'repeat', 'mod_contentscheduler');


        $group = [];
        $group[] = $mform->createElement('select','numberofsessions',get_string('numberofsessions','contentscheduler'),$selectnumbers);
        $group[] = $mform->createElement('checkbox', 'numberofsessionsenable',get_string('numberofsessionsenable','contentscheduler'));
        $mform->addGroup($group,'numberofsessions',get_string('numberofsessions','contentscheduler'));
        $mform->addHelpButton('numberofsessions', 'numberofsessions', 'mod_contentscheduler');

       $mform->addElement('date_time_selector', 'timefinish', get_string('finish', 'contentscheduler'),
               self::$datefieldoptions);

        $mform->addHelpButton('timefinish', 'timefinish', 'mod_contentscheduler');

       $mform->addElement('select','quzzespersession',get_string('quizzespersession','contentscheduler'),$selectnumbers);
       $mform->addHelpButton('quzzespersession', 'quzzespersession', 'mod_contentscheduler');

       $mform->addElement('header', 'activities', get_string('activities', 'mod_contentscheduler'));
       $mform->setExpanded('activities');
       $data = [];
       foreach($contents as $content) {
           if(count($content['modules']) > 0){
               foreach($content['modules'] as $module) {
                    $details = $DB->get_record('quiz',['id' => $module['instance']]);
                    $module['intro'] = strip_tags($details->intro);
                    $data['activities'][] = $module;
               }
           }
       }
       $out =  $OUTPUT->render_from_template('mod_contentscheduler/activities', $data);
       $group =[];
       $group[] =  $mform->createElement('html',$out);
       $mform->addGroup($group);

       // Add standard elements.
         $this->standard_coursemodule_elements();

        // Add standard buttons.
         $this->add_action_buttons();
    }
}
