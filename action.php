<?php
/**
 *
 * @author     Szymon Olewniczak
 */

class action_plugin_randompage2 extends DokuWiki_Action_Plugin {


    public function register(Doku_Event_Handler $controller) {
        $controller->register_hook('ACTION_ACT_PREPROCESS', 'BEFORE', $this, 'do_randompage');
    }

    public function do_randompage(Doku_Event $event, $param) {
        if($event->data !== 'randompage') return;
        $event->preventDefault();

        global $conf;
        $dir = $conf['indexdir'];

        $pages = file($dir.'/page.idx');
        shuffle($pages);

        foreach ($pages as $page) {
            $page = trim($page);
            if(!page_exists($page)) continue;
            if(isHiddenPage($page)) continue;
            if (auth_quickaclcheck($page)) {
                send_redirect(wl(
                    $page,
                    [
                        'utm_source' => 'internal',
                        'utm_medium' => 'referral',
                        'utm_content' => 'randompage',
                    ],
                    true,
                    '&'
                ));
            }
        }
    }
}
