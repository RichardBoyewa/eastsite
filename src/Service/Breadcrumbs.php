<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Service;
use Twig_Environment;
/**
 * Description of Breadcrumbs
 *
 * @author Trey
 */
class Breadcrumbs {
    //put your code here
    private $breadcrumbs;
    private $active;
    private $twig;
    public function __construct(Twig_Environment $env)
    {
        $this->breadcrumbs = array();
        $this->twig = $env;
    }
    public function setActive(string $title)
    {
        $this->active = $title;
    }
    public function getActive()
    {
        return $this->active;
    }
    public function addBreadcrumb(string $title, string $url)
    {
        //make sure no duplicates
        foreach($this->breadcrumbs as $crumb)
        {
            foreach($crumb as $c)
                if($c == $url)
                    return;
        }
        $this->breadcrumbs[] = array($title=>$url);
    }
    private function getBreadcrumbs()
    {
        return $this->breadcrumbs;
    }
    public function setBreadcrumbs()
    {
        $this->twig->addGlobal('breadcrumbs', $this->getBreadcrumbs());
        $this->twig->addGlobal('activeBreadcrumb', $this->getActive());
    }
}
