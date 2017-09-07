<?php

namespace jvleeuwen\broadsoft\Repositories\Contracts;

interface BsExampleInterface
{
    function GetCallCentersBySlug($slug);
    function GetCallCenterMonitoring();
    function GetUsersBySlug($slug);
} 