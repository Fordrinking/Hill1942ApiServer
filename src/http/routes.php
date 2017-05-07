<?php
return [
    ['GET', '/', 'IndexController@sayHello'],
    ['GET', '/api/upload', 'ApiController@upload'],
    ['GET', '/api/upload', 'ApiController@aroundByOpenId'],
    ['GET', '/api/upload', 'ApiController@aroundByPos']
];
