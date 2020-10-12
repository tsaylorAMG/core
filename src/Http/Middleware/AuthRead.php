<?php
namespace TypeRocket\Http\Middleware;

/**
 * Class AuthRead
 *
 * Authenticate user has read access and if the user does not
 * invalidate the response.
 *
 * @package TypeRocket\Http\Middleware
 */
class AuthRead extends Middleware
{
    public function handle() {

        if ( ! current_user_can('read') ) {
            tr_abort(401);
        }

        $this->next->handle();
    }
}
