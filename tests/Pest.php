<?php

function wrapFragmentWithDummyPage(string $html): string
{
    return <<<HTML
<!DOCTYPE html>
<html lang="en">
  <body id="wrapper">$html</body>
</html>
HTML;
}