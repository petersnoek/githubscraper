<?php

require 'inc/configureblade.php';

echo $blade->view()->make('_layout')->with('title', 'layoutpage')->render();