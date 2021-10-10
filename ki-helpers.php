<?php

ki_includer::addScheme('include_core', 'ki-core-script.sch');
ki_includer::addScheme('include_snippet', 'includer-snippet.sch', function($srcs) {
    ki_includer::setKeyMap('INCLUDE_SCRIPTS', ki_includer::jsArrayListJoin($srcs));
});