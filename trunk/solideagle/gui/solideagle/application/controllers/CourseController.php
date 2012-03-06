<?php

require_once '/data_access/Course.php';
use DataAccess\Course;

class CourseController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }
    
    public function getForm()
    {       
            $form = new Zend_Form();
            $form->setAction('../course/add')
                ->setMethod('post');

            // Create and configure courseName element:
            $courseName = $form->createElement('text', 'courseName', array('label' => 'Naam vak:'));
//            $courseName->addValidator('alnum')
//                    ->addValidator('regex', false, array('/^[a-z]+/'))
//                    ->addValidator('stringLength', false, array(1, 46))
//                    ->addErrorMessage("vul de naam van het vak in")
//                    ->setRequired(true);

            // Add elements to form:
            $form->addElement($courseName)
                // use addElement() as a factory to create 'Login' button:
                ->addElement('submit', 'add', array('label' => 'Voeg toe'));
            return $form;
       
    }

    public function indexAction()
    {
        $this->view->form = $this->getForm();
        $this->render('add');
    }

    public function addAction()
    {
        if (!$this->getRequest()->isPost()) {
            return $this->_forward('index');
        }
        $form = $this->getForm();
        if (!$form->isValid($_POST)) {
            // Failed validation; redisplay form
            $this->view->form = $form;
            return $this->render('add');
        }
 
        $values = $form->getValues();
        
        // add course
        $course = new Course();
        $course->setName($values['courseName']);
        $validationErrors = Course::validateCourse($course);
        if (sizeof($validationErrors) == 0)
        {
            Course::addCourse($course);
            echo "Vak toegevoegd.";
        }
        else {
            $this->view->validationErrors = $validationErrors[0];
            $this->view->form = $form;
            return $this->render('add');
            
        }
        
        
    }


}



