<?php
foreach ($this->flash_messages() as $type => $messages) {
    foreach ($messages as $message) {
        echo sprintf('<div class="alert alert-%s">%s</div>', $type, $message);
    }
}

?>

<form action="" method="post">


</form>

