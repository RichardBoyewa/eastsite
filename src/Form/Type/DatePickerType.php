<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
/**
 * Description of DatePickerType
 *
 * @author Trey
 */
class DatePickerType extends AbstractType {
    //put your code here
    private $twig;
    private $dispatcher;
    
    public function __construct() {
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'twig'=>null,
            'dispatcher'=>null,
            'id'=>null,
            'defaultDate'=> new \DateTime(),
        ));
    }
    
    public function getBlockPrefix() {
        return 'ckeditor';
        //parent::getBlockPrefix();
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $this->twig = $options['twig'];
        $this->dispatcher = $options['dispatcher'];
        parent::buildForm($builder, $options);
    }
    
    public function buildView(FormView $view, FormInterface $form, array $options) {
        //parent::buildView($view, $form, $options);
        $javascriptContent = $this->twig->render('form/type/DatePicker/main.js.twig', array(
            'id'=>$options['id'],
            'defaultDate'=> date_format($options['defaultDate'],'m/d/Y H:i a'),
        ));

        $cssContent = $this->twig->render('form/type/DatePicker/main.css.twig',array());

        $this->dispatcher->addListener('kernel.response', function($event) use ($javascriptContent, $cssContent) {
            $response = $event->getResponse();
            $content = $response->getContent();
            // finding position of </body> tag to add content before the end of the tag
            $pos = strripos($content, '</body>');
            $content = substr($content, 0, $pos).$javascriptContent.substr($content, $pos);

            $pos = strripos($content, '</head>');
            $content = substr($content, 0, $pos).$cssContent.substr($content, $pos);

            $response->setContent($content);
            $event->setResponse($response);
        });
        parent::buildView($view, $form, $options);
    }
       
   


    public function getParent()
    {
        return DateTimeType::class;
    }
}
