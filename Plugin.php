<?php
namespace MultipleLocalAuth;

use MapasCulturais\App;
use MapasCulturais\i;

class Plugin extends \MapasCulturais\Plugin {
    
    public function _init() {
        
        // register translation text domain
        i::load_textdomain( 'multipleLocal', __DIR__ . "/translations" );
        
    }

    public function register() {
        
    }
    
}
