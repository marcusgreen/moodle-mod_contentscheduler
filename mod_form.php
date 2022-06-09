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

require_once($CFG->dirroot . '/course/moodleform_mod.php');

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
        global $CFG,$COURSE, $PAGE, $OUTPUT,$DB;


        $update = optional_param('update', false, PARAM_INT);

        $cm = $DB->get_record('course_modules',['id' => $update]);

        $timing = $DB->get_record('contentscheduler_timing',['id' => $cm->instance]);


        $PAGE->requires->js_call_amd('mod_contentscheduler/modform', 'init');

        require_once($CFG->dirroot . '/course/externallib.php');

        // $activitytype = 'page';
        $activitytype = 'quiz';

        $options = [["name" => "modname", "value" => $activitytype]];
        $contents = get_contents($COURSE->id, $options);

        $mform = $this->_form;


        // Adding the "general" fieldset, where all the common settings are shown.
        $mform->addElement('header', 'general', get_string('general', 'form'));

        // Adding the standard "name" field.
        $mform->addElement('text', 'name', get_string('name', 'contentscheduler'), ['size' => '64']);
        $mform->setType('name',PARAM_TEXT);
        $mform->setDefault('name', 'Content Schedule');

        $this->standard_intro_elements();


        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addRule('name', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');
        $mform->addHelpButton('name', 'contentschedulername', 'mod_contentscheduler');

        $mform->addElement('header', 'timing', get_string('timing', 'contentscheduler'));
        $mform->setExpanded('timing');

        // Start dates.
        $mform->addElement(
            'date_time_selector',
            'timestart',
            get_string('start', 'contentscheduler'),
            self::$datefieldoptions
        );
        $mform->setDefault('timestart',$timing->timestart);
        $mform->addHelpButton('timestart', 'start', 'contentscheduler');

        $group[] = $mform->createElement('text', 'repeat', get_string('repeat', 'contentscheduler'), ['value' => 1, 'size' => '3']);
        $group[] = $mform->createElement('html', get_string('weeks', 'contentscheduler'));
        $group[] = $mform->createElement('advcheckbox', 'repeatenable', get_string('repeatenable', 'contentscheduler'));
        $mform->addGroup($group, 'repeatgroup', get_string('repeat', 'contentscheduler') . '&nbsp;&nbsp;','', ' ', true);
        $mform->setType('repeatgroup', PARAM_RAW);
        $mform->addHelpButton('repeatgroup', 'repeat', 'mod_contentscheduler');

        $group = [];
        $group[] = $mform->createElement('text', 'sessioncount', get_string('sessioncount', 'contentscheduler'), ['value' => 7, 'size' => '3']);
        $group[] = $mform->createElement('advcheckbox', 'sessioncountenable', get_string('sessioncountenable', 'contentscheduler'));
        $mform->addGroup($group, 'sessionsgroup', get_string('sessioncount', 'contentscheduler'));
        $mform->setType('sessionsgroup', PARAM_RAW);
        $mform->addHelpButton('sessionsgroup', 'sessioncount', 'mod_contentscheduler');

        // Finish dates.
        $mform->addElement(
            'date_time_selector',
            'timefinish',
            get_string('timefinish', 'contentscheduler'),
            self::$datefieldoptions
        );
        $mform->setType('timefinish', PARAM_INT);

        $week = strtotime('7 day', 0);
        $weekcount = get_config('contentscheduler', 'weekcount');
        $finishdate = time()+ ($week * $weekcount);
        $mform->setDefault('timefinish', $finishdate);

        $mform->addHelpButton('timefinish', 'timefinish', 'mod_contentscheduler');

        $mform->addElement('text', 'activitiespersession', get_string('activitiespersession', 'contentscheduler'), ['value' => 7, 'size' => '3']);
        $mform->setType('activitiespersession',PARAM_INT);

        $mform->addHelpButton('activitiespersession', 'activitiespersession', 'mod_contentscheduler');

        $mform->addElement('header', 'activityheader', get_string('activities', 'mod_contentscheduler'));
        $mform->setExpanded('activityheader');
        $sessionstarts = [];

        foreach ($contents as $content) {
            if (count($content['modules']) > 0) {
                foreach ($content['modules'] as $module) {
                    $questions = $DB->get_records('quiz_slots',['quizid' => $module['instance']]);
                    $details = $DB->get_record($module['modname'], ['id' => $module['instance']]);
                    $availability = $DB->get_record('course_modules', ['id' => $module['instance']], 'availability');
                    $module['questioncount'] = count($questions);
                    $module['name'] = $details->name;
                    $module['intro'] = strip_tags($details->intro);
                    $module['availability']  = get_availability($module);
                    $data['activities'][] = $module;
                }
            }
        }
        $mform = show_contents($mform, $contents);
        $data['wwwroot'] = $CFG->wwwroot;
        $out =  $OUTPUT->render_from_template('mod_contentscheduler/activities', $data);
        $mform->addElement('HTML',$out);

        // Add standard elements.
        $this->standard_coursemodule_elements();

        // Add standard buttons.
        $this->add_action_buttons();
    }
}
