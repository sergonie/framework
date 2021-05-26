<?php declare(strict_types=1);

namespace Sergonie\Tests\Fixtures;

use Sergonie\Application\Controller;
use SergonieTest\Fixtures\Boo;

class SampleController implements Controller
{
    private $boo;

    public function __construct(Boo $boo)
    {
        $this->boo = $boo;
    }

    public function __invoke()
    {
        return $this->boo->a->getA();
    }
}
