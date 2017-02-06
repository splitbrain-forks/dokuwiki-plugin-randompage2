<?php
/**
 *
 * @author     Szymon Olewniczak
 */
 
if(!defined('DOKU_INC')) die();
 
 
class action_plugin_randompage2 extends DokuWiki_Action_Plugin {

 
    public function register(Doku_Event_Handler $controller) {
        $controller->register_hook('ACTION_ACT_PREPROCESS', 'BEFORE', $this, 'do_randompage');
    }

    public function do_randompage(Doku_Event $event, $param) {
        if($event->data !== 'randompage') return; 
        $event->preventDefault();
        $this->action_randompage($event, $args);
    }
    
    function action_randompage(Doku_Event $event, $args) {
        global $conf;

        $dir = $conf['savedir'];

        $pages = file($dir.'/index/page.idx');
        shuffle($pages);
        
        foreach ($pages as $page) {
            if (auth_quickaclcheck($page)) {
                header('Location: '.wl($page,'',true));
            }
        }
    }
}
