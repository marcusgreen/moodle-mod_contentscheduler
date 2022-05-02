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
        global $CFG, $DB, $COURSE, $PAGE;

        $PAGE->requires->js_call_amd('mod_contentscheduler/modform', 'init');

        require_once($CFG->dirroot . '/course/externallib.php');

        $activity = 'quiz';

        $options = [["name" => "modname", "value" => $activity]];

        $contents = \core_course_external::get_course_contents($COURSE->id, $options);

        $mform = $this->_form;

        // Adding the "general" fieldset, where all the common settings are shown.
        $mform->addElement('header', 'general', get_string('general', 'form'));

        // Adding the standard "name" field.
        $mform->addElement('text', 'name', get_string('name', 'contentscheduler'), ['size' => '64']);

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
        $mform->addHelpButton('start', 'start', 'contentscheduler');

        $group[] = $mform->createElement('text', 'repeat', get_string('repeat', 'contentscheduler'), ['value' => 1, 'size' => '3']);
        $group[] = $mform->createElement('html', get_string('weeks', 'contentscheduler'));
        $group[] = $mform->createElement('advcheckbox', 'repeatenable', get_string('repeatenable', 'contentscheduler'));
        $mform->addGroup($group, 'repeatgroup', get_string('repeat', 'contentscheduler') . '&nbsp;&nbsp;');
        $mform->addHelpButton('repeat', 'repeat', 'mod_contentscheduler');

        $group = [];
        $group[] = $mform->createElement('text', 'numberofsessions', get_string('numberofsessions', 'contentscheduler'), ['value' => 7, 'size' => '3']);
        $group[] = $mform->createElement('advcheckbox', 'numberofsessionsenable', get_string('numberofsessionsenable', 'contentscheduler'));
        $mform->addGroup($group, 'sessionsgroup', get_string('numberofsessions', 'contentscheduler'));
        $mform->addHelpButton('sessionsgroup', 'numberofsessions', 'mod_contentscheduler');

        // Finish dates.
        $mform->addElement(
            'date_time_selector',
            'timefinish',
            get_string('finish', 'contentscheduler'),
            self::$datefieldoptions
        );
        $week = strtotime('7 day', 0);
        $weekcount = get_config('contentscheduler', 'weekcount');
        $finishdate = time()+ ($week * $weekcount);
        $mform->setDefault('timefinish', $finishdate);

        $mform->addHelpButton('timefinish', 'timefinish', 'mod_contentscheduler');

        $mform->addElement('text', 'activitiespersession', get_string('activitiespersession', 'contentscheduler'), ['value' => 7, 'size' => '3']);
        $mform->addHelpButton('quzzespersession', 'quzzespersession', 'mod_contentscheduler');

        $mform->addElement('header', 'activityheader', get_string('activities', 'mod_contentscheduler'));
        $mform->setExpanded('activityheader');
        $mform->addElement('checkbox','selectall','Select all');
        $data = [];
        foreach ($contents as $content) {
            if (count($content['modules']) > 0) {
                foreach ($content['modules'] as $module) {
                    $details = $DB->get_record('quiz', ['id' => $module['instance']]);
                    $module['intro'] = strip_tags($details->intro);
                    $group = [];
                    $group[$module['id']] =  $mform->createElement('checkbox', $module['id'], $module['name'], $module['intro']);
                    $mform->addGroup($group, 'activities');
                }
            }
        }


        // Add standard elements.
        $this->standard_coursemodule_elements();

        // Add standard buttons.
        $this->add_action_buttons();
    }
}
