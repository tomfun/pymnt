/**
 * Created by tomfun on 08.09.14.
 */
define('main', ['jquery'], function ($) {
        console.log($);
    }
);
require(['main'], function(m){
    console.log(111);
});