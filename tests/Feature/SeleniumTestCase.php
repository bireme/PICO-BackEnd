<?php

namespace Tests\Feature;

class SeleniumTestCase extends PHPUnit_Extensions_Selenium2TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
        protected function setUp()
    {
        $this->setBrowser('firefox');
        $this->setBrowserUrl('http://localhost:8000/');
    }

        protected function visit($path)
    {
        $this->url($path);
        return $this;
    }

        protected function see($text, $tag = 'body')
    {
        print_r(request()->session()->all());
        //method call by tag name;
        $this->assertContains($text,$this->byTag($tag)->text());
        return $this;
    }

        protected function pressByName($text){
        $this->byName($text)->click();
        return $this;
    }
        protected function pressByTag(){
        $this->byTag('button')->click();
        return $this;
    }
        protected function type($value, $name)
    {
        $this->byName($name)->value($value);
        return $this;
    }

        protected function hold($seconds){
        sleep($seconds);
        return $this;
    }

}
