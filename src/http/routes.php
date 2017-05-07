<?php
return [
    ['GET', '/', 'IndexController@sayHello'],
    ['GET', '/api/upload', 'ApiController@upload'],
    ['GET', '/api/aroundByOpenId', 'ApiController@aroundByOpenId'],
    ['GET', '/api/aroundByPos', 'ApiController@aroundByPos']
];
