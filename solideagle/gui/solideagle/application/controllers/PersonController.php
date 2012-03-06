<?php

class PersonController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }
    
    public function getForm()
    {
        $form = new Zend_Form();
        $form->setAction('../person/add')
             ->setMethod('post');

        // Create and configure elements:
        $firstName = $form->createElement('text', 'firstName', array('label' => 'Voornaam:'));
        $name = $form->createElement('text', 'name', array('label' => 'Familienaam:'));
        $accountUsername = $form->createElement('text', 'accountUsername', array('label' => 'Gebruikersnaam:'));
        $accountActive = $form->createElement('checkbox', 'accountActive', array('label' => 'Account actief:'));
        $accountActiveUntill = $form->createElement('text', 'accountActiveUntill', array('label' => 'Account actief tot:'));
        $accountActiveFrom = $form->createElement('text', 'accountActiveFrom', array('label' => 'Account actief vanaf:'));
        $gender = new Zend_Form_Element_Radio('gender');
        $gender->addMultiOptions(array('M' => 'Man','F' => 'Vrouw'));
        $gender->setLabel('Geslacht:');
        
        
        // Add elements to form:
        $form->addElements(array($firstName, $name, $accountUsername, $accountActive, $accountActiveUntill, $accountActiveFrom, $gender))
            // use addElement() as a factory to create 'Login' button:
            ->addElement('submit', 'add', array('label' => 'Voeg toe'));
        
        return $form;
    }

    public function addAction()
    {
        $this->view->form = $this->getForm();
        $this->render('add');
    }


}



