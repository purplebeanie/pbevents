<?php
/**
* @package      PurpleBeanie.PBBooking
* @license      GNU General Public License version 2 or later; see LICENSE.txt
* @link     http://www.purplebeanie.com
*/
 
// No direct access

defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport('joomla.application.component.controlleradmin');
jimport('joomla.html.pagination');
jimport('joomla.application.input');
 


class PbeventsController extends JControllerLegacy
{
    /**
     * Method to display the view
     *
     * @access    public
     */
    function display()
    {   
        JToolBarHelper::title( JText::_( 'PBEvents Manager' ), 'generic.png' );

        $view = $this->getView('pbevents','html');
        $view->setLayout('default');

        //load in the dashboard variables
        $db = &JFactory::getDbo();
        $config = JFactory::getConfig();
        $query = $db->getQuery(true);
        $query->select('#__pbevents_rsvps.*,#__pbevents_events.event_name,#__pbevents_events.fields')->from('#__pbevents_rsvps')->join('left','#__pbevents_events on #__pbevents_rsvps.event_id = #__pbevents_events.id')->order('#__pbevents_rsvps.id DESC')->limit(10);
        $view->rsvps = $db->setQuery($query)->loadObjectList();
        $query = $db->getQuery(true);
        $query->select('#__pbevents_events.*')->from('#__pbevents_events')->where('dtstart>= "'.date_create("now",new DateTimeZone($config->get('offset')))->format(DATE_ATOM).'" and publish =1');
        $view->events = $db->setQuery($query)->loadObjectList();

        //get the latest announcemenets into the view
        $view->announcements = $this->_load_announcements();

        $view->display();


    }
    
    
    /**
     * Method to list all the events in the database
     * @todo implement proper filtering and pagination on listed events
     * 
     */
    public function listevents()
    {
        JToolBarHelper::title( JText::_( 'COM_PBEVENTS_EVENTS_MANAGER' ).' '.JText::_('COM_PBEVENTS_ADMIN_LIST_EVENTS'), 'generic.png' );
        JToolBarHelper::addNew('add');

        //get offset if needed
        $input = JFactory::getApplication()->input;
        $limit = $input->get('limit',20,'integer');
        $limitstart = $input->get('limitstart',0,'integer');
        $filter_published = $input->get('filter_published',null,'string');

        $db = &JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*')->from('#__pbevents_events');
        $query->order('dtstart DESC');

        if (isset($filter_published) && $filter_published != '*')
            $query->where('publish = '.(int)$filter_published);

        $events = $db->setQuery($query)->loadObjectList();

        //get attendees for events and set date to the right timezone!
        $config =&JFactory::getConfig();
        foreach ($events as $event) {
            $query = $db->getQuery(true);
            $query->select('*')->from('#__pbevents_rsvps')->where('event_id = '.(int)$event->id);
            $attendees = $db->setQuery($query)->loadObjectList();
            $event->attendees = $attendees;
            $event->dtstart = new DateTime($event->dtstart,new DateTimeZone($config->get('offset')));
            $event->dtend = new DateTime($event->dtend,new DateTimeZone($config->get('offset')));
        }

        $total_records = count($events);
        if ($limit>0) {
            $events = array_slice($events,$limitstart,$limit);
        }
        

        $view = $this->getView('pbevents','html');
        $view->setLayout('listevents');
        $view->events = $events;
        $view->pagination = new JPagination($total_records,$limitstart,$limit);
        $view->filter_published = $filter_published;

        $view->display();
    }

    /**
    * Method to render the form to add a new event
    */

    public function add()
    {
        JToolBarHelper::title( JText::_( 'COM_PBEVENTS_EVENTS_MANAGER' ).' '.JText::_('COM_PBEVENTS_CREATE_EVENT'), 'generic.png' );
        JToolBarHelper::save('save');

        $db = &JFactory::getDbo();

        $view = $this->getView('pbevents','html');
        $view->setLayout('create');
        $view->config = $db->setQuery('select * from #__pbevents_config where id=1')->loadObject();

        $view->display();

    }

    /**
    * Method to edit an existing event in teh database
    */

    public function editevent()
    {
        JToolBarHelper::title( JText::_( 'COM_PBEVENTS_EVENTS_MANAGER' ).' '.JText::_('COM_PBEVENTS_EDIT_EVENT'), 'generic.png' );
        JToolBarHelper::save('save');

        $view = $this->getView('pbevents','html');
        $view->setLayout('create');

        $input = JFactory::getApplication()->input;
        $event_id = $input->get('id',0,'integer');
        if ($event_id) {
            $db = &JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('*')->from('#__pbevents_events')->where('id = '.(int)$event_id);
            $view->event = $db->setQuery($query)->loadObject();
        }
        $view->config = $db->setQuery('select * from #__pbevents_config where id=1')->loadObject();
        $view->display();
    }


    /**
    * Method to save the new event to the database
    */

    public function save()
    {
        $input= JFactory::getApplication()->input;

        $fields = $this->_process_custom_fields();

        $event = new JObject(array('event_name'=>$input->get('event_name',null,'string'),'description'=>$input->get('description',null,'string'),
                                    'fields'=>json_encode($fields),'max_people'=>$input->get('max_people',null,'string'),
                                    'dtend'=>date_create($input->get('dtend',null,'string'))->format(DATE_ATOM),
                                    'dtstart'=>date_create($input->get('dtstart',null,'string'))->format(DATE_ATOM),
                                    'confirmation_page'=>$input->get('confirmation_page',null,'string'),
                                    'failed_page'=>$input->get('failed_page',null,'string'),
                                    'email_admin_success'=>$input->get('email_admin_success',0,'integer'),'email_admin_failure'=>$input->get('email_admin_failure',0,'integer'),
                                    'send_notifications_to'=>$input->get('send_notifications_to',null,'string'),
                                    'show_counter'=>$input->get('show_counter',0,'integer'),
                                    'show_attendees'=>$input->get('show_attendees',0,'integer')
                                    ));
        $db = JFactory::getDbo();

        if ($input->get('id',null,'integer')>0) {
            //update the event
            $event->id = $input->get('id',null,'integer');
            if ($db->updateObject('#__pbevents_events',$event,'id')) {
                $this->setRedirect(JURI::root(false).'administrator/index.php?option=com_pbevents&task=listevents',JText::_('COM_PBEVENTS_EVENT_UPDATE_SUCCESFUL'));
            } else {
                echo $db->getErrorMsg();
            }
        } else {
            //insert the event....
            if ($db->insertObject('#__pbevents_events',$event)) {
                $this->setRedirect(JURI::root(false).'administrator/index.php?option=com_pbevents&task=listevents',JText::_('COM_PBEVENTS_EVENT_CREATE_SUCCESFUL'));
            }
        }
    }

    /**
    * gets all attendees for an event and displays along with all custom fields
    */

    public function viewattendees()
    {
        JToolBarHelper::title(JText::_('COM_PBEVENTS_EVENTS_MANAGER').' '.JText::_('COM_PBEVENTS_ATTENDEES'),'generic.png');
        JToolBarHelper::deleteList('','deleteattendee');
        
        $input = JFactory::getApplication()->input;
        $event_id = $input->get('id',null,'integer');
        $limit= $input->get('limit',20,'integer');
        $limitstart= $input->get('limitstart',0,'integer');

        //load the view and stuff in params
        $view = $this->getView('pbevents','html');
        $view->setLayout('viewattendees');

        if ($event_id) {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('*')->from('#__pbevents_events')->where('id = '.$db->escape($event_id));
            $view->event = $db->setQuery($query)->loadObject();
            if ($view->event) {
                $query = $db->getQuery(true);
                $query->select('*')->from('#__pbevents_rsvps')->where('event_id = '.$db->escape($view->event->id));
                $view->attendees = $db->setQuery($query)->loadObjectList();
                $total_records = count($view->attendees);
                if ($limit>0)
                    $view->attendees = array_slice($view->attendees,$limitstart,$limit);

                //display the view
                $view->pagination = new JPagination($total_records,$limitstart,$limit);
                $view->display();
            } else {
                $this->setRedirect(JURI::root(false).'administrator/index.php?option=com_pbevents&task=listevents',JText::_('COM_PBEVENT_INVALID_EVENT'));
            }
        } else {
            $this->setRedirect(JURI::root(false).'administrator/index.php?option=com_pbevents&task=listevents',JText::_('COM_PBEVENT_NO_ID'));
        }
    }

    /**
    * deletes attendees / attendee from an event and redirects to the list page
    */

    public function deleteattendee()
    {
        $input = JFactory::getApplication()->input;
        $cid = $input->get('cid',0,'array');
        $event_id = $input->get('event_id',0,'integer');

        if ($cid) {
            //process the delete attendes
            $db = &JFactory::getDbo();
            foreach ($cid as $id) {
                $query = $db->getQuery(true);
                $query->delete('#__pbevents_rsvps')->where('id = '.$db->getEscaped($id));
                $db->setQuery($query);
                $db->query();
            }
        }
        $this->setRedirect(JURI::root(false).'administrator/index.php?option=com_pbevents&task=viewattendees&id='.(int)$event_id);
    }

    /**
    * allows user to display and edit the master configuration
    */

    public function editconfiguration()
    {
        JToolBarHelper::title(JText::_('COM_PBEVENTS_EVENTS_MANAGER').' '.JText::_('COM_PBEVENTS_CONFIGURATION'),'generic.png');
        JToolBarHelper::save('editconfiguration');

        $db = &JFactory::getDbo();

        if ($_SERVER['REQUEST_METHOD'] == "GET") {
            $view= $this->getView('pbevents','html');

            $query = $db->getQuery(true);
            $query->select('*')->from('#__pbevents_config')->where('id = 1');
            $view->config = $db->setQuery($query)->loadObject();

            $view->setLayout('config');
            $view->display();
        } else {
            $input = JFactory::getApplication()->input;
            $email_success_body = $_POST['email_success_body'];
            $email_failed_body = $_POST['email_failed_body'];

            $email_success_body = preg_replace(array('/\n+/','/\r+/'),'',$email_success_body);
            $email_failed_body = preg_replace(array('/\n+/','/\r+/'),'',$email_failed_body);

            $config = new JObject(array('email_failed_subject'=>$input->get('email_failed_subject',null,'string'),
                                        'email_failed_body'=>$db->getEscaped($email_failed_body),
                                        'email_success_subject'=>$input->get('email_success_subject',null,'string'),
                                        'email_success_body'=>$db->getEscaped($email_success_body),
                                        'id'=>$input->get('id',0,'integer'),
                                        'date_picker_locale'=>$input->get('date_picker_locale','en-US','string')));
            $db->updateObject('#__pbevents_config',$config,'id'); 
            $this->setRedirect(JURI::root(false).'administrator/index.php?option=com_pbevents',Jtext::_('COM_PBEVENTS_CONFIG_UPDATE'));
        }
    }


    /**
    * responds to the publish function
    */

    public function publish()
    {
        echo print_r($_REQUEST);
        $db = &JFactory::getDbo();
        $input = &JFactory::getApplication()->input;

        $cids = $input->get('cid',null,'array');
        foreach ($cids as $cid)
            $db->updateObject('#__pbevents_events',new JObject(array('id'=>(int)$cid,'publish'=>1)),'id');
        $this->setRedirect(JURI::root(false).'administrator/index.php?option=com_pbevents&task=listevents');
    }

    /**
    * responds to the unpublish function
    */

    public function unpublish()
    {
        $db = &JFactory::getDbo();
        $input = &JFactory::getApplication()->input;

        $cids = $input->get('cid',null,'array');
        foreach ($cids as $cid)
            $db->updateObject('#__pbevents_events', new JObject(array('id'=>(int)$cid,'publish'=>0)),'id');
        $this->setRedirect(JURI::root(false).'administrator/index.php?option=com_pbevents&task=listevents');

    }

    /**
    * responds to the cancel function
    * @access public
    * @since 0.2
    */

    public function cancel()
    {
        $this->setRedirect(JURI::root(false).'administrator/index.php?option=com_pbevents');
    }

    /**
    * processes the fields from the create / edit actions and returns the fields array
    * @return array
    */

    private function _process_custom_fields()
    {
        $input = JFactory::getApplication()->input;

        //process the fields
        $fields = array();
        $labels = $input->get('label',null,'array');
        $vars = $input->get('var',null,'array');
        $types = $input->get('type',null,'array');
        $values = $input->get('values',null,'array');
        $required = $input->get('required',null,'array');
        $is_email = $input->get('is_email',null,'array');
        $display_in_list = $input->get('display_in_list',null,'array');
        $ordering = $input->get('ordering',null,'array');

        for ($i=0;$i<count($labels);$i++) {
            $fields[] = array('label'=>$labels[$i],'var'=>$vars[$i],'type'=>$types[$i],'values'=>$values[$i],'required'=>$required[$i],'is_email'=>$is_email[$i],'display_in_list'=>$display_in_list[$i],'ordering'=>$ordering[$i]);
        }

        usort($fields, function($a, $b) {
            if ($a['ordering'] == $b['ordering'])
                return 0;
            else
                return ($a['ordering'] < $b['ordering'] ? -1 : 1); 
        });

        return $fields;

    }


    /**
    * loads the latest announcments of the purplebeanie.com website for display in the dashboard
    * @access private
    * @since 0.2
    */

    private function _load_announcements()
    {
        $announce_url = "http://www.purplebeanie.com/Announcements/feed/rss.html";

        $parser = &JFactory::getFeedParser($announce_url);

        if (!$parser)
            return array();

        if (count($parser->get_items()>5))
            return array_slice($parser->get_items(),0,5);
        else 
            return $parser->get_items();
    }
}
