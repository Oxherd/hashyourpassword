<?php

class Console
{
    public function log(String $string){
        echo PHP_EOL . $string . PHP_EOL;
    }
}

class PasswordGenerator
{
    protected $password = null;
    protected $verify = null;
    protected $hashedPassword = null;

    public function ask(){
        $this->password = readline('Enter your password: ');

        if(!ctype_alnum($this->password)){
            throw new ErrorException("\e[1;31mPassword can only contain alphanumeric.\e[0m");
        }

        $this->hashedPassword = password_hash($this->password, PASSWORD_BCRYPT);
    }

    public function verify(){
        $this->verify = readline('Please enter your password again: ');

        if(!password_verify($this->verify, $this->hashedPassword)){
            throw new ErrorException("\e[1;31mVerify password failure. Please check the password you entered.\e[0m");
        }
    }

    public function get(){
        return $this->hashedPassword;
    }
}

class FlowManager
{
    protected $console = null;
    protected $password = null;

    public function __construct(){
        $this->console = new Console();
        $this->password = new PasswordGenerator();
    }

    public function main(){
        do{
            try{
                $this->password->ask();

                $this->password->verify();

                $this->console->log('Your hashed password is: ');

                $this->console->log($this->password->get());

                break;
            }catch(Exception $e){
                $this->console->log($e->getMessage() . PHP_EOL);
            }
        }while(1);
    }
}

$manager = new FlowManager();

$manager->main();