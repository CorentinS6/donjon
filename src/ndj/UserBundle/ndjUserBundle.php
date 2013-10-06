<?php

namespace ndj\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ndjUserBundle extends Bundle
{
  public function getParent()
  {
    return 'FOSUserBundle';
  }
}
